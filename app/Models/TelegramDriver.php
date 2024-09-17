<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TelegramDriver extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'telegram_drivers';

    /**
     * @var array
     */
    protected $fillable = [
        'token', 'url',
    ];
}
