<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CoinbaseController extends Controller
{
    /**
     * @var mixed
     */
    public $apiKey;
    /**
     * @var mixed
     */
    public $secretKey;

    public function __construct()
    {
        $method = PaymentMethod::find(1009);
        $this->apiKey = $method->val1;
        $this->secretKey = $method->val2;
    }

    /**
     * @param Request $request
     */
    public function process(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);
        $log = PaymentLog::whereOrderNumber($request->input('custom'))->whereStatus(0)->firstOrFail();

        $payload = [
            'name'         => $log->plan->name,
            'description'  => $log->plan->name . " Plan Subscription Charge",
            'local_price'  => [
                'amount'   => $log->usd,
                'currency' => $log->paymentMethod->currency,
            ],
            'metadata'     => [
                'customer_name'  => $log->user->name,
                'customer_email' => $log->user->email,
                'custom'         => $log->order_number,
            ],
            'pricing_type' => "fixed_price",
            'redirect_url' => route('coinbase-ipn'),
            'cancel_url'   => route('coinbase-fail'),
        ];

        $url = 'https://api.commerce.coinbase.com/charges';
        $response = Http::acceptJson()->withHeaders([
            'X-CC-Api-Key' => $this->apiKey,
            'X-CC-Version' => '2018-03-22',
        ])->post($url, $payload);

        if ($response->successful()) {
            $response = json_decode($response->body());
            return redirect()->to($response->data->hosted_url);
        }
        toastMessage('warning', 'Something wrong with payment.');
        return to_route('chose-payment-dashboard');
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $returnSignature = $request->header('X-Cc-Webhook-Signature');
        $signature = hash_hmac('sha256', $request, $this->secretKey);
        $custom = $request->metadata->custom;
        $log = PaymentLog::whereOrderNumber($custom)->whereStatus(0)->first();
        if ($log && ($returnSignature == $signature) && ($request->event->type == 'charge:confirmed') && ($request->local_price->amount == $log->usd)) {
            (new PaymentAction($log))->perform();
            toastMessage('success', 'Payment Successfully Completed.');
            return to_route('user-dashboard');
        } else {
            toastMessage('warning', 'Payment cancelled. Please try again.');
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function fail(Request $request)
    {
        toastMessage('warning', 'Payment cancelled. Please try again.');
        return to_route('chose-payment-method');
    }
}
