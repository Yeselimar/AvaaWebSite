<?php

namespace avaa\Http\Controllers;

use avaa\Desincorporacion;
use avaa\Imagen;
use avaa\Nomina;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use avaa\Mentor;
use avaa\User;
use avaa\Becario;
use avaa\Documento;
use DateTime;
use avaa\Costo;
use avaa\Concurso;
use Timestamp;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class DirectivoController extends Controller
{

    public function __construct()
    {
        $this->middleware('directivo');
    }

    //viejo

    //vieja no se usa
    public function cambioStatus(Request $request)
    {
        $postulantes = Becario::query()->where('status', '=','entrevistado')->where('acepto_terminos', '=',true)->get();
        $i=0;
        foreach($postulantes as $postulante)
        {
            if($request->get('all') == 1 )
            {
                //para que guarde todos
                $i++;
                $postulante->status='activo';
                $postulante->acepto_terminos=false;
                $postulante->save();
                $usuario=User::find($postulante->user_id);
                $usuario->rol = 'becario';
                $usuario->save();
             }
            else if($request->get($postulante->user_id) == 1 )//cuando está check
            {
                $i++;
                $postulante->status='activo';
                $postulante->acepto_terminos=false;
                $postulante->save();
                $usuario=User::find($postulante->user_id);
                $usuario->rol = 'becario';
                $usuario->save();
               //falta: enviar correo al becario con su fecha de entrevista
            }
        }

        if($i>0)
        {
            flash('Se ha agregado becario(s) exitosamente.','success');
        }
        else
        {
            flash('Disculpe, debe seleccionar al menos un postulante', 'danger');
        }
        return  redirect()->route('listarPostulantesBecarios',"3");
    }

    public function finalizarConcursoMentor()
    {
        $concursos = Concurso::query()->where('status','=','abierto')->where('tipo','=','mentores')->orWhere('status','=','cerrado')->first();
        $postulante = User::query()->where('rol', '=','rechazado')->delete();
        //falta: Estos mentores no deben ser eliminados durante 15 dias

        if(!is_null($concursos))
        {
            $concursos->status='finalizado';
            $concursos->save();
            flash('El proceso de postulaciones a mentor ha finalizado satisfactoriamente', 'success')->important();
                 return redirect()->route('seb');
        }
        else
        {
            flash('El proceso de postulaciones a mentor se encuentra finalizado')->important();
             return redirect()->route('seb');
        }
    }
//viejo no se usa
    public function asignacionEntrevistas(Request $request)
    {
        $becarios = Becario::query()->where('status','=','entrevista')->where('acepto_terminos','=',false)->get();
        $i=0;
        foreach($becarios as $becario)
        {
        	if($request->get('all') == 1 )
            {
                //para que guarde todos
                $i++;
                $becario->fecha_entrevista = DateTime::createFromFormat('d/m/Y', $request->get('fechaentrevista'));
                $becario->acepto_terminos=true;
                $becario->save();
            }
            else if($request->get($becario->user_id) == 1 )//cuando está check
            {
                $i++;
                $becario->fecha_entrevista = DateTime::createFromFormat('d/m/Y', $request->get('fechaentrevista'));
                $becario->acepto_terminos=true;
                $becario->save();
               //falta: enviar correo al becario con su fecha de entrevista
            }
        }
        if($i>0)
        {
            flash('Se han asignado las citas para el día '. $request->get('fechaentrevista'). ' exitosamente!');
        }
        else
        {
            flash('Disculpe, debe seleccionar al menos un postulante a becario.', 'danger');

        }
        return  redirect()->route('listarPostulantesBecarios',"0");
    }
//viejo no se usa
    public function modificarEntrevistas(Request $request)
    {
        $becarios = Becario::query()->where('status','=','entrevista')->where('acepto_terminos','=',true)->get();
        $i=0;
        foreach($becarios as $becario)
        {
            if($request->get('all') == 1 )
            {//para que guarde todos
                $i++;
                $becario->fecha_entrevista = DateTime::createFromFormat('d/m/Y', $request->get('fechaentrevista'));
                $becario->acepto_terminos=true;
                $becario->save();
            }
            else if($request->get($becario->user_id) == 1 )//cuando está check
            {
                $i++;
                $becario->fecha_entrevista = DateTime::createFromFormat('d/m/Y', $request->get('fechaentrevista'));
                $becario->acepto_terminos=true;
                $becario->save();
               //falta: enviar correo al becario con su fecha de entrevista
            }
        }
        if($i>0)
        {
            flash('Se han re-asignado las citas para el día '. $request->get('fechaentrevista'). ' exitosamente!');
        }
        else
        {
            flash('Disculpe, debe seleccionar al menos un postulante a becario', 'danger');
        }

        return  redirect()->route('listarPostulantesBecarios',"1");
    }

    public function listarMentores()
    {
        //busco solo los mentores que sean validos en la tabla mentor ya que l primer registro siempre es el mentor borrador
        $mentores = Mentor::all();
        if($mentores->count()>0)
        {
            $mentores->each(function ($mentores)
            {
                $mentores->becarios;
                foreach ($mentores->becarios as $becario) {
                    $becario->user;
                }
            });
        }

        return view('sisbeca.mentores.listar')->with('mentores',$mentores);
    }

    public function actualizarPostulanteMentor(Request $request, $id)
    {
        $postulante = Mentor::find($id);
        if($postulante->user->rol==='postulante_mentor')
        {
            if ($request->valor === '1')
            {
                $postulante->user->rol = 'mentor';
                $postulante->user->save();
                $postulante->status = 'activo';
                $postulante->save();
                flash($postulante->user->nombreyapellido(). ' ha sido registrado como mentor exitosamente.', 'success')->important();
                $estatus = "APROBADA";
            }
            else
            {
                //falta borrar su hoja de vida si es rechazado
                $postulante->status='rechazado';
                flash($postulante->user->nombreyapellido(). ' ha sido rechazado como mentor.', 'danger')->important();
                $postulante->save();
                $estatus = "RECHAZADA";
            }

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
            $body = view("emails.postulantementor.notificar-estatus-postulacion")->with(compact("usuario","estatus"));
            $mail->MsgHTML($body);
            $mail->addAddress($usuario->email);
            //$mail->send();
        }
        return  redirect()->route('listarPostulantesMentores');
    }

    public function listarPostulantesMentores()
    {
       $postulantes = Mentor::query()->where('status', '=', 'postulante')->orwhere('status', '=', 'rechazado')->get();
      //$postulantes = User::query()->where('rol','=','postulante_mentor')->get();
       //dd($postulantes);
        /*  $postulantes = User::query()->where('rol','=','postulante_mentor')->get();
        $rechazados = User::query()->where('rol','=','rechazado')->get();
        foreach($rechazados as $rechazado){
            $postulantes_becarios= Becario::find($rechazado->id);
                if($postulantes_becarios==NULL){
                    $postulantes = $postulantes->push($rechazado);
                }
        } */
        return view('sisbeca.postulaciones.postulantesMentores')->with('postulantes',$postulantes);
    }

    public function perfilPostulantesMentores($id)
    {
        $postulante = Mentor::find($id);
        $img_perfil_postulante=Imagen::query()->where('user_id','=',$postulante->user->id)->where('titulo','=','img_perfil')->get();
        $documento = Documento::query()->where('user_id', '=', $id)->where('titulo', '=', 'hoja_vida')->first();
        //  dd($img_perfil_postulante);
        // $postulante = User::find($id);

        /*  if(!is_null($postulante) &&$postulante->rol==='postulante_mentor'||$postulante->rol==='rechazado')
        {
            if (is_null($postulante)) {
                flash('Disculpe, el archivo solicitado no fue encontrado', 'danger')->important();
                return back();
            }


        }
        else
        {
            flash('Disculpe, el archivo solicitado no fue encontrado', 'danger')->important();
            return  redirect()->route('listarPostulantesMentores');
        } */

        return view('sisbeca.postulaciones.perfilPostulanteMentor')->with('postulanteMentor',$postulante)->with('documento', $documento)
            ->with('img_perfil_postulante',$img_perfil_postulante);
    }

    public function listarBecariosInactivos()
    {
        $becarios = Becario::query()->where('acepto_terminos', '=', true)->where('status', ['inactivo'])->get();

        return view('sisbeca.becarios.egresados.listarInactivos')->with('becarios',$becarios);
    }

    public function listarBecariosGraduados()
    {
        $becarios = Becario::query()->where('acepto_terminos', '=', true)->where('status', ['egresado'])->get();
        if($becarios->count()==0) {

            flash('Disculpe, no existen becarios graduados en el sistema.','danger');
        }
        return view('sisbeca.becarios.egresados.listarGraduados')->with('becarios',$becarios);
    }

    public function procesarDesincorporacion($user_id,$id)
    {
        $user= User::find($user_id);
        $desincorporacion= Desincorporacion::find($id);
        if($user->rol==='becario')
        {
            if($user->becario->status==='desincorporado')
            {
                $nomina=Nomina::query()->where('status','=','pendiente')->get();
                if($nomina->count()>0)
                {
                    flash('Disculpe, actualmente tiene nomina(s) pendiente(s) por ser procesada(s) le invitamos procesar dicha(s) nominas y luego proceder con la desincorporacion del becario','danger')->important();
                }
                else
                {
                    $user->rol='becario desincorporado';
                    $user->save();
                    $desincorporacion->status='ejecutada';
                    $desincorporacion->save();
                    flash('El Becario '.$user->name.' '.$user->last_name.' ha sido desincorporado exitosamente.','danger')->important();

                }
            }
        }
        else
        {
            if($user->rol==='mentor')
            {
                if($user->mentor->status==='desincorporado')
                {
                    $user->rol='mentor desincorporado';
                    $user->save();
                    $desincorporacion->status='ejecutada';
                    $desincorporacion->save();
                    flash('El Mentor '.$user->name.' '.$user->last_name.' ha sido desincorporado exitosamente.','info')->important();
                }
            }
            else
            {
                flash('Disculpe, el archivo no fue encontrado','danger')->important();
                return back();
            }
        }
        return redirect()->route('desincorporaciones.listar');
    }

    public function listarDesincorporaciones()
    {
        $desincorporacionesSolicitud = Desincorporacion::query()->where('tipo','solicitud')->get();
        $desincorporacionesSistema = Desincorporacion::query()->where('tipo','sistema')->get();
        return view('sisbeca.desincorporaciones.procesarDesincorporaciones')->with('desincorporacionesSolicitud',$desincorporacionesSolicitud)->with('desincorporacionesSistema',$desincorporacionesSistema);
    }

    public function listarBecariosDesincorporados()
        {
            $becarios = Becario::query()->where('acepto_terminos', '=', true)->where('status', ['desincorporado'])->get();

            return view('sisbeca.becarios.egresados.listarDesincorporados')->with('becarios',$becarios);
        }

    //añadir este metodo
        public function estipendioBecario()
        {
            $costos= Costo::first();
            if(is_null($costos))
            {
                $costos = null;
                $id=0;
                flash('Disculpe, no se han definido los costos, por favor defínalos.')->error()->important();
            }
            else
            {
                $id=$costos->id;
            }
            return view('sisbeca.costos.estipendio')->with('costo',$costos)->with('id',$id);
        }
        public function actualizarEstipendio(Request $request)
        {
            $costo = Costo::first();
            $costoVal = $request->get('estipendio');
            $costoAux = str_replace(".","",$request->get('estipendio'));
            $costoAux= str_replace(",",".",$costoAux);
            $costo->sueldo_becario=$costoAux;

            $costo->save();
            return response()->json(['success'=>'El Estipendio fue actualizado exitosamente.','costo'=>$costo->sueldo_becario,'costoval'=>$costoVal]);
        }

}
