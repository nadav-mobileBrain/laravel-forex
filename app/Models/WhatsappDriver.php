<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsappDriver extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'whatsapp_drivers';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'driver', 'image', 'status', 'data',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data'   => 'json',
        'status' => 'boolean',
    ];
}
