<?php

namespace avaa\Http\Controllers;

use avaa\Alerta;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use avaa\Becario;
use avaa\Nomina;
use avaa\NomBorrador;
use avaa\BecarioNomBorrador;
use avaa\Costo;
use avaa\User;
use avaa\BecarioNomina;
use avaa\FactLibro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use avaa\Http\Requests\NominaRequest;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use avaa\Exports\NominaGeneradaExport;
use avaa\Exports\NominaPagadaExport;



class NominaController extends Controller
{
	public function __construct()
    {
        $this->middleware('compartido_direc_coord');
    }

    public function listar()
    {
        /*$ultimodia = date("Y-m-d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1));
        $fechagenerar = strtotime ( '-1 day' , strtotime ( $ultimodia ) ) ;//--poner-5
        $fechagenerar = date ( 'Y-m-d' , $fechagenerar );
        $hoy = date('Y-m-d');
        $generar = false;
        $nominasaux = Nomina::where('mes',date('m'))->where('year',date('Y'))->get();
        if($hoy == $fechagenerar and count($nominasaux)==0)
        {
            $generar = true;
        }
        $anho = date ( 'Y' , strtotime($fechagenerar) );
        $mes = date ( 'm' , strtotime($fechagenerar) );

        $nominas = DB::table('nominas')
                     ->select(DB::raw('count(*) as total_becarios,sum(total) as total_pagado, mes, year,fecha_generada, fecha_pago,sueldo_base, status, id'))
                     ->where('status','=','generado')
                     ->groupBy('mes','year')
                     ->orderby('mes','desc')->orderby('year','desc')->get();

       */
        return View('sisbeca.nomina.listar');
    }

    public function listarver($mes, $anho)
    {
        $nominasfiltro = Nomina::where('mes','=',$mes)->where('year',$anho)->where('status','=','generado')->get();
        $nominasfiltro->each(function ($nominasfiltro){
        $nominasfiltro->becarios;
            foreach ($nominasfiltro->becarios as $becario)
            {

                $becario->user;
            }
        });
        return View('sisbeca.nomina.listarver')->with('nominas',$nominasfiltro)->with('mes',$mes)->with('anho',$anho);
    }

    public function listarpagadas($mes, $anho)
    {
        $nominasfiltro = Nomina::where('mes','=',$mes)->where('year',$anho)->where('status','=','pagado')->get();

        $nominasfiltro->each(function ($nominasfiltro){
        
            $nominasfiltro->becarios;

            foreach ($nominasfiltro->becarios as $becario)
            {

                $becario->user;
            }
        });
        return View('sisbeca.nomina.listarpagados')->with('nominas',$nominasfiltro)->with('mes',$mes)->with('anho',$anho);
    }

    public function procesar()
    {
        return View('sisbeca.nomina.procesar');
        /*
        //$ultimodia = date("Y-m-d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1));
        $ultimodia= '2018-04-30';//prueba
        $fechagenerar = strtotime ( '-5 day' , strtotime ( $ultimodia ) ) ;//--poner-5
        $fechagenerar = date ( 'Y-m-d' , $fechagenerar );
        //$hoy = date('Y-m-d');
        $hoy='2018-04-25';//prueba
        $generar = false;
        $nominasaux = Nomina::where('mes','04')->where('year','2018')->get();

        if($hoy == $fechagenerar and count($nominasaux)==0)
        {
            $generar = true;
        }
        $anho = '2018';
        $mes = '04';
        $generar = true;//quitar esto, Eso lo agrego Rafael
        $nominas = DB::table('nominas')
            ->select(DB::raw('count(*) as total_becarios,sum(total) as total_pagado, mes, year,created_at,fecha_generada, fecha_pago,sueldo_base, status, id'))
            ->where('status','=','pendiente')
            ->groupBy('mes','year')
            ->orderby('mes','desc')->orderby('year','desc')->get();
        return View('sisbeca.nomina.procesar')->with('nominas',$nominas)->with('generar',$generar)->with('mes',$mes)->with('anho',$anho);
        
        /*
        $ultimodia = date("Y-m-d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1));
        $fechagenerar = strtotime ( '-5 day' , strtotime ( $ultimodia ) ) ;//--poner-5
        $fechagenerar = date ( 'Y-m-d' , $fechagenerar );

        $hoy = date('Y-m-d');
        $generar = false;
        $nominasaux = Nomina::where('mes',date('m'))->where('year',date('Y'))->get();

        if($hoy == $fechagenerar and count($nominasaux)==0)
        {
            $generar = true;
        }
        $anho = date ( 'Y' , strtotime($fechagenerar) );
        $mes = date ( 'm' , strtotime($fechagenerar) );
        $nominas = DB::table('nominas')
                     ->select(DB::raw('count(*) as total_becarios,sum(total) as total_pagado, mes, year,fecha_generada, fecha_pago,sueldo_base, status, id'))
                     ->where('status','=','pendiente')
                     ->groupBy('mes','year')
                     ->orderby('mes','desc')->orderby('year','desc')->get();
        return View('sisbeca.nomina.procesar')->with('nominas',$nominas)->with('generar',$generar)->with('mes',$mes)->with('anho',$anho);
    */
    }

