@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Notificación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, su comprobante de voluntariado <strong>{{$data["tipo_voluntariado"]}}: {{$data["nombre_voluntariado"]}}</strong> realizado el día <strong>{{$data["fecha_voluntariado"]}}</strong> de <strong>{{$data["horas_volutariado"]}}</strong> fue actualizado con el estatus <strong>{{$data["estatus_voluntariado"]}}</strong> el día <strong>{{$data["fecha_hora"]}}</strong>. 
	</li>
</ul>

@stop
