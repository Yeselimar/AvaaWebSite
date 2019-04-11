@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Asignación de Becario</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->mentor->user->nombreyapellido()}}</strong>, te notificamos que se te fue asignado un becario.
	</li>
	<li><br></li>
	<li><strong>Datos del Becario:</strong></li>
	<li><br></li>
	<li><strong>Nombre y Apellido</strong>: {{$becario->user->nombreyapellido()}}</li>
	<li><strong>Correo Electrónico</strong>: <a style="color:#003865;text-decoration: none;" href="mailto:{{$becario->user->email}}">{{$becario->user->email}}</a></li>
	@if(!empty($becario->celular))
		<li><strong>Teléfono</strong>: {{$becario->celular}}</li>
	@endif
</ul>

@stop
