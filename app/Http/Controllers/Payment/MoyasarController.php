<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoyasarController extends Controller
{
    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $custom = $request->query('custom');
        $id = $request->query('id');
        $status = $request->query('status');
        if ($status == 'paid') {
            $method = PaymentMethod::find(1019);
            $response = Http::withBasicAuth($method->val1, '')->get("https://api.moyasar.com/v1/payments/{$id}");
            if ($response->successful()) {
                $response = $response->object();
                $log = PaymentLog::where([
                    'order_number' => $custom,
                    'payment_id'   => 1019,
                    'status'       => 0,
                ])->firstOrFail();

                $amountFormat = $log->usd . ' ' . $log->paymentMethod->currency;

                if (($response->status == 'paid') && ($response->currency == $log->paymentMethod->currency) && ($response->amount_format == $amountFormat)) {
                    (new PaymentAction($log))->perform();
                    toastMessage('success', 'Payment Successfully Completed.');

                    return to_route('user-dashboard');
                } else {
                    toastMessage("warning", "Payment not completed");
                    return to_route('chose-payment-method');
                }
            }

        } else {
            toastMessage("warning", $request->query('message'));
            return to_route('chose-payment-method');
        }

    }
}
