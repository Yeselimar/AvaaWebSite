<?php

namespace avaa\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use avaa\Http\Requests\ActividadRequest;
use File;
use DateTime;
use Validator;
use DateTimeImmutable;
use avaa\Aval;
use avaa\Becario;
use avaa\Actividad;
use avaa\ActividadBecario;
use avaa\ActividadFacilitador;
use Barryvdh\DomPDF\Facade as PDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ActividadController extends Controller
{
    public function actividadesbecario($id)
    {
        if(Auth::user()->id == $id or Auth::user()->esDirectivo() or Auth::user()->esCoordinador() )
        {
            $becario = Becario::find($id);
            $ab = ActividadBecario::paraBecario($id)->get();
            return view('sisbeca.actividad.actividadesbecario')->with(compact('becario','ab'));
        }
        return view('sisbeca.error.404');
    }

    public function actualizardisponible($id)
    {
        $actividad = Actividad::find($id);
        $actividad->status = "disponible";
        $actividad->save();
        return response()->json(['success'=>'El '.ucwords($actividad->tipo).' fue actualizado como DISPONIBLE.']);
    }

    public function actualizarsuspendido($id)
    {
        $actividad = Actividad::find($id);
        $actividad->status = "suspendido";
        $actividad->save();

        //colocar a becarios como no asistio y los justificativos en rechazado
        $ab = ActividadBecario::paraActividad($id)->get();
        foreach ($ab as $item)
        {
            $item->estatus = "no asistio";
            $item->save();
            if($item->aval_id!=null)
            {
                $item->aval->estatus = "negada";
                $item->aval->save();
            }
        }
        return response()->json(['success'=>'El '.ucwords($actividad->tipo).' fue actualizado como SUSPENDIDO.']);
    }

    public function actualizaroculto($id)
    {
        $actividad = Actividad::find($id);
        $actividad->status = "oculto";
        $actividad->save();
        return response()->json(['success'=>'El '.ucwords($actividad->tipo).' fue actualizado como OCULTO.']);
    }

    public function actualizarcerrado($id)
    {
        $actividad = Actividad::find($id);
        $actividad->status = "cerrado";
        $actividad->save();
        return response()->json(['success'=>'El '.ucwords($actividad->tipo).' fue actualizado como CERRADO.']);
    }

	public function listar()
	{
        if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo() )
        {
            $actividades = Actividad::orderby('fecha','asc')->get();
        }
        else
        {
            $actividades = Actividad::menosConEstatus('oculto')->orderby('fecha','asc')->get();
        }
        return view('sisbeca.actividad.listar')->with(compact('actividades'));
	}

    //todos los justificativos
    public function listarjustificativos()
    {
        return view('sisbeca.actividad.listarjustificativos');
    }

    //todos los justificativos
    public function obtenerjustificativos()
    {
        //$justificativos = Aval::justificativos()->orderby('updated_at','desc')->with("user")->with("becario")->get();
        $justificativos = ActividadBecario::where('aval_id','!=','null')->orderby('created_at','desc')->with("user")->with("actividad")->with("aval")->get();

        return response()->json(['justificativos'=>$justificativos]);
    }

    //justificativos por actividad
    public function listarjustificativosactividad($id)
    {
        $actividad = Actividad::find($id);
        return view('sisbeca.actividad.justificadosporactividad')->with(compact('actividad'));
    }

    //justificativos por actividad
    public function justificativosactividad($id)
    {
        $justificativos = ActividadBecario::where('actividad_id','=',$id)->where('aval_id','!=','null')->orderby('created_at','desc')->with("user")->with("actividad")->with("aval")->get();
        return response()->json(['justificativos'=>$justificativos]);
    }
    
    public function actulizarestatus(Request $request,$actividad_id,$becario_id)
    {
        $becario = Becario::find($becario_id);
        $ab = ActividadBecario::where('actividad_id','=',$actividad_id)->where('becario_id','=',$becario_id)->first();
        $ab->estatus = $request->estatus;
        $ab->save();
        return response()->json(['success'=>'Al becario '.$becario->user->nombreyapellido().' fue actualizado con el estatus '.strtoupper($request->estatus).'.']);
    }

    public function subirjustificacion($actividad_id,$becario_id)
    {
        $ab = ActividadBecario::paraActividad($actividad_id)->paraBecario($becario_id)->get();
        if($ab->count()==1)
        {
            if((Auth::user()->esBecario() and $becario_id==Auth::user()->id) or Auth::user()->esDirectivo()  or Auth::user()->esCoordinador())
            {
                $actividad = Actividad::find($actividad_id);
                $becario = Becario::find($becario_id);
                if($actividad->lapsoparajustificar() or Auth::user()->esDirectivo()  or Auth::user()->esCoordinador())
                {
                    $model = "crear";
                    return view('sisbeca.actividad.subirjustificacion')->with(compact('actividad','becario','model'));
                }
            }
        }
        return view('sisbeca.error.404');
    }

    public function guardarjustificacion(Request $request,$actividad_id,$becario_id)
    {
        $actividad = Actividad::find($actividad_id);
        $becario = Becario::find($becario_id);

        $validation = Validator::make($request->all(), ActividadRequest::cargarJustificativo());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }

        $archivo= $request->file('justificativo');
        $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
        $ruta = public_path().'/'.Aval::carpetaJustificacion();
        $archivo->move($ruta, $nombre);
        
        $aval = new Aval;
        $aval->url = Aval::carpetaJustificacion().$nombre;
        $aval->estatus = "pendiente";
        $aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg" or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
        $aval->tipo = "justificacion";
        $aval->becario_id = $becario->user_id;
        $aval->save();

        $ab = ActividadBecario::where('actividad_id','=',$actividad_id)->where('becario_id','=',$becario_id)->first();
        $ab->estatus = "justificacion cargada";//chequear que sea asi
        $ab->aval_id = $aval->id;
        $ab->save();

        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "fecha_hora" =>  date("d/m/Y h:i A"),
            "actividad_tipo" => $actividad->getTipo(),
            "actividad_nombre" => $actividad->nombre
        );

        //Enviar correo a la persona notificando que fue recibido su justificativo
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
        $body = view("emails.actividades.notificacion-justificativo-cargado")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();

        flash("El justificativo del becario ".$becario->user->nombreyapellido()." al ".$actividad->tipo." ".$actividad->nombre." fue cargado.",'success');
        return redirect()->route('actividad.detalles',$actividad->id);
    }

    public function editarjustificacion($actividad_id,$becario_id)
    {
        $ab = ActividadBecario::paraActividad($actividad_id)->paraBecario($becario_id)->get();
        if($ab->count()==1)
        {
            if((Auth::user()->esBecario() and $becario_id==Auth::user()->id) or Auth::user()->esDirectivo()  or Auth::user()->esCoordinador())
            {
                $actividad = Actividad::find($actividad_id);
                $becario = Becario::find($becario_id);
                $justificativo = ActividadBecario::where('becario_id','=',$becario->user_id)->where('actividad_id','=',$actividad->id)->first();
                $model = "editar";
                return view('sisbeca.actividad.subirjustificacion')->with(compact('actividad','becario','model','justificativo'));
            }
        }
        return view('sisbeca.error.404');
    }

    public function actualizarjustificacion(Request $request,$actividad_id,$becario_id)
    {
        $actividad = Actividad::find($actividad_id);
        $becario = Becario::find($becario_id);
        $ab = ActividadBecario::where('becario_id','=',$becario->user_id)->where('actividad_id','=',$actividad->id)->first();
        $ab->estatus = "justificacion cargada";
        $ab->updated_at = date("Y-m-d H:i:s");
        $ab->save();

        $model = "crear";
        $validation = Validator::make($request->all(), ActividadRequest::actualizarJustificativo());
        
        if($request->file('justificativo'))
        {
            File::delete($ab->aval->url);
            
            $archivo= $request->file('justificativo');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Aval::carpetaJustificacion();
            $archivo->move($ruta, $nombre);
            
            $aval = $ab->aval;
            $aval->estatus = "pendiente";
            $aval->url = Aval::carpetaJustificacion().$nombre;
            $aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg"or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
            $aval->save();
        }
        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "fecha_hora" =>  date("d/m/Y h:i A"),
            "actividad_tipo" => $actividad->getTipo(),
            "actividad_nombre" => $actividad->nombre,
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
        $body = view("emails.actividades.notificacion-justificativo-actualizado")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();

        flash("El justificativo del becario ".$becario->user->nombreyapellido()." al ".$actividad->tipo." ".$actividad->nombre." fue actualizado.",'success');
        return redirect()->route('actividad.editarjustificacion',array('actividad_id'=>$actividad->id,'becario_id'=>$becario->user_id));
    }

    public function detalles($id)
    {
        $actividad = Actividad::find($id);
        if((Auth::user()->esBecario() and !$actividad->estaOculto()) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
        {
            return view('sisbeca.actividad.detalles')->with(compact('actividad'));
        }
        return view('sisbeca.error.404');
    }

    public function detallesservicio($id)
    {
        $actividad = Actividad::find($id);
        $facilitadores = ActividadFacilitador::where('actividad_id','=',$id)->with("user")->get();
        $becarios = ActividadBecario::where('actividad_id','=',$id)->with("user")->with("aval")->get();
        $inscrito = ActividadBecario::where('actividad_id','=',$id)->where('becario_id','=',Auth::user()->id)->count();
        $inscrito = ($inscrito==0) ? false : true;
        $estatus = (object)["0"=>"asistira", "1"=>"lista de espera", "2"=>"justificacion cargada", "3"=>"asistio","4"=>"no asistio"];
        $id_autenticado = Auth::user()->id;
        $lapso_justificar = $actividad->lapsoparajustificar();
        $estatus_aval = null;
        if(Auth::user()->esBecario())
        {
            if(!empty(ActividadBecario::where('actividad_id','=',$id)->where('becario_id','=',Auth::user()->id)->first()))
            {
                $ab = ActividadBecario::where('actividad_id','=',$id)->where('becario_id','=',Auth::user()->id)->first();
                $estatus_becario = $ab->estatus;
                if($ab->aval_id!=null)
                {
                    $estatus_aval = $ab->aval->estatus;
                }

            }
            else
            {
                $estatus_becario = null;
            }
           

        }
        else
        {
            $estatus_becario = null;
        }
        return response()->json(['actividad'=>$actividad,'facilitadores'=>$facilitadores,'becarios'=>$becarios,'inscrito'=>$inscrito,'estatus'=>$estatus,'id_autenticado'=>$id_autenticado,'lapso_justificar'=>$lapso_justificar,'estatus_becario'=>$estatus_becario,'estatus_aval'=>$estatus_aval]);
    }

    public function actividadinscribirme($actividad_id,$becario_id)
    {
        $actividad = Actividad::find($actividad_id);
        //para inscribir: verificar estatus, limite y fecha
        if( $actividad->inscribionabierta() )
        {
            $becario = Becario::find($becario_id);
            $encontrado = ActividadBecario::where('becario_id','=',$becario->user_id)->where('actividad_id','=',$actividad_id)->count();
            if($encontrado==0)
            {
                $ab = new ActividadBecario;
                $ab->actividad_id = $actividad_id;
                $ab->becario_id = $becario->user_id;
                //verificamos si no va a la lista de espera 
                if($actividad->totalbecariosasistira()>=$actividad->limite_participantes)
                {
                    $ab->estatus = "lista de espera";
                    $ab->save();

                    $estatus = "LISTA DE ESPERA";//Variable que se usa para enviar el correo
                    $data = array(
                        "email" => $becario->user->email,
                        "becario" => $becario->user->nombreyapellido(),
                        "estatus" => $estatus,
                        "actividad_tipo" => $actividad->getTipo(),
                        "actividad_nombre" => $actividad->nombre,
                        "actividad_fecha" => $actividad->getDiaFecha().", ".$actividad->getFecha(),
                        "actividad_hora" => $actividad->getHoraInicio().' a '.$actividad->getHoraFin(),
                    );

                    //Enviar correo a la persona que se inscribio a la actividad
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
                    $body = view("emails.actividades.notificacion-inscripcion")->with(compact("data"));
                    $mail->MsgHTML($body);
                    $mail->addAddress($becario->user->email);
                    //$mail->send();

                    return response()->json(['tipo'=>'success','mensaje'=>'Ud. '.$becario->user->nombreyapellido().' fue inscrito en la LISTA DE ESPERA del '.$actividad->tipo.' '.$actividad->nombre.'.']);
                }
                else
                {
                    $ab->estatus = "asistira";
                    $ab->save();

                    $estatus = "ASISTIRÁ";//Variable que se usa para enviar el correo
                    $data = array(
                        "email" => $becario->user->email,
                        "becario" => $becario->user->nombreyapellido(),
                        "estatus" => $estatus,
                        "actividad_tipo" => $actividad->getTipo(),
                        "actividad_nombre" => $actividad->nombre,
                        "actividad_fecha" => $actividad->getDiaFecha().", ".$actividad->getFecha(),
                        "actividad_hora" => $actividad->getHoraInicio().' a '.$actividad->getHoraFin(),
                    );

                    //Enviar correo a la persona que se inscribio a la actividad
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
                    $body = view("emails.actividades.notificacion-inscripcion")->with(compact("data"));
                    $mail->MsgHTML($body);
                    $mail->addAddress($becario->user->email);
                    //$mail->send();

                    return response()->json(['tipo'=>'success','mensaje'=>'Ud. '.$becario->user->nombreyapellido().' fue inscrito al '.$actividad->tipo.' '.$actividad->nombre.'.']);
                }
            }
            else
            {
                return response()->json(['tipo'=>'danger','mensaje'=>'Disculpe, ud '.$becario->user->nombreyapellido().' ya está inscrito en el '.$actividad->tipo.' '.$actividad->nombre.'.']);
            }
        }
        else
        {
            return response()->json(['tipo'=>'danger','mensaje'=>'Disculpe, el proceso para inscribir becario está cerrado.']);
        }
    }

    public function inscribirbecario($id)
    {
        //colorcarlo en lista de espera en caso de que este llena la actividad.
        $actividad = Actividad::find($id);
        $becarios = Becario::activos()->terminosAceptados()->probatorio1()->probatorio2()->get();
        $model = "crear";
        //if( $actividad->inscribionabierta() )
        //{
            return view('sisbeca.actividad.inscribirbecario')->with(compact('actividad','becarios','model'));
        //}
        //else
        //{
        //    flash('Disculpe, el proceso para inscribir becario está cerrado.', 'danger')->important();
        //    return back();
        //}
    }

    public function inscribirbecarioguardar(Request $request,$id)
    {
        $actividad = Actividad::find($id);
        //if( $actividad->inscribionabierta() )
        //{
            $encontrado = ActividadBecario::where('becario_id','=',$request->becario_id)->where('actividad_id','=',$id)->count();
            $becario = Becario::find($request->becario_id);
            if($encontrado==0)
            {
                $ab = new ActividadBecario;
                $ab->actividad_id = $id;
                $ab->becario_id = $request->becario_id;
                if($actividad->totalbecariosasistira()>=$actividad->limite_participantes)
                {

                    $ab->estatus = "lista de espera";
                    $estatus = "LISTA DE ESPERA";
                    flash('El becario '.$becario->user->nombreyapellido().' fue inscrito en la LISTA DE ESPERA al '.ucwords($actividad->tipo).': '.$actividad->nombre.'.', 'success')->important();
                }
                else
                {
                    $estatus = "ASISTIRÁ";
                    flash('El becario '.$becario->user->nombreyapellido().' fue inscrito como ASISTIRÁ al '.ucwords($actividad->tipo).': '.$actividad->nombre.'.', 'success')->important();
                }
                $data = array(
                    "becario" => $becario->user->nombreyapellido(),
                    "estatus" => $estatus,
                    "actividad_tipo" => $actividad->getTipo(),
                    "actividad_nombre" => $actividad->nombre,
                    "actividad_fecha" => $actividad->getDiaFecha().", ".$actividad->getFecha(),
                    "actividad_hora" => $actividad->getHoraInicio().' a '.$actividad->getHoraFin(),
                );
                //Enviar correo a la persona que se inscribio a la actividad
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
                $body = view("emails.actividades.notificacion-inscripcion")->with(compact("data"));
                $mail->MsgHTML($body);
                $mail->addAddress($becario->user->email);
                //$mail->send();

                $ab->save();
                return redirect()->route('actividad.detalles',$id);
            }
            else
            {
                flash('Disculpe, el becario '.$becario->user->nombreyapellido().' ya está inscrito en el '.ucwords($actividad->tipo).': '.$actividad->nombre.'.', 'danger')->important();
                return redirect()->route('actividad.detalles',$id);
            }
        //}
        //else
        //{
        //    flash('Disculpe, el proceso para inscribir becario está cerrado.', 'danger')->important();
        //    return redirect()->route('actividad.detalles',$id);
        //}
    }

    public function desinscribir($actividad_id,$becario_id)
    {
        $becario = Becario::find($becario_id);
        $actividad = Actividad::find($actividad_id);

        //cada vez que se desinscriba alguien, actualizo mi lista de espera si hay
        $listadeespera  = $actividad->listadeespera();
        if($listadeespera->count()>=1)
        {
            $beneficiado = $listadeespera[0];
            $beneficiado->estatus = "asistira";
            $beneficiado->save();
            $usuario = $beneficiado->user;
            //notificar al becario que fue pasado de "lista de espera" a "asistira"
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
            $body = view("emails.actividades.notificacion-paso-a-asistio")->with(compact("usuario","actividad"));
            $mail->MsgHTML($body);
            $mail->addAddress($usuario->email);
            //$mail->send();
        }
        //if( $actividad->inscribionabierta() )
        //{
            $ab = ActividadBecario::paraActividad($actividad->id)->paraBecario($becario->user->id)->first();
            if($ab->aval_id!=null)
            {
                $aval = $ab->aval;
                $ab->delete();
                File::delete($ab->aval->url);
                $aval->delete();
            }
            else
            {
                $ab->delete();
            }

            $estatus = "ELIMINADO";
            if($becario->user->sexo=="femenino")
            {
                $estatus = "ELIMINADA";
            }
            $data = array(
                "becario" => $becario->user->nombreyapellido(),
                "estatus" => $estatus,
                "actividad_tipo" => $actividad->getTipo(),
                "actividad_nombre" => $actividad->nombre,
                "actividad_fecha" => $actividad->getDiaFecha().", ".$actividad->getFecha(),
                "actividad_hora" => $actividad->getHoraInicio().' a '.$actividad->getHoraFin(),
            );
            //Enviar correo al becario, notificando que fue elimiado de la actividad
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
            $mail->addAddress($becario->user->email);
            //$mail->send();
            return response()->json(['tipo'=>'success','mensaje'=>'El becario '.$becario->user->nombreyapellido().' fue eliminado del '.$actividad->tipo.'.']);
        //}
        //else
        //{
        //    return response()->json(['tipo'=>'danger','mensaje'=>'Disculpe, el proceso para eliminar inscripción está cerrado.']);
        //}
    }
    public function crear()
    {
    	$model = "crear";
        $actividad = null;
    	return view('sisbeca.actividad.model')->with(compact('model','actividad'));
    }

    public function guardar(Request $request)
    {
    	$request->validate([
            'nombre' 			 	=> 'required|min:5,max:255',
            'tipo'				 	=> 'required',
            'modalidad' 		 	=> 'required',
            'nivel' 			 	=> 'required',
            'anho_academico' 	 	=> 'min:0,max:255',
            'limite'  				=> 'required|integer|between:0,100',
            //'horas'					=> 'required|integer|between:0,100',
            'fecha'					=> 'required|date_format:d/m/Y',
            'hora_inicio'			=> 'required|date_format:h:i A',
            'hora_fin'				=> 'required|date_format:h:i A',
            'descripcion'			=> 'required|min:0,max:10000',
        ]);
    	
    	$actividad = new Actividad;
    	$actividad->nombre = $request->nombre;
    	$actividad->tipo = $request->tipo;
    	$actividad->modalidad = $request->modalidad;
    	$actividad->nivel = $request->nivel;
    	$actividad->anho_academico = $request->anho_academico;
    	$actividad->limite_participantes = $request->limite;
    	//$actividad->horas_voluntariado = $request->horas;
    	$actividad->fecha = DateTime::createFromFormat('d/m/Y', $request->fecha )->format('Y-m-d');
    	$actividad->hora_inicio = DateTime::createFromFormat('H:i a', $request->hora_inicio )->format('H:i:s');
    	$actividad->hora_fin = DateTime::createFromFormat('H:i a', $request->hora_fin )->format('H:i:s');
    	$actividad->descripcion = $request->descripcion;
    	$actividad->status = "disponible";
    	$actividad->save();
    	
    	//$index=0;
    	//$aux  = $request->get('facilitadores');
    	//$i=0;
    	//$tipo = gettype($request->facilitadores);
    	//$jsons = json_decode($request->facilitadores, true);
    	foreach($request->facilitadores as $facilitador)
    	{
    		//$i++;
    		//$primero = $facilitador["id"];
    		if($facilitador["becario"]=="no")
    		{
    			$af = new ActividadFacilitador;
    			$af->actividad_id = $actividad->id;
    			$af->nombreyapellido =  $facilitador["nombre"];
    			$af->save();
    		}
    		else
    		{
    			$af = new ActividadFacilitador;
    			$af->actividad_id = $actividad->id;
    			$af->becario_id = $facilitador["id"];
                $af->horas = $facilitador["horas"];
    			$af->save();
    		}
    	}
    	//crear actividad_facilitador
    	//$count = count($request->facilitadores);
    	return response()->json(['success'=>'El '.$actividad->tipo.' fue creado exitosamente.']);
    }

    public function editar($id)
    {
        $model = "editar";
        $actividad = Actividad::find($id);
        return view('sisbeca.actividad.model')->with(compact('actividad','model'));
    }

    public function actualizar(Request $request, $id)
    {
        $actividad = Actividad::find($id);
        $request->validate([
            'nombre'                => 'required|min:5,max:255',
            'tipo'                  => 'required',
            'modalidad'             => 'required',
            'nivel'                 => 'required',
            'anho_academico'        => 'min:0,max:255',
            'limite'                => 'required|integer|between:0,100',
            //'horas'                 => 'required|integer|between:0,100',
            'fecha'                 => 'required|date_format:d/m/Y',
            'hora_inicio'           => 'required|date_format:h:i A',
            'hora_fin'              => 'required|date_format:h:i A',
            'descripcion'           => 'min:0,max:10000',
        ]);
        $actividad->nombre = $request->nombre;
        $actividad->tipo = $request->tipo;
        $actividad->modalidad = $request->modalidad;
        $actividad->nivel = $request->nivel;
        $actividad->anho_academico = $request->anho_academico;
        $actividad->limite_participantes = $request->limite;
        //$actividad->horas_voluntariado = $request->horas;
        $actividad->fecha = DateTime::createFromFormat('d/m/Y', $request->fecha )->format('Y-m-d');
        $actividad->hora_inicio = DateTime::createFromFormat('H:i a', $request->hora_inicio )->format('H:i:s');
        $actividad->hora_fin = DateTime::createFromFormat('H:i a', $request->hora_fin )->format('H:i:s');
        $actividad->descripcion = $request->descripcion;
        $actividad->save();
        //borramos los facilitadores
        foreach($actividad->facilitadores as $facilitador)
        {
            $facilitador->delete();
        }
        //actualizamos los facilitadores
        foreach($request->facilitadores as $facilitador)
        {
            if($facilitador["becario"]=="no")
            {
                $af = new ActividadFacilitador;
                $af->actividad_id = $actividad->id;
                $af->nombreyapellido =  $facilitador["nombre"];
                $af->save();
            }
            else
            {
                $af = new ActividadFacilitador;
                $af->actividad_id = $actividad->id;
                $af->becario_id = $facilitador["id"];
                $af->horas = $facilitador["horas"];
                $af->save();
            }
        }
        
        return response()->json(['success'=>'El '.$actividad->tipo.' fue actualizado exitosamente.']);
    }

    public function obteneractividad($id)
    {
        //$actividad = Actividad::find($id);
        //return $actividad->facilitadores;
        $actividad = Actividad::where("id","=",$id)->with("facilitadores")->first();
        return response()->json(['actividad'=>$actividad]);
    }

    //becarios activos son los que pueden ser facilitador
    public function obtenerbecarios()
    {
    	$becarios = Becario::activos()->probatorio1()->TerminosAceptados()->with("user")->get();
    	return response()->json(['becarios'=>$becarios]);
    }

    public function eliminar($id)
    {
        //opcion de borrado blando
        //eliminar facilitadores,becarios,aval o justificativos
        //recorrer en Actividad becario para eliminar justificativos
        $actividad = Actividad::find($id);
        $tipo = $actividad->tipo;
        if($actividad->becarios->count()==0 )
        {
            foreach($actividad->facilitadores as $facilitador)
            {
                $facilitador->delete();
            }
            $actividad->delete();
            flash("El ".$tipo." fue eliminado exitosamente.",'success');
            return redirect()->route('actividad.listar');
        }
        else
        {
            flash("El ".$tipo." no puede ser eliminado. Existen información relacionado al mismo.",'danger');
            return redirect()->route('actividad.detalles',$actividad->id);
        }
    }

    public function listaasistente($id)
    {
        /*
        $nominas = Nomina::where('mes',$mes)->where('year',$anho)->where('status','=','generado')->get(); 
        $pdf = PDF::loadView('sisbeca.nomina.generadopdf', compact('nominas','mes','anho'));
        return $pdf->stream('listado.pdf','PDF xasa');
        */
        $actividad = Actividad::find($id);
        $facilitadores = ActividadFacilitador::where("actividad_id",'=',$id)->with("user")->get();
        $becarios = ActividadBecario::where("actividad_id",'=',$id)->with("user")->get();
        $pdf = PDF::loadView('pdf.actividad.listaasistentes', compact('actividad','facilitadores','becarios'));
        return $pdf->stream('listado.pdf');
    }

    public function colocarasistio($a_id,$b_id)
    {
        $actividad = Actividad::find($a_id);
        $becario = Becario::find($b_id);
        $ab = ActividadBecario::paraActividad($actividad->id)->paraBecario($becario->user_id)->first();
        $ab->estatus = "asistio";
        $ab->save();
        
        $genero = "apuntado";
        if($becario->user->sexo=="femenino")
        {
            $genero = "apuntada";
        }
        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "genero" => $genero,
            "estatus" => "ASISTIÓ",
            "actividad_tipo" => $actividad->getTipo(),
            "actividad_nombre" => $actividad->nombre,
            "actividad_fecha" => $actividad->getDiaFecha().", ".$actividad->getFecha(),
            "actividad_hora" => $actividad->getHoraInicio().' a '.$actividad->getHoraFin(),
        );
        //Notificar que colocado como asistió
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
        $body = view("emails.actividades.notificacion-asistio")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
        return response()->json(['success'=>'El becario '.$becario->user->nombreyapellido().' fue colocado como ASISTIÓ.']);
    }

    public function colocarnoasistio($a_id,$b_id)
    {
        $actividad = Actividad::find($a_id);
        $becario = Becario::find($b_id);
        $ab = ActividadBecario::paraActividad($actividad->id)->paraBecario($becario->user_id)->first();
        $ab->estatus = "no asistio";
        $ab->save();

        $genero = "apuntado";
        if($becario->user->sexo=="femenino")
        {
            $genero = "apuntada";
        }
        $data = array(
            "becario" => $becario->user->nombreyapellido(),
            "genero" => $genero,
            "estatus" => "NO ASISTIÓ",
            "actividad_tipo" => $actividad->getTipo(),
            "actividad_nombre" => $actividad->nombre,
            "actividad_fecha" => $actividad->getDiaFecha().", ".$actividad->getFecha(),
            "actividad_hora" => $actividad->getHoraInicio().' a '.$actividad->getHoraFin(),
        );
        //Notificar que colocado como no asistió
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
        $body = view("emails.actividades.notificacion-no-asistio")->with(compact("data"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
        return response()->json(['success'=>'El becario '.$becario->user->nombreyapellido().' fue colocado como NO ASISTIÓ.']);
    }

    public function obtenerjustificativobecario($a_id,$b_id)
    {
        $ab = ActividadBecario::paraActividad($a_id)->paraBecario($b_id)->with("aval")->first();
        return response()->json(['justificativo'=>$ab]);
    }
}   
