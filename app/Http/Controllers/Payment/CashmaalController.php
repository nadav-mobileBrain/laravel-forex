<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class CashmaalController extends Controller
{
    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $method = PaymentMethod::find(1012);
        $IPNSecret = $method->val1;
        $webId = $method->val2;
        if (($_POST['ipn_key'] == $IPNSecret) && ($_POST['web_id'] == $webId)) {
            if ($_POST['status'] == 1) {
                $custom = $_POST['order_id'];
                $log = PaymentLog::whereOrderNumber($custom)->wherePaymentId(1012)->whereStatus(0)->firstOrFail();
                $logAmount = customAmountFormat($log->usd);
                $payAmount = customAmountFormat($_POST['Amount']);
                if (($logAmount == $payAmount) && ($log->paymentMethod->currency == $_POST['currency'])) {
                    (new PaymentAction($log))->perform();
                    toastMessage('success', 'Payment Successfully Completed.');
                    return to_route('user-dashboard');
                } else {
                    toastMessage('warning', 'Wrong Payment Created.');
                }
            } elseif ($_POST['status'] == 2) {
                toastMessage('warning', "Pending! Your payment is in pending in cashmaal account.");
            } else {
                toastMessage('warning', "Error! Payment not received. Please pay again.");
            }
        } else {
            toastMessage('warning', "Error! Data Invalid");
        }
    }
}
