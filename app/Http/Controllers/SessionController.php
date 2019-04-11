<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use Validator;
use avaa\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use avaa\Http\Requests\UserRequest;
use Hash;


class SessionController extends Controller
{
    public function recuperarcontrasena()
	{
		return view('auth.passwords.recuperar-contrasena');
	}

	public function enviarcorreo(Request $request)
	{
		$user = User::where('email','=',$request->correo)->first();
		if($user)
		{
			$token = $user->remember_token;
			$body = view('emails.usuarios.recuperar-contrasena')->with(compact('user','token'));

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
	        $mail->Subject = "Restablecimiento de contraseña";
	        $mail->MsgHTML($body);
	        $mail->addAddress($user->email);
	        //$mail->send();

			flash("Hemos enviamos a su correo un enlace para restablecer su contraseña.",'success');

			return back();
		}
		else
		{
			flash("Disculpe, no existe ningún usuario con el correo electrónico ingresado.",'danger');
			return back()->withInput();
		}
	}

	public function restablecercontrasena($token)
	{
		$user = User::FindByToken($token);
		if(!$user)
		{
			return view('sisbeca.error.404-externo');
		}
		else
		{
			return view('auth.passwords.restablecer-contrasena')->with(compact('token','user'));
		}
	}

	public function postrestablecercontrasena(Request $request,$token)
	{
		$validation = Validator::make($request->all(), UserRequest::reglasContrasena());
		if( $validation->fails() )
		{
			flash("Por favor verifique la contraseña ingresada.",'danger');
			//return response()->json($validation);
			return back()->withErrors($validation)->withInput();
		}
		else
		{
			if($request->contrasenanueva != $request->repitacontrasena)
			{
				flash("La contraseñas ingresadas no coinciden.",'danger');
				return back();
			}
			else
			{
				$user = User::where('remember_token','=',$token)->first();
				$user->edad = 99;
				$user->password = bcrypt($request->contrasena_nueva);
				$user->remember_token = str_random(10);//actualizamos el token
				$user->save();

				$body = view('emails.usuarios.restablecida-contrasena')->with(compact('user'));

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
		        $mail->Subject = "Restablecida su contraseña";
		        $mail->MsgHTML($body);
		        $mail->addAddress($user->email);
		        //$mail->send();

				flash("Su contraseña fue restablecida exitosamente.",'success');
				return redirect('/login');
			}
		}

	}
}
