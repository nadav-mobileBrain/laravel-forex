<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    /**
     * @param Request $request
     */
    public function processRozorPay(Request $request)
    {
        $request->validate([
            'custom'              => 'required',
            'razorpay_payment_id' => 'required',
        ]);

        $method = PaymentMethod::find(1003);
        $api = new Api($method->val1, $method->val2);
        $payment = $api->payment->fetch($request->input('razorpay_payment_id'));
        try {
            $data = PaymentLog::whereOrderNumber($request->input('custom'))->whereStatus(0)->firstOrFail();

            $response = $api->payment->fetch($request->input('razorpay_payment_id'))->capture(['amount' => $payment['amount']]);
            $payableAmount = round($response->amount / 100, 2);
            if (($data->usd == $payableAmount) && ($response->currency == $data->paymentMethod->currency) && ($response->status == 'captured')) {
                (new PaymentAction($data))->perform();

                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');

                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', "Payment failed. Try again letter.");

                return to_route('chose-payment-method');
            }

        } catch (Exception $e) {
            toastMessage('warning', $e->getMessage());

            return to_route('chose-payment-method');
        }

    }
}
