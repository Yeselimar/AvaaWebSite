<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Rechazado extends Model
{
    protected $table= 'rechazados';

    public $primaryKey = 'id';

    protected $fillable = [
        'cedula','fecha_de_participacion',
    ];
}

