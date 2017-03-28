<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    protected $table = 'numbers';
    protected $guarded = ['id'];

    public function objects()
    {
        return $this->belongsToMany('App\Object')->withTimestamps();
    }
}
