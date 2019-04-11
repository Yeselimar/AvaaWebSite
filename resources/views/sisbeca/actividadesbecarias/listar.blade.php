@extends('sisbeca.layouts.main')
@section('title','Becarios - Pre-Carga Actividades Becarias')
@section('content')

<div class="col-lg-12">
	
	<div class="table-responsive">
		
		<table id="becarios" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center">Becario</th>
					<th class="text-center">Cédula</th>
					<th class="text-center">Estatus</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@if($becarios->count() > 0)
				@foreach($becarios as $becario)
				<tr>
					<td class="text-center">
						{{ $becario->user->name.' '.$becario->user->last_name }}
						<br>
						 {{ $becario->user->email }}
					</td>
					<td class="text-center">
						{{ $becario->user->cedula }}
					</td>
					<td class="text-center">
						@if($becario->status==='activo')
							<span class="label label-success">{{strtoupper( $becario->status )}}</span>
						@else
							@if($becario->status==='probatorio1')
								<span class="label label-warning">{{strtoupper( $becario->status )}}</span>
							@else
								<span class="label label-danger">{{strtoupper( $becario->status )}}</span>
							@endif
						@endif
					</td>

					<td class="text-center">
						

						
						@if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
						<a href="{{route('crear.periodo',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="top" title="Cargar Nota">
							<i class="fa fa-sticky-note-o"></i>
						</a>
						<a href="{{route('crear.curso',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="top" title="Cargar CVA">
							<i class="fa fa-book"></i>
						</a>
						<a href="{{route('crear.voluntariado',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="top" title="Cargar Voluntariado">
							<i class="fa fa-star"></i>
						</a>

						|

						<a href="{{route('actividades.becario',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="top" title="Ver Notas Académicas">
							<i class="fa fa-sticky-note-o"></i>
						</a>
						<a href="{{route('periodos.becario',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="top" title="Ver CVA">
							<i class="fa fa-book"></i>
						</a>
						<a href="{{route('voluntariados.becario',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="top" title="Ver Voluntariados">
							<i class="fa fa-star"></i>
						</a>
						@endif
						
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td class="text-center" colspan="4">No hay <strong>becarios</strong></td>
				</tr>
				@endif
			</tbody>
		</table>
		

	</div>

</div>
@endsection

@section('personaljs')

<script>
$(document).ready(function(){
    $('#becarios').DataTable({
        "ordering": true,

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

 
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

@endsection