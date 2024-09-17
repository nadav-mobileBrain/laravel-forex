<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MercadoPagoController extends Controller
{
    /**
     * @var mixed
     */
    public $accessToken;
    /**
     * @var mixed
     */
    public $isSandbox;

    public function __construct()
    {
        $method = PaymentMethod::find(1014);
        $this->accessToken = $method->val1;
        $this->isSandbox = $method->val3 == 'sandbox' ? true : false;
    }

    /**
     * @param Request $request
     */
    public function process(Request $request)
    {

        $request->validate([
            'custom' => 'required',
        ]);

        $log = PaymentLog::where([
            'order_number' => $request->input('custom'),
            'payment_id'   => 1014,
            'status'       => 0,
        ])->firstOrFail();

        $response = Http::acceptJson()->withToken($this->accessToken)->post("https://api.mercadopago.com/checkout/preferences?access_token={$this->accessToken}", [
            'items'            => [
                [
                    'id'          => $log->order_number,
                    'title'       => $log->plan->name . ' Plan Subscription Payment',
                    'description' => $log->plan->name . ' Plan Subscription Payment',
                    'unit_price'  => (float) $log->usd,
                    'currency_id' => $log->paymentMethod->currency,
                    'quantity'    => 1,
                ],
            ],
            'additional_info'  => [
                'custom' => $log->order_number,
            ],
            'payer'            => [
                'email' => $log->user->email,
            ],
            'back_urls'        => [
                'success' => route('mercado-pago-success', ['custom' => $log->order_number]),
                'pending' => route('chose-payment-method'),
                'failure' => route('chose-payment-method'),
            ],
            'notification_url' => route('mercado-pago-ipn', ['custom' => $log->order_number]),
            'auto_return'      => 'approved',
        ]);

        if ($response->successful()) {
            $response = $response->object();
            if ($response->auto_return == 'approved') {
                if ($this->isSandbox) {
                    return redirect()->to($response->sandbox_init_point);
                } else {
                    return redirect()->to($response->init_point);
                }
            } else {
                toastMessage('warning', 'Something Wrong with payment.');
                return to_route('chose-payment-method');
            }
        } else {
            toastMessage('warning', 'Something Wrong with payment.');
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function success(Request $request)
    {
        $log = PaymentLog::where([
            'order_number' => $request->custom,
            'payment_id'   => 1014,
            'status'       => 0,
        ])->firstOrFail();

        $paymentId = $request->payment_id;
        $response = Http::get("https://api.mercadopago.com/v1/payments/{$paymentId}?access_token={$this->accessToken}");
        if ($response->successful()) {
            $response = $response->object();
            if ($response->captured && ($response->status == 'approved') && ($response->transaction_amount == $log->usd) && ($response->currency_id == $log->paymentMethod->currency)) {
                (new PaymentAction($log))->perform();
                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');
                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', 'Payment Failed or Pending yet');
                return to_route('chose-payment-method');
            }
        } else {
            toastMessage('warning', 'Something goes wrong.');
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $log = PaymentLog::where([
            'order_number' => $request->custom,
            'payment_id'   => 1014,
            'status'       => 0,
        ])->firstOrFail();

        $paymentId = $request['data']['id'];
        $response = Http::get("https://api.mercadopago.com/v1/payments/{$paymentId}?access_token={$this->accessToken}");
        if ($response->successful()) {
            $response = $response->object();
            if ($response->captured && ($response->status == 'approved') && ($response->transaction_amount == $log->usd) && ($response->currency_id == $log->paymentMethod->currency)) {
                (new PaymentAction($log))->perform();
                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');
                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', 'Payment Failed or Pending yet');
                return to_route('chose-payment-method');
            }
        } else {
            toastMessage('warning', 'Something goes wrong.');
            return to_route('chose-payment-method');
        }
    }

}
