<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailDriver extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'email_drivers';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'driver', 'image', 'data', 'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'data'   => 'json',
        'status' => 'boolean',
    ];
}