    public function procesardetalle($mes, $anho)
    {
        //return $mes.'-'.$anho;
        $nominasfiltro = Nomina::where('mes','=',$mes)->where('year',$anho)->get();
        //return $nominasfiltro;
    	/*$ultimodia = date("Y-m-d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1));
        $fechagenerar = strtotime ( '-3 day' , strtotime ( $ultimodia ) ) ;
        $fechagenerar = date ( 'Y-m-d' , $fechagenerar );
        $hoy = date('Y-m-d');
        $generar = false;
        if($hoy == $fechagenerar)
        {
            //verificar que ya fue generada esta nomina
            $generar = true;
        }
        $anho = date ( 'Y' , strtotime($fechagenerar) );
        $mes = date ( 'm' , strtotime($fechagenerar) );*/
        //return $nominasfiltro->becarios
        //$user= User::find(1);
        //return $user->a;
        //return $nominasfiltro[1]->becarios;

        $nominasfiltro->each(function ($nominasfiltro)
        {
            $nominasfiltro->becarios;

            foreach ($nominasfiltro->becarios as $becario) {

                $becario->user;
            }
        });
        if($nominasfiltro->count()==0)
        {
           // flash('Error en la nomina a Consultar no existen registros','danger');
        }
        
    	return View('sisbeca.nomina.procesardetalle')->with('nominas',$nominasfiltro)->with('mes',$mes)->with('anho',$anho);
    	
    }

