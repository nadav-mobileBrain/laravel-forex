<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected  $guarded = [''];


    public function posts()
    {
        return $this->hasMany('App\Models\Post','category_id');
    }

}
