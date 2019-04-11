<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Editor extends Model
{
    protected $table= 'editores';

    public $primaryKey = 'user_id';

    public $guarded = ['created_at', 'updated_at'];
    
    public function user()//Relación uno a uno con USER
    {
        return $this->belongsTo('avaa\User','user_id');
    }

    public function noticias()
    {
        return $this->hasMany('avaa\Noticia','editor_id','user_id');
    }

    public function actividades()
    {
        return $this->hasMany('avaa\Actividad','editor_id','user_id');
    }

    public function instituciones()
    {
        return $this->hasMany('avaa\Institucion','editor_id','user_id');
    }

    public function storages()
    {
        return $this->hasMany('avaa\Storage','editor_id','user_id');
    }
}
