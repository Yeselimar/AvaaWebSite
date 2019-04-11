<?php

namespace avaa;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $primaryKey = 'id';

    protected $fillable = [
        'name', 'email', 'password','rol','cedula','last_name',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function nombreyapellido()
    {
        return $this->name." ".$this->last_name;
    }

    public function admin()
    {
        return $this->rol==='admin';
    }

    public function esBecario()
    {
        return $this->rol==='becario';
    }

    public function esPostulanteBecario()
    {
        return $this->rol==='postulante_becario';
    }

    public function esEntrevistador()
    {
        return $this->rol==='entrevistador';
    }

    public function esEditor()
    {
        return $this->rol==='editor';
    }

    public function esCoordinador()
    {
        return $this->rol==='coordinador';
    }

    public function esDirectivo()
    {
        return $this->rol==='directivo';
    }

    public function esMentor()
    {
        return $this->rol==='mentor';
    }

    public function esPostulanteMentor()
    {
        return $this->rol==='postulante_mentor';
    }

    public function esSoporte()
    {
        return $this->rol==='soporte';
    }

    public function coordinador()//para la relación de 1 a 1 que tiene con la tabla coordinadores (de llegar a tener)
    {
        return $this->hasOne('avaa\Coordinador','user_id');
    }

    public function mentor()//para la relación de 1 a 1 que tiene con la tabla mentores (de llegar a tener)
    {
        return $this->hasOne('avaa\Mentor','user_id');
    }

    //para la relación de 1 a 1 que tiene con la tabla becarios (de llegar a tener)
    public function becario()
    {
        return $this->hasOne('avaa\Becario','user_id');
    }

    //para la relación de 1 a 1 que tiene con la tabla editores (de llegar a tener)
    public function editor()
    {
        return $this->hasOne('avaa\Editor','user_id');
    }

    public function imagenes()
    {
        return $this->hasMany('avaa\Imagen','user_id');
    }

    public function alertas() //relación buena
    {
        return $this->hasMany('avaa\Alerta','user_id');
    }

    public function solicitudes()
    {
        return $this->hasMany('avaa\Solicitud','user_id');
    }

    public function documentos()
    {
        return $this->hasMany('avaa\Documento','user_id');
    }

    // cuando el rol es entrevistador puedo acceder a mis entrevistados
    public function entrevistados()//probar relacion
    {
        return $this->belongsToMany('avaa\Becario','becarios_entrevistadores','entrevistador_id','becario_id');
    }
    
    public function tickets()// relacion buena
    {
        return $this->hasMany('avaa\Ticket','usuario_genero_id');
    }
    
    public function ticketsrespondidos()// relacion buena
    {
        return $this->hasMany('avaa\Ticket','usuario_respuesta_id');
    }

    //scope para buscar los usuario con rol entrevistadores
    public function scopeEntrevistadores($query)
    {
        return $query->where('rol','=','entrevistador');
    }
    
    public function getFechaNacimiento()
    {
        return date("d/m/Y", strtotime($this->fecha_nacimiento));
    }

    public function getEdad()
    {
        return $this->edad.' años';
    }

    public static function findByToken($token)
    {
        $user = User::where('remember_token','=',$token)->first();
        if($user)
        {
            return $user;
        }
        else
            return false;
    }

    public function getRol()
    {
        switch ($this->rol)
        {
            case 'admin':
                $rol = 'Administrador';
                break;
            case 'directivo':
                $rol = 'Directivo';
                break;
            case 'rechazado':
                $rol = 'Rechazado';
                break;
            case 'coordinador':
                $rol = 'Coordinador';
                break;
            case 'mentor':
                $rol = 'Mentor';
                break;
            case 'postulante_becario':
                $rol = 'Postulante Becario';
                break;
            case 'postulante_mentor':
                $rol = 'Postulante Mentor';
                break;
            case 'pre_postulante_mentor':
                $rol = 'Pre Postulante Mentor';
                break;
            case 'becario':
                $rol = 'Becario';
                break;
            case 'editor':
                $rol = 'Editor';
                break;
            case 'mentor desincorporado':
                $rol = 'Mentor Desincorporado';
                break;
            case 'becario desincorporado':
                $rol = 'Becario Desincorporado';
                break;
            case 'entrevistador':
                $rol = 'Entrevistador';
                break;
             case 'soporte':
                $rol = 'Soporte';
                break;
            default:
                $rol = 'Rol no encontrado';
                break;
        }
        return $rol;
    }

    public static function carpeta()
    {
        return 'carpeta';
    }
}
