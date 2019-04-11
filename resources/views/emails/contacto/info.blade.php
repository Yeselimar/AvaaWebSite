@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Contacto</h1></strong></li>
	<li></li>
	<li><strong>Nombre y Apellido:</strong> {{$data["nombre_completo"]}}</li>
	<li><strong>Correo electrónico:</strong> {{$data["correo"]}} </li>
	<li><strong>Teléfono:</strong> {{$data["telefono"]}} </li>
	<li><strong>Asunto:</strong> {{$data["asunto"]}} </li>
	<li><strong>Mensaje:</strong> {{$data["mensaje"]}} </li>
	<li><strong>Fecha y Hora:</strong> {{$data["fecha_hora"]}} </li>
</ul>

@stop
