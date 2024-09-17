<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VoguePayController extends Controller
{
    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $method = PaymentMethod::find(1010);
        $vogueTransactionId = $request->transaction_id;

        $merchantId = $method->val1;
        $response = Http::get('https://pay.voguepay.com', [
            'v_transaction_id' => $vogueTransactionId,
            'type'             => 'json',
            'v_merchant_id'    => $merchantId,
        ]);

        if ($response->successful()) {
            $response = json_decode($response->body());
            $log = PaymentLog::whereOrderNumber($response->merchant_ref)->whereStatus(0)->first();
            if (($response->status == "Approved") && ($response->merchant_id == $merchantId) && ($response->total == round($log->usd, 2)) && ($response->cur == $log->paymentMethod->currency)) {
                (new PaymentAction($log))->perform();
                toastMessage('success', 'Payment Successfully Completed.');
                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', "Payment failed. Try again letter.");
                return to_route('chose-payment-method');
            }
        }
    }
}
