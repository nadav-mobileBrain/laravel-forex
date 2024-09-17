<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class AlipayGlobalController extends Controller
{
    public function __construct()
    {

    }

    /**
     * @param Request $request
     */
    public function process(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);
        $log = PaymentLog::where([
            'order_number' => $request->input('custom'),
            'payment_id'   => 1017,
            'status'       => 0,
        ])->firstOrFail();

        $method = PaymentMethod::find(1017);

        $gateway = Omnipay::create('GlobalAlipay_Web');
        $gateway->setPartner($method->val3);
        $gateway->setKey($method->val2); //for sign_type=MD5
        $gateway->setPrivateKey($method->val1); //for sign_type=RSA
        $gateway->setReturnUrl(route('user-dashboard'));
        $gateway->setNotifyUrl(route('alipayglobal-ipn'));
        $gateway->setEnvironment($method->extra['env']); //for Sandbox Test (Web/Wap)
        $params = [
            'out_trade_no' => $log->order_number, //your site trade no, unique
            'subject'      => $log->plan->name . ' Plan Subscription Payment', //order title
            'total_fee'    => $log->usd, //order total fee
            'currency'     => $log->paymentMethod->currency, //default is 'USD'
        ];

        $response = $gateway->purchase($params)->send();

        if ($response->isSuccessful()) {
            header("Content-type:text/html;charset=utf-8");
            return redirect()->to($response->getRedirectUrl());
        } else {
            toastMessage('warning', 'Something goes wrong.');
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $method = PaymentMethod::find(1017);

        $gateway = Omnipay::create('GlobalAlipay_Web');
        $gateway->setPartner($method->val3);
        $gateway->setKey($method->val2); //for sign_type=MD5
        $gateway->setPrivateKey($method->val1); //for sign_type=RSA
        $gateway->setEnvironment($method->extra['env']); //for Sandbox Test (Web/Wap)

        $params = [
            'request_params' => array_merge($_GET, $_POST), //Don't use $_REQUEST for may contain $_COOKIE
        ];

        $response = $gateway->completePurchase($params)->send();
        if ($response->isPaid()) {

            $custom = $params['out_trade_no'];
            $log = PaymentLog::where([
                'order_number' => $custom,
                'payment_id'   => 1017,
                'status'       => 0,
            ])->first();

            if (($params['total_fee'] == $log->usd) && ($params['currency'] == $log->paymentMethod->currency)) {
                (new PaymentAction($log))->perform();
                toastMessage('success', 'Payment Successfully Completed.');
                return to_route('user-dashboard');
            }
        } else {
            die('fail');
        }
    }
}
