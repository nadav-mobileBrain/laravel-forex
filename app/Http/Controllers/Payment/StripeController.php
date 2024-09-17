<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use App\Models\Plan;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class StripeController extends Controller
{
    /**
     * @var mixed
     */
    public $gateway;
    /**
     * @var mixed
     */
    public $completePaymentUrl;

    /**
     * @var mixed
     */
    public $currency;

    public function __construct()
    {
        $method = PaymentMethod::find(4);
        $this->gateway = Omnipay::create('Stripe\PaymentIntents');
        $this->gateway->setApiKey($method->val1);
        $this->completePaymentUrl = route('stripe-ipn');
        $this->currency = $method->currency;
    }

    /**
     * @param Request $request
     */
    public function submitStripe(Request $request)
    {
        $request->validate([
            'custom'      => 'required',
            'stripeToken' => 'required',
        ]);

        $log = PaymentLog::whereOrderNumber($request->input('custom'))->first();
        $token = $request->input('stripeToken');
        $response = $this->gateway->authorize([
            'amount'      => $log->usd,
            'currency'    => $this->currency,
            'description' => $log->plan->name . ' Plan Subscription Payment',
            'token'       => $token,
            'returnUrl'   => $this->completePaymentUrl,
            'confirm'     => true,
            'metadata'    => [
                'custom' => $log->order_number,
            ],
        ])->send();

        if ($response->isSuccessful()) {
            $response = $this->gateway->capture([
                'amount'                 => $log->amount,
                'currency'               => $this->currency,
                'paymentIntentReference' => $response->getPaymentIntentReference(),
            ])->send();

            $paymentData = $response->getData();
            $payAmount = $paymentData['amount'] / 100;
            $custom = $paymentData['metadata']['custom'];
            $data = PaymentLog::whereOrderNumber($custom)->wherePaymentId(4)->whereStatus(0)->firstOrFail();

            if ($data->usd == $payAmount) {

                (new PaymentAction($data))->perform();

                toastMessage('success', 'Payment Successfully Completed.');

                return to_route("user-dashboard");
            } else {
                toastMessage('warning', 'Something Wrong on Payment.');

                return to_route("chose-payment-method");
            }

        } elseif ($response->isRedirect()) {
            session(['payer_email' => $request->input('email')]);
            $response->redirect();
        } else {
            toastMessage('warning', $response->getMessage());

            return to_route("chose-payment-method");
        }
    }

    /**
     * @param Request $request
     */
    public function stripeIpn(Request $request)
    {
        $response = $this->gateway->confirm([
            'paymentIntentReference' => $request->input('payment_intent'),
            'returnUrl'              => $this->completePaymentUrl,
        ])->send();

        if ($response->isSuccessful()) {
            $response = $this->gateway->capture([
                'amount'                 => $request->input('amount'),
                'currency'               => $this->currency,
                'paymentIntentReference' => $request->input('payment_intent'),
            ])->send();

            $paymentData = $response->getData();
            $payAmount = $paymentData['amount'] / 100;
            $custom = $paymentData['metadata']['custom'];
            $data = PaymentLog::whereOrderNumber($custom)->wherePaymentId(4)->whereStatus(0)->firstOrFail();

            if ($data->usd == $payAmount) {
                (new PaymentAction($data))->perform();
                toastMessage('success', 'Payment Successfully Completed.');

                return to_route("user-dashboard");
            } else {
                toastMessage('warning', 'Something Wrong on Payment.');

                return to_route("chose-payment-method");
            }
        } else {
            toastMessage('warning', 'Something Wrong on Payment.');

            return to_route("chose-payment-method");
        }
    }
}
