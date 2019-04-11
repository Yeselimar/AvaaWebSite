@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Eliminación de Mentor</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$becario->user->nombreyapellido()}}</strong>, te notificamos que <strong>{{$mentor->user->nombreyapellido()}}</strong> desde ahora <strong>no será tu mentor</strong>.
	</li>
	<li><br></li>
	<li>Próximante te asignaremos un nuevo mentor y te lo notificaremos.</li>
</ul>

@stop
