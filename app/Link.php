<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';
    protected $guarded = ['id'];

    public function ta()
    {
        return $this->hasOne('App\Object', 'id', 'object1');
    }

    public function tb()
    {
        return $this->hasOne('App\Object', 'id', 'object2');
    }

}
