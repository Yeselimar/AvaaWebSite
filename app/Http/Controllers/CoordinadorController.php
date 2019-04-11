<?php

namespace avaa\Http\Controllers;

use avaa\Alerta;
use avaa\Imagen;
use avaa\Mentor;
use avaa\User;
use Illuminate\Http\Request;
use Auth;
use avaa\Solicitud;
use avaa\Becario;
use avaa\Events\SolicitudesAlerts;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CoordinadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('coordinador');
    }

    //Api
    
    public function getRelacionBecarioMentor()
    {        
        $collection = collect();
        $becarios= Becario::query()->where('acepto_terminos','=',1)
            ->whereIn('status',['activo','probatorio1','probatorio2'])->get();

            $becarios->each(function ($becarios)
            {
                $becarios->user;
            });
            
            foreach ($becarios as $becario)
            {
                $becarioId = $becario->user_id;
                $becarioName = $becario->user->name ;
                $becarioLastName= $becario->user->last_name ." | ".$becario->user->cedula." | ".$becario->user->email;
                $mentorId = null;
                $mentorName = "Sin";
                $mentorLastName = "Mentor";
                $rowVariant = 'warning';
                if($becario->mentor_id !== null){
                    $userMentor= User::find($becario->mentor_id);
                    $mentorId = $becario->mentor_id;
                    $mentorName = $userMentor->name ;
                    $mentorLastName = $userMentor->last_name." | ".$userMentor->cedula." | ".$userMentor->email;
                    $rowVariant = 'success';
                }
                 $collection->push(array(
                   "becario" => array('id' => $becarioId,
                   'name' => $becarioName." ".$becarioLastName),
                   "mentor" => array(
                   'id' => $mentorId,
                   'name' => $mentorName." ".$mentorLastName),
                   '_rowVariant' => $rowVariant

               ));
            }
        
        return $collection;
    }
    public function getBecarios()
    {        
        $becarios= Becario::query()->where('acepto_terminos','=',1)
            ->whereIn('status',['activo','probatorio1','probatorio2'])->get();
             $becarios->each(function ($becarios)
            {
                $becarios->user;

            });
        return $becarios;
    }
    public function getMentores()
    {    
        $collection = collect();    
        $mentores= Mentor::query()->where('status','=','activo')->get();
         $mentores->each(function ($mentores)
            {
                $mentores->user;

            });
            $collection->push(array(
                "user_id" =>null,
                "name" => "Sin Mentor",

            ));
            foreach ($mentores as $mentor){
                $collection->push(array(
                    "user_id" => $mentor->user_id,
                    "name" => $mentor->user->name." ".$mentor->user->last_name." | ".$mentor->user->cedula." | ".$mentor->user->email
                ));
 
            }

        return $collection;
    }
    //Fin API
    public function asignarBecarios()
    {        
        $mentores= Mentor::query()->where('status','=','activo')->get();
        $becarios= Becario::query()->where('acepto_terminos','=',1)
            ->whereIn('status',['activo','probatorio1','probatorio2'])->get();
        return view('sisbeca.mentores.asignarBecarios')->with('mentores',$mentores)->with('becarios',$becarios);
    }
    
    public function asignarMentorBecario(Request $request)
    {
        $becario= $request->get('becarioId');
        $beco= Becario::where("user_id","=",$becario)->first();
        $becario = $beco;
        if( $request->get('mentorId') !== null && $request->get('mentorId') !== "null")
        {
            //Asignación de mentor 
            $mentor = $request->get('mentorId');
            $userMentor= User::find($mentor);
            $beco->mentor_id= $mentor;
            $beco->save();

            Alerta::where('status', '=', 'generada')->where('solicitud','=',null)->where('user_id', '=',  $beco->user_id)->delete();
            $alerta = new Alerta();
            $alerta->titulo = 'Nuevo Mentor Asignado';
            $alerta->status='generada';
            $alerta->descripcion = $beco->user->name.' '.$beco->user->last_name.' Se le ha asignado a '.$userMentor->name.' '.$userMentor->last_name.' como Mentor.';
            $alerta->user_id= $beco->user_id;
            $alerta->save();

            Alerta::where('status', '=', 'generada')->where('solicitud','=',null)->where('user_id', '=', $mentor)->delete();
            $alerta = new Alerta();
            $alerta->titulo = 'Nuevo(s) Becario(s) Asignado(s)';
            $alerta->status='generada';
            $alerta->descripcion ='Se le ha asignado a un nuevo becario.';
            $alerta->user_id= $mentor;
            $alerta->save();
            
            //Notificar al Becario
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
            $body = view("emails.becarios.notificacion-nuevo-mentor")->with(compact("becario"));
            $mail->MsgHTML($body);
            $mail->addAddress($becario->user->email);
            //$mail->send();
            //Notificar al Mentor
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
            $body = view("emails.mentor.notificacion-nuevo-becario")->with(compact("becario"));
            $mail->MsgHTML($body);
            $mail->addAddress($becario->mentor->user->email);
            //$mail->send();
            return response()->json(['success'=>'El mentor fue asignado al becario exitosamente.']);
        }
        else
        {
            //Quito la relación becario mentor
            $mentor = Mentor::find($beco->mentor_id);//guardo el mentor viejo

            $beco->mentor_id = null;
            $beco->save();

            Alerta::where('status', '=', 'generada')->where('solicitud','=',null)->where('user_id', '=',  $beco->user_id)->delete();
            $alerta = new Alerta();
            $alerta->titulo = 'Sin Mentor Asignado';
            $alerta->status='generada';
            $alerta->descripcion = 'Su mentor fue removido, pronto se le asignara un nuevo mentor.';
            $alerta->user_id= $beco->user_id;
            $alerta->save();

            //Notificar al Becario
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
            $body = view("emails.becarios.notificacion-eliminacion-mentor")->with(compact("becario","mentor"));
            $mail->MsgHTML($body);
            $mail->addAddress($becario->user->email);
            //$mail->send();
            //Notificar al Mentor
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
            $body = view("emails.mentor.notificacion-eliminacion-becario")->with(compact("becario","mentor"));
            $mail->MsgHTML($body);
            $mail->addAddress($mentor->user->email);
            //$mail->send();
            return response()->json(['success'=>'El mentor fue removido exitosamente.']);
        }

    }
}
