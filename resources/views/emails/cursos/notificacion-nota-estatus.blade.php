@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Notificación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$data["becario"]}}</strong>, su nota del CVA del <strong>{{$data["modulo_cva"]}} - {{$data["modo_cva"]}}</strong> del <strong>{{$data["mes_cva"]}}-{{$data["anho_cva"]}}</strong> con nota <strong>{{$data["nota_cva"]}}</strong> fue actualizada con el estatus <strong>{{$data["estatus_cva"]}}</strong> el día <strong>{{$data["fecha_hora"]}}</strong>. 
	</li>
</ul>

@stop
