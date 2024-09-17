<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscribeMessageCron extends Model
{
    protected $table = 'subscribe_message_cron';

    protected $guarded = [''];

    public function message()
    {
        return $this->belongsTo(SubscribeMessage::class,'message_id');
    }
    public function subscriber()
    {
        return $this->belongsTo(Subscribe::class,'subscriber_id');
    }
}
