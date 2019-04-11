<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Aval extends Model
{
    protected $table='aval';

    public function user()//para vuejs
    {
        return $this->belongsTo('avaa\User','becario_id');
    }

    public function becario()// para vuejs
    {
        return $this->belongsTo('avaa\Becario','becario_id');
    }

    public static function carpetaConstancia()
    {
        return 'aval/constancias/';
    }
    
    public static function carpetaNota()
    {
        return 'aval/notas/';
    }

    public static function carpetaComprobante()
    {
        return 'aval/comprobantes/';
    }

    public static function carpetaJustificacion()
    {
        return 'aval/justificaciones/';
    }

    public function esImagen()
    {
        return $this->extension!="pdf";
    }

    public function esPdf()
    {
        return $this->extension=="pdf";
    }

    public function getEstatus()
    {
    	switch($this->estatus)
    	{
    		case 'pendiente':
    			return "<span style='border:1px solid red; border-radius:50px;background-color:yellow;color:#424242'>pendiente</span>";
    		break;
    		
    		case 'aceptada':
    			return "<span style='border:1px solid black; border-radius:50px;background-color:green;color:#424242'>aceptada</span>";
    		break;

    		case 'negada':
    			return "<span style='border:1px solid black; border-radius:50px;background-color:red;color:#424242'>negada</span>";
    		break;
    	}
    }

    public function scopeJustificativos($query)
    {
        return $query->where('tipo','=','justificacion');
    }

    public function scopeDePeriodos($query)
    {
        return $query->where('tipo','=','constancia');
    }

    public function scopeDeCVA($query)
    {
        return $query->where('tipo','=','nota');
    }

    public function scopeDeVoluntariados($query)
    {
        return $query->where('tipo','=','comprobante');
    }

    public function scopeConEstatus($query,$estatus)
    {
        return $query->where('estatus','=',$estatus);
    }
}
