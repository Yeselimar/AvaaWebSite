@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Lo lamentamos!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, ud. fue {{$data["genero"]}} como <strong>{{$data["estatus"]}}</strong> al <strong>{{$data["actividad_tipo"]}}</strong>: <strong>{{$data["actividad_nombre"]}}</strong> realizado el día <strong>{{$data["actividad_fecha"]}}</strong> de <strong>{{$data["actividad_hora"]}}</strong>.
	</li>
</ul>

@stop
