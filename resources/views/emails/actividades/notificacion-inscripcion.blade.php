@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Bien!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, usted fue inscrito con el estatus <strong>{{$data["estatus"]}}</strong> al <strong>{{$data["actividad_tipo"]}}</strong>: <strong>{{$data["actividad_nombre"]}}</strong> a realizarse el día <strong>{{$data["actividad_fecha"]}}</strong> de <strong>{{$data["actividad_hora"]}}</strong>.
	</li>
	<li>Cualquier cambio con respecto a esta actividad, le notificaremos por este medio.</li>
</ul>

@stop