    public function procesardetalleservicio($mes,$anho)
    {
        //este metodo es igual al procesadetalle

        $nominasfiltro = Nomina::where('mes','=',$mes)->where('year',$anho)->with("becario")->with("user")->get();
        foreach($nominasfiltro as $nomina)
        {
            //$nomina["total_facturas"] = DB::select('select *, sum(precio_unitario*cantidad) AS preciototal, sum(precio_unitario) AS preciounitario, sum(precio_unitario*alicuota) AS iva from reservas_items where id_reserva="'.$reserva->id_reserva.'" group by id_tipohabitacion, pax');;
            $nomina["numero_facturas"] = FactLibro::where('mes','=',$mes)->where('year','=',$anho)->where('becario_id','=',$nomina->datos_id)->count();
        }
       
        return response()->json(['nominas'=>$nominasfiltro]);
    }
    public function updateNominaApi(Request $request)
    {
        $mes = $request->mes;
        $year = $request->year;
        $nominasaux = Nomina::where('mes',$mes)->where('year',$year)->get();
        $hoy = date('Y-m-d H:m:s');

        if(count($nominasaux))
        {
    
            Nomina::where('mes',$mes)->where('year',$year)->where('status','generado')->delete();
            foreach($request->nomina as $n)
            {
                $nomina = new Nomina();
                $nomina->retroactivo = $n['nomina']['retroactivo'];
                $nomina->datos_nombres= $n['nomina']['datos_nombres'];
                $nomina->datos_apellidos = $n['nomina']['datos_apellidos'];
                $nomina->datos_cedula =  $n['nomina']['datos_cedula'];
                $nomina->datos_email = $n['nomina']['datos_email'];
                $nomina->datos_cuenta = $n['nomina']['datos_cuenta'];
                $nomina->datos_id= $n['nomina']['datos_id'];
                $nomina->sueldo_base = $n['nomina']['sueldo_base'];
                $nomina->datos_status = $n['status_becario']; 
                $nomina->datos_fecha_ingreso =  $n['fecha_ingreso'];
                $nomina->datos_final_carga_academica =  $n['final_carga_academica'];
                $nomina->datos_fecha_bienvenida =  $n['fecha_bienvenida'];
                foreach($n['facturas'] as $factlibro)
                {
                    $factura = FactLibro::find($factlibro['factura']['id']);
                    if($factlibro['factura']['status'] == 'por procesar' )
                    {
                        $factura->status = 'procesada';
                        $factura->mes = $mes;
                        $factura->year = $year;
                        $factura->fecha_procesada = $hoy;
                    }
                    else
                    {
                        if($factlibro['factura']['status'] == 'rechazada')
                        {
                            $factura->fecha_procesada = $hoy;
                        }
                        $factura->status= $factlibro['factura']['status'];
                    }
                    $factura->save();
                }
                $nomina->monto_libros = $n['nomina']['monto_libros'];
                $nomina->cva = $n['nomina']['cva'];
                $nomina->total = $n['nomina']['sueldo_base'] + $n['nomina']['retroactivo'] + $n['nomina']['monto_libros'] + $n['nomina']['cva'];
                $nomina->mes = $mes;
                $nomina->year = $year;
                $nomina->status = 'generado';
                $nomina->fecha_pago = null;
                $nomina->fecha_generada = $n['nomina']['fecha_generada'];
                $nomina->save();

                $bn = new BecarioNomina();
                $bn->user_id = $nomina->datos_id;//--becario_id
                $bn->nomina_id = $nomina->id;
                $bn->save();
                
            }

             $nominaBorrador=NomBorrador::where('mes',$mes)->where('year',$year)->where('status','generado')->get(); 
            if(count($nominaBorrador)){
                NomBorrador::where('mes',$mes)->where('year',$year)->where('status','generado')->delete();

            }
            foreach($request->nominaBorrador as $n)
            {
                $nomina = new NomBorrador();
                $nomina->retroactivo = $n['nomina']['retroactivo'];
                $nomina->datos_nombres= $n['nomina']['datos_nombres'];
                $nomina->datos_apellidos = $n['nomina']['datos_apellidos'];
                $nomina->datos_cedula =  $n['nomina']['datos_cedula'];
                $nomina->datos_email = $n['nomina']['datos_email'];
                $nomina->datos_cuenta = $n['nomina']['datos_cuenta'];
                $nomina->datos_id= $n['nomina']['datos_id'];
                $nomina->sueldo_base = $n['nomina']['sueldo_base'] ;
                $nomina->datos_status = $n['status_becario']; 
                $nomina->datos_fecha_ingreso =  $n['fecha_ingreso'];
                $nomina->datos_final_carga_academica =  $n['final_carga_academica'];
                $nomina->datos_fecha_bienvenida =  $n['fecha_bienvenida'];

                $nomina->monto_libros = 0;
                $nomina->cva = 0;
                $nomina->total = $n['nomina']['sueldo_base'] + 0 + 0 + 0;
                $nomina->mes = $mes;
                $nomina->year = $year;
                $nomina->status = 'generado';
                $nomina->fecha_pago = null;
                $nomina->fecha_generada = $n['nomina']['fecha_generada'];
                $nomina->save();

                $bn = new BecarioNomBorrador();
                $bn->user_id = $nomina->datos_id;//--becario_id
                $bn->nomborrador_id = $nomina->id;
                $bn->save();
                
            }


            return response()->json(['res'=>1]);
        }
        else
        {
            return response()->json(['res'=>0]);
        }

   }
    public function generarNominaApi(Request $request)
    {
        
        $mes = $request->mes;
        $year = $request->year;
        $nominasaux = Nomina::where('mes',$mes)->where('year',$year)->get();
        if(count($nominasaux) == 0)
        {
    
            $hoy = date('Y-m-d H:m:s');
            foreach($request->nomina as $n)
            {
                $nomina = new Nomina();
                $nomina->retroactivo = $n['nomina']['retroactivo'];
                $nomina->datos_nombres= $n['nomina']['datos_nombres'];
                $nomina->datos_apellidos = $n['nomina']['datos_apellidos'];
                $nomina->datos_cedula =  $n['nomina']['datos_cedula'];
                $nomina->datos_email = $n['nomina']['datos_email'];
                $nomina->datos_cuenta = $n['nomina']['datos_cuenta'];
                $nomina->datos_id= $n['nomina']['datos_id'];
                $nomina->sueldo_base = $n['nomina']['sueldo_base'];
                $nomina->datos_status = $n['status_becario']; 
                $nomina->datos_fecha_ingreso =  $n['fecha_ingreso'];
                $nomina->datos_final_carga_academica =  $n['final_carga_academica'];
                $nomina->datos_fecha_bienvenida =  $n['fecha_bienvenida'];
                foreach($n['facturas'] as $factlibro)
                {
                    $factura = FactLibro::find($factlibro['factura']['id']);
                    if($factlibro['factura']['status'] == 'por procesar' )
                    {
                        $factura->status = 'procesada';
                        $factura->mes = $mes;
                        $factura->year = $year;
                        $factura->fecha_procesada = $hoy;
                    }
                    else
                    {
                        if($factlibro['factura']['status'] == 'rechazada')
                        {
                            $factura->fecha_procesada = $hoy;
                        }
                        $factura->status= $factlibro['factura']['status'];
                    }
                    $factura->save();
                }
                $nomina->monto_libros = $n['nomina']['monto_libros'];
                $nomina->cva = $n['nomina']['cva'];
                $nomina->total = $n['nomina']['sueldo_base'] + $n['nomina']['retroactivo'] + $n['nomina']['monto_libros'] + $n['nomina']['cva'];
                $nomina->mes = $mes;
                $nomina->year = $year;
                $nomina->status = 'generado';
                $nomina->fecha_pago = null;
                $nomina->fecha_generada = $hoy;
                $nomina->save();

                $bn = new BecarioNomina();
                $bn->user_id = $nomina->datos_id;//--becario_id
                $bn->nomina_id = $nomina->id;
                $bn->save();
                
            }

             $nominaBorrador=NomBorrador::where('mes',$mes)->where('year',$year)->get(); 
            if(count($nominaBorrador) == 0){
                foreach($request->nominaBorrador as $n)
                {
                    $nomina = new NomBorrador();
                    $nomina->retroactivo = $n['nomina']['retroactivo'];
                    $nomina->datos_nombres= $n['nomina']['datos_nombres'];
                    $nomina->datos_apellidos = $n['nomina']['datos_apellidos'];
                    $nomina->datos_cedula =  $n['nomina']['datos_cedula'];
                    $nomina->datos_email = $n['nomina']['datos_email'];
                    $nomina->datos_cuenta = $n['nomina']['datos_cuenta'];
                    $nomina->datos_id= $n['nomina']['datos_id'];
                    $nomina->sueldo_base = $n['nomina']['sueldo_base'] ;
                    $nomina->datos_status = $n['status_becario']; 
                    $nomina->datos_fecha_ingreso =  $n['fecha_ingreso'];
                    $nomina->datos_final_carga_academica =  $n['final_carga_academica'];
                    $nomina->datos_fecha_bienvenida =  $n['fecha_bienvenida'];
  
                    $nomina->monto_libros = 0;
                    $nomina->cva = 0;
                    $nomina->total = $n['nomina']['sueldo_base'] + 0 + 0 + 0;
                    $nomina->mes = $mes;
                    $nomina->year = $year;
                    $nomina->status = 'generado';
                    $nomina->fecha_pago = null;
                    $nomina->fecha_generada = $hoy;
                    $nomina->save();
    
                    $bn = new BecarioNomBorrador();
                    $bn->user_id = $nomina->datos_id;//--becario_id
                    $bn->nomborrador_id = $nomina->id;
                    $bn->save();
                    
                }
            }


            return response()->json(['res'=>1]);
        }
        else
        {
            return response()->json(['res'=>0]);
        }

   }
   public function getEditarNominaApi($mes, $year)
   {

       $nominasaux = Nomina::where('mes',$mes)->where('year',$year)->get();
       if(count($nominasaux))
       {
           $sugeridos = collect();
           $noSugeridos = collect();
           foreach($nominasaux as $nominaEdit)
           {
               $nomina = new Nomina();
               $nomina->retroactivo = $nominaEdit->retroactivo;
               $nomina->cva = $nominaEdit->cva;
               $nomina->datos_nombres= $nominaEdit->datos_nombres;
               $nomina->datos_apellidos = $nominaEdit->datos_apellidos;
               $nomina->datos_cedula = $nominaEdit->datos_cedula;
               $nomina->datos_email = $nominaEdit->datos_email;
               $nomina->datos_cuenta = $nominaEdit->datos_cuenta;
               $nomina->datos_id= $nominaEdit->datos_id;
               $nomina->sueldo_base = $nominaEdit->sueldo_base;
               $nomina->datos_status = $nominaEdit->datos_status; 
               $nomina->datos_fecha_ingreso = $nominaEdit->datos_fecha_ingreso;
               $nomina->datos_final_carga_academica = $nominaEdit->datos_final_carga_academica;
               $nomina->datos_fecha_bienvenida = $nominaEdit->datos_fecha_bienvenida;
               $total = 0;
               $facturas = collect();
               $becario = Becario::find($nomina->datos_id);

               foreach($becario->factlibros as $factlibro)
               {
                   if($factlibro->status === 'cargada' || $factlibro->status === 'por procesar')
                   {
                       if($factlibro->status === 'por procesar')
                       {
                           $total = $total + $factlibro->costo;
                       }
                       $facturas->push(array(
                          "id" => count($facturas),
                          "factura" => $factlibro,
                          'selected' => false
                       ));
                  }
      
               }
               $nomina->monto_libros = $nominaEdit->monto_libros;
               $nomina->total = $nominaEdit->total;
               $nomina->mes = $nominaEdit->mes;
               $nomina->year = $nominaEdit->year;
               $nomina->status =  $nominaEdit->status;
               $nomina->fecha_pago =  $nominaEdit->fecha_pago;
               $nomina->fecha_generada =  $nominaEdit->fecha_generada;

                 $sugeridos->push(array(
                   "id" => count($sugeridos),
                   "nomina" => $nomina,
                   "facturas" => $facturas,
                   "is_sugerido" => true,
                   "status_becario" => $nomina->datos_status,
                   'final_carga_academica' => $nomina->datos_final_carga_academica,
                   'fecha_bienvenida' => $nomina->datos_fecha_bienvenida,
                   'fecha_ingreso' => $nomina->datos_fecha_ingreso,
                   'selected' => false

                   ));


           }

           $nominasaux = NomBorrador::where('mes',$mes)->where('year',$year)->get();
            if(count($nominasaux))
            {
                foreach($nominasaux as $nominaEdit)
                {
                    $nomina = new NomBorrador();
                    $nomina->retroactivo = $nominaEdit->retroactivo;
                    $nomina->cva = $nominaEdit->cva;
                    $nomina->datos_nombres= $nominaEdit->datos_nombres;
                    $nomina->datos_apellidos = $nominaEdit->datos_apellidos;
                    $nomina->datos_cedula = $nominaEdit->datos_cedula;
                    $nomina->datos_email = $nominaEdit->datos_email;
                    $nomina->datos_cuenta = $nominaEdit->datos_cuenta;
                    $nomina->datos_id= $nominaEdit->datos_id;
                    $nomina->sueldo_base = $nominaEdit->sueldo_base;
                    $nomina->datos_status = $nominaEdit->datos_status; 
                    $nomina->datos_fecha_ingreso = $nominaEdit->datos_fecha_ingreso;
                    $nomina->datos_final_carga_academica = $nominaEdit->datos_final_carga_academica;
                    $nomina->datos_fecha_bienvenida = $nominaEdit->datos_fecha_bienvenida;
                    $total = 0;
                    $facturas = collect();
                    $becario = Becario::find($nomina->datos_id);

                    foreach($becario->factlibros as $factlibro)
                    {
                        if($factlibro->status === 'cargada' || $factlibro->status === 'por procesar')
                        {
                            if($factlibro->status === 'por procesar')
                            {
                                $total = $total + $factlibro->costo;
                            }
                            $facturas->push(array(
                                "id" => count($facturas),
                                "factura" => $factlibro,
                                'selected' => false
                            ));
                        }
            
                    }
                    $nomina->monto_libros = $nominaEdit->monto_libros;
                    $nomina->total = $nominaEdit->total;
                    $nomina->mes = $nominaEdit->mes;
                    $nomina->year = $nominaEdit->year;
                    $nomina->status =  $nominaEdit->status;
                    $nomina->fecha_pago =  $nominaEdit->fecha_pago;
                    $nomina->fecha_generada =  $nominaEdit->fecha_generada;

                    $noSugeridos->push(array(
                        "id" => count($noSugeridos),
                        "nomina" => $nomina,
                        "facturas" => $facturas,
                        "is_sugerido" => false,
                        "status_becario" => $nomina->datos_status,
                        'final_carga_academica' => $nomina->datos_final_carga_academica,
                        'fecha_bienvenida' => $nomina->datos_fecha_bienvenida,
                        'fecha_ingreso' => $nomina->datos_fecha_ingreso,
                        'selected' => false
                    ));
                }
            }
           return response()->json(['sugeridos'=>$sugeridos,'noSugeridos'=>$noSugeridos,'res'=> 2]);

       }
       else
       {
           return response()->json(['res'=> 0]);
       }
   }
    public function getConsultarNominaApi($mes, $year)
    {

        $nominasaux = Nomina::where('mes',$mes)->where('year',$year)->get();
        if(count($nominasaux)==0)
        {
            $becarios = Becario::whereIn('status',['activo','probatorio1','probatorio2','egresado'])->get();
            $costo = Costo::first();
            $sugeridos = collect();
            $noSugeridos = collect();
            foreach($becarios as $becario)
            {
                $inNomina = false;
                $diff = 0;
                $date1 = new DateTime($becario->fecha_bienvenida);
                $date2 = new DateTime();
                // Entran en nomina aquellos becarios que su fecha de bienvendida sea mayor a un mes
                if($becario->fecha_bienvenida != null)
                {
                    $diff = $date1->diff($date2);

                    if(($diff->invert == 0) && ($diff->m > 0) || ($diff->y > 0))
                    {
                        $inNomina =true;
                    }
                    else
                    {
                        $inNomina =false;
                    }
                }

                if($inNomina)
                {
                    //Entran en nomina aquellos becarios cuyo fecha fin de carga academica no sea  mayor a 6 meses
                    $diff = 0;
                    $date1 = new DateTime($becario->final_carga_academica);
                    $date2 = new DateTime();
                    if ($becario->final_carga_academica != null)
                    {
                        $diff = $date2->diff($date1);
                        if(($diff->invert == 0) || (($diff->invert ==1) && ($diff->m <= 6) && ($diff->y == 0)) )
                        {
                            $inNomina =true;
                        }
                        else
                        {
                            $inNomina =false;
                        }
                    }
                    else
                    {
                        $inNomina = true;
                        if($becario->status === 'egresado')
                        {
                            $inNomina = false;
                        }
                    }
                }

                $nomina = new Nomina();
                $nomina->retroactivo = 0;
                $nomina->cva = 0;
                $nomina->datos_nombres= $becario->user->name;
                $nomina->datos_apellidos = $becario->user->last_name;
                $nomina->datos_cedula = $becario->user->cedula;
                $nomina->datos_email = $becario->user->email;
                $nomina->datos_cuenta = $becario->cuenta_bancaria;
                $nomina->datos_id= $becario->user_id;
                /*$becario->retroactivo=0;//se inicializa el retroactivo en 0 ya que fue cargado para el futuro pago
                $becario->save(); */
                $nomina->sueldo_base = $costo->sueldo_becario;
                $total = 0;
                $facturas = collect();
                foreach($becario->factlibros as $factlibro)
                {
                    if($factlibro->status === 'cargada' || $factlibro->status === 'por procesar')
                    {
                        if($factlibro->status === 'por procesar')
                        {
                            $total = $total + $factlibro->costo;
                        }
                        $facturas->push(array(
                           "id" => count($facturas),
                           "factura" => $factlibro,
                           'selected' => false
                        ));
                   }
       
                }
                $nomina->monto_libros = $total;
                $nomina->total = $nomina->sueldo_base + $nomina->retroactivo + $nomina->monto_libros;
                $nomina->mes = $mes;
                $nomina->year = $year;
                $nomina->status = 'pendiente';
                $nomina->fecha_pago = null;
                $nomina->fecha_generada = null;

                if ($inNomina)
                {
                  $sugeridos->push(array(
                    "id" => count($sugeridos),
                    "nomina" => $nomina,
                    "facturas" => $facturas,
                    "is_sugerido" => true,
                    "status_becario" => $becario->status,
                    'final_carga_academica' => $becario->final_carga_academica,
                    'fecha_bienvenida' => $becario->fecha_bienvenida,
                    'fecha_ingreso' => $becario->fecha_ingreso,
                    'selected' => false

                    ));
                }
                else
                {
                    $noSugeridos->push(array(
                    "id" => count($noSugeridos),
                    "nomina" => $nomina,
                    "facturas" => $facturas,
                    "is_sugerido" => false,
                    "status_becario" => $becario->status,
                    'final_carga_academica' => $becario->final_carga_academica,
                    'fecha_bienvenida' => $becario->fecha_bienvenida,
                    'fecha_ingreso' => $becario->fecha_ingreso,
                    'selected' => false
                    ));

                }


            }
            return response()->json(['sugeridos'=>$sugeridos,'noSugeridos'=>$noSugeridos,'res'=> 2]);

        }
        else
        {
            return response()->json(['res'=> 0]);
        }
    }

