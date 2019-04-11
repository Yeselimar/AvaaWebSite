@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Atención!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, su justificativo para el <strong>{{$data["actividad_tipo"]}}</strong>: <strong>{{$data["actividad_nombre"]}}</strong> fue <strong>{{$data["estatus_justificativo"]}}</strong>. 
	</li>
	<li>Te invitamos a revisar y cargar nuevamente el justificativo para esta actividad a la brevedad.</li>
</ul>

@stop
