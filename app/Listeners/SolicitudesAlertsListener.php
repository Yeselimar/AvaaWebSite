<?php

namespace avaa\Listeners;

use avaa\Alerta;
use avaa\Events\SolicitudesAlerts;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class SolicitudesAlertsListener
{
    public function __construct()
    {
        //
    }

    public function handle(SolicitudesAlerts $event)
    {
        $alerta = new Alerta();
        if(Auth::user()->rol==='coordinador' || Auth::user()->rol==='directivo')
        {
            $alerta->titulo = 'Tiene una respuesta a su solicitud de ' . strtoupper($event->solicitud->titulo);
            $alerta->status='generada';
            $alerta->solicitud= $event->solicitud->id;
            $alerta->descripcion = ' Se le ha respondido la solicitud tipo: '.strtoupper($event->solicitud->titulo).' que realizo el dia '.$event->solicitud->updated_at.' pronto se reflejaran sus cambios solicitados';

        }
        else
        {
            $alerta->titulo = 'Tiene una nueva Solicitud/Reclamo de ' . strtoupper($event->solicitud->titulo) . ' enviada por ' . $event->solicitud->user->name . ' ' . $event->solicitud->user->last_name;
            $alerta->solicitud= $event->solicitud->id;
            $alerta->descripcion = $event->solicitud->user->name . ' ' . $event->solicitud->user->last_name . ' realizo una solicitud se recomienda ir a la opcion de Solicitud/Reclamo y gestionar dicha solicitud';

        }
        $alerta->user_id= $event->solicitud->user_id;

        $alerta->save();
    }
}
