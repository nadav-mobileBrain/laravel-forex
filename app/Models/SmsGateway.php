<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsGateway extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'sms_gateways';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'data', 'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data'   => 'json',
        'status' => 'boolean',
    ];
}
