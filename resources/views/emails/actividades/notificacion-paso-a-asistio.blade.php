@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Importante</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$usuario->nombreyapellido()}}</strong>, usted fue pasado de lista de espera <strong>a como un posible asistente</strong> al <strong>{{$actividad->getTipo()}}</strong>: <strong>{{$actividad->nombre}}</strong> a realizarse el día <strong>{{$actividad->getDiaFecha()}}, {{ $actividad->getFecha()}}</strong> de <strong>{{$actividad->getHoraInicio()}} a {{$actividad->getHoraFin()}}</strong>.
	</li>
	<li>Cualquier cambio con respecto a esta actividad, le notificaremos por este medio.</li>
</ul>

@stop
