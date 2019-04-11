@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Notificación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$usuario->nombreyapellido()}}</strong>, su postulación como mentor fue <strong>{{$estatus}}</strong>.
	</li>
</ul>

@stop
