@extends('sisbeca.layouts.main')
@if(Auth::user()->esBecario())
	@section('title','Voluntariados')
@else
	@section('title','Voluntariados: '.$becario->user->nombreyapellido())
@endif
@section('content')
<div class="col-lg-12">
	<div class="text-right">
		@if(Auth::user()->esBecario())
			<a href="{{route('voluntariados.crear',Auth::user()->id)}}" class="btn btn-sm sisbeca-btn-primary">Cargar Voluntariado</a>
		@else
			<a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
		@endif
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered" id="voluntariados">
			<thead>
				<tr>
					<th>Institución</th>
					<th>Fecha</th>
					<th>Tipo</th>
					<th class="text-center">Horas</th>
					<th class="text-center">Estatus</th>
					<th class="text-right">Acciones</th>
				</tr>
			</thead>
			<tbody>
				
				@foreach($voluntariados as $voluntariado)
				<tr>
					<td>
						{{$voluntariado->institucion}}
					</td>
					<td>{{$voluntariado->getFecha()}}</td>
					<td>{{$voluntariado->tipo}}</td>
					<td class="text-center">{{$voluntariado->horas}}</td>
					<td class="text-center">
						@switch($voluntariado->aval->estatus)
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
					
					<td>
						<span data-toggle="modal" data-target="#voluntariado{{$voluntariado->id}}">
							<button type="button" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Respuesta Voluntariado"  >
								<i class="fa fa-eye"></i>
							</button>
						</span>

						<a href="{{url($voluntariado->aval->url)}}" target="_blank" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver Comprobante">
							@if( $voluntariado->aval->esImagen() )
                        		<i class="fa fa-photo"></i>
                        	@else
                        		<i class="fa fa-file-pdf-o"></i>
                        	@endif
						</a>

						<a href="{{route('voluntariados.editar',$voluntariado->id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar Voluntariado">
                        	<i class="fa fa-pencil"></i>
						</a>

						@if($voluntariado->aval->estatus!='aceptada')
							<span data-toggle="modal" data-target="#eliminarvoluntariado{{$voluntariado->id}}">
								<button type="button" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="bottom" title="Eliminar Voluntariado"  >
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
				
			</tbody>
		</table>
	</div>
</div>


@foreach($voluntariados as $voluntariado)
<!-- Modal para eliminar voluntariado -->
<div class="modal fade" id="eliminarvoluntariado{{$voluntariado->id}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
		    	<h5 class="modal-title pull-left"><strong>Eliminar Voluntariado</strong></h5>
		    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
		    </div>
			<div class="modal-body">
				<div class="col-lg-12">
					<br>
					<p class="h6 text-center">
						¿Está seguro que desea eliminar permanentemente el voluntariado <strong>{{$voluntariado->nombre}}</strong> con <strong>{{$voluntariado->horas}}</strong> horas?
					</p>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
				<a href="{{route('voluntariados.eliminar',$voluntariado->id)}}" class="btn btn-sm sisbeca-btn-primary pull-right">Si</a>
			</div>
		</div>
	</div>
</div>
<!-- Modal para eliminar voluntariado -->

<!-- Modal para ver voluntariado -->
<div class="modal fade" id="voluntariado{{$voluntariado->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><strong>Voluntariado</strong></h5>
                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
            </div>
            <div class="modal-body">
            	<div class="col" style="padding-left: 0px;padding-right: 0px;">
            		<label class="control-label " for="nombre">Nombre Voluntariado</label>
            		{{ Form::text('nombre', $voluntariado->nombre , ['class' => 'sisbeca-input sisbeca-disabled input-sm','disabled'=>'disabled','placeholder'=>'John Doe'])}}
            	</div>
                <div class="col" style="padding-left: 0px;padding-right: 0px;">
                    <label class="control-label " for="nombre">Estatus</label>
                    {{ Form::select('estatus', array('pendiente'=>'Pendiente','aceptada'=>'Aceptada','negada'=>'Negada','devuelto'=>'Devuelto'),$voluntariado->aval->estatus,['class' =>'sisbeca-input input-sm sisbeca-select sisbeca-disabled','disabled'=>'disabled']) }}
                </div>
                <div class="col" style="padding-left: 0px;padding-right: 0px;">
                    <label class="control-label" for="observacion">Observación del Comprobante</label>
                    <textarea name="observacion" class="sisbeca-input sisbeca-disabled" disabled="disabled" style="margin-bottom: 0px;">{{$voluntariado->aval->observacion}}
                    </textarea> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para ver voluntariado -->
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
    $('#voluntariados').DataTable({

        "language": {
        "decimal": "",
        "emptyTable": "No hay voluntariados",
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
