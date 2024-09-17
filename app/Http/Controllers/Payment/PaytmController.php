<?php

namespace App\Http\Controllers\Payment;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaytmController extends Controller
{
    public function __construct()
    {
        $method = PaymentMethod::find(1005);
        config(['services.paytm-wallet' => [
            'env'              => $method->extra['env'],
            'merchant_id'      => $method->val1,
            'merchant_key'     => $method->val2,
            'merchant_website' => $method->val3,
            'channel'          => $method->extra['channel'],
            'industry_type'    => $method->extra['industry_type'],
        ]]);
    }

    /**
     * @param Request $request
     */
    public function paymentProcess(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);

        $log = PaymentLog::whereOrderNumber($request->input('custom'))->whereStatus(0)->firstOrFail();
        $payload = [
            'name'     => $log->user->name, // Name of user
            'mobile'   => $log->user->phone, //Mobile number of user
            'email'    => $log->user->email, //Email of user
            'fee'      => $log->usd,
            'currency' => $log->paymentMethod->currency,
            'order_id' => $log->order_number, //Order id
        ];

        $payment = PaytmWallet::with('receive');

        $payment->prepare([
            'order'         => $log->order_number,
            'user'          => $log->user_id,
            'mobile_number' => $log->user->phone,
            'email'         => $log->user->email, // your user email address
            'amount'        => $log->usd, // amount will be paid in INR.
            'currency'      => $log->paymentMethod->currency, // amount will be paid in INR.
            'callback_url'  => route('paytm-ipn'), // callback URL
        ]);

        return $payment->receive(); // initiate a new payment
    }

    public function paytmIPN()
    {
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response();
        $custom = $transaction->getOrderId(); // return a order id

        $transaction->getTransactionId(); // return a transaction id
        $data = PaymentLog::whereOrderNumber($custom)->firstOrFail();
        // update the db data as per result from api call

        if ($transaction->isSuccessful()) {
            $payableAmount = round($response['TXNAMOUNT'], 2);
            $status = $response['STATUS'];
            if (($status == 'TXN_SUCCESS') && ($data->usd == $payableAmount) && ($data->paymentMethod->currency == $response['CURRENCY'])) {
                (new PaymentAction($data))->perform();
                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');

                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', 'Payment failed, Something wrong with this payment.');

                return to_route('chose-payment-method');
            }
        } elseif ($transaction->isFailed()) {
            toastMessage('warning', 'Payment failed, Please try again.');

            return to_route('chose-payment-method');
        } elseif ($transaction->isOpen()) {
            toastMessage('warning', 'Payment still on Open stage, Please try again.');

            return to_route('chose-payment-method');
        }
        //$transaction->getResponseMessage(); //Get Response Message If Available
        // $transaction->getOrderId(); // Get order id
    }
}
