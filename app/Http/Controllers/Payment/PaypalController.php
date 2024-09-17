<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class PaypalController extends Controller
{
    /**
     * @var mixed
     */
    private $gateway;

    public function __construct()
    {
        $method = PaymentMethod::find(1);
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($method->val1);
        $this->gateway->setSecret($method->val2);
        $this->gateway->setTestMode($method->val3 == 'live' ? false : true);
    }

    /**
     * @param Request $request
     */
    public function paypalSubmit(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);

        $log = PaymentLog::whereOrderNumber($request->input('custom'))->firstOrFail();

        try {
            $response = $this->gateway->purchase([
                'amount'        => $log->usd,
                'currency'      => $log->paymentMethod->currency,
                'returnUrl'     => route('paypal-ipn'),
                'cancelUrl'     => route('chose-payment-method'),
                'transactionId' => $log->order_number,
                'items'         => [
                    [
                        'name'     => $log->plan->name . ' Plan Subscription Payment',
                        'price'    => $log->usd,
                        'quantity' => 1,
                    ],
                ],
            ])->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                session()->flash('message', $response->getMessage());
                session()->flash('type', 'warning');
            }
        } catch (Exception $e) {
            session()->flash('message', $e->getMessage());
            session()->flash('type', 'warning');
        }

        return redirect()->route('chose-payment-method');
    }

    /**
     * @param Request $request
     */
    public function paypalIpn(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase([
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ]);

            $response = $transaction->send();
            if ($response->isSuccessful()) {
                $returnData = $response->getData();
                $custom = $returnData['transactions'][0]['invoice_number'];
                $data = PaymentLog::where('order_number', $custom)->wherePaymentId(1)->whereStatus(0)->firstOrFail();

                (new PaymentAction($data))->perform();

                session()->flash('message', 'Payment Successfully Completed.');
                session()->flash('type', 'success');

                return redirect()->route('user-dashboard');

            } else {
                $this->failRedirect();
            }
        } else {
            $this->failRedirect();
        }
    }
}
