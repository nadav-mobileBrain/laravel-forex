<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DusuPayController extends Controller
{

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
            'payment_id'   => 1020,
            'status'       => 0,
        ])->firstOrFail();

        $method = $log->paymentMethod;

        if ($method->extra['env'] == 'sandbox') {
            $url = 'https://sandbox.dusupay.com/v1/collections';
        } else {
            $url = 'https://dusupay.com/v1/collections';
        }

        $response = Http::acceptJson()->post($url, [
            'api_key'            => $method->val1,
            'currency'           => $method->currency,
            'amount'             => $log->usd,
            'method'             => "MOBILE_MONEY/CARD/BANK/CRYPTO",
            'provider_id'        => $method->val2,
            'account_number'     => $method->val3,
            'merchant_reference' => $log->order_number,
            'narration'          => $log->plan->name . ' Plan Subscription Payment',
            'mobile_money_hpp'   => true,
            'redirect_url'       => route('dusupay-ipn'),
        ]);
        if ($response->successful()) {
            $response = $response->object();
            return redirect()->to($response->data->payment_url);
        } else {
            toastMessage('warning', 'Something Wrong with payment.');
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $custom = $request->merchant_reference;
        if ($request->transaction_status == 'COMPLETED') {
            $log = PaymentLog::where([
                'order_number' => $custom,
                'payment_id'   => 1020,
                'status'       => 0,
            ])->firstOrFail();

            if (($request->request_currency == $log->paymentMethod->currency) && ($request->request_amount == $log->usd)) {
                (new PaymentAction($log))->perform();
                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');
                return to_route('user-dashboard');
            } else {
                toastMessage('warning', 'Something Wrong with payment.');
                return to_route('chose-payment-method');
            }

        } else {
            toastMessage('warning', 'Something Wrong with payment.');
            return to_route('chose-payment-method');
        }
    }
}
