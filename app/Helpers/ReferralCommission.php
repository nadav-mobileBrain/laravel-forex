<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\BasicSetting;
use App\Models\ReferralLevel;
use App\Models\TransactionLog;

class ReferralCommission
{
    public function __construct(public $data, public $user)
    {}

    public function distribute()
    {
        $basic = BasicSetting::first();
        $user = $this->user;
        $data = $this->data;
        if ($basic->referral_commission_status) {
            $parentChk = User::find($user->parent_id);
            $amount = $data->amount;
            if($parentChk){
                $levels = ReferralLevel::get();
                $parentId = $user->parent_id;
                foreach ($levels as $key => $level) {
                    $parent = User::find($parentId);
                    if($parent){
                        $referralCommission = round((($amount * $level->commission) / 100), 2);
                        $parent->balance = round($parent->balance + $referralCommission, 2);
                        $parent->save();
                        $this->referralTransactionLog($data->order_number, $parent->id, 7, $referralCommission, ++$key . date("S", mktime(0, 0, 0, 0, $key, 0)) . ' Level Reference Bonus');
                        if ($parent->parent_id) {
                            $parentId = $parent->parent_id;
                        } else {
                            break;
                        }
                    }else{
                        break;
                    }
                }
            }else{
                $user->parent_id = 0;
                $user->save();
            }
        }
    }

    public function referralTransactionLog($custom, $id, $type, $amount, $details)
    {
        $tr['custom'] = $custom;
        $tr['user_id'] = $id;
        $tr['type'] = $type;
        $tr['balance'] = $amount;
        $tr['post_balance'] = 0;
        $tr['details'] = $details;
        TransactionLog::create($tr);
    }
}
