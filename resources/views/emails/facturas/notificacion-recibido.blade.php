@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Recibido!</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->user->nombreyapellido()}}</strong>, la factura para el libro <strong>{{$factura->name}}</strong> perteneciente al curso <strong>{{$factura->curso}}</strong> por un costo de <strong>Bs. {{$factura->obtenerCosto()}}</strong> fue <strong>{{strtoupper($factura->status)}}</strong> el día <strong>{{$factura->fechaCreacion()}}</strong>.
	</li>
	<li>
		Te notificaremos por esta vía, cuando el estatus de tú factura cambie.
	</li>
</ul>

@stop
