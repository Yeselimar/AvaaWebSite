<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table= 'solicitudes';

    public function user()
    {
        return $this->belongsTo('avaa\User','user_id');
    }

    protected $fillable = [
        'titulo', 'descripcion','fecha_inactividad','fecha_desincorporacion', 'observacion',
    ];

    public function fechaActualizacion()
    {
    	return date("d/m/Y h:i:s a", strtotime($this->updated_at));
    }

    public function fechaCreacion()
    {
    	return date("d/m/Y h:i:s a", strtotime($this->created_at));
    }

    public function scopeVisibleAdmin($query)
    {
        return $query->where('oculto_admin','=',0);
    }

    public function scopeVisibleUsuario($query)
    {
        return $query->where('oculto_usuario','=',0);
    }

    public function scopeConEstatus($query,$estatus)
    {
        return $query->where('status','=',$estatus);
    }
}
