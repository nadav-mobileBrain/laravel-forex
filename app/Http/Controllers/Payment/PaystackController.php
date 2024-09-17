<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackController extends Controller
{

    public function __construct()
    {
        $method = PaymentMethod::find(1004);
        config([
            'paystack.publicKey'     => $method->val1,
            'paystack.secretKey'     => $method->val2,
            'paystack.merchantEmail' => $method->val3,
        ]);
    }

    /**
     * @param Request $request
     */
    public function processPaystack(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);

        $log = PaymentLog::whereOrderNumber($request->input('custom'))->whereStatus(0)->firstOrFail();

        $payload = [
            "amount"       => $log->usd * 100,
            "reference"    => Paystack::genTranxRef(),
            "email"        => $log->user->email,
            "currency"     => $log->paymentMethod->currency,
            "callback_url" => route('paystack-ipn'),
            "metadata"     => [
                "order_id" => $log->order_number,
            ],
        ];
        try {
            return Paystack::getAuthorizationUrl($payload)->redirectNow();
        } catch (\Exception $e) {
            toastMessage('warning', 'The paystack token has expired. Please refresh the page and try again.');

            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function paystackIPN(Request $request)
    {
        $response = Paystack::getPaymentData();
        if ($response['status']) {
            $data = PaymentLog::whereOrderNumber($response['data']['metadata']['order_id'])->whereStatus(0)->firstOrFail();
            $payableAmount = round($response['data']['amount'] / 100, 2);

            if (($data->usd == $payableAmount) && ($response['data']['status'] == 'success') && ($response['data']['currency'] == $data->paymentMethod->currency)) {
                (new PaymentAction($data))->perform();

                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');

                return redirect()->route('user-dashboard');
            }
        } else {
            toastMessage('warning', 'Payment failed, Please try again.');

            return to_route('chose-payment-method');
        }
    }
}
