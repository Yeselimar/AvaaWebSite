@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Eliminación de Becario</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$mentor->user->nombreyapellido()}}</strong>, te notificamos que el becario <strong>{{$becario->user->nombreyapellido()}}</strong> desde ahora <strong>no estará a tu cargo</strong> a mentorear.
	</li>
	<li><br></li>
	<li>Si te asignamos un nuevo becario a tu cargo, te notificaremos.</li>
</ul>

@stop
