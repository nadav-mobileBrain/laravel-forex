<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class AuthorizeNetController extends Controller
{

    /**
     * @param Request $request
     */
    public function process($log)
    {

        $method = $log->paymentMethod;
        try {
            $gateway = Omnipay::create('AuthorizeNetApi_HostedPage');
            $gateway->setAuthName($method->val1);
            $gateway->setTransactionKey($method->val2);
            $gateway->setTestMode($method->val3 == 'sandbox' ? true : false);
            $response = $gateway->authorize([
                'amount'            => $log->usd,
                'currency'          => $log->paymentMethod->currency,
                'transactionId'     => $log->order_number,
                'returnUrl'         => route('authorizenet-ipn', ['custom' => encrypt($log->order_number)]),
                'cancelUrl'         => route('chose-payment-method'),
                'showReceipt'       => false,
                'buttonOptionsText' => "Pay {$log->usd} {$log->paymentMethod->currency} now",
            ])->send();

            $payment['method'] = $response->getRedirectMethod();
            $payment['url'] = $response->getRedirectUrl();
            $payment['fields'] = $response->getRedirectData();
            $action = [
                'success' => true,
                'payment' => $payment,
            ];
        } catch (Exception $e) {
            $action = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $action;
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $encryptedValue = $request->custom;
        try {
            $custom = decrypt($encryptedValue);
            $log = PaymentLog::where([
                'order_number' => $custom,
                'payment_id'   => 1016,
                'status'       => 0,
            ])->first();

            if ($log) {
                (new PaymentAction($log))->perform();
                toastMessage('success', 'Payment Successfully Completed.');
                return to_route('user-dashboard');
            } else {
                toastMessage('warning', 'Something wrong with payment.');
                return to_route('chose-payment-method');
            }

        } catch (DecryptException $e) {
            toastMessage('warning', 'Something wrong with payment.');
            return to_route('chose-payment-method');
        }
    }
}
