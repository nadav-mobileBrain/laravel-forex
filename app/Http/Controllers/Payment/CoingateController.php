<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use CoinGate\Client;
use Illuminate\Http\Request;

class CoingateController extends Controller
{
    /**
     * @var mixed
     */
    public $client;

    public function __construct()
    {
        $method = PaymentMethod::find(1007);
        if ($method->val3 == 'sandbox') {
            $isSandbox = true;
        } else {
            $isSandbox = false;
        }
        $this->client = new Client($method->val1, $isSandbox);
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

        $params = [
            'order_id'         => $log->order_number,
            'price_amount'     => $log->usd,
            'price_currency'   => $log->paymentMethod->currency,
            'receive_currency' => $log->paymentMethod->currency,
            'callback_url'     => route('coingate-ipn'),
            'cancel_url'       => route('chose-payment-method'),
            'success_url'      => route('user-dashboard'),
            'title'            => $log->plan->name . ' Plan Subscription Payment',
        ];

        try {
            $order = $this->client->order->create($params);
            return redirect()->to($order->payment_url);
        } catch (\CoinGate\Exception\ApiErrorException $e) {
            toastMessage('warning', $e->getMessage());
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $ip = \Request::getClientIp(true);
        $coingateIp = $this->client->getIPAddresses();
        if (strpos($coingateIp, $ip) !== false) {
            $custom = $request->input('order_id');
            $log = PaymentLog::whereOrderNumber($custom)->whereStatus(0)->first();
            if ($log) {
                if (($request->status == 'paid') && ($request->price_amount == $log->usd) && ($request->price_currency == $log->paymentMethod->name)) {
                    (new PaymentAction($log))->perform();
                }
            }
        }
    }
}
