@extends('sisbeca.layouts.main')
@section('title','Taller/Chat Club: '.$becario->user->nombreyapellido())
@section('content')
<div class="col-lg-12">
	<div class="text-right">
		@if(!Auth::user()->esBecario())
		<a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
		@endif
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered" id="actividades">
			<thead>
				<tr>
					<th class="text-center">Fecha</th>
					<th class="text-center">Tipo</th>
					<th class="text-center">Modalidad</th>
					<th class="text-center">Nombre</th>
					<th class="text-center">Estatus</th>
				</tr>
			</thead>
			<tbody>
				@foreach($ab as $item)
				<tr>
					<td class="text-center">{{$item->actividad->getFecha()}}</td>
					<td class="text-center">{{$item->actividad->getTipo()}}</td>
					<td class="text-center">{{$item->actividad->getModalidad()}}</td>
					<td class="text-center">{{$item->actividad->nombre}}</td>
					<td class="text-center">
						
						@if($item->estatus=='asistira')
						<span class="label label-success">
							asistirá
						</span>
						@endif

						@if($item->estatus=='lista de espera')
						<span class="label label-warning">
							lista de espera
						</span>
						@endif

						@if($item->estatus=='no asistio')
						<span class="label label-danger">
							no asistió
						</span>
						@endif

						@if($item->estatus=='asistio')
						<span class="label label-success">
							asistió
						</span>
						@endif

						@if($item->aval_id!=null)
							/ <span  class="label label-custom">justificación cargada</span>
							
							@if($item->aval->estatus=='pendiente')
								está 
							@else
								fue
							@endif

							@if($item->aval->estatus=='pendiente')
							<span class="label label-warning">
								pendiente
							</span>
							@endif

							@if($item->aval->estatus=='aceptada')
							<span class="label label-success">
								aceptada
							</span>
							@endif

							@if($item->aval->estatus=='negada')
							<span class="label label-danger">
								negada
							</span>
							@endif

							@if($item->aval->estatus=='devuelto')
							<span class="label label-danger">
                                devuelto
                            </span>
                            @endif 
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@endsection

@section('personaljs')
<script>
$(document).ready(function() {
    $('#actividades').DataTable({

        "language": {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No hay resultados encontrados",
        "paginate":
            {
                "first": "Primero",
                "last": "Ultimo",
                "next": '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                "previous": '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
            }
        }
    });
});
</script>
@endsection