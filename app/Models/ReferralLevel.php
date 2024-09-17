<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLevel extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'referral_levels';

    /**
     * @var array
     */
    protected $fillable = [
        'level', 'commission',
    ];
}
