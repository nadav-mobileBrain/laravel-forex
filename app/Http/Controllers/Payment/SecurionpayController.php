<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use SecurionPay\Exception\SecurionPayException;
use SecurionPay\Request\ChargeRequest;
use SecurionPay\SecurionPayGateway;

class SecurionpayController extends Controller
{
    /**
     * @param Request $request
     */
    public function process(Request $request)
    {
        $request->validate([
            'custom' => 'required',
            'token'  => 'required',
        ]);
        $method = PaymentMethod::find(1015);
        $log = PaymentLog::where([
            'order_number' => $request->custom,
            'payment_id'   => 1015,
            'status'       => 0,
        ])->firstOrFail();

        $securionPay = new SecurionPayGateway($method->val2);

        try {
            $chargeRequest = new ChargeRequest();
            $chargeRequest->amount($log->usd)->currency($log->paymentMethod->currency)->card($request->input('token'));
            $charge = $securionPay->createCharge($chargeRequest);
            if (($log->usd == $charge->getAmount()) && ($log->paymentMethod->currency == $charge->getCurrency()) && ($charge->getCaptured() == true)) {
                (new PaymentAction($log))->perform();
                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');
                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', 'Payment Failed or Pending yet');
                return to_route('chose-payment-method');
            }

        } catch (SecurionPayException $e) {
            toastMessage('warning', $e->getMessage());
            return to_route('chose-payment-method');
        }
    }
}
