@extends('sisbeca.layouts.main')
@section('title','Contactos')
@section('content')
<div class="col-lg-12">
	<div class="text-right">
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th class="text-left">Nombre Completo</th>
					<th class="text-center">Télefono</th>
					<th class="text-left">Correo</th>
					<th class="text-center">Escribió el</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@if($contactos->count()==0)
					<td colspan="5" class="text-center">No hay <strong>contactos</strong></td>
				@else
					@foreach($contactos as $contacto)
					<tr>
						<td class="text-left">{{$contacto->nombre_completo}}</td>
						<td class="text-center">{{$contacto->telefono}}</td>
						<td class="text-left">{{$contacto->correo}}</td>
						<td class="text-center">{{$contacto->fechaCreacion()}}</td>
						<td class="text-center">
							<span data-toggle="modal" data-target="#detalles{{$contacto->id}}">
								<button type="button" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver Detalles">
		                        	<i class="fa fa-eye"></i>
								</button>
							</span>
						</td>
					</tr>
					@endforeach
				@endif
			</tbody>
		</table>
		<hr>

		<p class="text-right">{{$contactos->count()}} contacto(s)</p>
	</div>
</div>

<!-- Modal detalles -->
@foreach($contactos as $contacto)
<div class="modal fade" id="detalles{{$contacto->id}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
		    	<h5 class="modal-title pull-left"><strong>Contacto</strong></h5>
		    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
		    </div>
			<div class="modal-body">
				<div class="col-lg-12">
					<br>
					<p class="text-left h6"><strong>Nombre Completo:</strong> {{$contacto->nombre_completo}}</p>
					<p class="text-left h6"><strong>Teléfono:</strong> {{$contacto->telefono}}</p>
					<p class="text-left h6"><strong>Correo:</strong> {{$contacto->correo}}</p>
					<p class="text-left h6"><strong>Asunto:</strong> {{$contacto->asunto}}</p>
					<p class="text-left h6"><strong>Mensaje:</strong> {{$contacto->mensaje}}</p>
					<p class="text-left h6"><strong>Escribió el:</strong> {{$contacto->fechaCreacion()}}</p>
					
				</div>
			</div>
			<div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
				<div class="col-lg-12">
				<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endforeach
<!-- Modal detalles -->

@endsection

@section('personaljs')
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@endsection