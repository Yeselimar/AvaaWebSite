@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>¡Bien!</h1></strong></li>
	<li></li>
	<li>
		Estimad@ <strong>{{$usuario->nombreyapellido()}}</strong>, tú registro a postulante a <strong>ProExcelencia</strong> fue exitosa. Te invitamos a iniciar sesión para que termines con la carga de datos y envíes tú postulación.
	</li>
</ul>

@stop
