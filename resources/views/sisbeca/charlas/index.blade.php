@extends('sisbeca.layouts.main')
@section('title','Calendario Charlas')
@section('content')
<div class="col-lg-12">
	<div class="text-right">
		@if($charlas->count()==0)
		<a href="{{route('charla.create')}}" class="btn btn-sm sisbeca-btn-primary">Crear Calendario Charla</a>
		@endif
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th class="text-center">Año</th>
					<th class="text-center">Creada el</th>
					<th class="text-center">Actualizada el</th>
					<th class="text-right">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@if($charlas->count()==0)
					<td colspan="7" class="text-center">No hay <strong>calendario charlas</strong></td>
				@else
					@foreach($charlas as $charla)
					<tr>
						<td class="text-center">{{$charla->anho}}</td>
						<td class="text-center">{{$charla->fechaCreacion()}}</td>
						<td class="text-center">{{$charla->fechaActualizacion()}}</td>
						<td>
							<span data-toggle="modal" data-target="#ver{{$charla->id}}">
								<button type="button" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver">
		                        	<i class="fa fa-eye"></i>
								</button>
							</span>

							<a href="{{ route('charla.edit',$charla->id) }}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar">
	                        	<i class="fa fa-pencil"></i>
							</a>

							<span data-toggle="modal" data-target="#eliminarcharla">
								<button type="button" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="bottom" data-target="#eliminarcharla" title="Eliminar">
		                        	<i class="fa fa-trash"></i>
								</button>
							</span>
						</td>
					</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>

@foreach($charlas as $charla)
<!-- Modal para eliminar -->
<div class="modal fade" id="eliminarcharla">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
		    	<h5 class="modal-title pull-left"><strong>Calendario Charlas</strong></h5>
		    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
		    </div>
			<div class="modal-body" style="padding-top: 0px;">
				<br>
				<p>¿Está seguro que desea eliminar este <strong>calendario de charlas</strong>?</p>
			</div>
			<div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
				<a href="{{route('charla.destroy',$charla->id)}}" class="btn btn-sm sisbeca-btn-default pull-right">Si</a>
				<button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" data-dismiss="modal">No</button>
			</div>
		</div>
	</div>
</div>
<!-- Fin Modal para eliminar -->

<!-- Modal para ver imagen -->
<div class="modal fade" id="ver{{$charla->id}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
		    	<h5 class="modal-title pull-left"><strong>Calendario Charlas</strong></h5>
		    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
		    </div>
			<div class="modal-body">
				<br>
				<img src="{{url($charla->imagen)}}" alt="{{$charla->anho}}" class="img-responsive sisbeca-border" >
				<br><br>
				<p class="text-center h6">{{$charla->anho}}</p>
			</div>
			<div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
				<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<!-- Fin Modal para ver imagen -->
@endforeach

@endsection

@section('personaljs')

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
    </script>
@endsection
