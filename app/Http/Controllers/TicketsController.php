<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use avaa\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Auth;
use Validator;
use avaa\Ticket;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TicketsController extends Controller
{
	public function ticketsestatus()
	{
		$estatus = (object)["0"=>"enviado", "1"=>"en revision", "2"=>"cerrado"];
		return response()->json(['estatus'=>$estatus]);
	}

	public function todos()
    {
    	if(Auth::user()->esSoporte())
    	{
    		return view('sisbeca.tickets.todos');
    	}
    	return view('sisbeca.error.404');
    }

    public function todosservicio()
    {
    	if(Auth::user()->esSoporte())
    	{
	    	$todos = collect();
	    	$tickets = Ticket::all();
	    	foreach ($tickets as $t)
	        {
	        	$usuariorespuesta = '-';
	        	if($t->usuario_respuesta_id)
	        	{
	        		$usuariorespuesta = $t->usuariorespuesta->name.' '.$t->usuariorespuesta->last_name;
	        	}
	            $todos->push(array(
	                'id' => $t->id,
	                'nro' => $t->getNro(),
	                'estatus' => $t->getEstatus(),
	                'prioridad' => $t->prioridad,
	                'tipo' => $t->tipo,
	                'asunto' => $t->asunto,
	                'descripcion' => $t->descripcion,
	                'imagen' => $t->imagen,
	                'url' => $t->url,
	                'respuesta' => $t->respuesta,
	                "usuariorespuesta" => $usuariorespuesta,
	                "usuariogenero" => $t->usuariogenero->name.' '.$t->usuariogenero->last_name,
	                "rolgenero" => $t->usuariogenero->getRol(),
	                "created_at" => $t->created_at,
	                "updated_at" => $t->updated_at,
	            ));
	        }
	    	return response()->json(['tickets'=>$todos]);
	    }
	    return view('sisbeca.error.404');
    }

    public function index($id)
    {
    	//Los de soporte pueden ver los ticket de todos los usuarios
    	$usuario = Auth::user();
    	if(Auth::user()->esSoporte() or ($id==Auth::user()->id))
    	{
    		return view('sisbeca.tickets.mistickets')->with(compact('usuario'));
    	}
    	return view('sisbeca.error.404');
    }

    public function mistickets($id)
    {
    	//agregar seguridad para  que un usuario no vean los  tickets de otro
    	if(Auth::user()->esSoporte() or ($id==Auth::user()->id))
    	{
	    	$todos = collect();
	    	$tickets = Ticket::where('usuario_genero_id','=',$id)->get();
	    	foreach ($tickets as $t)
	        {
	        	$usuariorespuesta = '-';
	        	if($t->usuario_respuesta_id)
	        	{
	        		$usuariorespuesta = $t->usuariorespuesta->name.' '.$t->usuariorespuesta->last_name;
	        	}
	            $todos->push(array(
	                'id' => $t->id,
	                'nro' => $t->getNro(),
	                'estatus' => $t->getEstatus(),
	                'prioridad' => $t->prioridad,
	                'tipo' => $t->tipo,
	                'asunto' => $t->asunto,
	                'descripcion' => $t->descripcion,
	                'imagen' => $t->imagen,
	                'url' => $t->url,
	                'respuesta' => $t->respuesta,
	                "usuariorespuesta" => $usuariorespuesta,
	                "created_at" => $t->created_at,
	                "updated_at" => $t->updated_at,
	            ));
	        }
	    	return response()->json(['tickets'=>$todos]);
	    }
	    return view('sisbeca.error.404');
    }

    public function crear()
    {
    	if(!Auth::user()->esSoporte())
    	{
    		$model = "crear";
	    	$usuario = Auth::user();
	    	return view('sisbeca.tickets.model')->with(compact('model','usuario'));
    	}
    	return view('sisbeca.error.404');
    }

    public function guardar(Request $request)
    {
    	$validation = Validator::make($request->all(), TicketRequest::rulesCreate());
		if ( $validation->fails() )
		{
			flash("Por favor, verifique el formulario.",'danger');
			return back()->withErrors($validation)->withInput();
		}
		$ticket = new Ticket;
        if($request->file('imagen'))
        {
            $archivo= $request->file('imagen');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Ticket::carpeta();
            $archivo->move($ruta, $nombre);
            $ticket->imagen = Ticket::carpeta().$nombre;
        }
        $ticket->estatus = 'enviado';
        $ticket->prioridad = $request->prioridad;
        $ticket->tipo = $request->tipo;
        $ticket->asunto = $request->asunto;
        $ticket->descripcion = $request->descripcion;
        $ticket->url = $request->url;
        $ticket->usuario_genero_id = Auth::user()->id;
        $ticket->save();

        //Enviamos un correo a una dirección predeterminada a donde llegarán los avisos que fue cargado un ticket, por ejemplo : soporte@avaa.org
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
        $mail->Subject = "Nuevo Ticket: ".$ticket->getNro();
        $body = view("emails.tickets.ticket-cargado")->with(compact("ticket"));
        $mail->MsgHTML($body);
        $mail->addAddress('afodioficial@gmail.com');
        $mail->addAddress('ilimar.vasquez@gmail.com');
        $mail->addAddress('darniw@gmail.com');
        $mail->addAddress('delgadorafael2011@gmail.com');
        //$mail->send();

        flash("El ticket fue cargado exitosamente.",'success');
        return redirect()->route('ticket.index',Auth::user()->id);
    }

    public function editar()
    {
    	
    }

    public function actualizar(Request $request, $id)
    {
    	$ticket = Ticket::find($id);
        $ticket->respuesta = $request->respuesta;
        $ticket->estatus = $request->estatus;
        $ticket->usuario_respuesta_id = Auth::user()->id;
        $ticket->save();
        return response()->json(['success'=>'El ticket '.$ticket->getNro().' fue actualizado exitosamente.']);
    }

    public function detalles($id)
    {
    	//agregar seguridad para  que un usuario no vean los  tickets de otro. Soporte tambien pueden verlo
    	$ticket = Ticket::find($id);
    	if(Auth::user()->esSoporte() or (Auth::user()->id==$ticket->usuario_genero_id))
    	{
	    	return view('sisbeca.tickets.detalles')->with(compact('ticket'));
    	}
    	return view('sisbeca.error.404');
    }

    public function detallesservicio($id)
    {
    	$todo = collect();
    	$ticket = Ticket::where('id','=',$id)->with('usuariorespuesta')->with('usuariogenero')->first();
    	$t = $ticket;
    	if(Auth::user()->esSoporte() or (Auth::user()->id==$ticket->usuario_genero_id))
    	{
    		$usuariorespuesta = '-';
        	if($t->usuario_respuesta_id)
        	{
        		$usuariorespuesta = $t->usuariorespuesta->name.' '.$t->usuariorespuesta->last_name;
        	}
    		$todo->push(array(
                'id' => $t->id,
                'nro' => $t->getNro(),
                'estatus' => $t->getEstatus(),
                'estatus_original' => $t->estatus,
                'prioridad' => $t->prioridad,
                'tipo' => $t->tipo,
                'asunto' => $t->asunto,
                'descripcion' => $t->descripcion,
                'imagen' => $t->imagen,
                'url' => $t->url,
                'respuesta' => $t->respuesta,
                "usuariorespuesta" => $usuariorespuesta,
                "usuariogenero" => $t->usuariogenero->name.' '.$t->usuariogenero->last_name,
                "rolgenero" => $t->usuariogenero->getRol(),
                "created_at" => $t->created_at,
                "updated_at" => $t->updated_at,
                "notificado" => $t->notificado,
                "fecha_notificado" => $t->fecha_notificado,
            ));
    		return response()->json(['ticket'=>$todo[0]]);
    	}
    	return view('sisbeca.error.404');
    }

    public function eliminar()
    {
    	
    }

    public function enviarcorreo($id)
    {
    	$ticket = Ticket::find($id);
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
        return response()->json(['success'=>'El correo de notificación de respuesta fue enviado exitosamente.']);
    }

}
