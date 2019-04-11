@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Notificación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->user->nombreyapellido()}}</strong>, su justificativo para el <strong>{{ucwords($actividad->tipo)}}</strong>: <strong>{{$actividad->nombre}}</strong> fue <strong>{{$estatus_justificativo}}</strong> el día <strong>{{date("d/m/Y h:i A")}}</strong>
			@if($estatus_justificativo!='DEVUELTO') 
			y por lo tanto ud. fue calificad@ como <strong>{{$estatus_actividad}}</strong> a dicha actividad.
			@else
			, le recomendamos subir nuevamente el justicativo.
			@endif
	</li>
	<li><br></li>
	<li><strong>Observación del Justificativo:</strong> {{$aval->observacion}}</li>
</ul>

@stop
