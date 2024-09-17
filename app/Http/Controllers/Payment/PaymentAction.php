<?php

namespace App\Http\Controllers\Payment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Helpers\PaymentConfirm;
use App\Helpers\ReferralCommission;

class PaymentAction
{
    /**
     * @param $data
     */
    public function __construct(public $data)
    {}

    public function perform()
    {
        $data = $this->data;
        $user = User::findOrFail($data->user_id);
        $plan = Plan::findOrFail($data->plan_id);

        if ($plan->plan_type == 1) {
            $user->expire_time = 1;
        } else {
            $user->expire_time = Carbon::parse()->addDays($plan->duration);
        }

        $user->plan_status = 1;
        $user->plan_id = $data->plan_id;
        $user->up_status = 0;
        $user->up_plan_id = null;
        $user->save();

        $data->status = 1;
        $data->save();

        (new ReferralCommission($data, $user))->distribute();
        (new PaymentConfirm($user->id, $data->usd, $data->order_number, $data->paymentMethod->name,$data->paymentMethod->currency))->sendMessage();
    }
}
