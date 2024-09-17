<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Braintree\Gateway;
use Illuminate\Http\Request;

class BraintreeController extends Controller
{
    /**
     * @var mixed
     */
    public $gateway;

    public function __construct()
    {
        $method = PaymentMethod::find(1021);
        $gateway = new Gateway([
            'environment' => $method->extra['env'],
            'merchantId'  => $method->val1,
            'publicKey'   => $method->val2,
            'privateKey'  => $method->val3,
        ]);
        $this->gateway = $gateway;
    }

    /**
     * @param
     */
    public function process()
    {
        $clientToken = $this->gateway->clientToken()->generate();
        if ($clientToken) {
            $action = [
                'success' => true,
                'token'   => $clientToken,
            ];
        } else {
            $action = [
                'success' => false,
                'message' => 'Client Token not found',
            ];
        }

        return $action;
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $request->validate([
            'custom' => 'required',
            'token'  => 'required',
        ]);
        $log = PaymentLog::where([
            'order_number' => $request->custom,
            'payment_id'   => 1021,
            'status'       => 0,
        ])->firstOrFail();

        // $customer = $this->gateway->cus;

        $result = $this->gateway->transaction()->sale([
            'amount'             => $log->usd,
            'paymentMethodNonce' => $request->token,
            'customer'           => [
                'firstName' => $log->user->name,
                'lastName'  => "",
                'email'     => $log->user->email,
            ],
            'options'            => [
                'submitForSettlement' => true,
            ],
        ]);

        if ($result->success) {
            if (($result->transaction->processorResponseText == 'Approved') && ($result->transaction->currencyIsoCode == $log->paymentMethod->currency) && ($result->transaction->amount == $log->usd)) {
                (new PaymentAction($log))->perform();
                toastMessage('success', 'Payment Successfully Completed.');
                return to_route('user-dashboard');
            } else {
                toastMessage('warning', 'Something Wrong with payment.');
                return to_route('chose-payment-method');
            }
        } elseif ($result->transaction) {
            toastMessage('warning', $result->transaction->processorResponseText);
            return to_route('chose-payment-method');
        } else {
            toastMessage('warning', 'Something wrong with payment.');
            return to_route('chose-payment-method');
        }
    }
}
