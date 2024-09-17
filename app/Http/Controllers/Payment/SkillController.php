<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * @param Request $request
     */
    public function skrillIPN(Request $request)
    {
        $payment = PaymentMethod::whereId(5)->first();
        $concatFields = $request->merchant_id . $request->transaction_id . strtoupper(md5($payment->val2)) . $request->mb_amount . $request->mb_currency . $request->status;
        $merchantEmail = $payment->val1;
        // Ensure the signature is valid, the status code == 2,
        // and that the money is going to you
        $custom = $request->transaction_id;
        $data = PaymentLog::whereOrder_number($custom)->wherePayment_id(5)->whereStatus(0)->firstOrFail();
        if (strtoupper(md5($concatFields)) == $request->md5sig && $request->status == 2 && $request->pay_to_email == $merchantEmail) {
            (new PaymentAction($data))->perform();
            toastMessage('success', 'Payment Successfully Completed.');

            return to_route('user-dashboard');
        } else {
            toastMessage('success', 'Payment Not Completed.');

            return to_route('chose-payment-method');
        }
    }
}
