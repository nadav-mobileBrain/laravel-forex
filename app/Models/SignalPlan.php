<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignalPlan extends Model
{
    protected $table = 'signal_plans';

    protected $guarded = [''];

    public function signal()
    {
        return $this->belongsTo(Signal::class,'signal_id');
    }
}
