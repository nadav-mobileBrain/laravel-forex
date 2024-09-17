<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var array
     */
    protected $guarded = [''];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /* FIXME:its important */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /**
     * @return mixed
     */
    public function plan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * @return mixed
     */
    public function updateplan() {
        return $this->belongsTo(Plan::class, 'up_plan_id');
    }

    /**
     * @return mixed
     */
    public function referralUser() {
        return $this->belongsTo(User::class, 'ref_user_id');
    }
}
