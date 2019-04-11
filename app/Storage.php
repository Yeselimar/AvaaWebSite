<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    //
    protected $table= 'storages';

    public function editor()
    {
        return $this->belongsTo('avaa\User','user_id');
    }

}