    public function listarNominasApi()
    {
        $nominas = DB::table('nominas')
         ->select(DB::raw('count(*) as total_becarios,sum(total) as total_pagado, mes, year,fecha_generada, fecha_pago,sueldo_base, status, id'))
         ->where('status','=','generado')
         ->groupBy('mes','year')
         ->orderby('mes','desc')->orderby('year','desc')->get();
         $nominasPagadas = DB::table('nominas')
                     ->select(DB::raw('count(*) as total_becarios,sum(total) as total_pagado, mes, year,fecha_generada, fecha_pago,sueldo_base, status, id'))
                     ->where('status','=','pagado')
                     ->groupBy('mes','year')
                     ->orderby('mes','desc')->orderby('year','desc')->get();
        if(count($nominas)>0 || count($nominasPagadas)>0)
        {
            return response()->json(['nominas'=>$nominas,'nominasPagadas'=>$nominasPagadas,'res'=> 1]);

        }
        else
        {
            return response()->json(['res'=> 0]);
        }
    }
    public function getConsultarFacturasBecarioApi($id)
    { 
        $becario = Becario::find($id);
        $facturasAA = collect();
        foreach($becario->factlibros as $factlibro)
        {
            if($factlibro->status === 'cargada' || $factlibro->status === 'por procesar')
            {
                $facturasAA->push(array(
                    "id" => count($facturasAA),
                    "factura" => $factlibro,
                    'selected' => false
                 ));
            }
        }
        return response()->json(['facturasAA'=>$facturasAA,'res'=> 1]);
    }

