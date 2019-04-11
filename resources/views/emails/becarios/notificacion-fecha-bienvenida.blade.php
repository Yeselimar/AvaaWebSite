@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Acto de Bienvenida</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->user->nombreyapellido()}}</strong> nuestro equipo le complace invitarlo para el <strong>acto de bienvenida</strong> de nuestros becarios a <strong>ProExcelencia</strong>.
	</li>
	<li><br></li>
	<li><strong>Información del Acto</strong></li>
	<li><br></li>
	<li><strong>Fecha:</strong> {{$becario->fechaBienvenida()}}</li>
	<li><strong>Hora:</strong> {{$becario->horaBienvenida()}}</li>
	<li><strong>Lugar:</strong> {{$becario->lugar_bienvenida}}</li>
	<li><br></li>
	<li>Cualquier cambio, se lo notificaremos.</li>
	<li><br></li>
	<li><strong>¡Te esperamos!</strong></li>
</ul>

@stop
