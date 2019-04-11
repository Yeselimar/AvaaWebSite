@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865!important">
	<li><strong><h1>Respuesta Ticket {{$ticket->getNro()}}</h1></strong></li>
	<li><br></li>
	<li><strong>Datos del Ticket</strong></li>
	<li><br></li>
	<li><strong>Estatus:</strong> {{$ticket->getEstatus()}}</li>
	<li><strong>Prioridad:</strong> {{ucwords($ticket->prioridad)}}</li>
	<li><strong>Tipo:</strong> {{ucwords($ticket->tipo)}}</li>
	<li><br></li>
	<li><strong>Asunto:</strong> {{$ticket->asunto}}</li>
	<li><strong>Descripción:</strong> {{$ticket->descripcion}}</li>
	<li><br></li>
	<li>
		<strong>Ticket generado por:</strong> {{$ticket->usuariogenero->nombreyapellido()}} 
		({{$ticket->usuariogenero->getRol()}})</li>
	<li><strong>Ticket generado el:</strong> {{$ticket->fechaGenerado()}} - {{$ticket->horaGenerado()}}</li>
	<li><br></li>
	<li><strong>Respuesta:</strong> {{$ticket->respuesta}}</li>
	<li><strong>Respondido por:</strong> {{$ticket->usuariorespuesta->nombreyapellido()}}</li>
	<li><strong>Notificado el:</strong> {{$ticket->fechaNotificado()}} - {{$ticket->horaNotificado()}}</li>
	<li><br></li>
	
</ul>

@stop
