@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Lo sentimos!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, su justificativo para el <strong>{{$data["actividad_tipo"]}}</strong>: <strong>{{$data["actividad_nombre"]}}</strong> fue <strong>{{$data["estatus_justificativo"]}}</strong> el día <strong>{{$data["fecha_hora"]}}</strong> y por lo tanto ud. fue calificad@ como <strong>{{$data["estatus_actividad"]}}</strong> a dicha actividad.
	</li>
</ul>

@stop
