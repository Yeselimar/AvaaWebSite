@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Bien!</h1></strong></li>
	<li></li>
	<li>
		Estimado <strong>{{$becario->user->nombreyapellido()}}</strong>, tú postulación fue <strong> recibida exitosamente</strong>. A la brevedad nuestro equipo verificará tus datos y calificarán tu postulación.
	</li>
</ul>

@stop
