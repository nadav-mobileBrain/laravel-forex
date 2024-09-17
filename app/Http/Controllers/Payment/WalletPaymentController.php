<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class WalletPaymentController extends Controller
{
    /**
     * @param Request $request
     */
    public function commissionPaymentIPN(Request $request)
    {
        $request->validate([
            'custom' => 'required',
        ]);
        $custom = $request->custom;

        $data = PaymentLog::where('order_number', $custom)->wherePayment_id(7)->whereStatus(0)->first();
        if ($data) {
            $user = User::findOrFail($data->user_id);
            $plan = Plan::findOrFail($data->plan_id);
            if ($user->balance >= $plan->price) {
                $user->balance -= $plan->price;
                $user->save();
                (new PaymentAction($data))->perform();
                toastMessage('warning', 'Payment Successfully completed');

                return to_route('user-dashboard');
            } else {
                toastMessage('warning', 'Balance is Smaller than Price');

                return to_route('chose-payment-method');
            }
        } else {
            toastMessage('warning', 'This order already paid');

            return to_route('user-dashboard');
        }
    }
}
