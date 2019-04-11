@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Bienvenido</h1></strong></li>
	<li></li>
	<li>
		Bienvenido <strong>{{$usuario->nombreyapellido()}}</strong> al Sistema de Becarios AVAA.
	</li>
	<li><br></li>
	<li>Lo hemos registrado en nuestro sistema con el rol <strong>{{strtoupper($usuario->rol)}}</strong>.</li>
	<li><strong>Importante:</strong> Pónganse en contacto con el administrador del sistema para conocer su contraseña y poder ingresar al sistema.</li>
</ul>

@stop
