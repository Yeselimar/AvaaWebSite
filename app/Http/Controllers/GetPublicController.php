<?php

namespace avaa\Http\Controllers;

use avaa\Costo;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use avaa\BecarioEntrevistador;
use avaa\Noticia;
use avaa\User;
use avaa\Becario;
use avaa\Actividad;
use avaa\ActividadBecario;
use avaa\ActividadFacilitador;
use avaa\Voluntariado;
use avaa\Curso;
use avaa\Materia;
use avaa\Documento;
use avaa\FactLibro;
use avaa\Editor;
use avaa\Periodo;
use avaa\Coordinador;
use avaa\Mentor;
use avaa\Aval;
use avaa\Solicitud;
use avaa\Imagen;
use avaa\Ticket;
use avaa\Alerta;
use avaa\RecesoDecembrino;
use avaa\Mensaje;

use Illuminate\Support\Facades\DB;
use Redirect;
use Yajra\Datatables\Datatables;
use Mail;
use File;
use DateTime;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Auth;

class GetPublicController extends Controller
{
    public function enviarcorreo(Request $request)
    {
        return response()->json(['nombre'=>"exitoo!!"]);

        /*
        $data = array(
            "nombre_completo" => "Ivan Delgado",
            "correo" => "rafael1delgado@hotmail.com",
            "telefono" => "04265556677",
            "asunto" => "Urgente",
            "mensaje" => "hola adios",
            "fecha_hora" => date("d/m/Y H:i A"),
        );
        $mail = new PHPMailer(true);
        try
        {
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Timeout  = 60;
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "TLS";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->Username = "delgadorafael2011@gmail.com";
            $mail->Password = "scxxuchujshrgpao";

            $mail->setFrom("info@sisbeca.com", "Sisbeca");
            $mail->Subject = "Contacto";
            $body = view("emails.contacto.info")->with(compact("data"));
            $mail->MsgHTML($body);
            //foreach($correos as $correo)
            //{
                $mail->addAddress("delgadorafael2011@gmail.com", "Rafael Delgado");
            //}
            //$mail->addBCC('darwin@gmail.com');
            //$mail->send();
        }
        catch (phpmailerException $e)
        {
            $enviado = false;
            $error = "01";
        }
        catch (Exception $e)
        {
            $enviado = false;
            $error = "02";
            //return "enviado".$mail->ErrorInfo;
        }*/

    }

    public function sendmail(Request $request)
    {
        return response()->json(['nombre'=>$request->data]);
    }

