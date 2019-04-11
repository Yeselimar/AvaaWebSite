@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Recibido!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$usuario->nombreyapellido()}}</strong>, tú Solicitud/Reclamo del tipo <strong>{{strtoupper($solicitud->titulo)}}</strong> fue <strong>{{strtoupper($solicitud->status)}}</strong>  el día <strong>{{$solicitud->fechaCreacion()}}</strong>.
	</li>
	<li>
		Te notificaremos por esta vía, cuando el estatus cambie.
	</li>
</ul>

@stop
