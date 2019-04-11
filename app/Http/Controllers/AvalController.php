<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Aval;
use avaa\Periodo;
use avaa\Curso;
use avaa\Voluntariado;
use avaa\ActividadBecario;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AvalController extends Controller
{
	//este método es usado por varias vistas
   	public function getEstatus()
	{
		$estatus = (object)["0"=>"pendiente", "1"=>"aceptada", "2"=>"negada","3"=>"devuelto"];
		return response()->json(['estatus'=>$estatus]);
	}

	//este método es usado por varias vistas no cambiar el nombre del success
 	public function actualizarestatus(Request $request, $id)
	{
		$aval = Aval::find($id);
		$aval->estatus = $request->estatus;
		$aval->save();

        $becario = $aval->becario;

        //Para los PERIODOS el aval es tipo CONSTANCIA
        
        if($aval->tipo=="constancia")
        {
            $periodo = Periodo::ParaBecario($becario->user->id)->ParaAval($aval->id)->first();
            $data = array(
                "becario" => $becario->user->nombreyapellido(),
                "estatus_periodo" => strtoupper($request->estatus),
                "numero_periodo" =>  $periodo->getNumeroPeriodo(),
                "anho_lectivo" => $periodo->anho_lectivo,
                "promedio_periodo" => $periodo->getPromedio(),
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
            $body = view("emails.periodos.notificacion-constancia-estatus")->with(compact("data"));
            $mail->MsgHTML($body);
            $mail->addAddress($becario->user->email);
            //$mail->send();
        }
        //Para los CVA el aval es tipo NOTA
        if($aval->tipo=="nota")
        {
            $curso = Curso::paraBecario($becario->user->id)->paraAval($aval->id)->first();
            $data = array(
                "becario" => $becario->user->nombreyapellido(),
                "estatus_cva" => strtoupper($request->estatus),
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
        }
        //Para los VOLUNTARIADOS el aval es tipo COMPROBANTE
        if($aval->tipo=="comprobante")
        {
            $voluntariado = Voluntariado::paraBecario($becario->user->id)->paraAval($aval->id)->first();
            $data = array(
                "becario" => $becario->user->nombreyapellido(),
                "estatus_voluntariado" => strtoupper($request->estatus),
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
        }

		return response()->json(['success'=>'El estatus fue actualizado exitosamente.']);
	}

	public function aceptar($id)
	{
		$aval = Aval::find($id);
		$aval->estatus = "aceptada";
		$aval->save();

		$ab = ActividadBecario::paraAval($id)->first();
		$ab->estatus = "asistio";
		$ab->save();
		
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

		return response()->json(['success'=>'La justificación fue aceptada exitosamente.']);
	}

	public function devolver($id)
	{
		//generar un alerta para que el becario se entere de que su justificativo fue devuelto
		$aval = Aval::find($id);
		$aval->estatus = "devuelto";
		$aval->save();

		$ab = ActividadBecario::paraAval($id)->first();
		$ab->estatus = "justificacion cargada";
		$ab->save();

		$actividad = $ab->actividad;
		$becario = $aval->becario;

		$data = array(
            "becario" => $becario->user->nombreyapellido(),
            "actividad_tipo" => $actividad->getTipo(),
            "actividad_nombre" => $actividad->nombre,
            "estatus_justificativo" => "DEVUELTO",
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
        $body = view("emails.actividades.notificacion-justificativo-devuelto")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
		
		return response()->json(['success'=>'La justificación fue devuelta exitosamente.']);
	}

	public function negar($id)
	{
		$aval = Aval::find($id);
		$aval->estatus = "negada";
		$aval->save();

		$ab = ActividadBecario::paraAval($id)->first();
		$ab->estatus = "no asistio";
		$ab->save();
		
		$actividad = $ab->actividad;
		$becario = $aval->becario;

		$data = array(
            "becario" => $becario->user->nombreyapellido(),
            "fecha_hora" =>  date("d/m/Y h:i A"),
            "actividad_tipo" => $actividad->getTipo(),
            "actividad_nombre" => $actividad->nombre,
            "estatus_justificativo" => "NEGADA",
            "estatus_actividad" => "NO ASISTIÓ",
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
        $body = view("emails.actividades.notificacion-justificativo-negada")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();

		return response()->json(['success'=>'La justificación fue negada exitosamente.']);
	}

    public function tomaraccion(Request $request, $id)
    {
        $aval = Aval::find($id);
        $aval->estatus = $request->estatus;
        $aval->observacion = $request->observacion;
        $aval->save();

        $enviarcorreo = true;
        if($request->estatus=='aceptada')
        {
            $ab = ActividadBecario::paraAval($id)->first();
            $ab->estatus = "asistio";
            $ab->save();
            $estatus_justificativo = "ACEPTADA";
            $estatus_actividad = "ASISTIÓ";
        }
        else
        {
            if($request->estatus=='negada')
            {
                $ab = ActividadBecario::paraAval($id)->first();
                $ab->estatus = "no asistio";
                $ab->save();
                $estatus_justificativo = "NEGADA";
                $estatus_actividad = "NO ASISTIÓ";
            }
            else
            {
                if($request->estatus=='devuelto')
                {
                    $ab = ActividadBecario::paraAval($id)->first();
                    $ab->estatus = "justificacion cargada";
                    $ab->save();
                    $estatus_justificativo = "DEVUELTO";
                }
                else
                {
                    $ab = ActividadBecario::paraAval($id)->first();
                    $ab->estatus = "justificacion cargada";
                    $ab->save();
                    $enviarcorreo = false;
                }
            }
        }
        
        $actividad = $ab->actividad;
        $becario = $aval->becario;

        //Enviar correo al becario notificandole
        if($enviarcorreo)
        {
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
            $body = view("emails.actividades.notificacion-justificativo-estatus")->with(compact("actividad","becario","estatus_actividad","estatus_justificativo","aval"));
            $mail->MsgHTML($body);
            $mail->addAddress($becario->user->email);
            //$mail->send();
        }

        return response()->json(['success'=>'La justificación fue actualizada exitosamente.']);
    }
}
