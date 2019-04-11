@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Decisión de la Postulación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$postulanteBecario->user->nombreyapellido()}}</strong>, tú postulación a becario <strong>ProExcelencia</strong> fue <strong>{{$decision}}</strong>.
	</li>
</ul>

@stop