    public function generartodo($mes,$anho)
    {
    	//validar que no se haya generado para una fecha
    	//$becarios = Becario::where('acepto_terminos','=',1)->where('status','=','activo')->get();
    	//$costo = Costo::first();
        /*$control = Control::first();
        if(is_null($control))
        {
            cantidad=0;
        }*/
        //$control = count(Nomina::groupby('mes','year')->get());
        $anho = date ( 'Y' , strtotime(date('Y-m-d H:i:s')) );
        $mes = date ( 'm' , strtotime(date('Y-m-d H:i:s')) );
        $nominasaux = Nomina::where('mes',$mes)->where('year',$anho)->get();

        if(count($nominasaux)==0)
        {
            $becarios = Becario::where('acepto_terminos','=',1)->where('status','=','activo')->get();
            $costo = Costo::first();

            foreach($becarios as $becario)
            {
                $inNomina = false;
                $diff = 0;
                $date1 = new DateTime($becario->fecha_bienvenida);
                $date2 = new DateTime();
                // Entran en nomina aquellos becarios que su fecha de bienvendida sea mayor a un mes
                if($becario->fecha_bienvenida != null)
                {
                    $diff = $date1->diff($date2);

                    if(($diff->invert == 0) && ($diff->m > 0) || ($diff->y > 0))
                    {
                        $inNomina =true;
                    }
                    else {
                        $inNomina =false;
                    }
                }

                if($inNomina)
                {
                    //Entran en nomina aquellos becarios cuyo fecha fin de carga academica no sea  mayor a 6 meses
                    $diff = 0;
                    $date1 = new DateTime($becario->final_carga_academica);
                    $date2 = new DateTime();
                    if ($becario->final_carga_academica != null)
                    {
                        $diff = $date2->diff($date1);
                        if(($diff->invert == 0) || (($diff->invert ==1) && ($diff->m <= 6) && ($diff->y == 0)) )
                        {
                            $inNomina =true;
                        }
                        else
                        {
                            $inNomina =false;
                        }
                    }
                    else
                    {
                        $inNomina = true;
                    }
                }

                if ($inNomina)
                {
                   $nomina = new Nomina();
                   $nomina->retroactivo = $becario->retroactivo;
                   $nomina->datos_nombres= $becario->user->name;
                   $nomina->datos_apellidos = $becario->user->last_name;
                   $nomina->datos_cedula = $becario->user->cedula;
                   $nomina->datos_email = $becario->user->email;
                   $nomina->datos_cuenta = $becario->cuenta_bancaria;
                   $nomina->datos_id= $becario->user_id;
                   $becario->retroactivo=0;//se inicializa el retroactivo en 0 ya que fue cargado para el futuro pago
                   $becario->save();
                   $nomina->sueldo_base = $costo->sueldo_becario;
                   $total = 0;
                   foreach($becario->factlibros as $factlibro)
                   {
                       if($factlibro->status === 'cargada')
                       {
                           //$total = $total + $factlibro->costo; //se comenta porque no se sumara aún
                           $factlibro->status='por procesar';
                           $factlibro->save();
                       }
                   }
                   $nomina->monto_libros = $total;
                   $nomina->total = $nomina->sueldo_base + $nomina->retroactivo + $total;
                   $nomina->mes = $mes;
                   $nomina->year = $anho;
                   $nomina->status = 'pendiente';
                   $nomina->fecha_pago = null;
                   $nomina->fecha_generada = null;
                   $nomina->save();

                   $bn = new BecarioNomina();
                   $bn->user_id = $becario->user_id;//--becario_id
                   $bn->nomina_id = $nomina->id;
                   $bn->save();
                }


            }
            /*Enviar Alerta al directivo */
            //primero veo si la alerta existe
            if ($inNomina)
            {
               $alerta= Alerta::query()->where('titulo','=','Nomina(s) pendiente(s) por procesar')->where('tipo','=','nomina')->where('status','=','enviada')->first();
               if(!is_null($alerta))
               {
                   $alerta->leido=false;
                   $alerta->save();
               }
               else
               {
                   $alerta = new Alerta();
                   $alerta->titulo= 'Nomina(s) pendiente(s) por procesar';
                   $alerta->descripcion= 'Tiene Nomina(s) pendiente(s) por procesar, se le aconseja procesar las nominas que tiene pendiente en el Menu de Nominas->Por Procesar';
                   $alerta->user_id=null;
                   $alerta->tipo = 'nomina';
                   $alerta->nivel='alto';
                   $alerta->save();
               }
            }

        }
        /* $control->cont = $control->cont+1
        $control->save();*/
        flash("La nómina del ".$mes."/".$anho." esta lista para ser procesada.",'success');
    	return  redirect()->route('nomina.procesar');
    }

