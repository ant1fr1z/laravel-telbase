<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $table = 'objects';
    protected $guarded = ['id'];

    public function numbers()
    {
        return $this->belongsToMany('App\Number')->withTimestamps();
    }
}
