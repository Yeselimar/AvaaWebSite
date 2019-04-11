<?php

namespace avaa\Exports;

use avaa\Becario;
use avaa\Periodo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Illuminate\Support\Facades\Auth;

class ResumenBecarioExport implements FromView
{
	public function __construct(int $id,int $anho, int $mes)
    {
    	$this->id = $id;
        $this->mes = $mes;
        $this->anho  = $anho;
    }

     public function view(): View //Igual metodo SeguimietoController:resumenanhomespdf
    {
    	$id = $this->id;
    	$mes = $this->mes;
    	$anho = $this->anho;

    	
        $becario = Becario::find($id);
        //dd($becario);
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
            //$pdf = PDF::loadView('pdf.seguimiento.resumen-mes-anho', compact('becario','regimen','anho','mes','periodos','voluntariados','cursos','actividades_facilitadas','asistio','noasistio','mes_completo'));
            //dd($voluntariados);
            return view('excel.seguimiento.resumenbecario', compact('becario','regimen','anho','mes','periodos','voluntariados','cursos','actividades_facilitadas','asistio','noasistio','mes_completo'));
        }
    }
}
