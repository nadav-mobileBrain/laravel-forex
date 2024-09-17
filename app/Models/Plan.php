<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /**
     * @var string
     */
    protected $table = 'plans';

    /**
     * @var array
     */
    protected $guarded = [''];

    /**
     * @var array
     */
    protected $casts = [
        'contents' => 'json',
    ];

}
