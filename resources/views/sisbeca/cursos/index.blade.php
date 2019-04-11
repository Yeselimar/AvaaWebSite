@extends('sisbeca.layouts.main')
@if(Auth::user()->esBecario())
	@section('title','CVA')
@else
	@section('title','CVA: '.$becario->user->nombreyapellido())
@endif
@section('content')
<div class="col-lg-12">
	<div class="text-right">
		@if(Auth::user()->esBecario())
			<a href="{{route('cursos.crear',Auth::user()->id)}}" class="btn btn-sm sisbeca-btn-primary">Cargar CVA</a>
		@else
			<a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
		@endif
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered" id="cursos">
			<thead>
				<tr>
					<th class="text-center">Nivel</th>
					<th class="text-center">Módulo</th>
					<th class="text-center">Modo</th>
					<th class="text-center">Nota</th>
					<th class="text-center">Estatus</th>
					<th class="text-right">Acciones</th>
				</tr>
			</thead>
			<tbody>
				
				@foreach($cursos as $curso)
				<tr>
					<td class="text-center">{{$curso->getNivel()}}</td>
					<td class="text-center">{{$curso->modulo}}</td>
					<td class="text-center">{{ucwords($curso->modo)}}</td>
					<td class="text-center">{{$curso->getNota()}}</td>
					<td class="text-center">
						@switch($curso->aval->estatus)
				    		@case('pendiente')
				    			<span class="label label-warning">pendiente</span>
				    		@break;
				    		
				    		@case( 'aceptada')
				    			<span class="label label-success">aceptada</span>
				    		@break

				    		@case ('negada')
				    			<span class="label label-danger">negada</span>
				    		@break
				    	@endswitch
					</td>
					
					<td>
						<span data-toggle="modal" data-target="#curso{{$curso->id}}">
							<button type="button" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Detalles Respuesta CVA"  >
								<i class="fa fa-eye"></i>
							</button>
						</span>

						<a href="{{url($curso->aval->url)}}" target="_blank" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver Nota CVA">
							@if( $curso->aval->esImagen() )
                        		<i class="fa fa-photo"></i>
                        	@else
                        		<i class="fa fa-file-pdf-o"></i>
                        	@endif
						</a>

						<a href="{{route('cursos.editar',$curso->id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar CVA">
                        	<i class="fa fa-pencil"></i>
						</a>

						@if($curso->aval->estatus!='aceptada')
							<span data-toggle="modal" data-target="#eliminarcurso{{$curso->id}}">
								<button type="button" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="bottom" title="Eliminar CVA"  >
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
					<td colspan="4" class="text-right">
						<strong>Promedio General</strong>
					</td>
					<td class="text-center">
						<strong>{{$becario->promediotodoscva()}}</strong>
					</td>
					<td colspan="2"></td>
				</tr>
			-->
			</tbody>
		</table>
	</div>
</div>

@foreach($cursos as $curso)
<!-- Modal para eliminar curso -->
<div class="modal fade" id="eliminarcurso{{$curso->id}}">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
		    	<h5 class="modal-title pull-left"><strong>Eliminar CVA</strong></h5>
		    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
		    </div>
			<div class="modal-body">
				<div class="col-lg-12">
					<br>
					<p class="h6 text-center">
						¿Está seguro que desea eliminar permanentemente el CVA <strong>{{$curso->getIdCurso()}}</strong>?
					</p>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
				<a href="{{route('cursos.eliminar',$curso->id)}}" class="btn btn-sm sisbeca-btn-primary pull-right">Si</a>
			</div>
		</div>
	</div>
</div>
<!-- Modal para eliminar curso -->

<!-- Modal para ver curso -->
<div class="modal fade" id="curso{{$curso->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><strong>CVA</strong></h5>
                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
            </div>
            <div class="modal-body">
            	<div class="col" style="padding-left: 0px;padding-right: 0px;">
            		<label class="control-label " for="cva">Nivel/Módulo</label>
            		{{ Form::text('cva', $curso->nivel.' '.$curso->modulo , ['class' => 'sisbeca-input sisbeca-disabled input-sm','disabled'=>'disabled','placeholder'=>'John Doe'])}}
            	</div>
                <div class="col" style="padding-left: 0px;padding-right: 0px;">
                    <label class="control-label " for="nombre">Estatus</label>
                    {{ Form::select('estatus', array('pendiente'=>'Pendiente','aceptada'=>'Aceptada','negada'=>'Negada','devuelto'=>'Devuelto'),$curso->aval->estatus,['class' =>'sisbeca-input input-sm sisbeca-select sisbeca-disabled','disabled'=>'disabled']) }}
                </div>
                <div class="col" style="padding-left: 0px;padding-right: 0px;">
                    <label class="control-label" for="observacion">Observación</label>
                    <textarea name="observacion" class="sisbeca-input sisbeca-disabled" disabled="disabled" style="margin-bottom: 0px;">{{$curso->aval->observacion}}
                    </textarea> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para ver curso -->
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
    $('#cursos').DataTable({

        "language": {
        "decimal": "",
        "emptyTable": "No hay CVA",
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
