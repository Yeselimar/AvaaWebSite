@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Notificación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->user->nombreyapellido()}}</strong>, la factura para el libro <strong>{{$factura->name}}</strong> perteneciente al curso <strong>{{$factura->curso}}</strong> por un costo de <strong>Bs. {{$factura->obtenerCosto()}}</strong> fue actualizada al estatus <strong>{{strtoupper($factura->status)}}</strong> el día <strong>{{ date('d/m/Y h:i A') }}</strong>.
	</li>
</ul>

@stop
