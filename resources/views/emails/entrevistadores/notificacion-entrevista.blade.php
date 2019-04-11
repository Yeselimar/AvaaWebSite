@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865!important">
	<li><strong><h1>Entrevista</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$entrevistador->nombreyapellido()}}</strong> a ud. se le asignó a un postulante becario a entrevistar.
	</li>
	<li><br></li>
	<li><strong>Datos del Postulante</strong></li>
	<li><br></li>
	<li><strong>Nombres y Apellido:</strong> {{$becario->user->nombreyapellido()}}</li>
	<li><strong>Correo Electrónico:</strong> {{$becario->user->email}}</li>
	<li><strong>Edad:</strong> {{$becario->user->edad}}</li>
	<li><strong>Sexo:</strong> {{$becario->user->sexo}}</li>
	<li><br></li>
	<li><strong>Datos Entrevista</strong></li>
	<li><br></li>
	<li><strong>Fecha:</strong> {{$becario->fechaEntrevista()}}</li>
	<li><strong>Hora:</strong> {{$becario->horaEntrevistaCorta()}}</li>
	<li><strong>Lugar:</strong> {{$becario->lugar_entrevista}}</li>
	<li><br></li>
	<li>Cualquier cambio en la entrevista, se la notificaremos.</li>
</ul>

@stop