    public function prueba()
    {
        $mensaje = Mensaje::find(1);
        return $mensaje;
        $fullusername = getRecesoDecembrino();
        //return getRecesoDecembrino();
        $receso = RecesoDecembrino::first();
        $receso->fecha_inicio = DateTime::createFromFormat('d/m/Y', '01/01/2019')->format('Y-m-d');
        $receso->save();
        return "gg";
        //return Auth::user()->id;
        //Genero una alerta para el postulante becario}
        $becario  = Becario::find(6);
        $factura = FactLibro::find(1);
        return $factura->usuario;
        return $becario->factLibros;
        $alerta = new Alerta;
        $alerta->titulo = "Entrevista";
        $alerta->descripcion = "Nuestro equipo lo invita a una entrevista para el ".$becario->fechaEntrevista()." a las ".$becario->horaEntrevistaCorta()." en ".$becario->lugar_entrevista;
        $alerta->leido = 0;
        $alerta->nivel = "alto";
        $alerta->status = "enviada";
        $alerta->tipo = "entrevista";
        $alerta->oculto = 0;
        $alerta->user_id = $becario->user_id;
        $alerta->save();

        //Genero una alertas para los entrevistadores
        foreach ($becario->entrevistadores as $entrevistador)
        {
            $alerta = new Alerta;
            $alerta->titulo = "Entrevista";
            $alerta->descripcion = "Nuestro equipo le asignó la entrevista del postulante ".$becario->user->nombreyapellido()." el ".$becario->fechaEntrevista()." a las ".$becario->horaEntrevistaCorta()." en ".$becario->lugar_entrevista;
            $alerta->leido = 0;
            $alerta->nivel = "alto";
            $alerta->status = "enviada";
            $alerta->tipo = "entrevista";
            $alerta->oculto = 0;
            $alerta->user_id = $entrevistador->id;
            $alerta->save();
        }
        return "Too bien";
        $ticket = Ticket::find(1);
        $ticket->notificado = 1;
        $ticket->fecha_notificado = date("Y-m-d H:i:s");
        $ticket->save();
        //Enviamos un correo al que generó el ticket con la respuesta
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "Respuesta Ticket: ".$ticket->getNro();
        $body = view("emails.tickets.ticket-respuesta")->with(compact("ticket"));
        $mail->MsgHTML($body);
        $mail->addAddress($ticket->usuariogenero->email);
        //$mail->send();
        return "exitoo:)";
        return Alerta::where('user_id', '=', Auth::user()->id)->where('status', '=', 'generada')->get();
        $id = 17;
        $becario = Becario::find($id);
        $usuario = User::find($id);
        $alerta = Alerta::find(1);
        return $alerta->user;
        return $usuario->alertas;
        //return $becario->entrevistadores;
        //Enviar correo a los entrevistadores
        $alerta = new Alerta;
        $alerta->titulo = "Entrevista";
        $alerta->descripcion = "A ud se le asigno una fecha entrevista.";
        $alerta->leido = 0;
        $alerta->nivel = "alto";
        $alerta->nivel = "enviada";
        $alerta->tipo = "entrevista";
        $alerta->oculto = 0;
        $alerta->user_id = 17;
        $alerta->save();
        return "se creo una alerta";
        foreach ($becario->entrevistadores as $entrevista)
        {
            $mail = new PHPMailer();
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "TLS";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->Username = "delgadorafael2011@gmail.com";
            $mail->Password = "scxxuchujshrgpao";
            $mail->setFrom("no-responder@avaa.org", "Sisbeca");
            $mail->Subject = "IMPORTANTE";
            $entrevistador = $entrevista;//Reasigno por si falla la iteracción
            $body = view("emails.entrevistadores.notificacion-entrevista")->with(compact("becario","entrevistador"));
            $mail->MsgHTML($body);
            $mail->addAddress($entrevistador->email);
            ////$mail->send();
        }
        return "correos enviados";
        $ticket = Ticket::find(1);
        return $ticket->usuariorespuesta;
        $anho = '2019';
        $ab = ActividadBecario::paraActividad(1)->get();
        $af = DB::table('actividades')
            
            ->selectRaw('*')
            ->join('actividades_facilitadores', function ($join) use($id,$anho)
        {
        $join->on('actividades.id', '=', 'actividades_facilitadores.actividad_id')
            ->where('actividades.status', '!=', 'suspendido')
            ->where('actividades_facilitadores.becario_id', '=', $id)
            ->whereYear('actividades.fecha', '=', $anho);
        })->orderby('fecha', 'desc')->first();
            $fechafinal = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))));
                $fechainicial = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($af->fecha)));
                $desde = $fechainicial->diff($fechafinal);
        return response()->json($desde);
        return "mensaje enviado :D :D :D :D";
        return "Eliminada la foto";
        $solicitud = Solicitud::find(1);
        return $solicitud->user;
        $becario = Becario::find(6);
        return $becario->mentor->user->nombreyapellido();
        $estatus = "APROBADO";
        $id = 81;
        $usuario = User::find($id);

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "IMPORTANTE";
        $body = view("emails.postulanteMentor.notificar-estatus-postulacion")->with(compact("usuario","estatus"));
        $mail->MsgHTML($body);
        $mail->addAddress($usuario->email);
        //$mail->send();
        return "Correo enviado";
        if (Auth::attempt(['email' => 'rafael1delgado@hotmail.com', 'password' => '123456']))
        {
            return "logueado";
            return redirect()->intended('sisbeca');
        }
        else
        {
            return "mal";   
        }
        $anho = '2019';
        $mes = 2;
        $id=6;
        $becario = Becario::find(81);
        $periodos = Periodo::paraBecario($id)->delAnho($anho)->delMes($mes)->ordenadoPorPeriodo('asc')->with('aval')->with('materias')->get();//corregido
        //$periodos = Periodo::paraBecario($id)->delAnho($anho)->ordenadoPorPeriodo('asc')->with('aval')->with('materias')->get();//corregido
        return $periodos;
        $usuario = User::find(6);
        $usuario->fecha_nacimiento = DateTime::createFromFormat('d/m/Y', "14/03/1995" )->format('Y-m-d');
        $usuario->save();
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
        return response()->json($periodos);
        $actividades_facilitadas = DB::table('actividades')
        ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
        ->join('actividades_facilitadores', function ($join) use($id,$anho,$mes)
        {
            $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                ->where('actividades_facilitadores.becario_id', '=', $id)
                ->whereYear('actividades.fecha', '=', $anho)
                ->whereMonth('actividades.fecha', '=', $mes)
                ;
        })->get();
        $actividades_facilitadas = DB::table('actividades')
                ->selectRaw("*,SUM(horas) as horas_voluntariado,Count(*) as total_actividades")
                ->join('actividades_facilitadores', function ($join) use($id,$anho,$mes)
                {
                    $join->on('actividades_facilitadores.actividad_id', '=','actividades.id')
                        ->where('actividades_facilitadores.becario_id', '=', $id)
                        ->whereYear('actividades_facilitadores.created_at', '=', $anho)
                        ->whereMonth('actividades_facilitadores.created_at', '=', $mes)
                        ;
                })->get();
        return response()->json($actividades_facilitadas);
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
            ->orderby('cursos.fecha_inicio', 'desc')
            ->selectRaw('*,cursos.fecha_inicio as fecha')
            ->join('aval', function ($join) use($id)
        {
            $join->on('cursos.aval_id','=','aval.id')
            ->where('aval.tipo','=','nota')
            ->where('aval.estatus','=','aceptada')
            ->where('cursos.becario_id','=',$id)
            ;
        })->first();
        //return response()->json($cursos);
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
        })->get();
        //para periodos
        $periodos = DB::table('periodos')
            ->orderby('periodos.fecha_inicio','asc')
            ->selectRaw('*,periodos.fecha_inicio as fecha,periodos.created_at as fecha_carga')
            ->join('aval', function ($join) use($id,$mes)
        {
            $join->on('periodos.aval_id','=','aval.id')
            ->where('periodos.becario_id','=',$id)
            ->whereMonth('periodos.fecha_inicio', '<=', $mes)
            ->whereMonth('periodos.fecha_fin', '>=', $mes)
             ->where('aval.tipo','=','constancia')
            ->where('aval.estatus','=','aceptada')
            ;
        })->first();
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
        $periodos = Periodo::paraBecario(6)->delAnho('2019')->ordenadoPorPeriodo('asc')->with('aval')->with('materias')->get();
        return response()->json($periodos);
        $fechafinal = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))));
            $fechainicial = DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($periodos->fecha_carga)));
            $desde = $fechainicial->diff($fechafinal);
        return response()->json($desde);
        $tiempo_actividades = "Nunca";
        $tiempo_cva = "Nunca";
        $tiempo_voluntariados = "Nunca";
        $tiempo_periodos = "Nunca";
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
                            }
                            else
                                $tiempo_actividades = ($desde->i==1)?"Hace 1 minuto":"Hace ".$desde->i." minutos";
                        }
                        else
                            $tiempo_actividades = ($desde->h==1)?"Hace 1 hora":"Hace ".$desde->h." horas";
                    }
                    else
                        $tiempo_actividades = ($desde->d==1)?"Hace 1 día":"Hace ".$desde->d." días";
                }
                else
                    $tiempo_actividades = ($desde->m==1)?"Hace 1 mes":"Hace ".$desde->m." meses";
            }
            else
                $tiempo_actividades = ($desde->y==1)?"Hace 1 año":"Hace ".$desde->y." años";
        }
        return $tiempo_actividades;

        return $becario->getTiempoParticipaTaller();
        $actividades_facilitadas = ActividadFacilitador::paraBecario($id)->paraAnho($anho)->get();
        $total_horas_facilitador = 0;
        foreach ($actividades_facilitadas as $ab)
        {
            $total_horas_facilitador = $total_horas_facilitador + $ab->horas;
        }

        $voluntariado = DB::table('voluntariados')
            ->where('aval.tipo','=','comprobante')
            ->where('aval.estatus','=','aceptada')
            //->groupby('voluntariados.tipo')
            ->selectRaw('*,SUM(horas) as horas_voluntariado')
            ->join('aval', function ($join) use($id,$anho,$mes)
        {
            $join->on('voluntariados.aval_id','=','aval.id')
            ->where('voluntariados.becario_id','=',$id)
            ->whereYear('voluntariados.fecha', '=', $anho)
            //->whereMonth('voluntariados.fecha', '=', $mes)
            ->orderby('voluntariados.fecha','asc');

        })->first();

        $tmp_voluntariado = ($voluntariado->horas_voluntariado==null) ? 0 : $voluntariado->horas_voluntariado;
            $horas_voluntariado = $tmp_voluntariado + $total_horas_facilitador;
        $todos = collect();
        $todos->push(array(

                'horas_voluntariados' => $horas_voluntariado,

                   'id' => $becario->user->id,
                   'nombreyapellido' => $becario->user->nombreyapellido())
            );
        return $todos;
        $periodos = DB::table('periodos')
            ->where('aval.tipo','=','constancia')
            ->where('aval.estatus','=','aceptada')
            //->groupby('periodos.tipo')
            //->selectRaw('*,SUM(horas) as horas_voluntariado,Count(*) as total_voluntariado,periodos.tipo as tipo_voluntariado')
            ->join('aval', function ($join) use($id,$anho,$mes)
        {
            $join->on('periodos.aval_id','=','aval.id')
            ->where('periodos.becario_id','=',$id)
            ->whereYear('periodos.created_at', '=', $anho)
            //->whereMonth('periodos.created_at', '=', $mes)
            ->orderby('periodos.created_at','asc');

        })->count();
        $becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
        $todos = collect();
        /*foreach ($becarios as $key => $becario)
        {
            $id = $becario->user->id;
            $voluntariados = DB::table('voluntariados')
                ->where('aval.tipo','=','comprobante')
                ->where('aval.estatus','=','aceptada')
                //->groupby('voluntariados.tipo')
                ->selectRaw('*,SUM(horas) as horas_voluntariado')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
                $join->on('voluntariados.aval_id','=','aval.id')
                ->where('voluntariados.becario_id','=',$id)
                ->whereYear('voluntariados.fecha', '=', $anho)
                //->whereMonth('voluntariados.fecha', '=', $mes)
                ->orderby('voluntariados.fecha','asc');

            })->first();

            $todos->push(array(
                'nivel_carrera' => '0',
                'horas_voluntariados' => ($voluntariados->horas_voluntariado==null) ? 0 : $voluntariados->horas_voluntariado,
                'asistio_t' => '0',
                'asistio_cc' => '0',
                'nivel_cva' => '0',
                'avg_cva' => '0',
                'avg_academico' => '0',
                "becario" => array(
                   'id' => $becario->user->id,
                   'nombreyapellido' => $becario->user->nombreyapellido() )
            ));
        }*/
        //return $todos;
        //return "exito";
        $voluntariados = DB::table('voluntariados')
                ->where('aval.tipo','=','comprobante')
                ->where('aval.estatus','=','aceptada')
                //->groupby('voluntariados.tipo')
                ->selectRaw('*,SUM(horas) as horas_voluntariado')
                ->join('aval', function ($join) use($id,$anho,$mes)
            {
                $join->on('voluntariados.aval_id','=','aval.id')
                ->where('voluntariados.becario_id','=',$id)
                ->whereYear('voluntariados.fecha', '=', $anho)
                //->whereMonth('voluntariados.fecha', '=', $mes)
                ->orderby('voluntariados.fecha','asc');

            })->first();
        //return response()->json($voluntariados);
        $cursos = DB::table('cursos')
            ->where('aval.tipo','=','nota')
            ->where('aval.estatus','=','aceptada')
            //->groupby('cursos.modulo')
            //->orderby('cursos.modulo','asc')
            ->selectRaw('*,MAX(modulo) as nivel')
            ->join('aval', function ($join) use($id,$anho,$mes)
        {
            $join->on('cursos.aval_id','=','aval.id')
            ->where('cursos.becario_id','=',$id)
            ->whereYear('cursos.created_at', '=', $anho)
            //->whereMonth('cursos.created_at', '=', $mes)
            ;

        })->first();
        $cursos2 = DB::table('cursos')
            ->where('aval.tipo','=','nota')
            ->where('aval.estatus','=','aceptada')
            //->groupby('cursos.modulo')
            //->orderby('cursos.modulo','asc')
            ->selectRaw('*,AVG(nota) as promedio_modulo')
            ->join('aval', function ($join) use($id,$anho,$mes)
        {
            $join->on('cursos.aval_id','=','aval.id')
            ->where('cursos.becario_id','=',$id)
            ->whereYear('cursos.created_at', '=', $anho)
            //->whereMonth('cursos.created_at', '=', $mes)
            ;

        })->first();
        $periodo = DB::table('periodos')
            ->where('aval.tipo','=','constancia')
            ->where('aval.estatus','=','aceptada')
            ->selectRaw('*,MAX(numero_periodo) as nivel_carrera')
            //->orderby('periodos.numero_periodo','desc')
            ->join('aval', function ($join) use($id)
        {
          $join->on('periodos.aval_id','=','aval.id')
            ->where('periodos.becario_id','=',$id);
        })->first();
        $asistio_t = DB::table('actividades')
        ->selectRaw('*,Count(*) as total_actividades')
        ->where('tipo','taller')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                //->whereMonth('actividades_becarios.created_at', '=', $mes)
                ->where('actividades_becarios.estatus','=','asistio');
        })->first();
        $asistio_cc = DB::table('actividades')
        ->selectRaw('*,Count(*) as total_actividades')
        ->where('tipo','chat club')->join('actividades_becarios', function ($join) use($id,$anho,$mes)
        {
           $join->on('actividades.id', '=', 'actividades_becarios.actividad_id')
                ->where('actividades_becarios.becario_id', '=', $id)
                ->whereYear('actividades_becarios.created_at', '=', $anho)
                //->whereMonth('actividades_becarios.created_at', '=', $mes)
                ->where('actividades_becarios.estatus','=','asistio');
        })->first();
        //return $cursos2;
        return response()->json($cursos);
        //$periodos = Periodo::paraBecario($id)->porAnho()->porMes();
        //return "Inicio";
        /*
        $id=6;
        $anho = date('Y');
        $mes = date('m');
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
        $noasistio = array(
            "na_t_v" => 0,
            "na_t_p" => 0,
            "na_c_v" => $na_c_v->total_actividades,
            "na_c_p" => 0,
        );
        return $noasistio['na_c_v'];
        $periodos = Periodo::paraBecario($id)->porAnho($anho)->porMes($mes)->ordenadoPorPeriodo('asc')->get();

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
            ->groupby('cursos.modulo')
            ->orderby('cursos.modulo','asc')
            ->selectRaw('*,AVG(nota) as promedio_modulo,Count(*) as total_modulo')
            ->join('aval', function ($join) use($id,$anho)
        {
          $join->on('cursos.aval_id','=','aval.id')
            ->where('cursos.becario_id','=',$id)
            ->whereYear('cursos.created_at', '=', $anho)
            ;

        })->get();*/
        return $cursos;
        return $voluntariados;
        return $periodos;
        //$aval->estatus = $request->estatus;
        //$aval->save();

        /*PARA VOLUNTARIADOS*/
        /*
        $aval = Aval::find(3);
        $becario = $aval->becario;
        $voluntariado = Voluntariado::paraBecario($becario->user->id)->paraAval($aval->id)->first();
        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "estatus_voluntariado" => strtoupper("NEGADIS"),
            "nombre_voluntariado" =>  $voluntariado->nombre,
            "tipo_voluntariado" => ucwords($voluntariado->tipo),
            "fecha_voluntariado" => $voluntariado->getFecha(),
            "horas_volutariado" => $voluntariado->getHorasVoluntariado().' hora(s)',
            "fecha_hora" =>  date("d/m/Y h:i A"),
        );
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "Notificación";
        $body = view("emails.voluntariados.notificacion-comprobante-estatus")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
        return "exitoooVoluntariado";
        */

        /*PARA CVA*/
        /*$aval = Aval::find(2);
        $becario = $aval->becario;
        $curso = Curso::paraBecario($becario->user->id)->paraAval($aval->id)->first();

        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "estatus_cva" => strtoupper("gola"),
            "modulo_cva" =>  $curso->getModulo(),
            "modo_cva" => $curso->getModo(),
            "mes_cva" => $curso->getMes(),
            "anho_cva" => $curso->getAnho(),
            "nota_cva" => $curso->getNota(),
            "fecha_hora" =>  date("d/m/Y h:i A"),
        );
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "Notificación";
        $body = view("emails.cursos.notificacion-nota-estatus")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
        return "exito";*/

        /*PARA PERIODOS*/
        /*
        $aval = Aval::find(1);
        $becario = $aval->becario;
        $periodo = Periodo::ParaBecario($becario->user->id)->ParaAval($aval->id)->first();
        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "numero_periodo" =>  $periodo->getNumeroPeriodo(),
            "anho_lectivo" => $periodo->anho_lectivo,
            "promedio_periodo" => $periodo->getPromedio(),
            "estatus_periodo" => strtoupper("hola vale"),
            "fecha_hora" =>  date("d/m/Y h:i A"),
        );

        //Enviar correo al becario notificandole del cambio de estatus.
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "Notificación";
        $body = view("emails.periodos.notificacion-constancia-estatus")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
        return "Exitoo";

        */
        /*
        $aval = Aval::find(2);
        $aval->estatus = "aceptada";
        //$aval->save();

        $ab = ActividadBecario::paraAval(2)->first();
        $ab->estatus = "asistio";
        //$ab->save();

        $actividad = $ab->actividad;
        $becario = $aval->becario;

        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "fecha_hora" =>  date("d/m/Y h:i A"),
            "actividad_tipo" => $actividad->getTipo(),
            "actividad_nombre" => $actividad->nombre,
            "estatus_justificativo" => "ACEPTADA",
            "estatus_actividad" => "ASISTIÓ",
        );
        //Enviar correo al becario notificandole
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "Notificación";
        $body = view("emails.actividades.notificacion-justificativo-aceptada")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();

        return "bien";*/
        //$client = new Client();

        //return response()->json(route('enviarcorreo'));
        /*$client = new \GuzzleHttp\Client();
        $request = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');
        return $result= $request->getBody();
        return  "";*/
       /* $client = new \GuzzleHttp\Client();
        //return $client;
        $request = $client->request('POST', route('enviarcorreo'), [
            'form_params' => [
                'asunto' => "urgente",
                'correo' => "rafael1delgado@hotmail.com",
                'cuerpo' => view("emails.contacto.info")->with(compact("data")),
            ]
        ]);*/

        /*Mail::send('emails.change_password', array(
            //'user_names'     => $enterprise_name,
            'email'     => $enterprise_email,
            'password'       => $clave_por_correo,
            //'route_signin'   => 'melagoso.app/api/v1/user/signin',
            //'route_change_password'   => 'melagoso.app/api/v1/password/reset'
          ),
          function ($message) use($request,$enterprise_email,$enterprise_name )
          {
            $message->from('delgadorafael2011@gmail.com');
            $message->to($enterprise_email)->subject('¡Bienvenido! '. $enterprise_name);
          });*/

        /*$actividad = Actividad::find(1);
        $listadeespera  = $actividad->listadeespera();
        if($listadeespera->count()>=1)
        {
            $beneficiado = $listadeespera[0];
            $beneficiado->estatus = "asistira";
            $beneficiado->save();
        }
        return "listo";*/

        //return date('Y-m-d H:i:s');
        /*$ab = ActividadBecario::find(8);
        $ab->updated_at = date("Y-m-d H:i:s");
        $ab->save();
        return "exito";
        */
        /*$becario = Becario::find(34);
        $becario->hora_entrevista = "3:23 PM";
        $becario->lugar_entrevista = "miami";
        $becario->fecha_entrevista = DateTime::createFromFormat('d/m/Y', "14/02/2019" )->format('Y-m-d');
        $becario->save();
        return "exito";*/
        //$hora = DateTime::createFromFormat('H:i a', "03:22 AM" )->format('H:i:s');
        //$hora = date("h:i A", strtotime("3:22 PM"));
        //$fecha = "14/02/1993".' '.date("h:i a", strtotime("3:22 PM"));
        //$fechan = DateTime::createFromFormat('d/m/Y h:i A', $fecha )->format('Y-m-d H:i:s');
        //return response()->json( date("h:i a", strtotime($hora)) );
        /*$entrevistadores = User::entrevistadores()->get();
        return $entrevistadores;
        */
        //prueba para enviar email
        /*$request= "";
        Mail::send('emails.base',
            array(
                'name'          => 'Rafael Delgado',
                'addressee'     => 'delgadorafael2011@gmail.com',
                'message'       => 'prueba',
                'location'      => 'delgadorafael2011@gmail.com'
            ), function($message) use ($request)
        {
            $message->from('no-reply@bdc.com.co');
            $message->to('delgadorafael2011@gmail.com', 'delgadorafael2011@gmail.com')->subject('Avvaa.');
        });/*

        //otra forma
        /*Mail::raw('Sending emails with Mailgun and Laravel is easy!', function($message)
        {
            $message->subject('stmp and Laravel are awesome!');
            $message->from('soporte@genesyzgroup.com', 'Website Name');
            $message->to('delgadorafael2011@gmail.com');
        });
        return "exito";

        */

        //contraseña del correo de rafael
        //scxxuchujshrgpao


        //return date("d-m-Y H:i A");
        $data = array(
            "becario" => "Ivan Delgado",
            "actividad_tipo" => "Chat Club",
            "actividad_nombre" => "Valores Ciudadanos",
            "actividad_fecha" =>  "Lunes, 14/01/2019",
            "actividad_hora" => "01:00pm a 02:00pm",
            "correo" => "rafael1delgado@hotmail.com",
            "telefono" => "04265556677",
            "asunto" => "Urgente",
            "mensaje" => "hola adios",
            "fecha_hora" => date("d/m/Y H:i A"),
        );
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "Notificación";
        $body = view("emails.actividades.notificacion-eliminar-inscripcion")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress("delgadorafael2011@gmail.com");
        //$mail->send();
        /*Mail::send('emails.contacto.gracias', ['data' => $data], function($message) use ($data)
        {
            $message->from( 'not-reply@sisbeca.com', 'Sisbeca');
            $message->to( $data['correo'] )->subject('Notificación');
        });
        return "exitoo";
        */


        /*try
        $mail = new PHPMailer(true);
        {*/
            /*
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            //$mail->Timeout  = 60;
            $mail->CharSet = "utf-8";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->IsHTML(true);

            $mail->Username = "delgadorafael2011@gmail.com";
            $mail->Password = "scxxuchujshrgpao";

            $mail->setFrom("info@sisbeca.com", "Sisbeca");
            $mail->Subject = "Contacto";
            $body = view("emails.contacto.info")->with(compact("data"));
            $mail->MsgHTML($body);
            //foreach($correos as $correo)
            //{
                $mail->addAddress("delgadorafael2011@gmail.com", "Rafael Delgado");
            //}
            //$mail->addBCC('darwin@gmail.com');
            //$mail->send();
            return "asasas";*/
        /*}
        catch (phpmailerException $e)
        {
            $enviado = false;
            $error = "01";
            return "exito";
        }
        catch (Exception $e)
        {
            $enviado = false;
            $error = "02";
            return "exito";
            //return "enviado".$mail->ErrorInfo;
        }
        die('success');*/

        //relación de becarios a actividades
        /*
        $becario = Becario::find(8);
        return  $becario->actividades;*/
        /*
        $id=54;
        $anho =date('Y');
        //obtengo el  nivel de cva
        $curso = DB::table('cursos')
            ->where('aval.tipo','=','nota')
            ->where('aval.estatus','=','aceptada')
            ->orderby('cursos.modulo','desc')
            ->join('aval', function ($join) use($id,$anho)
        {
          $join->on('cursos.aval_id','=','aval.id')
            ->where('cursos.becario_id','=',$id)
            ;

        })->first();
        return $curso->modulo.' - '.$curso->modo;
        */

        //obtengo el año/semestre de carrera aprobada
        /*$id=58;
        $periodo = DB::table('periodos')
            ->where('aval.tipo','=','constancia')
            ->where('aval.estatus','=','aceptada')
            ->orderby('periodos.numero_periodo','desc')
            ->join('aval', function ($join) use($id)
        {
          $join->on('periodos.aval_id','=','aval.id')
            ->where('periodos.becario_id','=',$id);
        })->first();

        return $periodo->numero_periodo;*/
        //relacion actividad_becario a aval
        /*
        $actividad_becario = ActividadBecario::where('becario_id','=','5')->where('actividad_id','=','1')->first();
        return $actividad_becario->aval;
        */

        //relación de actividades a becarios
        /*
        $actividad = Actividad::find(2);
        return $actividad->becarios->count();
        foreach($actividad->becarios as $becario)
        {
            return  $becario->user;
        }
        */

        //relación de becarios a cursos
        /*
        $becario = Becario::find(5);
        return $becario->cursos->first()->tipocurso->nombre;
        return $becario->cursos->first()->aval;
        */

        //relación de cursos a becarios
        /*
        $curso = Curso::find(1);
        return $curso->becario->user;
        */

        //relación becario a voluntariados
        /*
        $becario = Becario::find(5);
        return $becario->voluntariados;
        */

        //relación voluntariado a becario
        /*
        $voluntariado = Voluntariado::find(1);
        return $voluntariado->aval;
        return $voluntariado->becario->user;
        */

        //relación becario a periodos
        /*
        $becario = Becario::find(5);
        return $becario->periodos;
        */

        //relación a periodo a materias
        /*
        $periodo = Periodo::find(1);
        return $periodo->materias;
        return $periodo->aval->url;
        */

        //relacion materias a periodo
        /*
        $materia = Materia::find(1);
        return $materia->periodo;
        */

        //relación actividades a facilitadores
        /*
        $actividad = Actividad::find(1);
        //return response()->json($actividad->facilitadores[1]->becario_id==null);// cuando no es becario
        return $actividad->facilitadores->first()->becario;
        */

        //relación becarios a actividadesfacilitadas (becario como facilitador de una actividad)
        /*
        $becario = Becario::find(6);
        return $becario->actividadesfacilitadas;
        */

        //relacion de becario a entrevistador

        /*$becario = Becario::find(4);
        return $becario->entrevistadores;

        //relacion de entrevistador a becario

        $entrevistador = User::find(47)->;
        return $entrevistador->entrevistados;
        */


        //probar desasignar mentor
        /*
        $becario = Becario::find(4);//en este punto becario no tiene mentor asignado
        $becario->mentor_id = 14;//asigno el mentor, obviamente ya existe ese mentor
        $becario->save();//guardo. esto es importante
        return $becario->mentor;//aqui puedo observar de que la relacion sirve y la asignacion  fue exitosa

        $becario->mentor_id = null;//quito la relacion becario mentor
        $becario->save();// guardo
        return $becario;// compruebo de que fue exitosa

        $mentores = Mentor::all();//listo mis mentores
        return $mentores;
        */
    }

    public function getNoticias($tip=1)
    {
        $noticias=null;
        if($tip==0)
        {
            $noticias = Noticia::query()->select(['id', 'titulo', 'contenido', 'tipo'])->orderBy('updated_at','desc');
            $objeto= Datatables::of($noticias)->addColumn('fechaActualizacion', function ($noticias) {
                $noti=Noticia::find($noticias->id); //llamo al regististro actual
                //$noti->editor; //aqui lo que hago es llamar a la relaciones para cada uno de las noticias
                $fecha_actual=date("d/m/Y h:i:m A", strtotime($noti->updated_at));
                return $fecha_actual; // Retorna al Fecha Actualizacion

            })->addColumn('content', function ($noticias) {
                $content= $noticias->contenido;
                $content=substr(strip_tags($content), 0, 20).'...';// Esto elimina las etiquetas html
                return  $content;

            })->addColumn('tipoA', function ($noticias) {
                $tipoA= ( 'noticia' === $noticias->tipo ) ? 'Noticia' : 'Miembro Institucional';
                return  $tipoA;

            })->addColumn('title', function ($noticias)
            {
                $title= $noticias->titulo;
                $title=substr(strip_tags($title), 0, 30).'...';
                return  $title;

            })->make(true);
        }
        else
        {
            $noticias = Noticia::select(['id', 'titulo', 'contenido', 'tipo'])->where('tipo','=','noticia')->orderBy('updated_at','desc');
            $objeto= Datatables::of($noticias)->make(true);
        }
        return $objeto;
    }

    public function generarBD()
    {
        $admin = User::find(1);
        $admin->rol = 'admin';
        $admin->email='admin@avaa.com';
        $admin->save();

        $directivo = User::find(2);
        $directivo->rol = 'directivo';
        $directivo->email='directivo@avaa.com';
        $directivo->save();

        $editor = User::find(3);
        $editor->rol = 'editor';
        $editor->email='editor@avaa.com';
        $editor->save();

        $editor = User::find(4);
        $editor->rol = 'coordinador';
        $editor->email='coordinador@avaa.com';
        $editor->save();

        $editor = User::find(5);
        $editor->rol = 'entrevistador';
        $editor->email='entrevistador1@avaa.com';
        $editor->save();

        $usuario = User::find(6);
        $usuario->name="Rafael";
        $usuario->last_name="Delgado";
        $usuario->email='rafael1delgado@hotmail.com';
        $usuario->save();

        $editor = User::find(7);
        $editor->rol = 'entrevistador';
        $editor->email='entrevistador2@avaa.com';
        $editor->save();

        //$editor->editor()->save(new Editor());

        /*

        $editor = new Editor;
        $editor->user_id = 2;
        $editor->save();

        $coordinador = new Coordinador;
        $coordinador->user_id = 3;
        $coordinador->save();

        $mentor = new Mentor;
        $mentor->user_id = 4;
        $mentor->save();
        */
/*
        for($i=5,$j=1;$i<8;$i++,$j++) {
            $coordinador = User::find($i);
            $coordinador->rol = 'coordinador';
            $coordinador->email= 'coord'.$j.'@avaa.com';
            $coordinador->save();

            $coordinador = new Coordinador;
            $coordinador->user_id = $i;
            $coordinador->save();
        }
*/

        $usuarios = User::where('rol','=','becario')->get();

        foreach($usuarios as $usuario)
        {
            $becario = new Becario();
            $becario->user_id = $usuario->id;
            $becario->mentor_id = null;
            $becario->acepto_terminos = true;
            $becario->status='activo';
            $becario->cuenta_bancaria= $this->getRandomCode();
            $becario->save();

           /* $documento = new Documento();
            $titulo = str_random(10);
            $documento->titulo = $titulo;
            $documento->url = '/'.$titulo.'.pdf';
            $documento->user_id = $usuario->id;
            $documento->verificado = 0;
            $documento->save();*/

          /*  $factlibro = new FactLibro();
            $name = str_random(10);
            $factlibro->name = $name;
            $factlibro->curso =  str_random(10);
            $factlibro->url = '/'.$name.'.pdf';
            $factlibro->status = 'cargada';
            $factlibro->costo = rand(1000, 2000) / 10;
            $factlibro->becario_id  = $usuario->id;
            $factlibro->save();*/

            //return $factlibro;

        }

        for($i=10;$i<=30;$i++)
        {
            $usuarios->get($i)->rol= 'postulante_becario';
            $usuarios->get($i)->save();

            $usuarios->get($i)->becario->status='postulante';
             $usuarios->get($i)->becario->save();
        }

        $costo= new Costo();
        $costo->sueldo_becario =1000000;
        $costo->fecha_valido=date('Y-m-d');
        $costo->save();


        return 'Listo';

    }

    public function getRandomCode()
    {
        $an = "0123456789";
        $su = strlen($an) - 1;
        return substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1).
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1).
            substr($an, rand(0, $su), 1).
            substr($an, rand(0, $su), 1);
    }


}
