@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Restablece tu contraseña</h1></strong></li>
	<li></li>
	<li>
		Estimado, <strong>{{ $user->nombreyapellido() }}</strong>. Ingrese al siguiente <strong><a href="{{ route('restablecer.contrasena',array('token'=>$token)) }}" target="_blank" style="color:#dc3545">enlace</a></strong> para restablecer su contraseña.
	</li>
	<li>
		Ignore este mensaje si no desea restablecer la contraseña.</p>
	</li>
</ul>

@stop
