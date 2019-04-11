<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table='noticias';

    public function editor()
    {
        return $this->belongsTo('avaa\User','user_id');
    }

    protected $fillable = [
        'titulo', 'contenido', 'tipo','url_articulo','informacion_contacto','email_contacto','telefono_contacto',
    ];

    public function scopeSearch($query,$titulo)
    {
        return $query->where('titulo','LIKE',"%$titulo%");
    }

    public static function getSlug($string)
    {
        $separator='-';
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and', "'" => '');
        $string = mb_strtolower( trim( $string ), 'UTF-8' );
        $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
        $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }

    public function fechaActualizacion()
    {
        return date("d/m/Y h:iA", strtotime($this->updated_at));
    }

    public function fechaActualizacionCorta()
    {
        return date("d/m/Y", strtotime($this->updated_at));
    }

    public function esDestacada()
    {
        return $this->al_carrousel==1;
    }
    
}
