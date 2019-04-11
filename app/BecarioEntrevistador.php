<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class BecarioEntrevistador extends Model
{
    protected $table= 'becarios_entrevistadores';

    public function user()// probar
    {
        return $this->belongsTo('avaa\User','becario_id');
    }

    public function becario()// probar
    {
        return $this->belongsTo('avaa\Becario','becario_id');
    }

    public function entrevistador()// probar: La relacion que faltaba
    {
        return $this->belongsTo('avaa\User','entrevistador_id');
    }

    public function scopeParaBecario($query,$id)
    {
        return $query->where('becario_id','=',$id);
    }

    public function scopeParaEntrevistador($query,$id)
    {
        return $query->where('entrevistador_id','=',$id);
    }

    public static function carpetaDocumento()
    {
        return 'documentos/entrevistados/';
    }

    public static function carpetaDocumentoConjunto()
    {
        return 'documentos/entrevistados/conjunto/';
    }

}
