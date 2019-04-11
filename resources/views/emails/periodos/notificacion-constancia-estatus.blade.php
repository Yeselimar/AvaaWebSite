@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Notificación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, su nota académica perteneciente al periodo <strong>{{$data["numero_periodo"]}} ({{$data["anho_lectivo"]}})</strong> con promedio <strong>{{$data["promedio_periodo"]}}</strong> fue actualizada con el estatus <strong>{{$data["estatus_periodo"]}}</strong> el día <strong>{{$data["fecha_hora"]}}</strong>. 
	</li>
</ul>

@stop
