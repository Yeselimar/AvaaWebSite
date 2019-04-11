@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Entrevista</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->user->nombreyapellido()}}</strong> nuestro equipo le complace notificarle que hemos decidido convocarl@ para una entrevista.
	</li>
	<li><br></li>
	<li><strong>Datos Entrevista</strong></li>
	<li><br></li>
	<li><strong>Fecha:</strong> {{$becario->fechaEntrevista()}}</li>
	<li><strong>Hora:</strong> {{$becario->horaEntrevistaCorta()}}</li>
	<li><strong>Lugar:</strong> {{$becario->lugar_entrevista}}</li>
	<li><br></li>
	<li><strong>Entrevistadores:</strong></li>
		@foreach($becario->entrevistadores as $index=>$entrevistador)
			<li><strong>{{$index+1}}.</strong> {{$entrevistador->nombreyapellido()}}</li>
		@endforeach
	<li><br></li>
	<li>Cualquier cambio en su entrevista, se la notificaremos.</li>
</ul>

@stop
