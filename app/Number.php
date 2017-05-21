<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    protected $table = 'numbers';
    protected $guarded = ['id'];

    public function object()
    {
        return $this->belongsTo('App\Object');
    }
}
