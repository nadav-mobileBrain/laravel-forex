<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $table = 'payment_logs';

    protected $guarded = [''];

    public function paymentMethod()
    {
    	return $this->belongsTo(PaymentMethod::class, 'payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_id');
    }

    public function paymentLogImage()
    {
        return $this->hasMany(PaymentLogImage::class,'payment_log_id');
    }

}
