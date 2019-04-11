@section('personalcss')
 <script src="{{asset('public_sisbeca/js/lib/bootstrap/js/popper.min.js')}}"></script> 
@endsection
@extends('sisbeca.layouts.main')
@if(!(Auth::user()->rol==='mentor'))
	@section('title','Becarios')
@else
	@section('title','Becarios Asignados')
@endif
@section('content')

<div class="col-lg-12">
	@if(!(Auth::user()->rol==='mentor'))
		Becarios
		@else
		Becarios Asignados
	@endif

	<div class="table-responsive">
		
		<table id="becarios" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center">Becario</th>
					<th class="text-center">Estatus</th>
					@if(!(Auth::user()->rol==='mentor'))
						<th class="text-center">Mentor Asignado</th>
					@endif
					<th class="text-center">Perfil</th>
				</tr>
			</thead>
			<tbody>
				@if($becarios->count() > 0)
				@foreach($becarios as $becario)
				<tr>
					<td class="text-center">
						{{ $becario->user->name.' '.$becario->user->last_name }}
						| {{ $becario->user->cedula }}
						<br>
						 {{ $becario->user->email }}
					</td>
					@if($becario->status==='activo')
						<td class="text-center">
							<span class="label label-success">{{strtoupper( $becario->status )}}</span>
						</td>
						@else
							@if($becario->status==='probatorio1')
								<td class="text-center">
									<span class="label label-warning">{{strtoupper( $becario->status )}}</span>
								</td>
							@else
								<td class="text-center">
									<span class="label label-danger">{{strtoupper( $becario->status )}}</span>
								</td>
							@endif
					@endif
					@if(!(Auth::user()->rol==='mentor'))
						@if($becario->mentor_id===null)
							<td class="text-center">
								<span class="label label-danger">Sin asignar</span>
							</td>
						@else
							<td class="text-center">
								{{ $becario->mentor->user->name.' '.$becario->mentor->user->last_name }}
								<br>
								{{ $becario->mentor->user->email }}
							</td>
						@endif
					@endif

					<td class="text-center">
						
						@if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
						<a href="{{route('becarios.editar.datos',$becario->user_id)}}" class='btn btn-xs sisbeca-btn-primary' data-toggle="tooltip" data-placement="top" title="Editar Datos del Becarios">
							<i class='fa fa-pencil'></i>
						</a>
						@endif

						@if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador() or Auth::user()->esMentor())
						<a href="{{route('postulanteObecario.perfil',$becario->user_id)}}" class='btn btn-xs sisbeca-btn-primary' data-toggle="tooltip" data-placement="top" title="Ver Perfil">
							<i class='fa fa-eye'></i>
						</a>
						<a href="{{route('seguimiento.becarioreportegeneral',$becario->user_id)}}" class='btn btn-xs sisbeca-btn-primary' data-toggle="tooltip" data-placement="top" title="Reporte General">
							<i class='fa fa-bar-chart'></i>
						</a>
						<a href="{{route('seguimiento.resumen',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="top" title="Resumen Becario">
							<i class="fa fa-user"></i>
						</a>
						@endif

						@if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
						<a href="{{route('periodos.crear',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="top" title="Cargar Nota">
							<i class="fa fa-sticky-note-o"></i>
						</a>
						<a href="{{route('cursos.crear',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="top" title="Cargar CVA">
							<i class="fa fa-book"></i>
						</a>
						<a href="{{route('voluntariados.crear',$becario->user_id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="top" title="Cargar Voluntariado">
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
