@extends('sisbeca.layouts.main')
@section('title','Mi Perfil')
@section('content')

<div class="col-lg-12">
	<div class="alert  alert-warning alert-important" role="alert">
		Una Vez su postulacion haya sido enviada, no podrá ser modificada, por favor verifique sus datos con detenimiento.
	</div>

	<div class="panel panel-default">

		
		<div class="panel-body">
		        
	       	<div class="col-lg-12">
				<h5 class="m-t-30">Proceso de Postulación 
					<span class="pull-right">{{$progreso}} %</span>
				</h5>

				<div class="progress">
	              	<div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{$progreso}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$progreso}}%;height: 26px;">
	              		<span class="sr-only">50% Complete</span>
	              	</div>
	            </div>

	            <br/>
				
				
				@if($progreso==100)
					<form class="form-horizontal" method="post" action ="{{route('postulantebecario.enviarPostulacionGuardar', array('progreso'=>$progreso))}}">
						{{csrf_field()}}	
						
						<input class="btn btn-md sisbeca-btn-primary pull-right"  style="margin-left: 5px;" type="submit" value="Enviar Postulación">
					</form>
				@else
					<button type="button" class="btn sisbeca-btn-primary disabled pull-right">
						Enviar Postulación
					</button>
				@endif
				<br>
			</div>

		</div>
	</div>
</div>

@endsection

@section('personaljs')

@endsection
