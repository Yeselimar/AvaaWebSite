<?php

namespace avaa\Exports;

use avaa\Nomina;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class NominaPagadaExport implements FromView
{
    public function __construct(int $mes, int $anho)
    {
        $this->mes = $mes;
        $this->anho  = $anho;
    }

    public function view(): View //Igual metodo NominaController:pagadopdf
    {
    	$mes = $this->mes;
    	$anho = $this->anho;
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
    	 $nominas = Nomina::where('mes',$mes)->where('year',$anho)->where('status','=','pagado')->get(); 

    	return view('excel.nomina.pagada', [
            'nominas'=>$nominas,'mes'=>$mes,'anho'=>$anho,'mes_completo'=>$mes_completo
        ]);
    }
}
