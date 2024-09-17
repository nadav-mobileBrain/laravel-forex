<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSignal extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_signals';

    /**
     * @var array
     */
    protected $guarded = [''];

    /**
     * @return mixed
     */
    public function signal()
    {
        return $this->belongsTo(Signal::class, 'signal_id');
    }

    /**
     * Get the user that owns the UserSignal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
