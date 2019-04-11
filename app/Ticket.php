<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Ticket extends Model
{
    protected $table='tickets';

    public function usuariogenero()// relacion buena
    {
        return $this->belongsTo('avaa\User','usuario_genero_id');
    }

    public function usuariorespuesta()// relacion buena
    {
        return $this->belongsTo('avaa\User','usuario_respuesta_id');
    }

    public static function carpeta()
    {
        return 'images/tickets/';
    }

    public function getNro()
    {
        return '#'.str_pad($this->id, 4, "0", STR_PAD_LEFT);
    }

    public function getEstatus()
    {
        switch ($this->estatus)
        {
            case 'enviado':
                $estatus = "Enviado";
                break;
            case 'en revision':
                $estatus = "En revisión";
                break;
            case 'cerrado':
                $estatus = "Cerrado";
                break;
            default:
                $estatus = 'Estatus no encontrado';
                break;
        }
        return $estatus;
    }

    public function fechaGenerado()
    {
        return date("d/m/Y", strtotime($this->created_at));
    }

    public function horaGenerado()
    {
        return date("h:i A", strtotime($this->created_at));
    }

    public function fechaNotificado()
    {
        return date("d/m/Y", strtotime($this->fecha_notificado));
    }

    public function horaNotificado()
    {
        return date("h:i A", strtotime($this->fecha_notificado));
    }
}