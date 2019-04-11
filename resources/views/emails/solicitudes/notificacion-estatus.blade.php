@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Notificación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$solicitud->user->nombreyapellido()}}</strong>, tú Solicitud/Reclamo del tipo <strong>{{strtoupper($solicitud->titulo)}}</strong> realizada el día <strong><strong>{{$solicitud->fechaCreacion()}}</strong> fue <strong>{{strtoupper($solicitud->status)}}</strong>.
	</li>
</ul>

@stop
