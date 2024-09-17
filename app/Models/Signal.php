<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    /**
     * @var string
     */
    protected $table = 'signals';

    /**
     * @var array
     */
    protected $guarded = [''];

    /**
     * @return mixed
     */
    public function comments()
    {
        return $this->hasMany(SignalComment::class, 'signal_id');
    }

    /**
     * @return mixed
     */
    public function signalPlans()
    {
        return $this->hasMany(SignalPlan::class, 'signal_id');
    }

    /**
     * @return mixed
     */
    public function symbol()
    {
        return $this->belongsTo(Symbol::class, 'symbol_id');
    }

    /**
     * @return mixed
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * @return mixed
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    /**
     * @return mixed
     */
    public function frame()
    {
        return $this->belongsTo(Frame::class, 'frame_id');
    }

    /**
     * @return mixed
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function admin()
    {
        return Admin::first();
    }

    /**
     * @return mixed
     */
    public function ratings()
    {
        return $this->hasMany(SignalRating::class, 'signal_id');
    }

    /**
     * @param $value
     */
    public function getImageAttribute($value)
    {
        if ($value != null) {
            return $value;
        }
        return 'default.jpg';
    }
}
