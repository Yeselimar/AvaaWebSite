<?php

namespace avaa\Http\Controllers;

use avaa\Coordinador;
use avaa\Desincorporacion;
use avaa\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use avaa\Becario;
use avaa\Mentor;
use avaa\Alerta;
use avaa\Solicitud;
use avaa\Documento;
use avaa\Imagen;
use avaa\Rechazado;
use avaa\BecarioEntrevistador;
use avaa\Events\SolicitudesAlerts;
use Illuminate\Support\Facades\Auth;
use DateTime;
use avaa\Concurso;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use File;

class CompartidoDirecCoordController extends Controller
{
    public function __construct()
    {
        $this->middleware('compartido_direc_coord');
    }

    //Asignar fecha de bienvenida a todos por Igual
    public function asignarfechabienvenidaparatodos(Request $request){
        $postulantes = Becario::where('status','=','entrevistado')->orwhere('status','=','rechazado')->orwhere('status','=','activo')->where('acepto_terminos','=',false)->get();
            foreach ($postulantes as $postulante)
            {
                $postulante->lugar_bienvenida = $request->lugar;
                $postulante->hora_bienvenida = DateTime::createFromFormat('H:i a', $request->hora )->format('H:i:s');
                $postulante->fecha_bienvenida = DateTime::createFromFormat('d/m/Y', $request->fecha )->format('Y-m-d');
                $postulante->save();
            }
            return response()->json(['success'=>'Entré Exitosamente']);
    }
    //Asignar fecha de bienvenida Individualmente
    public function asignarfechabienvenida(Request $request)
    {
        $becario = Becario::find($request->id);
        $becario->lugar_bienvenida = $request->lugar;
        $becario->hora_bienvenida = DateTime::createFromFormat('H:i a', $request->hora )->format('H:i:s');
        $becario->fecha_bienvenida = DateTime::createFromFormat('d/m/Y', $request->fecha )->format('Y-m-d');
        $becario->save();

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
        $body = view("emails.becarios.notificacion-fecha-bienvenida")->with(compact("becario"));
        $mail->MsgHTML($body);
        $mail->addAddress($becario->user->email);
        //$mail->send();
        return response()->json(['success'=>'La fecha bienvenida para el becario '.$becario->user->nombreyapellido()." fue asignada exitosamente."]);
    }
    //Agregar Observacion a un Becario
    public function agregarObservacion(Request $request, $id)
    {
        $becario = Becario::find($id);
        $becario->observacion = $request->get("observaciones");
        $becario->save();
        return redirect()->route('perfilPostulanteBecario',$id);
    }
    //Obtener los postulantes que ya pueden ser aprobados o rechazados como becario
    public function obtener_entrevistados()
    {
        $postulantes = Becario::where('status','=','entrevistado')->orwhere('status','=','activo')->where('acepto_terminos','=',false)->with("user")->with('entrevistadores')->with('imagenes')->get();
        $rechazados= Rechazado::where('etapa','=','despues')->where('datos','=','sinborrar')->get();

        //$rechazados = Becario::where('status','=','rechazado')->where('fecha_entrevista', '!=', null)->with("user")->get();

       // return view('sisbeca.postulaciones.asignarNuevoIngreso')->with('becarios',$becarios)->with('rechazados', $rechazados);

        return response()->json(['postulantes'=>$postulantes, 'rechazados'=>$rechazados]);
    }
    public function perfilPostulanteRechazado($id){
        $usuario = User::where('cedula', '=', $id)->first();
        if($usuario!=NULL){ //si consigue usuario
            $becario = Becario::find($usuario->id);//entonces busca el becario
            return redirect()->route('perfilPostulanteBecario',$becario->user_id);
        }

    }
    //Aprobar/Rechazar a un postulante para ir a entrevista
    public function aprobarParaEntrevista(Request $request, $id)
    {
        $postulante = Becario::find($id);
        if($postulante->status==='postulante')
        {
            if ($request->valor === '1') {
                $postulante->status = 'entrevista';
                $postulante->acepto_terminos ='0';
                $postulante->save();
                flash( $postulante->user->name . ' ha sido aprobado para ir a entrevista. ', 'success')->important();
            }
            else
            {
                $rechazado=new Rechazado;
                $rechazado->cedula=$postulante->user->cedula;
                $rechazado->name=$postulante->user->name;
                $rechazado->last_name=$postulante->user->last_name;
                $rechazado->telefono=$postulante->telefono;
                $rechazado->fecha_de_participacion=$postulante->user->created_at;
                $rechazado->save();
                $postulante->status='rechazado';
                $postulante->acepto_terminos ='0';
                $postulante->save();
                $postulante->borrarDocumentos();
                flash( $postulante->user->name . ' ha sido descartado para ir a entrevista. ', 'danger')->important();
            }
        }
        return  redirect()->route('listarPostulantesBecarios',"todos");
    }
    //Aprobar/rechazar la entrevista de un postulante
    public function cambiostatusentrevistado(Request $request)
    {
        $postulanteBecario = Becario::find($request->id);
        if(!is_null($postulanteBecario))
        {
            if($request->funcion=='Aprobar'){
                $postulanteBecario->status= 'entrevistado';
                $postulanteBecario->save();
                return response()->json(['success'=>$postulanteBecario->user->name.' '.$postulanteBecario->user->last_name.' ha sido aprobado durante el proceso de entrevista.']);
                }
            else{
                $rechazado=new Rechazado;
                $rechazado->cedula=$postulanteBecario->user->cedula;
                $rechazado->name=$postulanteBecario->user->name;
                $rechazado->last_name=$postulanteBecario->user->last_name;
                $rechazado->telefono=$postulanteBecario->telefono;
                $rechazado->fecha_de_participacion=$postulanteBecario->user->created_at;
                $rechazado->save();
                $postulanteBecario->status= 'rechazado';
                $postulanteBecario->save();
                $postulanteBecario->borrarDocumentos();
                //borrar documentos
                return response()->json(['success'=>$postulanteBecario->user->name.' '.$postulanteBecario->user->last_name.' ha sido rechazado durante el proceso de entrevista.']);
            }
        }
        else
        {
            flash('Disculpe, ha forzado la información incorrecta','danger')->important();
        }
    }
    //Aprobar/Rechazar a un postulante a ProExcelencia
    public function veredictoPostulantesBecarios(Request $request)
    {
        $postulanteBecario = Becario::find($request->id);
        if($request->funcion=='Aprobar')
        {
            $postulanteBecario->status='activo';
            $postulanteBecario->acepto_terminos=false;
            $postulanteBecario->fecha_ingreso= date('Y-m-d H:i:s');
            $postulanteBecario->save();
            $decision = "APROBADA";

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
            $body = view("emails.postulantebecario.aprobado-proexcelencia")->with(compact("postulanteBecario","decision"));
            $mail->MsgHTML($body);
            $mail->addAddress($postulanteBecario->user->email);
            //$mail->send();
            return response()->json(['success'=>'El Postulante ha sido Aprobado Exitosamente']);
        }
        else
        {
            $rechazado=new Rechazado;
            $rechazado->cedula=$postulanteBecario->user->cedula;
            $rechazado->name=$postulanteBecario->user->name;
            $rechazado->last_name=$postulanteBecario->user->last_name;
            $rechazado->telefono=$postulanteBecario->telefono;
            $rechazado->fecha_de_participacion=$postulanteBecario->user->created_at;
            $rechazado->etapa='despues';
            $rechazado->save();
            $postulanteBecario->status='rechazado';
            //$postulanteBecario->user->save();
            $postulanteBecario->acepto_terminos=false;
            $postulanteBecario->save();
            $decision = "RECHAZADA";
            $postulanteBecario->borrarDocumentos();
            //Enviar correo a la persona notificando que fue rechazado
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
            $body = view("emails.postulantebecario.rechazado-proexcelencia")->with(compact("postulanteBecario","decision"));
            $mail->MsgHTML($body);
            $mail->addAddress($postulanteBecario->user->email);
            //$mail->send();

            //borrar documentos
/*
            $id = $postulanteBecario->user->id;
            $fotografia = Imagen::where('user_id','=',$id)->where('titulo','=','fotografia')->first();
            if($fotografia)
            {
                $postulanteBecario->documentos = 0; //Para indicar que no ha cargado los documentos
                $postulanteBecario->save();
                $cedula = Imagen::where('user_id','=',$id)->where('titulo','=','cedula')->first();
                $constancia_cnu = Documento::where('user_id','=',$id)->where('titulo','=','constancia_cnu')->first();
                $calificaciones_bachillerato = Documento::where('user_id','=',$id)->where('titulo','=','calificaciones_bachillerato')->first();
                $constancia_aceptacion = Documento::where('user_id','=',$id)->where('titulo','=','constancia_aceptacion')->first();
                $constancia_estudios = Documento::where('user_id','=',$id)->where('titulo','=','constancia_estudios')->first();
                $calificaciones_universidad = Documento::where('user_id','=',$id)->where('titulo','=','calificaciones_universidad')->first();
                $constancia_trabajo = Documento::where('user_id','=',$id)->where('titulo','=','constancia_trabajo')->first();
                $declaracion_impuestos = Documento::where('user_id','=',$id)->where('titulo','=','declaracion_impuestos')->first();
                $recibo_pago = Documento::where('user_id','=',$id)->where('titulo','=','recibo_pago')->first();
                $referencia_profesor1 = Documento::where('user_id','=',$id)->where('titulo','=','referencia_profesor1')->first();
                $referencia_profesor2 = Documento::where('user_id','=',$id)->where('titulo','=','referencia_profesor2')->first();
                $ensayo = Documento::where('user_id','=',$id)->where('titulo','=','ensayo')->first();

                File::delete(substr($fotografia->url,1));
                File::delete(substr($cedula->url,1));
                File::delete(substr($constancia_cnu->url,1));
                File::delete(substr($calificaciones_bachillerato->url,1));
                File::delete(substr($constancia_aceptacion->url,1));
                File::delete(substr($constancia_estudios->url,1));
                File::delete(substr($calificaciones_universidad->url,1));
                File::delete(substr($constancia_trabajo->url,1));
                File::delete(substr($declaracion_impuestos->url,1));
                File::delete(substr($recibo_pago->url,1));
                File::delete(substr($referencia_profesor1->url,1));
                File::delete(substr($referencia_profesor2->url,1));
                File::delete(substr($ensayo->url,1));

                $fotografia->delete();
                $cedula->delete();
                $constancia_cnu->delete();
                $calificaciones_bachillerato->delete();
                $constancia_aceptacion->delete();
                $constancia_estudios->delete();
                $calificaciones_universidad->delete();
                $constancia_trabajo->delete();
                $declaracion_impuestos->delete();
                $recibo_pago->delete();
                $referencia_profesor1->delete();
                $referencia_profesor2->delete();
                $ensayo->delete();
            } */
            return response()->json(['success'=>'El Postulante ha sido rechazado exitosamente.']);
        }
    }

