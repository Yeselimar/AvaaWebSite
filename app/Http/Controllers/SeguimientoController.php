<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use DB;
use avaa\Becario;
use avaa\Actividad;
use avaa\ActividadBecario;
use avaa\ActividadFacilitador;
use avaa\Curso;
use avaa\Voluntariado;
use avaa\Periodo;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use avaa\Exports\BecariosReporteGeneralExport;
use avaa\Exports\BecariosReporteTiempoExport;
use avaa\Exports\ResumenBecarioExport;


class SeguimientoController extends Controller
{   
    public function reportetiempo()
    {
        return view('sisbeca.becarios.reporte-tiempo');
    }

    public function reportetiempoapi() //Actulizado el 25/01/2019
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
        return response()->json(['becarios'=>$todos]);
    }

    public function reportetiempoexcel()
    {
        return Excel::download(new BecariosReporteTiempoExport(), 'Reporte Tiempo.xlsx');
    }

    public function reportetiempobecario($id) //Actulizado el 25/01/2019
    {
        $becario = Becario::find($id);
        if(!empty($becario))
        {
            $id = $becario->user->id;
            //para actividades
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
            })->first();//va first

            //return response()->json($periodos);
            //para actividades  facilitadas
            $af = DB::table('actividades')
                ->selectRaw('*')
                ->join('actividades_facilitadores', function ($join) use($id)
            {
            $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
                ->where('actividades.status', '!=', 'suspendido')
                ->where('actividades_facilitadores.becario_id', '=', $id)
                ;
            })->orderby('fecha', 'desc')->first();

            $tiempo_actividades = "Nunca";
            $tiempo_cva = "Nunca";
            $tiempo_voluntariados = "Nunca";
            $tiempo_periodos = "Nunca";
            $tiempo_actividad_facilitada = "Nunca";
            $color_actividad = "#FFEBEE";
            $color_cva = "#FFEBEE";
            $color_voluntariado = "#FFEBEE";
            $color_periodo = "#FFEBEE";
            $color_actividad_facilitada = "#FFEBEE";
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
                                    $color_actividad =   "#E8F5E9";
                                    $puntos += 0;
                                }
                                else
                                {
                                    $tiempo_actividades = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $
                                    $color_actividad =   "#E8F5E9";
                                    $puntos += 1;
                                }
                            }
                            else
                            {
                                $tiempo_actividades = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $color_actividad =   "#E8F5E9";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_actividades = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $color_actividad =   "#E8F5E9";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_actividades = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $color_actividad =   "#FFFDE7";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_actividades = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $color_actividad =   "#FFEBEE";
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
                                    $color_cva =   "#E8F5E9";
                                    $puntos += 0;
                                }
                                else
                                {
                                    $tiempo_cva = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $color_cva =  "#E8F5E9";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_cva = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $color_cva =  "#E8F5E9";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_cva = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $color_cva =  "#E8F5E9";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_cva = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $color_cva =  "#FFFDE7";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_cva = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $color_cva = "#FFEBEE";
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
                                    $color_voluntariado =   "#E8F5E9";
                                    $puntos += 0;
                                }
                                else
                                {
                                    $tiempo_voluntariados = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $color_voluntariado =   "#E8F5E9";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_voluntariados = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $color_voluntariado =   "#E8F5E9";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_voluntariados = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $color_voluntariado =   "#E8F5E9";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_voluntariados = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $color_voluntariado = "#FFFDE7";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_voluntariados = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $color_voluntariado = "#FFEBEE";
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
                                    $color_periodo =   "#E8F5E9";
                                    $puntos += 1;
                                }
                                else
                                {
                                    $tiempo_periodos = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $color_periodo =   "#E8F5E9";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_periodos = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $color_periodo =   "#E8F5E9";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_periodos = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $color_periodo =   "#E8F5E9";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_periodos = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $color_periodo = "#FFFDE7";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_periodos = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $color_periodo = "#FFEBEE";
                    $puntos += 10;
                }
            }
            if(!empty($af))
            {
                $fechafinal = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))));
                $fechainicial = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($af->fecha)));
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
                                    $tiempo_actividad_facilitada = "Hace un momento";
                                    $color_actividad_facilitada =   "#E8F5E9";
                                    $puntos += 1;
                                }
                                else
                                {
                                    $tiempo_actividad_facilitada = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                                    $color_actividad_facilitada =   "#E8F5E9";
                                    $puntos += 0;
                                }
                            }
                            else
                            {
                                $tiempo_actividad_facilitada = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                                $color_actividad_facilitada =   "#E8F5E9";
                                $puntos += 1;
                            }
                        }
                        else
                        {
                            $tiempo_actividad_facilitada = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                            $color_actividad_facilitada =   "#E8F5E9";
                            $puntos += 2;
                        }
                    }
                    else
                    {
                        $tiempo_actividad_facilitada = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
                        $color_actividad_facilitada = "#FFFDE7";
                        $puntos += 5;
                    }
                }
                else
                {
                    $tiempo_actividad_facilitada = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
                    $color_actividad_facilitada = "#FFEBEE";
                    $puntos += 10;
                }
            }
            $becario = array(
                'tiempo_actividades' => $tiempo_actividades,
                'tiempo_cva' => $tiempo_cva,
                'tiempo_voluntariado' => $tiempo_voluntariados,
                'tiempo_periodos' => $tiempo_periodos,
                'tiempo_actividad_facilitada' => $tiempo_actividad_facilitada,
                'color_actividad' => $color_actividad,
                'color_cva' => $color_cva,
                'color_voluntariado'=> $color_voluntariado,
                'color_periodo' => $color_periodo,
                'color_actividad_facilitada' => $color_actividad_facilitada,
                'puntos'  => $puntos,
                "becario" => array(
                   'id' => $becario->user->id,
                   'cedula' => $becario->user->cedula,
                   'nombreyapellido' => $becario->user->nombreyapellido()
                )
            );
            return response()->json(['becario'=>$becario]);
        }
        else
        {
            return response()->json(['error'=>"El becario no existe"]);
        }
        
    }

    public function becariosreportegeneral() // Bien
    {
        //no borrar
        $becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
        return view('sisbeca.becarios.reportegeneral')->with(compact('becarios'));
    }

    public function becariosreportegeneralapi($anho,$mes)//Actualizado el 25/01/2019
    {
        $becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
        $todos = collect();
        foreach ($becarios as $becario)
        {
            $id = $becario->user->id;

            if($mes!='00')
            {
                $periodo = DB::table('periodos')
                    ->where('aval.tipo','=','constancia')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(numero_periodo) as nivel_carrera')
                    ->join('aval', function ($join) use($id)
                {
                $join->on('periodos.aval_id','=','aval.id')
                    ->where('periodos.becario_id','=',$id);
                })->first();

                $voluntariado = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->whereMonth('voluntariados.fecha', '=', $mes)
                    ->orderby('voluntariados.fecha','asc');
                    
                })->first();

                $af = DB::table('actividades')
                ->selectRaw('*,SUM(horas) as total_horas')
                    ->join('actividades_facilitadores', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
                    ->where('actividades.status', '!=', 'suspendido')
                    ->where('actividades_facilitadores.becario_id', '=', $id)
                    ->whereYear('actividades.fecha', '=', $anho)
                    ->whereMonth('actividades.fecha', '=', $mes);
                })->first();

                $total_horas_facilitador = ($af->total_horas==null) ? 0 : $af->total_horas;
                
                $tmp_voluntariado = ($voluntariado->horas_voluntariado==null) ? 0 : $voluntariado->horas_voluntariado;
                $horas_voluntariado = $tmp_voluntariado + $total_horas_facilitador;

                $asistio_t = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','taller')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->whereMonth('actividades_becarios.created_at', '=', $mes)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $asistio_cc = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','chat club')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->whereMonth('actividades_becarios.created_at', '=', $mes)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $curso = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(modulo) as nivel')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ->whereMonth('cursos.created_at', '=', $mes)
                    ;
                })->first();

                $curso_aux = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,AVG(nota) as promedio_modulo')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ->whereMonth('cursos.created_at', '=', $mes)
                    ;
                    
                })->first();
            }
            else
            {
                $periodo = DB::table('periodos')
                    ->where('aval.tipo','=','constancia')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(numero_periodo) as nivel_carrera')
                    ->join('aval', function ($join) use($id)
                {
                $join->on('periodos.aval_id','=','aval.id')
                    ->where('periodos.becario_id','=',$id);
                })->first();

                $voluntariado = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->orderby('voluntariados.fecha','asc');
                    
                })->first();

                $af = DB::table('actividades')
                ->selectRaw('*,SUM(horas) as total_horas')
                    ->join('actividades_facilitadores', function ($join) use($id,$anho)
                {
                $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
                    ->where('actividades.status', '!=', 'suspendido')
                    ->where('actividades_facilitadores.becario_id', '=', $id)
                    ->whereYear('actividades.fecha', '=', $anho);
                })->first();

                $total_horas_facilitador = ($af->total_horas==null) ? 0 : $af->total_horas;

                $tmp_voluntariado = ($voluntariado->horas_voluntariado==null) ? 0 : $voluntariado->horas_voluntariado;
                $horas_voluntariado = $tmp_voluntariado + $total_horas_facilitador;

                $asistio_t = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','taller')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $asistio_cc = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','chat club')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $curso = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(modulo) as nivel')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ;
                })->first();

                $curso_aux = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,AVG(nota) as promedio_modulo')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ;
                    
                })->first();
            }

            if($becario->esAnual())
            {
                $regimen = "año";
            }
            else
            {
                if($becario->esSemestral())
                {
                    $regimen = "semestre";
                }
                else
                {
                    $regimen = "trimestre";
                }
            }

            $todos->push(array(
                'nivel_carrera' => ($periodo->nivel_carrera==null) ? 'N/A' : $periodo->nivel_carrera.' '.$regimen,
                'horas_voluntariados' => $horas_voluntariado,
                'asistio_t' => ($asistio_t->total==null) ? '0' : $asistio_t->total,
                'asistio_cc' => ($asistio_cc->total==null) ? '0' : $asistio_cc->total,
                'nivel_cva' => ($curso->nivel==null) ? 'N/A' : $curso->nivel.' Nivel - '.$curso->modo,
                'avg_cva' => ($curso_aux->promedio_modulo==null)? '0' : $curso_aux->promedio_modulo,
                'avg_academico' => $becario->promediotodosperiodos(),
                "becario" => array(
                   'id' => $becario->user->id,
                   'nombreyapellido' => $becario->user->nombreyapellido()),
            ));
        }
        return response()->json(['becarios'=>$todos]);
    }
    

    public function becariosreportegeneralpdf($anho,$mes)//Actualizado el 25/01/2019
    {
        $becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
        $todos = collect();
        foreach ($becarios as $becario)
        {
            $id = $becario->user->id;
            if($mes!='00')
            {
                $periodo = DB::table('periodos')
                    ->where('aval.tipo','=','constancia')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(numero_periodo) as nivel_carrera')
                    ->join('aval', function ($join) use($id)
                {
                $join->on('periodos.aval_id','=','aval.id')
                    ->where('periodos.becario_id','=',$id);
                })->first();

                $voluntariado = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->whereMonth('voluntariados.fecha', '=', $mes)
                    ->orderby('voluntariados.fecha','asc');
                    
                })->first();

                $af = DB::table('actividades')
                ->selectRaw('*,SUM(horas) as total_horas')
                    ->join('actividades_facilitadores', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
                    ->where('actividades.status', '!=', 'suspendido')
                    ->where('actividades_facilitadores.becario_id', '=', $id)
                    ->whereYear('actividades.fecha', '=', $anho)
                    ->whereMonth('actividades.fecha', '=', $mes);
                })->first();

                $total_horas_facilitador = ($af->total_horas==null) ? 0 : $af->total_horas;

                $tmp_voluntariado = ($voluntariado->horas_voluntariado==null) ? 0 : $voluntariado->horas_voluntariado;
                $horas_voluntariado = $tmp_voluntariado + $total_horas_facilitador;

                $asistio_t = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','taller')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->whereMonth('actividades_becarios.created_at', '=', $mes)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $asistio_cc = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','chat club')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->whereMonth('actividades_becarios.created_at', '=', $mes)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $curso = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(modulo) as nivel')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ->whereMonth('cursos.created_at', '=', $mes)
                    ;
                })->first();

                $curso_aux = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,AVG(nota) as promedio_modulo')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ->whereMonth('cursos.created_at', '=', $mes)
                    ;
                    
                })->first();
            }
            else
            {
                $periodo = DB::table('periodos')
                    ->where('aval.tipo','=','constancia')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(numero_periodo) as nivel_carrera')
                    ->join('aval', function ($join) use($id)
                {
                $join->on('periodos.aval_id','=','aval.id')
                    ->where('periodos.becario_id','=',$id);
                })->first();

                $voluntariado = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->orderby('voluntariados.fecha','asc');
                    
                })->first();

                $af = DB::table('actividades')
                ->selectRaw('*,SUM(horas) as total_horas')
                    ->join('actividades_facilitadores', function ($join) use($id,$anho)
                {
                $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
                    ->where('actividades.status', '!=', 'suspendido')
                    ->where('actividades_facilitadores.becario_id', '=', $id)
                    ->whereYear('actividades.fecha', '=', $anho);
                })->first();

                $total_horas_facilitador = ($af->total_horas==null) ? 0 : $af->total_horas;

                $tmp_voluntariado = ($voluntariado->horas_voluntariado==null) ? 0 : $voluntariado->horas_voluntariado;
                $horas_voluntariado = $tmp_voluntariado + $total_horas_facilitador;

                $asistio_t = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','taller')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $asistio_cc = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total')
                    ->where('tipo','chat club')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                $curso = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,MAX(modulo) as nivel')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ;
                })->first();

                $curso_aux = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->selectRaw('*,AVG(nota) as promedio_modulo')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.created_at', '=', $anho)
                    ;
                    
                })->first();
            }

            if($becario->esAnual())
            {
                $regimen = "año";
            }
            else
            {
                if($becario->esSemestral())
                {
                    $regimen = "semestre";
                }
                else
                {
                    $regimen = "trimestre";
                }
            }

            $todos->push(array(
                'nivel_carrera' => ($periodo->nivel_carrera==null) ? 'N/A' : $periodo->nivel_carrera.' '.$regimen,
                'horas_voluntariados' => $horas_voluntariado,
                'asistio_t' => ($asistio_t->total==null) ? '0' : $asistio_t->total,
                'asistio_cc' => ($asistio_cc->total==null) ? '0' : $asistio_cc->total,
                'nivel_cva' => ($curso->nivel==null) ? 'N/A' : $curso->nivel.' Nivel - '.$curso->modo,
                'avg_cva' => ($curso_aux->promedio_modulo==null)? '0' : $curso_aux->promedio_modulo,
                'avg_academico' => $becario->promediotodosperiodos(),
                "becario" => array(
                   'id' => $becario->user->id,
                   'nombreyapellido' => $becario->user->nombreyapellido()),
            ));
        }
        switch ($mes)
        {
            case '00':
                $mes_completo = "Todos";
            break;
            case '01':
                $mes_completo = "Enero";
            break;
            case '02':
                $mes_completo = "Febrero";
            break;
            case '03':
                $mes_completo = "Marzo";
            break;
            case '04':
                $mes_completo = "Abril";
            break;
            case '05':
                $mes_completo = "Mayo";
            break;
            case '06':
                $mes_completo = "Junio";
            break;
            case '07':
                $mes_completo = "Julio";
            break;
            case '08':
                $mes_completo = "Agosto";
            break;
            case '09':
                $mes_completo = "Septiembre";
            break;
            case '10':
                $mes_completo = "Octubre";
            break;
            case '11':
                $mes_completo = "Noviembre";
            break;
            case '12':
                $mes_completo = "Diciembre";
            break;
        }
        //return response()->json($todos[0]['becario']);
        $pdf = PDF::loadView('pdf.seguimiento.reporte-general-mes-anho', compact('todos','mes_completo','anho'));
        $pdf->setPaper('A4', 'landscape');//PDF Horizontal
        return $pdf->stream('Reporte General - '.$mes_completo.'-'.$anho.'.pdf');
    }

    public function becariosreportegeneralexcel($anho,$mes)
    {
        switch ($mes)
        {
            case '00':
                $mes_completo = "Todos";
            break;
            case '01':
                $mes_completo = "Enero";
            break;
            case '02':
                $mes_completo = "Febrero";
            break;
            case '03':
                $mes_completo = "Marzo";
            break;
            case '04':
                $mes_completo = "Abril";
            break;
            case '05':
                $mes_completo = "Mayo";
            break;
            case '06':
                $mes_completo = "Junio";
            break;
            case '07':
                $mes_completo = "Julio";
            break;
            case '08':
                $mes_completo = "Agosto";
            break;
            case '09':
                $mes_completo = "Septiembre";
            break;
            case '10':
                $mes_completo = "Octubre";
            break;
            case '11':
                $mes_completo = "Noviembre";
            break;
            case '12':
                $mes_completo = "Diciembre";
            break;
        }

        return Excel::download(new BecariosReporteGeneralExport($anho,$mes), 'Reporte General ('.$mes_completo.'-'.$anho.').xlsx');
    }

   	public function todosbecarios()
   	{
        $becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
        return view('sisbeca.becarios.todos')->with(compact('becarios'));
   	}

    public function todosbecariosapi()
    {
        $todos = collect();
        $becarios = Becario::with("user")->activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
        foreach ($becarios as $becario)
        {
            $todos->push(array(
                'id' => $becario->user->id,
                'estatus' => $becario->status,
                'rol' => $becario->user->rol,
                'cedula' => $becario->user->cedula,
                "becario" => $becario->user->name.' '.$becario->user->last_name,
                'final_carga_academica' => $becario->final_carga_academica
            ));
        }
        return response()->json(['becarios'=>$todos]);
    }

    public function obtenerestatusbecarios()
    {
        $estatus = (object)["0"=>"egresado", "1"=>"activo", "2"=>"inactivo","3"=>"probatorio1","4"=>"probatorio2"];
        return response()->json(['estatus'=>$estatus]);
    }

    public function actualizarestatusbecario(Request $request,$id)
    {
        $becario = Becario::find($id);
        $becario->status = $request->estatus;
        $becario->save();
        return response()->json(['success'=>'El becario '.$becario->user->nombreyapellido().' fue actualizado exitosamente.']);
    }

    public function guardarfechaacademica(Request $request,$id)
    {
        $becario = Becario::find($id);
        if($request->fecha_nula==0)
        {
            $becario->final_carga_academica = DateTime::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d');
        }
        else
        {
            $becario->final_carga_academica = null;
        }
       
        $becario->save();
        return response()->json(['success'=>'Al becario '.$becario->user->nombreyapellido().' se le actualizó la fecha final de carga académica.']);
    }

   	public function todosbecariosservicio()
   	{
   		$becarios = Becario::activos()->terminosaceptados()->probatorio1()->probatorio2()->get();
   	}

    public function resumenanhomes($id,$anho,$mes) // Actualizado el 25/01/2019
    {
        $becario = Becario::find($id);
        if(Auth::user()->id==$id or Auth::user()->esCoordinador() or Auth::user()->esDirectivo() or (Auth::user()->esMentor() and Auth::user()->id==$becario->mentor_id))
        {
            if($mes!='00')
            {
                $periodos = Periodo::paraBecario($id)->delAnho($anho)->delMes($mes)->ordenadoPorPeriodo('asc')->with('aval')->with('materias')->get();//corregido
                $voluntariados = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('voluntariados.tipo')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado,Count(*) as total_voluntariado,voluntariados.tipo as tipo_voluntariado')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                    $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->whereMonth('voluntariados.fecha', '=', $mes)
                    ->orderby('voluntariados.fecha','asc')
                    ;
                })->get();
                $cursos = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('cursos.nivel')
                    ->orderby('cursos.modulo','asc')
                    ->selectRaw('*,AVG(nota) as promedio_modulo,Count(*) as total_modulo')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                    $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.fecha_inicio', '=', $anho)//corregido
                    ->whereMonth('cursos.fecha_inicio', '=', $mes)//corregido
                    ;
                })->get();
                $actividades_facilitadas = DB::table('actividades')
                ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
                ->join('actividades_facilitadores', function ($join) use($id,$anho,$mes)
                {
                    $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                        ->where('actividades_facilitadores.becario_id', '=', $id)
                        ->where('actividades.status', '!=', 'suspendido')
                        ->whereYear('actividades_facilitadores.created_at', '=', $anho)
                        ->whereMonth('actividades_facilitadores.created_at', '=', $mes)
                        ;
                })->get();
                // asistio talleres presenciales
                $a_t_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                //asistio talleres virtuales
                $a_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club presenciales
                $a_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club virtuales
                $a_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();
                
                
                //no asistio taller presencial
                $na_t_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio taller virtual
                $na_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio chat club presencial
                $na_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio chat club virtual
                $na_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();
            }
            else
            {
                $periodos = Periodo::paraBecario($id)->delAnho($anho)->ordenadoPorPeriodo('asc')->with('aval')->with('materias')->get();//corregido
                $voluntariados = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('voluntariados.tipo')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado,Count(*) as total_voluntariado,voluntariados.tipo as tipo_voluntariado')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                    $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->orderby('voluntariados.fecha','asc')
                    ;
                })->get();
                $cursos = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('cursos.nivel')
                    ->orderby('cursos.modulo','asc')
                    ->selectRaw('*,AVG(nota) as promedio_modulo,Count(*) as total_modulo')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                    $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.fecha_inicio', '=', $anho)//corregido
                    ;
                })->get();
                //return response()->json($cursos);
                $actividades_facilitadas = DB::table('actividades')
                ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
                ->join('actividades_facilitadores', function ($join) use($id,$anho)
                {
                    $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                        ->where('actividades_facilitadores.becario_id', '=', $id)
                        ->where('actividades.status', '!=', 'suspendido')
                        ->whereYear('actividades_facilitadores.created_at', '=', $anho);
                })->get();
                // asistio talleres presenciales
                $a_t_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                //asistio talleres virtuales
                $a_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club presenciales
                $a_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club virtuales
                $a_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();
                
                
                //no asistio taller presencial
                $na_t_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio taller virtual
                $na_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio chat club presencial
                $na_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio chat club virtual
                $na_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();
                
            }
            $asistio = array(
                "a_t_v" => $a_t_v->total_actividades,
                "a_t_p" => $a_t_p->total_actividades,
                "a_c_v" => $a_c_v->total_actividades,
                "a_c_p" => $a_c_p->total_actividades,
            );
            $noasistio = array(
                "na_t_v" => $na_t_v->total_actividades,
                "na_t_p" => $na_t_p->total_actividades,
                "na_c_v" => $na_c_v->total_actividades,
                "na_c_p" => $na_c_p->total_actividades,
            );
            //return ".l";
            return response()->json(['regimen'=>$becario->regimen,'anho'=>$anho,'mes'=>$mes,'periodos'=>$periodos,'voluntariados'=>$voluntariados,'cursos'=>$cursos,'actividades_facilitadas'=>$actividades_facilitadas[0],"asistio"=>$asistio,"noasistio"=>$noasistio]);
        }

        return response()->json(['error'=>"Error de permisos"]);
    }   

    public function resumenanhomespdf($id,$anho,$mes)
    {
        $becario = Becario::find($id);
        if(Auth::user()->id==$id or Auth::user()->esCoordinador() or Auth::user()->esDirectivo() or (Auth::user()->esMentor() and Auth::user()->id==$becario->mentor_id))
        {
            if($mes!='00')
            {
                $periodos = Periodo::paraBecario($id)->delAnho($anho)->delMes($mes)->ordenadoPorPeriodo('asc')->with('aval')->with('materias')->get();//corregido
                $voluntariados = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('voluntariados.tipo')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado,Count(*) as total_voluntariado,voluntariados.tipo as tipo_voluntariado')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                    $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->whereMonth('voluntariados.fecha', '=', $mes)
                    ->orderby('voluntariados.fecha','asc')
                    ;
                })->get();
                $cursos = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('cursos.nivel')
                    ->orderby('cursos.modulo','asc')
                    ->selectRaw('*,AVG(nota) as promedio_modulo,Count(*) as total_modulo')
                    ->join('aval', function ($join) use($id,$anho,$mes)
                {
                    $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.fecha_inicio', '=', $anho)//corregido
                    ->whereMonth('cursos.fecha_inicio', '=', $mes)//corregido
                    ;
                })->get();
                $actividades_facilitadas = DB::table('actividades')
                ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
                ->join('actividades_facilitadores', function ($join) use($id,$anho,$mes)
                {
                    $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                        ->where('actividades_facilitadores.becario_id', '=', $id)
                        ->where('actividades.status', '!=', 'suspendido')
                        ->whereYear('actividades_facilitadores.created_at', '=', $anho)
                        ->whereMonth('actividades_facilitadores.created_at', '=', $mes)
                        ;
                })->get();
                // asistio talleres presenciales
                $a_t_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                //asistio talleres virtuales
                $a_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club presenciales
                $a_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club virtuales
                $a_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();
                
                
                //no asistio taller presencial
                $na_t_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio taller virtual
                $na_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio chat club presencial
                $na_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio chat club virtual
                $na_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->whereMonth('actividades_becarios.created_at', '=', $mes)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();
            }
            else
            {
                $periodos = Periodo::paraBecario($id)->delAnho($anho)->ordenadoPorPeriodo('asc')->with('aval')->with('materias')->get();//corregido
                $voluntariados = DB::table('voluntariados')
                    ->where('aval.tipo','=','comprobante')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('voluntariados.tipo')
                    ->selectRaw('*,SUM(horas) as horas_voluntariado,Count(*) as total_voluntariado,voluntariados.tipo as tipo_voluntariado')
                    ->join('aval', function ($join) use($id,$anho)
                {
                    $join->on('voluntariados.aval_id','=','aval.id')
                    ->where('voluntariados.becario_id','=',$id)
                    ->whereYear('voluntariados.fecha', '=', $anho)
                    ->orderby('voluntariados.fecha','asc');
                    
                })->get();
                $cursos = DB::table('cursos')
                    ->where('aval.tipo','=','nota')
                    ->where('aval.estatus','=','aceptada')
                    ->groupby('cursos.nivel')
                    ->orderby('cursos.modulo','asc')
                    ->selectRaw('*,AVG(nota) as promedio_modulo,Count(*) as total_modulo')
                    ->join('aval', function ($join) use($id,$anho)
                {
                    $join->on('cursos.aval_id','=','aval.id')
                    ->where('cursos.becario_id','=',$id)
                    ->whereYear('cursos.fecha_inicio', '=', $anho)//corregido
                    ;
                })->get();
                $actividades_facilitadas = DB::table('actividades')
                ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
                ->join('actividades_facilitadores', function ($join) use($id,$anho)
                {
                    $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                        ->where('actividades_facilitadores.becario_id', '=', $id)
                        ->where('actividades.status', '!=', 'suspendido')
                        ->whereYear('actividades_facilitadores.created_at', '=', $anho);
                })->get();
                // asistio talleres presenciales
                $a_t_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');
                })->first();

                //asistio talleres virtuales
                $a_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club presenciales
                $a_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();

                //asistio chat club virtuales
                $a_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','asistio');

                })->first();
                
                //no asistio taller presencial
                $na_t_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio')
                        ;
                })->first();

                //no asistio taller virtual
                $na_t_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio')
                        ;
                })->first();

                //no asistio chat club presencial
                $na_c_p = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();

                //no asistio chat club virtual
                $na_c_v = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
                {
                   $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                        ->where('actividades_becarios.becario_id', '=', $id)
                        ->whereYear('actividades_becarios.created_at', '=', $anho)
                        ->where('actividades_becarios.estatus','=','no asistio');

                })->first();
                
            }
            $asistio = array(
                "a_t_v" => $a_t_v->total_actividades,
                "a_t_p" => $a_t_p->total_actividades,
                "a_c_v" => $a_c_v->total_actividades,
                "a_c_p" => $a_c_p->total_actividades,
            );
            $noasistio = array(
                "na_t_v" => $na_t_v->total_actividades,
                "na_t_p" => $na_t_p->total_actividades,
                "na_c_v" => $na_c_v->total_actividades,
                "na_c_p" => $na_c_p->total_actividades,
            );
            $actividades_facilitadas=$actividades_facilitadas[0];
            switch ($mes)
            {
                case '00':
                    $mes_completo = "Todos";
                break;
                case '01':
                    $mes_completo = "Enero";
                break;
                case '02':
                    $mes_completo = "Febrero";
                break;
                case '03':
                    $mes_completo = "Marzo";
                break;
                case '04':
                    $mes_completo = "Abril";
                break;
                case '05':
                    $mes_completo = "Mayo";
                break;
                case '06':
                    $mes_completo = "Junio";
                break;
                case '07':
                    $mes_completo = "Julio";
                break;
                case '08':
                    $mes_completo = "Agosto";
                break;
                case '09':
                    $mes_completo = "Septiembre";
                break;
                case '10':
                    $mes_completo = "Octubre";
                break;
                case '11':
                    $mes_completo = "Noviembre";
                break;
                case '12':
                    $mes_completo = "Diciembre";
                break;
            }

            
            $pdf = PDF::loadView('pdf.seguimiento.resumen-mes-anho', compact('becario','regimen','anho','mes','periodos','voluntariados','cursos','actividades_facilitadas','asistio','noasistio','mes_completo'));

            return $pdf->stream('Resumen-Becario-'.$mes_completo.'-'.$anho.'-'.$becario->user->nombreyapellido().'.pdf');
        }
    }

    public function resumenanhomesexcel($id,$anho,$mes)
    {
        $becario = Becario::find($id);
        //return $anho;
        switch ($mes)
        {
            case '00':
                $mes_completo = "Todos";
            break;
            case '01':
                $mes_completo = "Enero";
            break;
            case '02':
                $mes_completo = "Febrero";
            break;
            case '03':
                $mes_completo = "Marzo";
            break;
            case '04':
                $mes_completo = "Abril";
            break;
            case '05':
                $mes_completo = "Mayo";
            break;
            case '06':
                $mes_completo = "Junio";
            break;
            case '07':
                $mes_completo = "Julio";
            break;
            case '08':
                $mes_completo = "Agosto";
            break;
            case '09':
                $mes_completo = "Septiembre";
            break;
            case '10':
                $mes_completo = "Octubre";
            break;
            case '11':
                $mes_completo = "Noviembre";
            break;
            case '12':
                $mes_completo = "Diciembre";
            break;
        }
        //php artisan make:export ResumenBecarioExport --model=avaa\Becario
        return Excel::download(new ResumenBecarioExport($id,$anho,$mes), 'Resumen Becario '.$becario->user->nombreyapellido().' ('.$mes_completo.'-'.$anho.').xlsx');
    }

    public function becarioreportegeneral($id)
    {
        $becario = Becario::find($id);
        if( (Auth::user()->id==$id) or Auth::user()->esCoordinador() or Auth::user()->esDirectivo() or (Auth::user()->esMentor() and Auth::user()->id==$becario->mentor_id))
        {
            return view('sisbeca.becarios.reportegeneral-becario')->with(compact('becario'));
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function becarioreportegeneralapi($id,$anho,$mes)
    {
        //$todo = collect();
        $becario = Becario::find($id);
        if($mes!='00')
        {
            $periodo = DB::table('periodos')
                ->where('aval.tipo','=','constancia')
                ->where('aval.estatus','=','aceptada')
                ->orderby('aval.updated_at','desc')
                ->selectRaw('*,numero_periodo as nivel_carrera,periodos.id as periodo_id')
                ->join('aval', function ($join) use($id)
            {
            $join->on('periodos.aval_id','=','aval.id')
                ->where('periodos.becario_id','=',$id);
            })->first();

            $voluntariado = DB::table('voluntariados')
                ->where('aval.tipo','=','comprobante')
                ->where('aval.estatus','=','aceptada')
                ->selectRaw('*,SUM(horas) as horas_voluntariado')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
            $join->on('voluntariados.aval_id','=','aval.id')
                ->where('voluntariados.becario_id','=',$id)
                ->whereYear('voluntariados.fecha', '=', $anho)
                ->whereMonth('voluntariados.fecha', '=', $mes)
                ->orderby('voluntariados.fecha','asc');
                
            })->first();

            $af = DB::table('actividades')
            ->selectRaw('*,SUM(horas) as total_horas')
                ->join('actividades_facilitadores', function ($join) use($id,$anho,$mes)
            {
            $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
                ->where('actividades.status', '!=', 'suspendido')
                ->where('actividades_facilitadores.becario_id', '=', $id)
                ->whereYear('actividades.fecha', '=', $anho)
                ->whereMonth('actividades.fecha', '=', $mes);
            })->first();

            $total_horas_facilitador = ($af->total_horas==null) ? 0 : $af->total_horas;

            $tmp_voluntariado = ($voluntariado->horas_voluntariado==null) ? 0 : $voluntariado->horas_voluntariado;
            $horas_voluntariado = $tmp_voluntariado + $total_horas_facilitador;

            $asistio_t = DB::table('actividades')
                ->selectRaw('*,Count(*) as total')
                ->where('tipo','taller')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
            {
            $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->whereMonth('actividades_becarios.created_at', '=', $mes)
                ->where('actividades_becarios.estatus','=','asistio');
            })->first();

            $asistio_cc = DB::table('actividades')
                ->selectRaw('*,Count(*) as total')
                ->where('tipo','chat club')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
            {
            $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->whereMonth('actividades_becarios.created_at', '=', $mes)
                ->where('actividades_becarios.estatus','=','asistio');
            })->first();

            $curso = DB::table('cursos')
                ->where('aval.tipo','=','nota')
                ->where('aval.estatus','=','aceptada')
                ->selectRaw('*,MAX(modulo) as nivel')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
            $join->on('cursos.aval_id','=','aval.id')
                ->where('cursos.becario_id','=',$id)
                ->whereYear('cursos.created_at', '=', $anho)
                ->whereMonth('cursos.created_at', '=', $mes)
                ;
            })->first();

            $curso_aux = DB::table('cursos')
                ->where('aval.tipo','=','nota')
                ->where('aval.estatus','=','aceptada')
                ->selectRaw('*,AVG(nota) as promedio_modulo')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
            $join->on('cursos.aval_id','=','aval.id')
                ->where('cursos.becario_id','=',$id)
                ->whereYear('cursos.created_at', '=', $anho)
                ->whereMonth('cursos.created_at', '=', $mes)
                ;
                
            })->first();
        }
        else
        {
            $periodo = DB::table('periodos')
                ->where('aval.tipo','=','constancia')
                ->where('aval.estatus','=','aceptada')
                ->orderby('aval.updated_at','desc')
                ->selectRaw('*,numero_periodo as nivel_carrera,periodos.id as periodo_id')
                ->join('aval', function ($join) use($id)
            {
            $join->on('periodos.aval_id','=','aval.id')
                ->where('periodos.becario_id','=',$id);
            })->first();

            $voluntariado = DB::table('voluntariados')
                ->where('aval.tipo','=','comprobante')
                ->where('aval.estatus','=','aceptada')
                ->selectRaw('*,SUM(horas) as horas_voluntariado')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
            $join->on('voluntariados.aval_id','=','aval.id')
                ->where('voluntariados.becario_id','=',$id)
                ->whereYear('voluntariados.fecha', '=', $anho)
                ->orderby('voluntariados.fecha','asc');
                
            })->first();

            $af = DB::table('actividades')
            ->selectRaw('*,SUM(horas) as total_horas')
                ->join('actividades_facilitadores', function ($join) use($id,$anho)
            {
            $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
                ->where('actividades.status', '!=', 'suspendido')
                ->where('actividades_facilitadores.becario_id', '=', $id)
                ->whereYear('actividades.fecha', '=', $anho);
            })->first();

            $total_horas_facilitador = ($af->total_horas==null) ? 0 : $af->total_horas;

            $tmp_voluntariado = ($voluntariado->horas_voluntariado==null) ? 0 : $voluntariado->horas_voluntariado;
            $horas_voluntariado = $tmp_voluntariado + $total_horas_facilitador;

            $asistio_t = DB::table('actividades')
                ->selectRaw('*,Count(*) as total')
                ->where('tipo','taller')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
            {
            $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','asistio');
            })->first();

            $asistio_cc = DB::table('actividades')
                ->selectRaw('*,Count(*) as total')
                ->where('tipo','chat club')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
            {
            $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','asistio');
            })->first();

            $curso = DB::table('cursos')
                ->where('aval.tipo','=','nota')
                ->where('aval.estatus','=','aceptada')
                ->selectRaw('*,MAX(modulo) as nivel')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
            $join->on('cursos.aval_id','=','aval.id')
                ->where('cursos.becario_id','=',$id)
                ->whereYear('cursos.created_at', '=', $anho)
                ;
            })->first();

            $curso_aux = DB::table('cursos')
                ->where('aval.tipo','=','nota')
                ->where('aval.estatus','=','aceptada')
                ->selectRaw('*,AVG(nota) as promedio_modulo')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
            $join->on('cursos.aval_id','=','aval.id')
                ->where('cursos.becario_id','=',$id)
                ->whereYear('cursos.created_at', '=', $anho)
                ;
                
            })->first();
        }
        //return response()->json($periodo);
        $nivel_carrera='-';
        $regimen = '-';
        if($periodo)
        {
            $periodo_tmp = Periodo::find($periodo->periodo_id);
            //return $
            //return response()->json($periodo);
            if($periodo_tmp->esAnual())
            {
                $regimen = "año";
            }
            else
            {
                if($periodo_tmp->esSemestral())
                {
                    $regimen = "semestre";
                }
                else
                {
                    $regimen = "trimestre";
                }
            }
            $nivel_carrera = ($periodo->nivel_carrera==null) ? 'N/A' : $periodo->nivel_carrera.' '.$regimen;
        }

        $todo = array(
            'nivel_carrera' => $nivel_carrera,
            'horas_voluntariados' => $horas_voluntariado,
            'asistio_t' => ($asistio_t->total==null) ? '0' : $asistio_t->total,
            'asistio_cc' => ($asistio_cc->total==null) ? '0' : $asistio_cc->total,
            'nivel_cva' => ($curso->nivel==null) ? 'N/A' : $curso->nivel.' Nivel - '.$curso->modo,
            'avg_cva' => ($curso_aux->promedio_modulo==null)? '0' : $curso_aux->promedio_modulo,
            'avg_academico' => $becario->promediotodosperiodos(),
        );
        return response()->json(['becario'=>$todo]);
    }

   	public function resumen($id)
   	{
   		$anho = date('Y');
        $becario = Becario::find($id);
   		if(Auth::user()->id==$id or Auth::user()->esCoordinador() or Auth::user()->esDirectivo() or (Auth::user()->esMentor() and Auth::user()->id==$becario->mentor_id))
   		{
   			$becario = Becario::find($id);
	   		$periodos = Periodo::paraBecario($id)->seleccionAnho($anho)->ordenadoPorPeriodo('asc')->get();
            // ------------------ Esto esta funcional
    	   		//$voluntariados = Voluntariado::paraBecario($id)->porAnho($anho)->sumaHoras('horas_voluntariado')->contarVoluntariado('total_voluntariado')->agrupadoPorTipo()->get();
    	   		//$cursos = Curso::paraBecario($id)->porAnho($anho)->agrupadoPorModulo()->promedioPorModulo('promedio_modulo')->contarModulo('total_modulo')->get();
            //------------------ Ejemplos de consultas
            //En estas consultas join no se como introducir scope
            //->selectRaw('*,Count(*) as total_actividades')
            //Count(*) as total_cursos
            //AVG(nota) as nota_modulo
            /*$users = DB::table('aval')
                ->leftJoin('cursos', 'aval.becario_id', '=', 'cursos.becario_id')
                ->where('cursos.becario_id',"")
                ->get();*/
            //----------------- Ejemplos de consultas

            $voluntariados = DB::table('voluntariados')
                ->where('aval.tipo','=','comprobante')
                ->where('aval.estatus','=','aceptada')
                ->groupby('voluntariados.tipo')
                ->selectRaw('*,SUM(horas) as horas_voluntariado,Count(*) as total_voluntariado,voluntariados.tipo as tipo_voluntariado')
                ->join('aval', function ($join) use($id,$anho)
            {
              $join->on('voluntariados.aval_id','=','aval.id')
                ->where('voluntariados.becario_id','=',$id)
                ->whereYear('voluntariados.fecha', '=', $anho)
                ->orderby('voluntariados.fecha','asc');
                
            })->get();
            //return $voluntariados;

            $cursos = DB::table('cursos')
                ->where('aval.tipo','=','nota')
                ->where('aval.estatus','=','aceptada')
                ->groupby('cursos.modulo')
                ->orderby('cursos.modulo','asc')
                ->selectRaw('*,AVG(nota) as promedio_modulo,Count(*) as total_modulo')
                ->join('aval', function ($join) use($id,$anho)
            {
              $join->on('cursos.aval_id','=','aval.id')
                ->where('cursos.becario_id','=',$id)
                ->whereYear('cursos.created_at', '=', $anho)
                ;
                
            })->get();
            //return $cursos;

            /* Sirve pero no del todo
            $periodos = Periodo::with('materias')->
                where('aval.tipo','=','constancia')
                ->where('aval.estatus','=','aceptada')
                ->groupby('periodos.numero_periodo')
                ->selectRaw('*,Count(*) as total_modulo')
                ->join('aval', function ($join) use($id,$anho)
            {
              $join->on('periodos.aval_id','=','aval.id')
                ->where('periodos.becario_id','=',$id)
                ->whereYear('periodos.created_at', '=', $anho)
                ->orderby('periodos.numero_periodo','desc');

                
            })->get();*/
            //return $periodos;

            $actividades_facilitadas = DB::table('actividades')
                    ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
                    ->join('actividades_facilitadores', function ($join) use($id,$anho)
            {
                $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                    ->where('actividades_facilitadores.becario_id', '=', $id)
                    ->where('actividades.status', '!=', 'suspendido')
                    ->whereYear('actividades_facilitadores.created_at', '=', $anho);

            })->get();
            //return $actividades_facilitadas;

            // asistio talleres presenciales
    	   	$a_t_p = DB::table('actividades')
            ->selectRaw('*,Count(*) as total_actividades')
            ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');
            })->first();

            //asistio talleres virtuales
            $a_t_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');

            })->first();

            //asistio chat club presenciales
            $a_c_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');

            })->first();

            //asistio chat club virtuales
            $a_c_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','asistio');

            })->first();
            
            
            //no asistio taller presencial
            $na_t_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','no asistio');

            })->first();

            //no asistio taller virtual
            $na_t_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','no asistio');

            })->first();

            //no asistio chat club presencial
            $na_c_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','no asistio');

            })->first();

            //no asistio chat club virtual
            $na_c_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','no asistio');

            })->first();
            
            //---------------- Como estaban antes las consultas para Actividades 
            /*
            $actividades_noasistio = DB::table('actividades')
                    ->selectRaw('*,Count(*) as total_actividades')
                    ->groupby('tipo','modalidad')->join('actividades_becarios', function ($join) use($id,$anho)
            {
               $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                    ->where('actividades_becarios.becario_id', '=', $id)
                    ->whereYear('actividades_becarios.created_at', '=', $anho)
                    ->where('actividades_becarios.estatus','=','no asistio');

            })->first();*/

	   		return view('sisbeca.becarios.resumen')->with(compact('becario','anho','periodos','voluntariados','cursos','actividades_facilitadas','a_t_p','a_t_v','a_c_p','a_c_v','na_t_p','na_t_v','na_c_p','na_c_v'));
   		}
   		else
   		{
   			return view('sisbeca.error.404');
   		}
   	}

    

    public function resumenpdf($id)//no usado
    {
        $anho = date('Y');
        $becario = Becario::find($id);
        $periodos = Periodo::paraBecario($id)->porAnho($anho)->ordenadoPorPeriodo('asc')->get();
        $voluntariados = DB::table('voluntariados')
            ->where('aval.tipo','=','comprobante')
            ->where('aval.estatus','=','aceptada')
            ->groupby('voluntariados.tipo')
            ->selectRaw('*,SUM(horas) as horas_voluntariado,Count(*) as total_voluntariado,voluntariados.tipo as tipo_voluntariado')
            ->join('aval', function ($join) use($id,$anho)
        {
          $join->on('voluntariados.aval_id','=','aval.id')
            ->where('voluntariados.becario_id','=',$id)
            ->whereYear('voluntariados.created_at', '=', $anho)
            ->orderby('voluntariados.created_at','asc');
            
        })->get();
        $cursos = DB::table('cursos')
            ->where('aval.tipo','=','nota')
            ->where('aval.estatus','=','aceptada')
            ->groupby('cursos.modulo')
            ->orderby('cursos.modulo','asc')
            ->selectRaw('*,AVG(nota) as promedio_modulo,Count(*) as total_modulo')
            ->join('aval', function ($join) use($id,$anho)
        {
          $join->on('cursos.aval_id','=','aval.id')
            ->where('cursos.becario_id','=',$id)
            ->whereYear('cursos.created_at', '=', $anho)
            ;
            
        })->get();
        $actividades_facilitadas = DB::table('actividades')
                ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
                ->join('actividades_facilitadores', function ($join) use($id,$anho)
        {
            $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                ->where('actividades_facilitadores.becario_id', '=', $id)
                ->where('actividades.status', '!=', 'suspendido')
                ->whereYear('actividades_facilitadores.created_at', '=', $anho);

        })->get();
        //return $actividades_facilitadas;

        // asistio talleres presenciales
        $a_t_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','asistio');

        })->first();

        //asistio talleres virtuales
        $a_t_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','asistio');

        })->first();

        //asistio chat club presenciales
        $a_c_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','asistio');

        })->first();

        //asistio chat club virtuales
        $a_c_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','asistio');

        })->first();
        
        
        //no asistio taller presencial
        $na_t_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','no asistio');

        })->first();

        //no asistio taller virtual
        $na_t_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','taller')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','no asistio');

        })->first();

        //no asistio chat club presencial
        $na_c_p = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','presencial')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','no asistio');

        })->first();

        //no asistio chat club virtual
        $na_c_v = DB::table('actividades')
                ->selectRaw('*,Count(*) as total_actividades')
                ->where('tipo','chat club')->where('modalidad','virtual')->join('actividades_becarios', function ($join) use($id,$anho)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                ->where('actividades_becarios.estatus','=','no asistio');

        })->first();

        $pdf = PDF::loadView('pdf.seguimiento.resumen', compact('becario','anho','periodos','voluntariados','cursos','actividades_facilitadas','a_t_p','a_t_v','a_c_p','a_c_v','na_t_p','na_t_v','na_c_p','na_c_v'));
        return $pdf->stream('resumen-becario.pdf');
    }
}
