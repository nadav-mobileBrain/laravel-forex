<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Instamojo\Instamojo;

class InstamojoController extends Controller
{
    /**
     * @var mixed
     */
    protected $gateway;

    /**
     * @var mixed
     */
    protected $insta;

    public function __construct()
    {
        $method = PaymentMethod::find(1011);
        $this->gateway = $method;
    }

    /**
     * @param Request $request
     */
    public function process(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);

        $log = PaymentLog::whereOrderNumber($request->input('custom'))->wherePaymentId(1011)->firstOrFail();

        $payload = [
            'purpose'      => $log->plan->name,
            'amount'       => $log->usd,
            'send_email'   => true,
            'name'         => $log->user->name,
            'email'        => $log->user->email,
            'redirect_url' => route('instamojo-ipn', ['custom' => $log->order_number]),
            'webhook'      => route('instamojo-ipn', ['custom' => $log->order_number]),
        ];

        try {
            $isSandbox = $this->gateway->val3 == 'sandbox' ? true : false;
            $api = Instamojo::init("app", [
                "client_id"     => $this->gateway->val1,
                "client_secret" => $this->gateway->val2,
            ], $isSandbox);

            $response = $api->createPaymentRequest($payload);
            return redirect()->to($response['longurl']);
        } catch (Exception $e) {
            toastMessage('warning', "Gateway Authentication issue. try with another gateway.");
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $paymentRequestId = $request->payment_request_id;
        $custom = $request->custom;
        $log = PaymentLog::whereOrderNumber($custom)->wherePaymentId(1011)->whereStatus(0)->firstOrFail();
        try {
            $isSandbox = $this->gateway->val3 == 'sandbox' ? true : false;
            $api = Instamojo::init("app", [
                "client_id"     => $this->gateway->val1,
                "client_secret" => $this->gateway->val2,
            ], $isSandbox);

            $response = $api->getPaymentRequestDetails($paymentRequestId);
            $payableAmount = customAmountFormat($log->usd);
            if ($response['status'] == 'Completed' && $response['amount'] == $payableAmount) {
                (new PaymentAction($log))->perform();
                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');
                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', 'Payment is not completed.');
                return to_route('chose-payment-method');
            }
        } catch (Exception $e) {
            toastMessage('warning', 'Something wrong with payment.');
            return to_route('chose-payment-method');
        }
    }
}
