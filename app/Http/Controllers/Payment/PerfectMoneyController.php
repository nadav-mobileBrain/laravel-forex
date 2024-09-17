<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;

class PerfectMoneyController extends Controller
{
    public function perfectIPN()
    {
        $pay = PaymentMethod::whereId(2)->first();
        $passphrase = strtoupper(md5($pay->val2));

        define('ALTERNATE_PHRASE_HASH', $passphrase);
        define('PATH_TO_LOG', storage_path('logs'));
        $string = $_POST['PAYMENT_ID'] . ':' . $_POST['PAYEE_ACCOUNT'] . ':' .
            $_POST['PAYMENT_AMOUNT'] . ':' . $_POST['PAYMENT_UNITS'] . ':' .
            $_POST['PAYMENT_BATCH_NUM'] . ':' .
            $_POST['PAYER_ACCOUNT'] . ':' . ALTERNATE_PHRASE_HASH . ':' .
            $_POST['TIMESTAMPGMT'];
        $hash = strtoupper(md5($string));
        $hash2 = $_POST['V2_HASH'];
        if ($hash == $hash2) {
            $amount = $_POST['PAYMENT_AMOUNT'];
            $unit = $_POST['PAYMENT_UNITS'];
            $custom = $_POST['PAYMENT_ID'];
            $data = PaymentLog::where('order_number', $custom)->wherePayment_id(2)->whereStatus(0)->firstOrFail();

            if ($_POST['PAYEE_ACCOUNT'] == $pay->val1 && $unit == $pay->currency && $amount == $data->usd) {

                (new PaymentAction($data))->perform();

                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');

                return redirect()->route('user-dashboard');

            } else {
                session()->flash('message', 'Something error In Payment');
                session()->flash('type', 'warning');

                return redirect()->route('chose-payment-method');
            }
        }
    }
}
