<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $table= 'mentores';

    public $primaryKey = 'user_id';

    public $guarded = ['created_at', 'updated_at'];

    public function user()//Relación uno a uno con USER
    {
        return $this->belongsTo('avaa\User','user_id');
    }

    public function becarios()//Relación uno a muchos
    {
        return $this->hasMany('avaa\Becario','mentor_id','user_id');
    }

    public function numeroBecarios()
    {
        return $this->becarios()->count();
    }
    public function esPostulante()
    {
        return $this->status=="postulante";
    }
}
