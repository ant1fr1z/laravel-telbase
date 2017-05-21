<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $table = 'objects';
    protected $guarded = ['id'];

    public function numbers()
    {
        return $this->hasMany('App\Number');
    }
}
