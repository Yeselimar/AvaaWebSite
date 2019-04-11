<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Alerta;
use avaa\Events\SolicitudesAlerts;
use avaa\Solicitud;
use Auth;
use DateTime;
use Timestamp;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CompartidoMentorBecarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('compartido_mentor_becario');
    }

    public function listarsolicitudes()
    {
        $solicitudes = Solicitud::visibleUsuario()->where('user_id','=',Auth::user()->id)->get();
        return view('sisbeca.solicitudes.listar')->with(compact('solicitudes'));
    }

    public function solicitud()
    {
        $id_user=Auth::user()->id;
        $solicitudes = Solicitud::query()->where('user_id','=',$id_user)->get();
        return view('sisbeca.solicitudes.rlsolicitud')->with('solicitudes',$solicitudes);
    }

    public function crearsolicitud()
    {
        $model = "crear";
        return view('sisbeca.solicitudes.model')->with(compact('model'));
    }

    public  function solicitudStore(Request $request)
    {
       // $becario = Auth::user()->becario;
        $status=null;
        $request->validate([
            'titulo' 			 	=> 'required',
            'descripcion'				 	=> 'required',
        ]);

        if(Auth::user()->rol==='becario')
        {
            $status=Auth::user()->becario->status;
        }
        else
        {
            $status=Auth::user()->mentor->status;
        }

        if(($status!=='inactivo') &&($request->titulo==='reincorporacion'))
        {
            $msg = "Disculpe, no puede solicitar reincorporacion ya que actualmente se encuentra en el programa";
            flash('Disculpe, no puede solicitar reincorporacion ya que actualmente se encuentra en el programa','danger')->important();
            return response()->json(['res' => 0,'msg'=>$msg]);
            //return redirect()->route('solicitud.listar');
        }
        $controlQuery = Solicitud::query()->select('titulo')->where('user_id','=',Auth::user()->id)->where('status','=','enviada')
            ->whereIn('titulo',['desincorporacion temporal','desincorporacion definitiva','reincorporacion'])->get();
        $control= $controlQuery->pluck('titulo')->toArray();

        $clave = array_search( $request->get('titulo'),$control); // me devuelve falso si  no encontro elemento

        if(!is_bool($clave))
        {
            flash('Disculpe, actualmente usted ya hizo una solicitud tipo '.$request->get('titulo').' la cual se encuentra en estado enviada','danger')->important();
            $msg = 'Disculpe, actualmente usted ya hizo una solicitud tipo '.$request->get('titulo').' la cual se encuentra en estado enviada';
            return response()->json(['res' => 0,'msg'=>$msg]);

            //return redirect()->route('solicitud.listar');
        }else {
            if(Auth::user()->rol==='becario')
            {
                if(($request->get('titulo')==='desincorporacion temporal') && Auth::user()->becario->fecha_inactivo !== null) {
                    flash('Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva','danger')->important();
                    $msg = 'Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva';
                    return response()->json(['res' => 0,'msg'=>$msg]);
                    //return redirect()->route('solicitud.listar');
                } else {
                    if(($request->get('titulo')==='desincorporacion definitiva') && Auth::user()->becario->fecha_desincorporado !== null) {
                        flash('Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva','danger')->important();
                         $msg = 'Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva';
                        return response()->json(['res' => 0,'msg'=>$msg]);
                        //return redirect()->route('solicitud.listar');
                    }
                }

            } else {
                if(($request->get('titulo')==='desincorporacion temporal') && Auth::user()->mentor->fecha_inactivo !== null) {
                    flash('Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva','danger')->important();
                    $msg = 'Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva';
                        return response()->json(['res' => 0,'msg'=>$msg]);
                    //return redirect()->route('solicitud.listar');
                } else {
                    if(($request->get('titulo')==='desincorporacion definitiva') && Auth::user()->mentor->fecha_desincorporado !== null) {
                        flash('Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva','danger')->important();
                        $msg = 'Disculpe no puede continuar con este tipo de solicitud, usted se encuentra a la espera de un cambio de status por parte de la directiva';
                        return response()->json(['res' => 0,'msg'=>$msg]);
                        //return redirect()->route('solicitud.listar');
                    }
                }
            }
        }

        $solicitud= new Solicitud($request->all());

        if($solicitud->titulo==='desincorporacion definitiva')
        {
          $solicitud->fecha_desincorporacion=  DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_desincorporacion').' 00:00:00');
          $request->validate([
            'fecha_desincorporacion' 			 	=> 'required',
            ]);
        }
        else
        {
            if($solicitud->titulo==='desincorporacion temporal')
            {
                $request->validate([
                    'fecha_inactividad' 			 	=> 'required',
                ]);
                $solicitud->fecha_inactividad=  DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_inactividad').' 00:00:00');
            }
        }
        $solicitud->status = "enviada";
        $solicitud->user_id=Auth::user()->id;

        if($solicitud->save())
        {
            $usuario = Auth::user();
            //  return($usuario);
            event(new SolicitudesAlerts($solicitud));
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
            $body = view("emails.solicitudes.notificacion-recibida")->with(compact("usuario","solicitud"));
            $mail->MsgHTML($body);
            $mail->addAddress($usuario->email);
            ////$mail->send();
            flash('Su solicitud fue enviada exitosamente','success')->important();
        }
        else
        {
            flash('Ha ocurrido un error al enviar solicitud','danger')->important();
        }

        return response()->json(['res' => 1,'msg'=> 'Su solicitud fue enviada exitosamente']);

        //return redirect()->route('solicitud.listar');

    }

    public function solicitudShow($id)
    {
        $solicitud = Solicitud::find($id);
        
        if(is_null($solicitud)|| ($solicitud->user_id!=Auth::user()->id))
        {
            flash('El archivo solicitado no ha sido encontrado','danger')->important();
            return back();
        }

        $alerta= Alerta::query()->select()->where('solicitud','=',$id)->where('status','=','generada')->first();

        if(!is_null($alerta))
        {
            $alerta->leido = true;

            $alerta->save();
        }
        return view('sisbeca.solicitudes.showSolicitud')->with('solicitud',$solicitud);
    }

    public function SolicitudUpdate(Request $request, $id)
    {
        /*
        $solicitud = Solicitud::find($id);

        $solicitud->fill($request->all());

        if($solicitud->titulo==='desincorporacion definitiva')
        {
            $solicitud->fecha_desincorporacion=  DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_desincorporacion').' 00:00:00');
        }
        else
        {
            if($solicitud->titulo==='desincorporacion temporal')
            {
                $solicitud->fecha_inactividad=  DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_inactividad').' 00:00:00');
            }
        }


        if(($solicitud->user_id==Auth::user()->id) &&($solicitud->status==='enviada') && $solicitud->save())
        {
            Alerta::where('solicitud', $id)->delete();
            event(new SolicitudesAlerts($solicitud));
            flash('Su solicitud fue actualizada exitosamente.','success')->important();
        }
        else
        {
            flash('Ha ocurrido un error al actualizar su solicitud.')->error()->important();
        }

        return  redirect()->route('solicitud.listar');
        */

    }

    public function solicitudCancelar($id)
    {
        $solicitud = Solicitud::find($id);
        if(is_null($solicitud))
        {
            flash('El archivo solicitado no ha sido encontrado.')->error()->important();
            return back();
        }

        if(($solicitud->user_id==Auth::user()->id) &&($solicitud->status==='enviada'))
        {
            //Alerta::where('solicitud', $id)->delete();
            $solicitud->status='cancelada';
            $solicitud->save();
            flash('Su solicitud ha sido cancelada exitosamente.','success')->important();
        }
        else
        {
            flash('Ha ocurrido un error al cancelar esta solicitud.')->error()->important();
        }

        return  redirect()->route('solicitud.listar');
    }

    public function ocultarsolicitud($id)
    {
        $solicitud = Solicitud::find($id);

        if($solicitud->status !== 'enviada')
        {
            $solicitud->oculto_usuario = 1;
            $solicitud->save();
            flash('La solicitud se oculto exitosamente.',"success");
        }
        else {
            flash('Error al ocultar Su solicitud no se encuentra respondida, puede cancelar solicitud y volver a intentarlo')->error()->important();
        }

        return redirect()->route('solicitud.listar');
    }
}
