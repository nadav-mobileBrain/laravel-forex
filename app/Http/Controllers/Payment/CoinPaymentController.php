<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use Illuminate\Http\Request;

class CoinPaymentController extends Controller
{
    /**
     * @param Request $request
     */
    public function coinPaymentIPN(Request $request)
    {
        $custom = $request->custom;
        $status = $request->status;
        $amount1 = floatval($request->amount1);
        $currency1 = $request->currency1;
        $data = PaymentLog::where('order_number', $custom)->wherePayment_id(6)->whereStatus(0)->first();

        if ($currency1 == $data->paymentMethod->currency && $amount1 >= $data->usd && ($status >= 100 || $status == 2)) {
            (new PaymentAction($data))->perform();
            toastMessage('success', 'Payment Successfully Completed.');

            return to_route('user-dashboard');
        } else {
            toastMessage('success', 'Payment Not Completed.');

            return to_route('chose-payment-method');
        }
    }
}
