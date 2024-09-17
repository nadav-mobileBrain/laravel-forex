<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
     * @var string
     */
    protected $table = 'payment_methods';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'rate',
        'val1',
        'val2',
        'val3',
        'extra',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'extra' => 'json',
    ];

}