    public function generadopdf($mes,$anho)
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
        $nominas = Nomina::where('mes',$mes)->where('year',$anho)->where('status','=','generado')->get();
        $pdf = PDF::loadView('sisbeca.nomina.generadopdf', compact('nominas','mes','anho','mes_completo'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream('Nómina Generada '.$mes_completo.'-'.$anho.'.pdf','PDF');
    }

    public function pagadopdf($mes,$anho)
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
        $nominas = Nomina::where('mes',$mes)->where('year',$anho)->where('status','=','pagado')->get(); 
        $pdf = PDF::loadView('sisbeca.nomina.pagadopdf', compact('nominas','mes','anho','mes_completo'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Nómina Pagada '.$mes_completo.'-'.$anho.'.pdf','PDF');
    }

    public function cambiar()
    {
    	return -9;
    }

    public function generar(Request $request,$mes, $anho)
    {
        $nominas = Nomina::where('mes','=',$mes)->where('year','=',$anho)->get();
        foreach($nominas as $nomina)
        {
            $nomina->status = 'generado';
            $nomina->fecha_generada = date('Y-m-d H:m:s');
            $nomina->total = $nomina->cva+$nomina->sueldo_base+$nomina->retroactivo+$nomina->monto_libros;
            $nomina->save();
        }
        flash('La nómina fue generada exitosamente.','success');
        $nominas = Nomina::where('mes',$mes)->where('year',$anho)->get(); 
        $pdf = PDF::loadView('sisbeca.nomina.pdf', compact('nominas','mes','anho'));
        //return $pdf->download('nomina-pendiente.pdf',array('Attachment'=>0));
        return redirect()->route('nomina.listar');
    }

    public function pagar($mes,$anho)
    {
        $nominas = Nomina::where('mes','=',$mes)->where('year','=',$anho)->where('status','=','generado')->get();
        foreach($nominas as $nomina)
        { 
            $nomina->status = 'pagado';
            $nomina->fecha_pago = date('Y-m-d H:m:s');
            $nomina->save();
            // Alerta esto puede generar error
            //$becario = Becario::find($nomina->becarios[0]->user_id);
            $becario = Becario::find($nomina->datos_id);
            if(!is_null($becario))
            {
                $becario->retroactivo = 0.0;
                $becario->save();
                $factlibros = FactLibro::where('becario_id', '=', $nomina->becarios[0]->user->id)->where('status', '=', 'procesada')->where('mes', '=', $mes)->where('year', '=', $anho)->get();
                foreach ($factlibros as $factura)
                {
                    $factura->status = 'pagada';
                    $factura->fecha_pagada = date('Y-m-d H:m:s');
                    $factura->save();
                }
            }
        }
        //return redirect()->route('nomina.pagadas');
        return response()->json(['res'=> 1]);

    }

    public function pagadas()
    {
        $ultimodia = date("Y-m-d",(mktime(0,0,0,date('m')+1,1,date('Y'))-1));
        $fechagenerar = strtotime ( '-1 day' , strtotime ( $ultimodia ) ) ;//--poner-5
        $fechagenerar = date ( 'Y-m-d' , $fechagenerar );
        $hoy = date('Y-m-d');
        $generar = false;
        $nominasaux = Nomina::where('mes',date('m'))->where('year',date('Y'))->get();
        if($hoy == $fechagenerar and count($nominasaux)==0)
        {
            $generar = true;
        }
        $anho = date ( 'Y' , strtotime($fechagenerar) );
        $mes = date ( 'm' , strtotime($fechagenerar) );
        $nominas = DB::table('nominas')
                     ->select(DB::raw('count(*) as total_becarios,sum(total) as total_pagado, mes, year,fecha_generada, fecha_pago,sueldo_base, status, id'))
                     ->where('status','=','pagado')
                     ->groupBy('mes','year')
                     ->orderby('mes','desc')->orderby('year','desc')->get();
        return view('sisbeca.nomina.pagadas')->with('nominas',$nominas)->with('generar',$generar)->with('mes',$mes)->with('anho',$anho);
    }

    public function validarFacturas(Request $request,$mes,$anho,$id)
    {
	    $becario= Becario::find($id);
	    $nominasAProcesar=$becario->nominas;
        $nomina = $nominasAProcesar->where('mes','=',$mes)->where('year','=',$anho)->where('status','=','pendiente')->first(); //Nomina a Actualizar campo libros
        if(!is_null($nomina))
        {
            $factLibros = FactLibro::query()->where('becario_id', '=', $id)->where('status', '=', 'por procesar')->get();
            $total= $nomina->total;
            $libros= $nomina->monto_libros;
            foreach ($factLibros as $libro)
            {
                if ($request->get($libro->id) == 1)
                {
                    $libro->status= 'revisada';
                    $libro->mes=$mes;
                    $libro->year=$anho;
                    $libros +=$libro->costo;
                }
                else
                {
                    $libro->status= 'rechazada';
                }
                $libro->save();
            }
            $total += $libros;
            $nomina->total= $total;
            $nomina->monto_libros=$libros;
            $nomina->save();
            flash('Las Facturas de '.$becario->user->name.' '.$becario->user->apellido.' Han sido revisadas y procesadas exitosamente!','success')->important();
        }
        return redirect()->route('nomina.procesar.detalle',array('$mes'=>$mes, 'anho'=>$anho));
    }

    //servicio para guardar CVA
    public function guardarCVA(Request $request, $id)
    {
        $request->validate([
            'cva' => 'required|numeric|between:0,9999999999',
        ]);
        $nomina = Nomina::find($id);
        $nomina->cva = $request->cva;
        $nomina->save();
        return response()->json(['success'=>'El monto CVA para '.$nomina->datos_nombres.' '.$nomina->datos_apellidos.' fue guardado exitosamente']);
    }

    //servicio para guardar CVA
    public function guardarRetroactivo(Request $request, $id)
    {
        $request->validate([
            'retroactivo' => 'required|numeric|between:0,9999999999',
        ]);
        $nomina = Nomina::find($id);
        $nomina->retroactivo = $request->retroactivo;
        $nomina->save();
        return response()->json(['success'=>'El monto retroactivo para '.$nomina->datos_nombres.' '.$nomina->datos_apellidos.' fue guardado exitosamente']);
    }

    //Reporte Excel de  Nómia  generada
    public function nominageneradaexcel($mes,$anho)
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
        return Excel::download(new NominaGeneradaExport($mes,$anho), 'Nómina Generada '.$mes_completo.'-'.$anho.'.xlsx');
    }

    public function nominapagadaexcel($mes,$anho)
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
        return Excel::download(new NominaPagadaExport($mes,$anho), 'Nómina Pagada '.$mes_completo.'-'.$anho.'.xlsx');
    }
}
