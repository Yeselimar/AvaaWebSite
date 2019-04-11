@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Lo lamentamos!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, ud. fue <strong>{{$data["estatus"]}}</strong> como participante al <strong>{{$data["actividad_tipo"]}}</strong>: <strong>{{$data["actividad_nombre"]}}</strong> a realizarse el día <strong>{{$data["actividad_fecha"]}}</strong> de <strong>{{$data["actividad_hora"]}}</strong>.
	</li>
</ul>

@stop
