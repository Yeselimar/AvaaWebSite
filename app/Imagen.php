<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    protected $table= 'imagenes';

    public function user()
    {
        return $this->belongsTo('avaa\User','user_id');
    }
}
