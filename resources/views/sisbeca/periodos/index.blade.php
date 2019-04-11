@extends('sisbeca.layouts.main')
@if(Auth::user()->esBecario())
	@section('title','Periodos')
@else
	@section('title','Periodos: '.$becario->user->nombreyapellido() )
@endif
@section('content')
	<div class="col-lg-12">
		<div class="text-right">
			@if(Auth::user()->esBecario())
				<a href="{{route('periodos.crear',Auth::user()->id)}}" class="btn btn-sm sisbeca-btn-primary">Cargar Periodo</a>
			@else
				<a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
			@endif
		</div>
		<br>
		<div class="table-responsive">
			<table class="table table-hover table-bordered" id="periodos">
				<thead>
					<tr>
						<th class="text-center">Año Lectivo</th>
						<th class="text-center">Número Periodo</th>
						<th class="text-center">Nro Materias</th>
						<th class="text-center">Promedio</th>
						<th class="text-center">Estatus</th>
						<th class="text-right">Acciones</th>
					</tr>
				</thead>
				<tbody>
					
					@foreach($periodos as $periodo)
					<tr>
						<td class="text-center">
							{{$periodo->anho_lectivo}}
						</td>
						<td>{{$periodo->getNumeroPeriodo()}}</td>
						<td class="text-center">{{$periodo->getTotalMaterias()}} materia(s)</td>
						<td class="text-center">{{$periodo->getPromedio()}}</td>
						<td class="text-center">
							@switch($periodo->aval->estatus)
					    		@case('pendiente')
					    			<span class="label label-warning">pendiente</span>
					    		@break;
					    		
					    		@case( 'aceptada')
					    			<span class="label label-success">aceptada</span>
					    		@break

					    		@case ('negada')
					    			<span class="label label-danger">negada</span>
					    		@break
					    		@case ('devuelto')
					    			<span class="label label-danger">devuelto</span>
					    		@break
					    	@endswitch
						</td>
						
						<td >
							<span data-toggle="modal" data-target="#periodo{{$periodo->id}}">
								<button type="button" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Detalles Respuesta Periodo"  >
									<i class="fa fa-eye"></i>
								</button>
							</span>

							<a href="{{url($periodo->aval->url)}}" target="_blank" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver Constancia">
								@if( $periodo->aval->esImagen() )
	                        		<i class="fa fa-photo"></i>
	                        	@else
	                        		<i class="fa fa-file-pdf-o"></i>
	                        	@endif
							</a>

							<a href="{{route('materias.mostrar',$periodo->id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Agregar Materias">
								<i class="fa fa-plus"></i>
							</a>

							<a href="{{route('periodos.editar',$periodo->id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar Periodo">
								<i class="fa fa-pencil"></i>
							</a>

							@if($periodo->aval->estatus!='aceptada')
								<span data-toggle="modal" data-target="#eliminarperiodo{{$periodo->id}}">
									<button type="button" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="bottom" title="Eliminar Periodo"  >
										<i class="fa fa-trash"></i>
									</button>
								</span>
							@else
								<button class="btn btn-xs sisbeca-btn-default" disabled="disabled">
									<i class="fa fa-trash"></i>
								</button>
							@endif
						</td>
					</tr>
					@endforeach
					<!--
					<tr>
						<td ></td>
						<td ></td>
						<td ></td>
						<td class="text-right">
							<strong>Promedio General</strong>
						</td>
						<td class="text-center">
							<strong>{{$becario->promediotodosperiodos()}}</strong>
						</td>
						<td ></td>
					</tr>
					-->
				</tbody>
			</table>
		</div>
	</div>
		
	@foreach($periodos as $periodo)
	<!-- Modal para eliminar periodo -->
	<div class="modal fade" id="eliminarperiodo{{$periodo->id}}">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left"><strong>Eliminar Periodo</strong></h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="col-lg-12">
						<br>
						<p class="h6 text-center">
							¿Está seguro que desea eliminar permanentemente el periodo <strong>{{$periodo->getNumeroPeriodo()}}</strong> del año lectivo <strong>{{$periodo->anho_lectivo}}</strong>?
						</p>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
					<a href="{{route('periodos.eliminar',$periodo->id)}}" class="btn btn-sm sisbeca-btn-primary pull-right">Si</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para eliminar periodo -->

	<!-- Modal para ver periodo -->
	<div class="modal fade" id="periodo{{$periodo->id}}">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title pull-left"><strong>Periodo</strong></h5>
	                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
	            </div>
	            <div class="modal-body">
	            	<div class="col" style="padding-left: 0px;padding-right: 0px;">
	            		<label class="control-label " for="nombre">Año Lectivo</label>
	            		{{ Form::text('anho_lectivo', $periodo->anho_lectivo , ['class' => 'sisbeca-input sisbeca-disabled input-sm','disabled'=>'disabled'])}}
	            	</div>
	                <div class="col" style="padding-left: 0px;padding-right: 0px;">
	                    <label class="control-label " for="nombre">Estatus</label>
	                    {{ Form::select('estatus', array('pendiente'=>'Pendiente','aceptada'=>'Aceptada','negada'=>'Negada','devuelto'=>'Devuelto'),$periodo->aval->estatus,['class' =>'sisbeca-input input-sm sisbeca-select sisbeca-disabled','disabled'=>'disabled']) }}
	                </div>
	                <div class="col" style="padding-left: 0px;padding-right: 0px;">
	                    <label class="control-label" for="observacion">Observación del Comprobante</label>
	                    <textarea name="observacion" class="sisbeca-input sisbeca-disabled" disabled="disabled" style="margin-bottom: 0px;">{{$periodo->aval->observacion}}
	                    </textarea> 
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cerrar</button>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- Modal para ver periodo -->
	@endforeach
	
@endsection

@section('personaljs')
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
<script>
$(document).ready(function() {
    $('#periodos').DataTable({

        "language": {
        "decimal": "",
        "emptyTable": "No hay periodos",
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
