<?php

namespace avaa\Exports;

use avaa\Becario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use DateTime;

class BecariosReporteTiempoExport implements FromView
{
     public function view(): View //Igual metodo SeguimietoController:reportetiempoapi
    {
    	$becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
        $todos = collect();
        foreach ($becarios as $becario)
        {
            $id = $becario->user->id;
            $actividades = DB::table('actividades')
                ->join('actividades_becarios', function ($join) use($id)
            {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->where('actividades_becarios.estatus','=','asistio')
                    ;
            }) ->orderby('fecha', 'desc')->first();
            //para cva
            $cursos = DB::table('cursos')
                ->orderby('cursos.created_at', 'desc')
                ->selectRaw('*,cursos.created_at as fecha')
                ->join('aval', function ($join) use($id)
            {
                $join->on('cursos.aval_id','=','aval.id')
                ->where('aval.tipo','=','nota')
                ->where('aval.estatus','=','aceptada')
                ->where('cursos.becario_id','=',$id)
                ;
            })->first();
            //para voluntariados
            $voluntariados = DB::table('voluntariados')
                ->orderby('voluntariados.fecha','desc')
                ->join('aval', function ($join) use($id)
            {
                $join->on('voluntariados.aval_id','=','aval.id')
                ->where('voluntariados.becario_id','=',$id)
                 ->where('aval.tipo','=','comprobante')
                ->where('aval.estatus','=','aceptada')
                ;
            })->first();
            //para periodos
            $periodos = DB::table('periodos')
                ->orderby('periodos.fecha_inicio','desc')
                ->selectRaw('*,periodos.fecha_inicio as fecha,periodos.created_at as fecha_carga')
                ->join('aval', function ($join) use($id)
            {
                $join->on('periodos.aval_id','=','aval.id')
                ->where('periodos.becario_id','=',$id)
                 ->where('aval.tipo','=','constancia')
                ->where('aval.estatus','=','aceptada')
                ;
            })->first();
            $tiempo_actividades = "Nunca";
            $tiempo_cva = "Nunca";
            $tiempo_voluntariados = "Nunca";
            $tiempo_periodos = "Nunca";
            $puntos = 0;
            if(!empty($actividades))
            {
                $fechafinal = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))));
                $fechainicial = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($actividades->fecha)));
                $desde = $fechainicial->diff($fechafinal);
                
                if($desde->y==0)//año
                {
                    if($desde->m==0)//mes
                    {
                        if($desde->d==0)//días
                        {
                            if($desde->h==0)//horas
                            {
                                if($desde->i==0)//minutos
                                {
                                    $tiempo_actividades = "Hace un momento";
                                    $puntos += 0;
                                }
                                else
                                {
                                    $tiempo_actividades = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_actividades = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_actividades = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_actividades = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_actividades = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $puntos += 10;
                }
            }
            if(!empty($cursos))
            {
                $fechafinal = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))));
                $fechainicial = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($cursos->fecha)));
                $desde = $fechainicial->diff($fechafinal);
                
                if($desde->y==0)//año
                {
                    if($desde->m==0)//mes
                    {
                        if($desde->d==0)//días
                        {
                            if($desde->h==0)//horas
                            {
                                if($desde->i==0)//minutos
                                {
                                    $tiempo_cva = "Hace un momento";
                                    $puntos += 0;
                                }
                                else
                                {
                                    $tiempo_cva = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_cva = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_cva = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_cva = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_cva = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $puntos += 10;
                }
            }
            if(!empty($voluntariados))
            {
                $fechafinal = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))));
                $fechainicial = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($voluntariados->fecha)));
                $desde = $fechainicial->diff($fechafinal);
                
                if($desde->y==0)//año
                {
                    if($desde->m==0)//mes
                    {
                        if($desde->d==0)//días
                        {
                            if($desde->h==0)//horas
                            {
                                if($desde->i==0)//minutos
                                {
                                    $tiempo_voluntariados = "Hace un momento";
                                    $puntos += 0;
                                }
                                else
                                {
                                    $tiempo_voluntariados = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_voluntariados = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_voluntariados = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_voluntariados = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_voluntariados = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $puntos += 10;
                }
            }
            if(!empty($periodos))
            {
                $fechafinal = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))));
                $fechainicial = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($periodos->fecha_carga)));
                $desde = $fechainicial->diff($fechafinal);
                
                if($desde->y==0)//año
                {
                    if($desde->m==0)//mes
                    {
                        if($desde->d==0)//días
                        {
                            if($desde->h==0)//horas
                            {
                                if($desde->i==0)//minutos
                                {
                                    $tiempo_periodos = "Hace un momento";
                                    $puntos += 0;
                                }
                                else
                                {
                                    $tiempo_periodos = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_periodos = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_periodos = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_periodos = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_periodos = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $puntos += 10;
                }
            }

            $todos->push(array(
                'tiempo_actividades' => $tiempo_actividades,
                'tiempo_cva' => $tiempo_cva,
                'tiempo_voluntariado' => $tiempo_voluntariados,
                'tiempo_periodos' => $tiempo_periodos,
                'puntos' => $puntos,
                "becario" => array(
                   'id' => $becario->user->id,
                   'cedula' => $becario->user->cedula,
                   'nombreyapellido' => $becario->user->nombreyapellido()
                ),
            ));
        }
        
        return view('excel.seguimiento.reportetiempo', 
        [
            'todos'=>$todos
        ]);
    }
}
