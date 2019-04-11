<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use avaa\Documento;
use avaa\Imagen;
use DateTime;
use avaa\User;
use avaa\Becario;
use Timestamp;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use avaa\Http\Requests\UserRequest;

class RegistroSisbecaController extends Controller
{
    public function registropostulantementor()
    {
    	return view('auth.registerMentor');
    }

    public function guardarpostulantementor(Request $request)
    {
        $email_existe = User::where('email','=',$request->email)->first();
        $cedula_existe = User::where('cedula','=',$request->cedula)->first();
        if(empty($email_existe) and empty($cedula_existe))
        {  
        	$user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->last_name = $request->last_name;
            $user->fecha_nacimiento = DateTime::createFromFormat('d/m/Y H:i:s', $request->fecha_nacimiento.' 00:00:00');
            $user->password = bcrypt($request->password);
            $user->cedula = $request->cedula;
            $user->sexo = $request->sexo;
            $user->rol= 'pre_postulante_mentor';
            $user->edad = $request->edad;//redundante
            $user->remember_token = str_random(10);
            $user->save();

        	/* $file = $request->file('url_pdf');
            $name = 'HojaDeVida_' . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/documentos/mentores/';
            $file->move($path, $name);
            $documento = new Documento();
            $documento->user_id = $user->id;
            $documento->url = '/documentos/mentores/'.$name;
            $documento->titulo='hoja_vida';
            $documento->save();
            */

            $file = $request->file('image_perfil');

            if(!is_null($file))
            {
                $name = 'img-user_' . $user->cedula . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/perfil/';
                $file->move($path, $name);
                $img_perfil = new Imagen();
                $img_perfil->titulo = 'img_perfil';
                $img_perfil->url = '/images/perfil/' . $name;
                $img_perfil->verificado = true;
                $img_perfil->user_id = $user->id;
                $img_perfil->save();
            }
            
            $usuario = $user;

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
            $body = view('emails.postulantementor.bienvenida-prepostulantementor')->with(compact('usuario'));
            $mail->MsgHTML($body);
            $mail->addAddress($user->email);
            //$mail->send();

            flash("Gracias por registrarte. Ahora puedes iniciar sesión para culminar el proceso de postulación.",'success');
        }
        else
        {
            flash("Disculpe, ya existe un usuario con el correo electrónico y/o con la cédula suministrada.",'danger');
        }
        return redirect('/login');

    }

    public function registropostulantebecario()
    {
    	return view('auth.register');
    }

    public function guardarpostulantebecario(Request $request)
    {
        $email_existe = User::where('email','=',$request->email)->first();
        $cedula_existe = User::where('cedula','=',$request->cedula)->first();
        if(empty($email_existe) and empty($cedula_existe))
        {
        	$user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->last_name = $request->last_name;
            $user->fecha_nacimiento = DateTime::createFromFormat('d/m/Y H:i:s', $request->fecha_nacimiento.' 00:00:00');
            $user->password = bcrypt($request->password);
            $user->cedula = $request->cedula;
            $user->sexo = $request->sexo;
            $user->edad = $request->edad;
            $user->remember_token = str_random(10);
            $user->save();

            $becario = new Becario;
            $becario->user_id = $user->id;
            $becario->mentor_id = null;
            $becario->save();

            $file= $request->file('image_perfil');
            if(!is_null($file))
            {
                $name = 'img-user_' . $user->cedula . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/images/perfil/';
                $file->move($path, $name);
                $img_perfil = new Imagen();
                $img_perfil->titulo = 'img_perfil';
                $img_perfil->url = '/images/perfil/' . $name;
                $img_perfil->verificado = true;
                $img_perfil->user_id = $user->id;
                $img_perfil->save();
            }

            $usuario = $user;

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
            $body = view('emails.postulantebecario.bienvenida-prepostulantebecario')->with(compact('usuario'));
            $mail->MsgHTML($body);
            $mail->addAddress($user->email);
            //$mail->send();

            flash("Gracias por registrarte en ProExcelencia. Ahora puedes iniciar sesión para comenzar el proceso de postulación.",'success');
        }
        else
        {
            flash("Disculpe, ya existe un usuario con el correo electrónico o con la cédula suministrada.",'danger');
        }
        return redirect('/login');
    }
}
