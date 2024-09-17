<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\PaymentAction;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class SslCommerzPaymentController extends Controller
{
    public function __construct()
    {
        $method = PaymentMethod::find(1006);
        if ($method->val3 == 'sandbox') {
            $url = 'https://sandbox.sslcommerz.com';
            $local = true;
        } else {
            $url = 'https://securepay.sslcommerz.com';
            $local = false;
        }
        config(['sslcommerz.apiDomain' => $url]);
        config(['sslcommerz.apiCredentials' => [
            'store_id'       => $method->val1,
            'store_password' => $method->val2,
        ]]);
        config(['sslcommerz.connect_from_localhost' => $local]);
    }

    /**
     * @param Request $request
     */
    public function process(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);

        $log = PaymentLog::with([
            'paymentMethod',
            'user',
            'plan',
        ])->whereOrderNumber($request->input('custom'))->whereStatus(0)->firstOrFail();

        $post_data = [];
        $post_data['total_amount'] = $log->usd; # You cant not pay less than 10
        $post_data['currency'] = $log->paymentMethod->currency;
        $post_data['tran_id'] = $log->order_number; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $log->user->name;
        $post_data['cus_email'] = $log->user->email;
        $post_data['cus_phone'] = $log->user->phone;
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = config('app.name');
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = $log->plan->name . ' Plan Subscription charge';
        $post_data['product_category'] = "Plan Subscription";
        $post_data['product_profile'] = "digital-goods";

        $post_data['value_a'] = $log->order_number;

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = [];
        } else {
            toastMessage('warning', 'Something Wrong on payment');
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function success(Request $request)
    {
        $request->validate([
            'tran_id'  => 'required',
            'amount'   => 'required',
            'currency' => 'required',
        ]);

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();
        $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
        if ($validation == true) {

            $log = PaymentLog::whereOrderNumber($request->input('tran_id'))->whereStatus(0)->firstOrFail();
            $payableAmount = sprintf('%0.2f', round($log->usd, 2));
            if (($payableAmount == $amount) && ($currency == $log->paymentMethod->currency) && ($request->input('status') == 'VALID')) {
                (new PaymentAction($log))->perform();
                toastMessage('success', 'Payment Successfully Completed.');
                return to_route('user-dashboard');
            } else {
                toastMessage('success', 'Payment Not Completed.');
                return to_route('chose-payment-method');
            }
        } else {
            toastMessage('success', 'Payment Not Completed.');
            return to_route('chose-payment-method');
        }
    }

    /**
     * @param Request $request
     */
    public function fail(Request $request)
    {
        toastMessage('warning', 'Payment Failed. Try again.');
        return to_route('chose-payment-method');
    }

    /**
     * @param Request $request
     */
    public function cancel(Request $request)
    {
        toastMessage('warning', 'Payment Cancelled. Try again.');
        return to_route('chose-payment-method');
    }

    /**
     * @param Request $request
     */
    public function ipn(Request $request)
    {
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {
            $tran_id = $request->input('tran_id');

            $log = PaymentLog::whereOrderNumber($request->input('tran_id'))->firstOrFail();
            if (!$log->status) {
                $payableAmount = sprintf('%0.2f', round($log->usd, 2));
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $payableAmount, $log->paymentMethod->currency);
                if ($validation == true) {
                    (new PaymentAction($log))->perform();
                    toastMessage('success', 'Payment Successfully Completed.');
                    return to_route('user-dashboard');
                } else {
                    toastMessage('warning', 'Payment goes wrong. Try again.');
                    return to_route('chose-payment-method');
                }
            } else {
                toastMessage('warning', 'Payment already completed');
                return to_route('chose-payment-method');
            }

        } else {
            toastMessage('warning', 'Payment goes wrong. Try again.');
            return to_route('chose-payment-method');
        }
    }
}
