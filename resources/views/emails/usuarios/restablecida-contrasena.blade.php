@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Bien!</h1></strong></li>
	<li></li>
	<li>
		Estimado <strong>{{ $user->nombreyapellido() }}</strong>, su contraseña fue restablecida exitosamente.</p>
	</li>
</ul>

@stop
