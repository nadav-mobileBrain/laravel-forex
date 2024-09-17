<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class ZarinPalController extends Controller
{
    /**
     * @var mixed
     */
    public $gateway;

    public function __construct()
    {
        $method = PaymentMethod::find(1018);
        $gateway = Omnipay::create('ZarinPal');
        $gateway->setMerchantId($method->val1);
        $gateway->setTestMode($method->val3 == 'sandbox' ? true : false);
        $this->gateway = $gateway;
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
            'payment_id'   => 1018,
            'status'       => 0,
        ])->firstOrFail();

        $method = PaymentMethod::find(1018);

        $this->gateway->setReturnUrl(route('zarinpal-ipn', ['custom' => $log->order_number]));

        // Send purchase request
        $response = $this->gateway->purchase([
            'amount'      => $log->usd,
            'description' => $log->plan->name . ' Plan Subscription Payment',
            'email'       => $log->user->email,
            'mobile'      => $log->user->phone,
        ])->send();

        if ($response->isRedirect()) {
            $response->redirect();
        } else {
            toastMessage('warning', $response->getMessage());
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        $custom = $request->custom;
        $Authority = $request->Authority;

        $log = PaymentLog::where([
            'order_number' => $custom,
            'payment_id'   => 1018,
            'status'       => 0,
        ])->firstOrFail();

        $response = $this->gateway->completePurchase([
            'amount'    => $log->usd,
            'authority' => $Authority,
        ])->send();

        if ($response->isSuccessful()) {
            (new PaymentAction($log))->perform();
            toastMessage('success', 'Payment Successfully Completed.');

            return to_route('user-dashboard');
        } else {
            toastMessage('warning', $response->getMessage());
            return to_route('chose-payment-method');
        }
    }
}