    public function listarPostulantesBecarios($data)
    {

        if($data===0)
        {
            //Lista los aprobados para entrevisa

            $becarios = Becario::query()->where('status','=','entrevista')->where('acepto_terminos','=',$data)->get();
            return view('sisbeca.postulaciones.entrevistas')->with('becarios',$becarios)->with('becarios',$becarios);
        }
        if($data==="todos") //2todos los postulantes
        {
            $usuario=User::where('rol','=','postulante_becario')->get();
            $becarios= Becario::where('status','=','postulante')->orwhere('status','=','rechazado')->orwhere('status','=','entrevista')->orwhere('status','=','entrevistado')->orwhere('status','=','activo')->where('acepto_terminos','=',false)->get();
            return view('sisbeca.postulaciones.verPostulantesBecario')->with('becarios',$becarios);
        }
        if($data==="rechazados") //Lista todos los rechazados
        {
           //$postulantes = Becario::query()->where('status','=','rechazado')->get();
            $postulantes = Rechazado::all();
           // dd($postulantes);
            return view('sisbeca.postulaciones.postulantesRechazados')->with('postulantes',$postulantes);
        }
       if($data=="entrevistaAprobada") //todos lo que Aprobaron la entrevista o fueron aprobados como becario
        {
            $rechazados = Becario::where('status','=','rechazado')->get();
           // dd($rechazados);
            $becarios = Becario::where('status','=','entrevistado')->orwhere('status','=','activo')->where('acepto_terminos','=',false)->get();
            return view('sisbeca.postulaciones.asignarNuevoIngreso')->with('becarios',$becarios)->with('rechazados', $rechazados);
        }
    }

