@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Asignación de Mentor</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->user->nombreyapellido()}}</strong>, te notificamos que se te fue asignado un mentor.
	</li>
	<li><br></li>
	<li><strong>Datos del Mentor:</strong></li>
	<li><br></li>
	<li><strong>Nombre y Apellido</strong>: {{$becario->mentor->user->nombreyapellido()}}</li>
	<li><strong>Correo Electrónico</strong>: <a style="color:#003865;text-decoration: none;" href="mailto:{{$becario->mentor->user->email}}">{{$becario->mentor->user->email}}</a></li>
</ul>

@stop
