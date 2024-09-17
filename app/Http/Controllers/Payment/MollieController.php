<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;

class MollieController extends Controller
{
    /**
     * @param Request $request
     */
    public function preparePayment(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);

        $method = PaymentMethod::find(1002);
        config(['mollie.key' => $method->val1]);
        $log = PaymentLog::whereOrderNumber($request->input('custom'))->whereStatus(0)->firstOrFail();

        $payment = Mollie::api()->payments->create([
            "amount"      => [
                "currency" => $log->paymentMethod->currency,
                "value"    => '' . sprintf('%0.2f', round($log->usd, 2)) . '',
            ],
            "description" => $log->plan->name . " Plan subscription payment",
            "redirectUrl" => route('mollie-ipn'),
            "metadata"    => [
                "order_id" => $log->order_number,
            ],
        ]);

        session()->put('mollie_payment_id', $payment->id);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    /**
     * @param Request $request
     */
    public function handleWebhookNotification(Request $request)
    {
        $method = PaymentMethod::find(1002);
        config(['mollie.key' => $method->val1]);
        $paymentId = session()->get('mollie_payment_id');
        $payment = Mollie::api()->payments()->get($paymentId);
        $custom = $payment->metadata->order_id;
        $data = PaymentLog::where('order_number', $custom)->wherePaymentId(1002)->whereStatus(0)->firstOrFail();

        if ($payment->isPaid() && ($payment->amount->value == $data->usd)) {
            (new PaymentAction($data))->perform();
            session()->flash('message', 'Payment Successfully Completed.');
            session()->flash('type', 'success');

            return redirect()->route('user-dashboard');
        } else {
            toastMessage('warning', 'Payment failed. Please try again.');

            return to_route('chose-payment-method');
        }

    }
}
