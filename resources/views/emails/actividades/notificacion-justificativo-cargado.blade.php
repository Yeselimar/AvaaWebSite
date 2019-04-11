@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Bien!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, su justificativo para el <strong>{{$data["actividad_tipo"]}}</strong>: <strong>{{$data["actividad_nombre"]}}</strong> fue cargado exitosamente el día <strong>{{$data["fecha_hora"]}}</strong>.
	</li>
	<li>Si su justificativo es aprobado, rechazado o devuelto le notificaremos por este medio.</li>
</ul>

@stop
