<?php
/**
 * Created by PhpStorm.
 * User: Darwin
 * Date: 01/04/2018
 * Time: 01:50 PM
 */
namespace avaa\Http\ViewComposers;

use avaa\Becario;
use avaa\Desincorporacion;
use avaa\Imagen;
use avaa\Mentor;
use avaa\Solicitud;
use avaa\User;
use Illuminate\Contracts\View\View;
use avaa\Alerta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class NotificacionComposer{

    public function compose(View $view)
    {
        $numPostulantesB=0;
        $numPostulantesM=0;
        $numSolicitudes=0;
        $numSolicitudesDeBecarios=0;
        $numdesincorporaciones=0;
        $numNominas=0;
        $user_id = Auth::user()->id;
        $alertas= null;
        if(Auth::user()->rol==='directivo' || Auth::user()->rol==='coordinador')
        {
            if(Auth::user()->rol==='directivo' )
            {
            //preguntar si querer mostrarle el numero de postulantes  entrevistas
                $prepostulante= Becario::query()->where('status','=','postulante')->orWhere('status','=','entrevista')->orWhere('status','=','entrevistado')->get();

                $numPostulantesB= $prepostulante->count();

                $prepostulanteM= User::query()->where('rol','=','postulante_mentor')->get();

                $numPostulantesM= $prepostulanteM->count();


                //Si hay nominas pendientes
                $nominas = DB::table('nominas')
                    ->select(DB::raw('count(*) as total_becarios,sum(total) as total_pagado, mes, year,created_at,fecha_generada, fecha_pago,sueldo_base, status, id'))
                    ->where('status','=','pendiente')
                    ->groupBy('mes','year')
                    ->orderby('mes','desc')->orderby('year','desc')->get();
                $numNominas= $nominas->count();
    
                //$numdesincorporaciones= Desincorporacion::query()->where('status','=','sin ejecutar')->get()->count();
                $alertas= Alerta::query()->where('user_id','=', Auth::user()->id)->orWhere('tipo','=','nomina')->where('status','=','enviada')->get();

                if($numNominas==0)
                {
                    if($alertas->count()>0)
                    {
                        $alerta= Alerta::query()->where('user_id','=', Auth::user()->id)->orWhere('tipo','=','nomina')->where('status','=','enviada')->where('titulo','=','Nomina(s) pendiente(s) por procesar')->first();
                        if(!is_null($alerta))
                            $alerta->delete();
                        $alertas= Alerta::query()->where('user_id','=', Auth::user()->id)->where('leido','=',false)->get();

                    }
                }
            }

            /*if($numdesincorporaciones==0)
            {
                if($alertas->count()>0)
                {
                    $alerta= Alerta::query()->where('user_id','=', Auth::user()->id)->where('status','=','enviada')->where('titulo','=','Desincorporacion(es) pendiente(s) por procesar')->first();

                   if(!is_null($alerta))
                       $alerta->delete();
                    $alertas= Alerta::query()->where('user_id','=', Auth::user()->id)->where('leido','=',false)->get();

                }
            }
            */


            $becariosAsignados=  Becario::query()->where('acepto_terminos', '=', true)->whereIn('status', ['probatorio1', 'probatorio2', 'activo','inactivo'])->get();
            $beca_to_array= $becariosAsignados->pluck('user_id')->toArray();
            $mentoresNuevos= Mentor::all();
            $collection = collect();
            foreach ($mentoresNuevos as $mentor)
            {

                if($mentor->becarios()->count()==0)
                {
                    $collection->push($mentor);
                }

            }
            $listMentoresA=$collection->pluck('user_id')->toArray();
            $mentoresAsignados= Mentor::query()->whereIn('user_id',$listMentoresA)->get();
            $listaMentoresA= $mentoresAsignados->pluck('user_id')->toArray();
            ///convertir ambos arreglos en un array
            $collectionUnion = collect();
            $collectionUnion->push($beca_to_array)->toArray();
            $collectionUnion->push($listaMentoresA)->toArray();
            $principal=$collectionUnion->toArray()[0];
            $segundo_array=$collectionUnion->toArray()[1];
            foreach ($segundo_array as $s)
            {
                array_push($principal,$s);
            }
            $solicitudesDeBecarios= Solicitud::query()->where('status','=','enviada')
                ->whereIn('user_id',$principal)->get();
            $numSolicitudesDeBecarios= $solicitudesDeBecarios->count();
            //Ademas Las Notificaciones que se le mostraran a los coordinadores tendran status enviada ya que esta es enviada por uno de sus becarios que tiene
            //a su disposicion

            if(Auth::user()->rol==='directivo')
            {
                $alertas = Alerta::where('leido', '=', false)
                ->where('status','=','enviada')->whereIn('user_id',$principal)->orWhere('user_id','=',Auth::user()->id)->orWhere('tipo','=','nomina')->get();
            }
            else
            {

                if(Auth::user()->esCoordinador())
                {
                    $alertas = Alerta::where('leido', '=', false)->where('tipo','<>','nomina')
                    ->where('tipo','=','solicitud')->get();

                    //dd($alertas);
                }
                
            }

        }
        else
        {
            /*if(Auth::user()->rol==='coordinador')
            {
                //ademas solo se le mostrara las solicitudes que tiene a cargo de sus becarios
                $becariosAsignados= Becario::query()->where('coordinador_id','=',Auth::user()->id)->get();

                $beca_to_array= $becariosAsignados->pluck('user_id')->toArray();

                $collection = collect();
                foreach ($becariosAsignados as $beca)
                {

                    $collection->push($beca->mentor);
                }
                $listMentoresA=$collection->pluck('user_id')->toArray();

                $mentoresAsignados= Mentor::query()->whereIn('user_id',$listMentoresA)->get();

                $listaMentoresA= $mentoresAsignados->pluck('user_id')->toArray();

                ///convertir ambos arreglos en un array
                $collectionUnion = collect();
                    $collectionUnion->push($beca_to_array)->toArray();

                     $collectionUnion->push($listaMentoresA)->toArray();

                 $principal=$collectionUnion->toArray()[0];
                 $segundo_array=$collectionUnion->toArray()[1];


                 foreach ($segundo_array as $s)
                 {
                     array_push($principal,$s);
                 }

                $solicitudesDeBecarios= Solicitud::query()->where('status','=','enviada')
                    ->whereIn('user_id',$principal)->get();

                $numSolicitudesDeBecarios= $solicitudesDeBecarios->count();

                //Ademas Las Notificaciones que se le mostraran a los coordinadores tendran status enviada ya que esta es enviada por uno de sus becarios que tiene
                //a su disposicion
                $alertas = Alerta::query()->where('leido', '=', false)
                    ->where('status','=','enviada')->whereIn('user_id',$principal)->get();


            }*/
            if(Auth::user()->rol==='becario' || Auth::user()->rol==='mentor')
            {
                //Las Notificaciones que se le mostraran a los becarios tendran status generado ya que esta es generada por otro ente del sistema
                $alertas = Alerta::where('user_id', '=', $user_id)->where('leido', '=', '0')
                    ->where('status','=','generada')->get();
            }
        }
        if(is_null($alertas))
        {
            $cant_notificaciones=0;
        }
        else
        {
            $cant_notificaciones= $alertas->count();
        }

        if(Auth::user()->esEntrevistador()) // agregado por Rafael
        {

            $alertas = Alerta::where('user_id', '=', Auth::user()->id)->get();
            //dd($alertas);
            $cant_notificaciones = $alertas->count();
        }

        $imagen_perfil= Imagen::query()->where('user_id','=',Auth::user()->id)->where('titulo','=','img_perfil')->get();
        $view->with('alertas',$alertas)->with('numT',$numPostulantesB+$numPostulantesM)->with('cantNotif',$cant_notificaciones)
        ->with('numSoliBecarios',$numSolicitudesDeBecarios)->with('numNominas',$numNominas)->with('image_perfil',$imagen_perfil)->with('numdesincorporaciones',$numdesincorporaciones);
    }
}


