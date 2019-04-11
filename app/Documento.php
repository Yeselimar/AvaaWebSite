<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    //nombre de la tabla a quien hace referencia en base de datos
    protected $table= 'documentos';

    public function user()
    {
        return $this->belongsTo('avaa\User','user_id');
    }
}
