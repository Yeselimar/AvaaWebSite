@extends('emails.base')

@section('content')

<ul style="list-style: none;color:#003865">
	<li><strong><h1>Decisión de la Postulación</h1></strong></li>
	<li></li>
	<li>
		Hola <strong>{{$postulanteBecario->user->nombreyapellido()}}</strong>, tú postulación a becario <strong>ProExcelencia</strong> fue <strong>{{$decision}}</strong>.
	</li>
	<li><br></li>
	<li>Mantente atento, en los próximos días te notificaremos por este medio el día y el lugar de tu fecha de bienvenida como becario a nuestro programa de Pro-Excelencia.</li>
	<li><br></li>
	<li>¡FELICIDADES!</li>
</ul>

@stop
