<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table='cursos';

    public function becario() //relacion buena
    {
        return $this->belongsTo('avaa\Becario','becario_id');
    }

    public function usuario()//relacion buena
    {
        return $this->belongsTo('avaa\User','becario_id');
    }
    
    public function tipocurso() //relacion buena
    {
        return $this->hasOne('avaa\TipoCurso', 'id','tipocurso_id');
    }

    public function aval()//relacion buena
    {
        return $this->hasOne('avaa\Aval', 'id','aval_id');
    }
    
    public function institucion()//creo que no va
    {
        return $this->belongsTo('avaa\Institucion','institucion_id');
    }

    public function notas()//creo que no va
    {
        return $this->hasMany('avaa\Nota','curso_id');
    }

    public function scopeParaBecario($query,$id)
    {
        return $query->where('becario_id', '=', $id);
    }

    public function scopeParaAval($query,$id)
    {
        return $query->where('aval_id', '=', $id);
    }

    public function scopePorAnho($query,$anho)
    {
        return $query->whereYear('created_at', '=', $anho);
    }
    
    public function scopeAgrupadoPorModulo($query)
    {
        return $query->groupby('modulo');
    }

    public function scopePromedioPorModulo($query,$nombre_as)
    {
        return $query->selectRaw('*,avg(nota) as '.$nombre_as);
    }

    public function scopeContarModulo($query,$nombre_as)
    {
        return $query->selectRaw('Count(*) as '.$nombre_as);
    }

    public function getIdCurso()
    {
        return $this->nivel." - ".$this->modo." - ".$this->modulo;
    }

    public function getNota()
    {
        return number_format($this->nota, 2, '.', ',');
    }

    public function getNivel()
    {
        if($this->nivel=='basico')
        {
            $nivel = "Básico";
        }
        else
        {
            if($this->nivel=='intermedio')
            {
                $nivel = "Intermedio";
            }
            else
            {
                $nivel = "Avanzado";
            }
        }
        return $nivel;
    }

    public function getModulo()
    {
        return $this->modulo." Nivel";
    }

    public function getModo()
    {
        return ucwords($this->modo);
    }

    public function getMes()
    {
        return date("m", strtotime($this->fecha_inicio));
    }

    public function getAnho()
    {
        return date("Y", strtotime($this->fecha_inicio));
    }
}
