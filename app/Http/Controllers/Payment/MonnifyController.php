<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MonnifyController extends Controller
{
    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $custom = $request->custom;
        $method = PaymentMethod::find(1013);
        $baseURl = strpos($method->val1, 'TEST') === false ? 'https://api.monnify.com' : 'https://sandbox.monnify.com';
        $url = "{$baseURl}/api/v1/merchant/transactions/query?paymentReference={$custom}";
        $response = Http::withHeaders([
            "Authorization" => ['Basic ' . base64_encode($method->val1 . ':' . $method->val2)],
        ])->get($url);

        if ($response->successful()) {
            $response = $response->object();
            $log = PaymentLog::where([
                'order_number' => $custom,
                'payment_id'   => 1013,
                'status'       => 0,
            ])->first();
            if ($log && $response->requestSuccessful && ($response->responseMessage == 'success') && ($response->responseBody->paymentStatus == 'PAID') && ($response->responseBody->currencyCode == $log->paymentMethod->currency) && ($response->responseBody->payableAmount == $log->usd)) {
                (new PaymentAction($log))->perform();
                toastMessage('success', 'Payment Successfully Completed.');
                return to_route('user-dashboard');
            } elseif ($response->paymentStatus == 'PENDING') {
                toastMessage('warning', 'Payment still on pending');
                return to_route('chose-payment-method');
            } else {
                toastMessage('warning', 'Something wrong with payment.');
                return to_route('chose-payment-method');
            }
        } else {
            toastMessage('warning', 'Something wrong with payment.');
            return to_route('chose-payment-method');
        }

    }
}
