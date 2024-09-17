<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class FlutterwaveController extends Controller
{
    /**
     * @param Request $request
     */
    public function flutterwaveIPN(Request $request)
    {
        $status = $request->status;
        $custom = $request->tx_ref;
        if ($status == 'cancelled') {
            toastMessage('warning', 'Payment is not completed.');

            return to_route('chose-payment-method');
        } elseif ($status == 'successful') {
            $method = PaymentMethod::find(1001);
            $data = PaymentLog::where('order_number', $custom)->wherePaymentId(1001)->whereStatus(0)->firstOrFail();

            $successTransactionId = $request->transaction_id;
            $url = "https://api.flutterwave.com/v3/transactions/{$successTransactionId}/verify";
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $method->val2,
            ];
            $returnData = customGetCURLHeader($url, $headers);
            $response = json_decode($returnData, true);

            if ($response['status'] == 'success' && ($custom == $response['data']['tx_ref']) && ($data->usd == $response['data']['amount'])) {
                (new PaymentAction($data))->perform();

                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');

                return redirect()->route('user-dashboard');
            } else {
                toastMessage('warning', 'Payment is not completed.');

                return to_route('chose-payment-method');
            }
        } else {
            dd('status different');
        }
    }
}