    public function listarBecarios()
    {
        if( Auth::user()->rol==='directivo' or Auth::user()->rol==='coordinador')
        {
            $becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
            return view('sisbeca.becarios.listar')->with('becarios',$becarios);
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function listarMentores()
    {
        //$mentores = Mentor::where('status','=','activo')->orwhere('status','=','inactivo')->orwhere('status','=','desincorporado')->get();
        //dd($mentores);
        $mentores = Mentor::where('status','=','activo')->get();
        //dd($mentores);
        if($mentores->count()>0)
        {
                $mentores->each(function ($mentores)
                {
                    $mentores->becarios;
                    foreach ($mentores->becarios as $becario)
                    {

                        $becario->user;
                    }
                });
        }
        /*else
        {
            flash('Disculpe, no existen mentores registrados.','danger');
        }*/
        return view('sisbeca.mentores.listar')->with('mentores',$mentores);
    }


    // Controlador    CompartidoDirecCoordController modificar metodos
    public function listarSolicitudes()
    {
        $becariosAsignados = Becario::query()->where('acepto_terminos', '=', true)->whereIn('status', ['probatorio1', 'probatorio2', 'activo','inactivo','desincorporado','egresado'])->get();
        $listaBecariosA=$becariosAsignados->pluck('user_id')->toArray();
        /* if(Auth::user()->rol==='directivo' or Auth::user()->rol==='coordinador')
        {
            $mentoresNuevos= Mentor::all();

            $collection = collect();
            foreach ($mentoresNuevos as $mentor)
            {
                if($mentor->becarios()->count()==0)
                {
                    $collection->push($mentor);
                }
            }
            dd($collection);
        }
        else
        {
            $collection = collect();
            foreach ($becariosAsignados as $beca)
            {
                $collection->push($beca->mentor);
            }
        }
        $listMentoresA = $collection->pluck('user_id')->toArray();
        $mentoresAsignados = Mentor::query()->whereIn('user_id', $listMentoresA)->get();
        $listaMentoresA = $mentoresAsignados->pluck('user_id')->toArray(); */
        $solicitudes = Solicitud::visibleAdmin()->get();
        return view('sisbeca.gestionSolicitudes.listarSolicitudes')->with('solicitudes',$solicitudes);
    }

    public function revisarSolicitud($id)
    {
        $solicitudes = Solicitud::find($id);

        $alerta= Alerta::query()->select()->where('solicitud','=',$id)->first();
        $alerta->leido=true;
        $alerta->save();

        $img_perfil_postulante=Imagen::query()->where('user_id','=',$solicitudes->user_id)->where('titulo','=','img_perfil')->get();

        return view('sisbeca.gestionSolicitudes.procesarSolicitud')->with('solicitud',$solicitudes)->with('img_perfil_postulante',$img_perfil_postulante);
    }

    public function gestionSolicitudUpdate(Request $request, $id)
    {
        $solicitud = Solicitud::find($id);

        $request->validate([
            'observacion' 			 	=> 'required',
        ]);
        $msg = '';
        if($solicitud->status !== 'enviada'){
            return response()->json(['res' => 0,'msg'=>'Disculpe, esta solicitud ya ha sido respondida anteriormente']);
        }
        if(Auth::user()->rol==='directivo' or Auth::user()->rol==='coordinador')
        {
            $becariosAsignados = Becario::query()->where('acepto_terminos', '=', true)->whereIn('status', ['probatorio1', 'probatorio2', 'activo','inactivo'])->get();
            $listaBecariosA = $becariosAsignados->pluck('user_id')->toArray();
            $clave = array_search($solicitud->user_id, $listaBecariosA); // me devuelve falso si  no encontro elemento
            $mentoresNuevos= Mentor::all();
            $collection = collect();
            foreach ($mentoresNuevos as $mentor)
            {
                if($mentor->becarios()->count()==0)
                {
                    $collection->push($mentor);
                }
            }
        }

        $listMentoresA = $collection->pluck('user_id')->toArray();
        $mentoresAsignados = Mentor::query()->whereIn('user_id', $listMentoresA)->get();
        $listaMentoresA = $mentoresAsignados->pluck('user_id')->toArray();
        $clave1 = array_search($solicitud->user_id, $listaMentoresA); // me devuelve falso si  no encontro elemento


        if($request->get('valor')==='1')
        {
            flash('La solicitud de '.$solicitud->user->name.' ha sido aprobada exitosamente','success')->important();
            $msg = 'La solicitud de '.$solicitud->user->name.' ha sido aprobada exitosamente';

            if($solicitud->user->rol==='becario')
                $instancia= Becario::query()->select()->where('user_id','=',$solicitud->user_id)->first();
            else
            {
                $instancia= Mentor::query()->select()->where('user_id','=',$solicitud->user_id)->first();
            }

            if($solicitud->titulo==='desincorporacion temporal')
            {
                if($solicitud->user->rol==='becario')
                {
                    $instancia->fecha_inactivo= $solicitud->fecha_inactividad;
                    $instancia->observacion_inactivo= 'Usuario Solicitud: '.$solicitud->descripcion.' Respuesta a Solicitud: '.$request->get('observacion');
                }
                else
                {
                    $instancia->fecha_inactivo= $solicitud->fecha_inactividad;
                    $instancia->observacion_inactivo= 'Usuario Solicitud: '.$solicitud->descripcion.' Respuesta a Solicitud: '.$request->get('observacion');

                }
            }
            else
            {
                if($solicitud->titulo==='desincorporacion definitiva')
                {
                    if($solicitud->user->rol==='becario')
                    {
                        $instancia->fecha_desincorporado=$solicitud->fecha_desincorporacion;
                        $instancia->observacion_desincorporado= 'Descripción Solicitud=> '.$solicitud->descripcion.' Respuesta a Solicitud=> '.$request->get('observacion');
                    }
                    else
                    {
                        $instancia->fecha_desincorporado=$solicitud->fecha_desincorporacion;
                        $instancia->observacion_desincorporado= 'Descripción Solicitud=> '.$solicitud->descripcion.' Respuesta a Solicitud=> '.$request->get('observacion');
                    }
                   

                }
                else
                {
                    if($solicitud->titulo==='reincorporacion')
                    {
                        $instancia->status='activo';
                        $instancia->fecha_inactivo = null;
                        $instancia->fecha_desincorporado = null;
                        $instancia->observacion_inactivo = null;
                        $instancia->observacion_desincorporado = null;
                        flash($instancia->user->name.' Ha sido Reincorporado nuevamente al Programa AVAA','info')->important();

                        $msg = $instancia->user->name.' Ha sido Reincorporado nuevamente al Programa AVAA';
                    }
                }

            }
            $solicitud->status='aceptada';
            $instancia->save();

        }
        else
        {
            $solicitud->status='rechazada';
            flash('La solicitud de '.$solicitud->user->name.' ha sido rechazada','info')->important();
            $msg = 'La solicitud de '.$solicitud->user->name.' ha sido rechazada';
        }


        $solicitud->usuario_respuesta= Auth::user()->id;
        if($request->get('observacion'))
        {
            $solicitud->observacion = $request->get('observacion');
        }
        $solicitud->save();

        //Enviar correo a la persona notificando
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
        $body = view("emails.solicitudes.notificacion-estatus")->with(compact("solicitud"));
        $mail->MsgHTML($body);
        $mail->addAddress($solicitud->user->email);
        //$mail->send();

        event(new SolicitudesAlerts($solicitud));
        Alerta::where('status', '=', 'enviada')->where('solicitud','=',$solicitud->id)->where('user_id', '=',$solicitud->user_id)->update(array('leido' => true));

    
        return response()->json(['res' => 1,'msg'=>$msg]);
        //return  redirect()->route('gestionSolicitudes.listar');

    }


    public function perfilPostulanteBecario($id)
    {
        $usuario = User::find($id);
        if(($usuario->rol==="becario")||($usuario->rol==="postulante_becario"))
        {
            // dd($id);
            $postulante = Becario::find($id);
            $documentos = Documento::query()->where('user_id','=',$id)->get();
            $img_perfil=Imagen::query()->where('user_id','=',$id)->where('titulo','=','img_perfil')->get();
            $entrevistadores=BecarioEntrevistador::query()->where('becario_id','=',$id)->get();
            // dd($entrevistadores);
            // return $entrevistadores;
            //return $entrevistadores;
            if(is_null($documentos))
            {
                flash('Disculpe, el postulante seleccionado no tiene documentos.', 'danger')->important();
            }
            else
            {
                $fotografia = Imagen::where('user_id','=',$id)->where('titulo','=','fotografia')->first();
                $cedula = Imagen::where('user_id','=',$id)->where('titulo','=','cedula')->first();
                $constancia_cnu = Documento::where('user_id','=',$id)->where('titulo','=','constancia_cnu')->first();
                $calificaciones_bachillerato = Documento::where('user_id','=',$id)->where('titulo','=','calificaciones_bachillerato')->first();
                $constancia_aceptacion = Documento::where('user_id','=',$id)->where('titulo','=','constancia_aceptacion')->first();
                $constancia_estudios = Documento::where('user_id','=',$id)->where('titulo','=','constancia_estudios')->first();
                $calificaciones_universidad = Documento::where('user_id','=',$id)->where('titulo','=','calificaciones_universidad')->first();
                $constancia_trabajo = Documento::where('user_id','=',$id)->where('titulo','=','constancia_trabajo')->first();
                $declaracion_impuestos = Documento::where('user_id','=',$id)->where('titulo','=','declaracion_impuestos')->first();
                $recibo_pago = Documento::where('user_id','=',$id)->where('titulo','=','recibo_pago')->first();
                $referencia_profesor1 = Documento::where('user_id','=',$id)->where('titulo','=','referencia_profesor1')->first();
                $referencia_profesor2 = Documento::where('user_id','=',$id)->where('titulo','=','referencia_profesor2')->first();
                $ensayo = Documento::where('user_id','=',$id)->where('titulo','=','ensayo')->first();
                return view('sisbeca.postulaciones.perfilPostulanteBecario')->with('postulante',$postulante)->with('documentos', $documentos)->with('usuario',$usuario)->with('fotografia',$fotografia)->with('cedula',$cedula)->with('constancia_cnu',$constancia_cnu)->with('calificaciones_bachillerato',$calificaciones_bachillerato)->with('constancia_aceptacion',$constancia_aceptacion)->with('constancia_estudios',$constancia_estudios)->with('calificaciones_universidad',$calificaciones_universidad)->with('constancia_trabajo',$constancia_trabajo)->with('declaracion_impuestos',$declaracion_impuestos)->with('recibo_pago',$recibo_pago)->with('referencia_profesor1',$referencia_profesor1)->with('referencia_profesor2',$referencia_profesor2)->with('ensayo',$ensayo)->with('img_perfil',$img_perfil)->with('entrevistadores', $entrevistadores);
            }
        }
        else
        {
            flash('Usuario Inválido','danger')->important();
            return redirect()->route('seb');
        }
    }
    public function verPerfilMentor($id)
    {
        $mentor= Mentor::find($id);
        //return $mentor->user;
        $img_perfil=Imagen::query()->where('user_id','=',$mentor->user_id)->where('titulo','=','img_perfil')->get();
        $documento = Documento::query()->where('user_id', '=', $mentor->user_id)->where('titulo', '=', 'hoja_vida')->first();

        return view('sisbeca.mentores.perfilMentor')->with('mentor',$mentor)->with('img_perfil',$img_perfil)->with('documento',$documento);
    }

    public function formularioReporteSolicitudes()
    {
        $users= User::query()->whereIn('rol',['mentor','becario'])->get();
        return view('sisbeca.solicitudes.formularioReporteSolicitudes')->with('users',$users)->with('user_id',0);
    }
    public function solicitudespdf(Request $request)
    {
        $data= explode ('/',$request->get('fechaDesde'));
        $fechaDesde= Carbon::createFromDate($data[2],$data[1],$data[0])->format('Y-m-d');
        $data= explode ('/',$request->get('fechaHasta'));
        $fechaHasta= Carbon::createFromDate($data[2],$data[1],$data[0])->format('Y-m-d');

        $user= $request->get('user_id');
        $fechaD = $request->get('fechaDesde');
        $fechaH = $request->get('fechaHasta');
        if($user==0)
        {
            $solicitudes = Solicitud::query()->whereDate('updated_at','>=',$fechaDesde)->whereDate('updated_at','<=',$fechaHasta)->get();

        }else {
            $solicitudes = Solicitud::query()->where('user_id', '=', $user)->whereDate('updated_at','>=',$fechaDesde)->whereDate('updated_at','<=',$fechaHasta)->get();
        }

        if($solicitudes->count()==0){
            flash('No existe data con los parametros establecidos para generar el reporte','danger')->important();
            $users= User::query()->whereIn('rol',['mentor','becario'])->get();
            return  view('sisbeca.solicitudes.formularioReporteSolicitudes')->with('users',$users)->with('fechaD',$fechaD)->with('fechaH',$fechaH)->with('user_id',$user);
        }
        else
        {
            flash('Reporte ejecutado Exitosamente haga click en el boton generar pdf para visualizarlo','success')->important();

        }

        $users= User::query()->whereIn('rol',['mentor','becario'])->get();
        return view('sisbeca.solicitudes.formularioReporteSolicitudes')->with('users',$users)->with('fechaD',$fechaD)->with('fechaH',$fechaH)->with('user_id',$user)->with('fechaDesde',$fechaDesde)->with('fechaHasta',$fechaHasta)->with('pdf',true);

    }

    public function pdfSolicitud($fechaDesde,$fechaHasta,$user_id)
    {
        if($user_id==0)
        {
            $solicitudes = Solicitud::query()->whereDate('updated_at','>=',$fechaDesde)->whereDate('updated_at','<=',$fechaHasta)->get();

        }else {
            $solicitudes = Solicitud::query()->where('user_id', '=', $user_id)->whereDate('updated_at','>=',$fechaDesde)->whereDate('updated_at','<=',$fechaHasta)->get();
        }

        if($solicitudes->count()==0){
            flash('No existe data con los parametros establecidos para generar el reporte','danger')->important();
            return redirect()->route('formularioReporte.solicitudes');
        }

        switch (date('m')) {
            case 1:
                $mes= 'Enero';
                break;
            case 2:
                $mes=  'Febrero';
                break;
            case 3:
                $mes= 'Marzo';
                break;
            case 4:
                $mes=  'Abril';
                break;
            case 5:
                $mes=  'Mayo';
                break;
            case 6:
                $mes=  'Junio';
                break;
            case 7:
                $mes=  'Julio';
                break;
            case 8:
                $mes=  'Agosto';
                break;
            case 9:
                $mes=  'Septiembre';
                break;
            case 10:
                $mes=  'Octubre';
                break;
            case 11:
                $mes=  'Noviembre';
                break;
            case 12:
                $mes=  'Diciembre';
                break;
        }

        $pdf = PDF::loadView('sisbeca.solicitudes.reporteDeSolicitudes', compact('solicitudes','mes','fechaDesde','fechaHasta'));

        return $pdf->stream('reporteDeSolicitudes.pdf', 'PDF xasa');
    }

    public function ocultarsolicitud($id)
    {
        $solicitud = Solicitud::find($id);
        if($solicitud->status !== 'enviada' || $solicitud->status !== 'cancelada')
        {
            $solicitud->oculto_admin = 1;
            $solicitud->save();
            flash('La solicitud se oculto exitosamente.',"success");
        } else {
            flash('La solicitud no ha sido respondida.',"danger");

        }

        return redirect()->route('gestionSolicitudes.listar');
    }

    public function eliminarpostulante($cedula)
    {
    $usuario = User::where('cedula', '=', $cedula)->first();

        if($usuario!=NULL){ //si consigue usuario
            $becario = Becario::find($usuario->id);//entonces busca el becario
            //Elimino la foto de perfil
            if($becario->status==='rechazado'){ //si el becario es rechazado entonces lo puede eliminar
                $img_perfil = Imagen::where('user_id','=',$usuario->id)->where('titulo','=','img_perfil')->first();
                if($img_perfil)
                {
                    if($img_perfil->url!=null)
                    {
                        File::delete(substr($img_perfil->url,1));
                    }
                    $img_perfil->delete();
                }
                //Eliminio el documeto final entrevista
                if($becario->documento_final_entrevista!=null)
                {
                    File::delete($becario->documento_final_entrevista);
                }
                //Eliminado el documento que subió cada entrevistador
                $entrevistadores = BecarioEntrevistador::paraBecario($usuario->id)->get();
                foreach ($entrevistadores as $entrevistador)
                {
                    if($entrevistador->documento!=null)
                    {
                        File::delete($entrevistador->documento);
                    }
                    $entrevistador->delete();
                }
                //Elimino el usuario  y el becario
                $becario->delete();
                $usuario->delete();
                $rechazado = Rechazado::where('cedula', '=', $cedula)->first();
                $rechazado->datos='borrados';
                $rechazado->save();
                flash('Los datos de este postulante fueron eliminados exitosamente.',"success");
            }
            else{
                flash('Este Postulante está nuevamente en proceso de Postulación y sus datos no pueden ser eliminados.',"danger");
            }
        }
        else{
            flash('La data de este postulante ya fue eliminada anteriormente.',"danger");

        }
        return  redirect()->route('listarPostulantesBecarios',"rechazados");
       // return response()->json(['success'=>'El postulante ha sido eliminado exitosamente.']);
    }
    public function cambiarStatusPendiente(Request $request){
        $user = json_decode($request->data);
        $user = $user->items;
        
        if($user->rol == 'becario'){
            $becario = Becario::find($user->id);
            if ($user->statusAprobar == 'inactivo' && $becario && $becario->fecha_inactivo !== null){
                $becario->mentor_id = null;
                $becario->status = 'inactivo';
                $becario->save();
                return response()->json(['msg'=>'El cambio de status ha sido autorizado exitosamente','res'=> 1]);

            } else if($user->statusAprobar == 'desincorporado'  && $becario && $becario->fecha_desincorporado !== null){
                $becario->mentor_id = null;
                $becario->status = 'desincorporado';
                $desincorporacion= new Desincorporacion();
                $desincorporacion->tipo='solicitud';
                $desincorporacion->observacion= 'El motivo de la gestion de desincorporacion radica en que el usuario: '.$becario->user->name.' '.$becario->user->last_name.' solicito dicha desincorporación la cual fue evaluada y aceptada por el consejo.';
                $desincorporacion->user_id= $becario->user->id;
                $desincorporacion->datos_nombres= $becario->user->name;
                $desincorporacion->datos_apellidos= $becario->user->last_name;
                $desincorporacion->datos_email= $becario->user->email;
                $desincorporacion->datos_cedula= $becario->user->cedula;
                $desincorporacion->datos_rol= $becario->user->rol;
                $desincorporacion->gestionada_por= Auth::user()->id;
                $desincorporacion->fecha_gestionada= date('Y-m-d');
                $desincorporacion->save();
                $becario->save();
                return response()->json(['msg'=>'El cambio de status ha sido autorizado exitosamente','res'=> 1]);


            }
        } else if($user->rol == 'mentor'){
            $mentor = Mentor::find($user->id);
            if ($user->statusAprobar == 'inactivo' && $mentor && $mentor->fecha_inactivo !== null){
                $mentor->status = 'inactivo';
                $becariosT= Becario::query()->where('mentor_id','=',$user->id)->get();
                foreach ($becariosT as $becario)
                {
                    $becario->mentor_id= null;
                    $becario->save();
                }
                $mentor->save();
                return response()->json(['msg'=>'El cambio de status ha sido autorizado exitosamente','res'=> 1]);


            } else if($user->statusAprobar == 'desincorporado'  && $mentor && $mentor->fecha_desincorporado !== null){
                 $mentor->status = 'desincorpordo';
                $becariosT= Becario::query()->where('mentor_id','=',$user->id)->get();
                foreach ($becariosT as $becario)
                {
                    $becario->mentor_id= null;
                    $becario->save();
                }
                $mentor->save();
                return response()->json(['msg'=>'El cambio de status ha sido autorizado exitosamente','res'=> 1]);


            }
        }
        return response()->json(['msg'=>'El cambio de status no pudo ser autorizado correctamente. Por favor intentelo de nuevo','res'=> 0]);

    }
    public function getSolicitudesPendientes(){

        $hoy = new DateTime();
        //inactivos
        $becariosInactivos = Becario::where('fecha_inactivo', '!=', null)->where('status','!=','inactivo')->where('fecha_inactivo','<=',$hoy)->get();
        $becariosDesincorporados = Becario::where('fecha_desincorporado', '!=', null)->where('status','!=','desincorporado')->where('fecha_desincorporado','<=',$hoy)->get();
        $mentoresInactivos = Mentor::where('fecha_inactivo', '!=', null)->where('status','!=','inactivo')->where('fecha_inactivo','<=',$hoy)->get();
        $mentoresDesincorporados = Mentor::where('fecha_desincorporado', '!=', null)->where('status','!=','desincorporado')->where('fecha_desincorporado','<=',$hoy)->get();
        $items = collect();
        if(count($becariosInactivos) || count($becariosDesincorporados) || count($mentoresInactivos) || count($mentoresDesincorporados))
        {
            foreach($becariosInactivos as $becariosIn){
                $items->push(array(
                    "id" => $becariosIn->user_id,
                    "nombre" => $becariosIn->user->name,
                    "apellido" =>$becariosIn->user->last_name,
                    "cedula" =>$becariosIn->user->cedula,
                    "rol" => $becariosIn->user->rol,
                    "status" =>$becariosIn->status,
                    "statusAprobar" => 'inactivo',
                    "fechaAprocesar" => $becariosIn->fecha_inactivo
                ));
            }
            foreach($becariosDesincorporados as $becariosD){
                $items->push(array(
                    "id" => $becariosD->user_id,
                    "nombre" => $becariosD->user->name,
                    "apellido" =>$becariosD->user->last_name,
                    "cedula" =>$becariosD->user->cedula,
                    "rol" => $becariosD->user->rol,
                    "status" =>$becariosD->status,
                    "statusAprobar" => 'desincorporado',
                    "fechaAprocesar" => $becariosD->fecha_desincorporado
                ));
            }
            foreach($mentoresInactivos as $mentoresIn){
                $items->push(array(
                    "id" =>$mentoresIn->user_id,
                    "nombre" => $mentoresIn->user->name,
                    "apellido" =>$mentoresIn->user->last_name,
                    "cedula" =>$mentoresIn->user->cedula,
                    "rol" => $mentoresIn->user->rol,
                    "status" =>$mentoresIn->status,
                    "statusAprobar" => 'inactivo',
                    "fechaAprocesar" => $mentoresIn->fecha_inactivo
                ));
            }
            foreach($mentoresDesincorporados as $mentoresD){
                $items->push(array(
                    "id" => $mentoresD->user_id,
                    "nombre" => $mentoresD->user->name,
                    "apellido" =>$mentoresD->user->last_name,
                    "cedula" =>$mentoresD->user->cedula,
                    "rol" => $mentoresD->user->rol,
                    "status" =>$mentoresD->status,
                    "statusAprobar" => 'desincorporado',
                    "fechaAprocesar" => $mentoresD->fecha_desincorporado
                ));
            }
            return response()->json(['items'=>$items,'res'=> count($items)]);

        } else {
            return response()->json(['items'=>$items,'res'=> 0]);
        }

    }
}
