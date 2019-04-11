<?php

namespace avaa\Exports;

use avaa\Becario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class BecariosReporteGeneralExport implements FromView
{
    public function __construct(int $anho, int $mes)
    {
        $this->mes = $mes;
        $this->anho  = $anho;
    }

    public function view(): View //Igual metodo SeguimietoController:becariosreportegeneralpdf
    {
    	$mes = $this->mes;
    	$anho = $this->anho;
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
                    'nombreyapellido' => $becario->user->nombreyapellido(),
                	'cedula' => $becario->user->cedula),
                    
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
        return view('excel.seguimiento.reportegeneral', [
            'todos'=>$todos,'mes_completo'=>$mes_completo,'anho'=>$anho
        ]);
    }
}
