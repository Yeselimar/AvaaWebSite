@extends('sisbeca.layouts.main')
@section('title',ucwords($actividad->tipo).': '.$actividad->nombre)
@section('content')
<div class="col-lg-12" id="app">
	<div class="text-right">
		@if(Auth::user()->esBecario() ) <!-- Para el Becario -->

			<template v-if="justificativo.aval_id!=null">
				<button v-b-popover.hover.bottom="'Estatus del justificativo'"  class="btn sisbeca-btn-primary" @click="modalJustificativo()">
					<i class="fa fa-eye"></i>
				</button>
				<a v-b-popover.hover.bottom="'Ver justificativo'" class="btn sisbeca-btn-primary" target="_blank" :href="urlJustificativo(justificativo.aval.url)">
					<template v-if="justificativo.aval.extension=='imagen'">
						<i class="fa fa-image"></i>
					</template>
					<template v-else>
						<i class="fa fa-file-pdf-o"></i>
					</template>
				</a>
			</template>

			<template v-if="!inscrito">
				@if($actividad->inscribionabierta())
				<button type="button" class="btn btn-sm sisbeca-btn-primary" @click="actividadinscribirme()">Inscribirme</button>
				@else
				<button type="button" class="btn btn-sm sisbeca-btn-primary" disabled="disabled">Inscribirme</button>
				@endif
			</template>

			<template v-else>
				@if($actividad->inscribionabierta())
					<button type="button" class="btn btn-sm sisbeca-btn-primary" @click="desinscribirme()">Desinscribirme</button>
				@else
					<button type="button" class="btn btn-sm sisbeca-btn-primary" disabled="disabled">Desinscribirme</button>
				@endif
			</template>

			<template v-if="lapso_justificar==true">
				<template v-if="estatus_becario==='justificacion cargada'">
					<span data-toggle="tooltip"  title="Editar Justificativo" data-placement="bottom">
						<a href="{{ route('actividad.editarjustificacion',array('actividad_id'=>$actividad->id,'becario_id'=>Auth::user()->id)) }}" class="btn btn-sm sisbeca-btn-primary">Editar Justificativo</a>
					</span>
				</template>
				<template v-else>
					<template v-if="estatus_becario==='asistira' ">
					<span data-toggle="tooltip"  title="Subir Justificativo" data-placement="bottom">
						<a href="{{ route('actividad.subirjustificacion',array('actividad_id'=>$actividad->id,'becario_id'=>Auth::user()->id)) }}" class="btn btn-sm sisbeca-btn-primary">Subir Justificativo </a>
					</span>
					</template>
					
				</template>
				
			</template>
			<template v-else>
				<!-- Puede editar su justificacion estando fuera del lapso y devuelto-->
				<template v-if="estatus_becario==='justificacion cargada'">
					<template  v-if="estatus_aval=='devuelto'">
						<a href="{{ route('actividad.editarjustificacion',array('actividad_id'=>$actividad->id,'becario_id'=>Auth::user()->id)) }}" class="btn btn-sm sisbeca-btn-primary">Editar justificativo</a>
					</template>
					<template v-if="estatus_aval=='pendiente' || estatus_aval=='aceptada' || estatus_aval=='negada'">
						<a href="#" class="btn btn-sm sisbeca-btn-primary" disabled="disabled">Editar Justificativo</a>
					</template>
				</template>
			</template>
		@else <!-- Para el Coordinador o Directivo -->
			<span data-toggle="tooltip"  title="Eliminar Taller/Chat Club" data-placement="bottom">
				<button type="button" class="btn btn-sm sisbeca-btn-default" @click="eliminarActividad()" >
					<i class="fa fa-trash"></i>
				</button>	
			</span>
			<a href="{{route('actividad.listaasistente',$actividad->id)}}" target="_blank" data-toggle="tooltip"  title="PDF Lista de Asistentes" data-placement="bottom" class="btn btn-sm sisbeca-btn-primary">
				<i class="fa fa-file-pdf-o"></i>
			</a>
			
			<template v-if="actividad.status!='cerrado' && actividad.status!='suspendido'">
				<a :href="obtenerRutaEditarActividad()" class="btn btn-sm sisbeca-btn-primary" data-toggle="tooltip" title="Editar Taller/Chat Club" data-placement="bottom">
					<i class="fa fa-pencil"></i>
				</a>
			</template>
			<template v-else>
				<a href="#" class="btn btn-sm sisbeca-btn-primary" data-toggle="tooltip" title="Editar Taller/Chat Club" data-placement="bottom" disabled="disabled">
					<i class="fa fa-pencil"></i>
				</a>
			</template>

			<span data-toggle="tooltip"  title="Editar Justificativo" data-placement="bottom">
				<a href="{{route('actividad.justificativos.todos',$actividad->id)}}" class="btn btn-sm sisbeca-btn-primary">Justificativos de este @{{ actividad.tipo }}</a>
			</span>
			
			<a href="{{route('actividad.inscribir.becario',$actividad->id)}}" class="btn btn-sm sisbeca-btn-primary" data-toggle="tooltip"  title="Inscribir Becario" data-placement="bottom">Inscribir Becario</a>

		@endif
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered" id="informacion">
			<thead>
				<tr>
					<th colspan="2">
						Información General
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left">
						<template v-if="actividad.tipo=='taller'">
							<strong>Taller</strong>
						</template>
						<template v-else>
							<strong>Chat Club</strong>
						</template>
					</td>
					<td class="text-left">
						@{{ actividad.nombre }}
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Modalidad</strong>
					</td>
					<td class="text-left">
						<template v-if="actividad.modalidad=='virtual'">
							<i class="fa fa-laptop"></i>
						</template>
						<template v-else>
							<i class="fa fa-male"></i>
						</template>
						@{{ actividad.modalidad }}
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Fecha</strong>
					</td>
					<td class="text-left">
						@{{ diaSemana(actividad.fecha)}}, 
						@{{ fechaformartear(actividad.fecha)}}
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Hora</strong>
					</td>
					<td class="text-left">
						@{{ horaformatear(actividad.hora_inicio)}} 
						a 
						@{{ horaformatear(actividad.hora_fin)}} 
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Nivel</strong>
					</td>
					<td class="text-left">
						<template v-if="actividad.nivel=='basico'">
							Básico
						</template>
						<template v-else>
							<template v-if="actividad.nivel=='intermedio'">
								Intermedio
							</template>
							<template v-else>
								<template v-if="actividad.nivel=='avanzado'">
									Avanzado
								</template>
								<template v-else>
									Cualquier nivel
								</template>
							</template>
						</template>
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Año Académico</strong>
					</td>
					<td class="text-left">
						@{{ actividad.anho_academico }}
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Estatus @{{ actividad.tipo}}</strong>
					</td>
					<td class="text-left">
						<span v-if="actividad.status=='disponible'" class="label label-success">
							@{{ actividad.status }}</span>
						<span v-if="actividad.status=='suspendido'" class="label label-danger">
							@{{ actividad.status }}</span>
						<span v-if="actividad.status=='oculto'" class="label label-warning">
							@{{ actividad.status }}</span>
						<span v-if="actividad.status=='cerrado'" class="label label-danger">
							@{{ actividad.status }}</span>

						@if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador())

						<button v-b-popover.hover.bottom="'Colocar en Disponible'" class="btn btn-xs sisbeca-btn-primary mb-3" @click="colocarDisponible()">
							<i class="fa fa-check"></i>
						</button>

						<button v-b-popover.hover.bottom="'Colocar en Suspendido'" class="btn btn-xs sisbeca-btn-primary mb-3" @click="colocarSuspendido()">
							<i class="fa fa-remove"></i>
						</button>

						<button v-b-popover.hover.bottom="'Colocar en Cerrado'" class="btn btn-xs sisbeca-btn-primary mb-3" @click="colocarCerrado()">
							<i class="fa fa-ban"></i>
						</button>

						<button v-b-popover.hover.bottom="'Colocar en Oculto'" class="btn btn-xs sisbeca-btn-primary mb-3" @click="colocarOculto()">
							<i class="fa fa-eye-slash"></i>
						</button>

						@endif
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Límite participantes</strong>
					</td>
					<td class="text-left">
						@{{ actividad.limite_participantes }}
					</td>
				</tr>
				<tr>
					<td class="text-left">
						<strong>Descripción</strong>
					</td>
					<td class="text-left">
						@{{ actividad.descripcion }}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered" id="facilitador">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th>Tipo Facilitador</th>
					<th>
						Nombre y apellido
					</th>
					@if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo() )
						<th>Horas Voluntariado</th>
					@endif
				</tr>
			</thead>
			<tbody>
				<tr v-for="(facilitador, index) in facilitadores">
					<td class="text-center" style="width: 25px;">
	                    @{{index+1}}
	                </td>
					
					<template v-if="facilitador.becario_id!=null">
						<td>
							Es Becario
						</td>
					</template>
					<template v-else>
						<td class="text-left">
							No es becario
						</td>
					</template>

					<td class="text-left">
						<template v-if="facilitador.becario_id!=null">
							@{{facilitador.user.name}} @{{facilitador.user.last_name}}
						</template>
						<template v-else>
							@{{facilitador.nombreyapellido}}
						</template>
					</td>

					@if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo() )
						<template v-if="facilitador.becario_id!=null">
							<td class="text-left">
								@{{facilitador.horas}} hora(s)
							</td>
						</template>
						<template v-else>
							<td></td>
						</template>
					@endif
				</tr>
				<tr v-if="facilitadores.length==0">
					<td class="text-left">No hay <strong>facilitador(es)</strong> para este <strong>@{{ actividad.tipo}}</strong>.</td>
				</tr>
			</tbody>
		</table>
		<hr>
		<p class="h6 text-right">@{{ facilitadores.length }} facilitador(es)</p>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-striped" id="becarios">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th>
						Becarios Inscritos
					</th>
					<th class="text-center">
						Estatus
					</th>
					@if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
					<!--<th class="text-center">Cambiar Estatus</th>-->
					<th class="text-right">
						Acciones
					</th>
					@endif
				</tr>
			</thead>
			<tbody>
				<tr v-for="(becario,index) in becarios">
					<td class="text-center" style="width: 25px;">
	                    @{{index+1}}
	                </td>
					<td class="text-left" colspan="1">
						@{{ becario.user.name}} @{{ becario.user.last_name}} 
					</td>
					<td class="text-center">
						<span v-if="becario.estatus=='asistira'" class="label label-success">
							asistirá
						</span>
						<span v-if="becario.estatus=='lista de espera'" class="label label-warning">
							lista de espera
						</span>
						<template v-if="becario.estatus=='asistio'">
							<span class="label label-success">
								asistió
							</span>
							 
							<template v-if="becario.aval_id!=null">
								&nbsp;/&nbsp;
								<span  class="label label-custom">justificación cargada</span>
								
								fue 

								<span v-if="becario.aval.estatus=='aceptada'" class="label label-success">
									@{{ becario.aval.estatus }}
								</span>
							</template>
						</template>
						
						<template v-if="becario.estatus=='no asistio'">
							<span class="label label-danger">
								no asistió
							</span>
							 
							<template v-if="becario.aval_id!=null">
								&nbsp;/&nbsp;
								<span  class="label label-custom">justificación cargada</span>
								
								fue 

								<span v-if="becario.aval.estatus=='negada'" class="label label-danger">
									@{{ becario.aval.estatus }}
								</span>
							</template>
						</template>
						
						<template v-if="becario.estatus=='justificacion cargada'">
							<span  class="label label-custom">@{{ becario.estatus }}</span>
							
							<template v-if="becario.aval.estatus=='pendiente' || becario.aval.estatus=='aceptada' || becario.aval.estatus=='negada'">
                                está
                            </template>
                            <template v-else>
                                fue
                            </template> 

							<span v-if="becario.aval.estatus=='pendiente'" class="label label-warning">
								@{{ becario.aval.estatus }}
							</span>
							<span v-if="becario.aval.estatus=='aceptada'" class="label label-success">
								@{{ becario.aval.estatus }}
							</span>
							<span v-if="becario.aval.estatus=='negada'" class="label label-danger">
								@{{ becario.aval.estatus }}
							</span>
							<span v-if="becario.aval.estatus=='devuelto'" class="label label-danger">
                                @{{ becario.aval.estatus }}
                            </span> 
						</template>
					</td>
					@if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo() )
					<!--
					<td>
						<select v-model="becario.estatus" @change="actualizarestatusbecario(becario.estatus,becario.user.id)" class="sisbeca-input input-sm sisbeca-select" style="margin-bottom: 0px !important">
							<option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
						</select>
					</td>
					-->
					<td>
						<template v-if="becario.aval_id==null">
							<template v-if="becario.estatus=='no asistio' || becario.estatus=='asistira' || becario.estatus=='lista de espera'">
								<button v-b-popover.hover.bottom="'Colocar como Asistió'" class="btn btn-xs sisbeca-btn-primary" @click="colocarAsistio(becario.user.id)" >
									<i class="fa fa-check"></i>
								</button>
							</template>
								
							<template v-if="becario.estatus=='asistio' || becario.estatus=='asistira' ||  becario.estatus=='lista de espera'">
								<button v-b-popover.hover.bottom="'Colocar como No Asistió'" class="btn btn-xs sisbeca-btn-primary" @click="colocarNoAsistio(becario.user.id)">
									<i class="fa fa-remove"></i>
								</button>
							</template>
						</template>
						<template v-if="becario.aval_id==null">
							<template v-if="becario.estatus!='asistio' && becario.estatus!='no asistio' && actividad.status!='cerrado' && actividad.status!='suspendido'">
								<a v-b-popover.hover.bottom="'Subir Justificativo'" :href="obtenerRutaJustificacion(becario.user.id)" class="btn btn-xs sisbeca-btn-primary">
									<i class="fa fa-upload" aria-hidden="true"></i>
								</a>
							</template>
							<template v-else> 
								<a v-b-popover.hover.bottom="'Subir Justificativo'" href="#" class="btn btn-xs sisbeca-btn-primary" disabled="disabled">
									<i class="fa fa-upload" aria-hidden="true"></i>
								</a>
							</template>
						</template>
						<template v-else>

							<template v-if="becario.estatus!='asistio' && becario.estatus!='no asistio' && actividad.status!='cerrado' && actividad.status!='suspendido'">
								<a v-b-popover.hover.bottom="'Editar Justificativo'" :href="getRutaEditarJustificativos(becario.user.id)" class="btn btn-xs sisbeca-btn-primary" >
									<i class="fa fa-edit"></i>
								</a>
								
							</template>
							<template v-else>
								<a v-b-popover.hover.bottom="'Editar Justificativo'" href="#" class="btn btn-xs sisbeca-btn-primary" disabled="disabled">
									<i class="fa fa-edit"></i>
								</a>
							</template>

						</template>
						<template v-if="actividad.status=='disponible' && becario.estatus=='asistira'">
							<button v-b-popover.hover.bottom="'Eliminar Inscripción'" class="btn btn-xs sisbeca-btn-default" @click="desinscribirModal(becario.user.id,becario.user.name+' '+becario.user.last_name)" >
								<i class="fa fa-trash" ></i>
							</button>
						</template>
						<template v-else>
							<button v-b-popover.hover.bottom="'Eliminar Inscripción'" class="btn btn-xs sisbeca-btn-default" disabled="disabled">
								<i class="fa fa-trash" ></i>
							</button>
						</template>
					</td>
					@endif
				</tr>
				<tr v-if="becarios && becarios.length==0">
					<td class="text-center" @if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo() ) colspan="4" @else colspan="3" @endif>
						No hay <strong>becarios</strong> para este <strong>@{{ actividad.tipo }}</strong>
					</td>
				</tr>
			</tbody>
		</table>
		<hr>
		<p class="h6 text-right">@{{ becarios.length }} becario(s)</p>
	</div>
	

	<!-- Modal para eliminar inscripición -->
	<form method="POST" @submit.prevent="desinscribir(id)" class="form-horizontal"  >
	{{ csrf_field() }}
		<div class="modal fade" id="desinscribirModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header pull-left">
				    	<h5 class="modal-title"><strong>Eliminar Inscripción</strong></h5>
				    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
				    </div>
					<div class="modal-body">
						<br>
						<div class="col">
							<p class="text-center">¿Está seguro que desea eliminar al becario <strong>@{{ nombrebecario}}</strong> del <strong>@{{this.actividad.tipo}}</strong> con nombre <strong>@{{this.actividad.nombre}}</strong> ?</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Sí</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- Modal para eliminar inscripición -->

	<!-- Modal para eliminar actividad -->
	<div class="modal fade" id="mostrareliminar">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left"><strong>Eliminar @{{actividad.tipo}}</strong></h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="col-lg-12">
						<br>
						<p class="h6 text-center">¿Está seguro que desea eliminar el <strong>@{{actividad.tipo}}</strong> <strong>@{{actividad.nombre}}</strong>?</p>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
					<a href="{{route('actividad.eliminar',$actividad->id)}}" class="btn btn-sm sisbeca-btn-primary pull-right">Sí</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para eliminar actividad -->

	<!-- Modal para justificativo -->
	<div class="modal fade" id="justificativo">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left"><strong>Justificativo</strong></h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label ">Estatus Justificativo</label>
                        <input type="text" class="sisbeca-input sisbeca-disabled input-sm" disabled="disabled" :value="estatus_justificativo">
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" >Observación Justificativo</label>
                        <textarea name="observacion" class="sisbeca-input sisbeca-disabled" disabled="disabled" style="margin-bottom: 0px;">@{{observacion_justificativo}}
                        </textarea> 
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para justificativo -->

	<!-- Cargando.. -->
	<section class="loading" id="preloader">
		<div>
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
		</div>
	</section>
	<!-- Cargando.. -->
</div>
@endsection

@section('personaljs')


<script>
const app = new Vue({
    el: '#app',
    data:
    {
    	nombreactividad:'',
    	id_autenticado:0,
    	estatus_becario:0,
    	estatus_aval:0,
    	lapso_justificar:0,
    	id:0,
    	nombrebecario:'',
        actividad:'',
        facilitadores:'',
        becarios:'',
        inscrito:true,
        estatus:'',
        estatus_actividad:'',
        justificativo:{},
        estatus_justificativo:'',
        observacion_justificativo:''
    },
    created: function()
    {
        this.obtenerdetallesactividad();
        this.obtenerjustificativobecario();
    },
    mounted: function()
    {
    	$('[data-toggle="tooltip"]').tooltip(); 
    },
    methods: 
    {	
    	//obtener  justificativo logueado
    	modalJustificativo()
    	{
    		$('#justificativo').modal('show');
    		this.estatus_justificativo=this.justificativo.aval.estatus;
    		this.observacion_justificativo=this.justificativo.aval.observacion;
    	},
    	urlJustificativo(link)
    	{
    		var url = '{{url(':link')}}';
            url = url.replace(':link', link);
            return url;
    	},
    	obtenerjustificativobecario()
    	{
    		var a_id = "{{$actividad->id}}";
    		var b_id = "{{Auth::user()->id}}";
    		var url = "{{route('actividad.obtener.justificativo',array('a_id'=>':a_id','b_id'=>':b_id'))}}";
    		url = url.replace(':a_id', a_id);
    		url = url.replace(':b_id', b_id);
            axios.get(url).then(response => 
            {
				this.justificativo = response.data.justificativo;
            });
    	},
    	colocarDisponible()
    	{
    		$("#preloader").show();
    		var id = "{{$actividad->id}}";
    		var url = "{{route('actividad.disponible',':id')}}";
    		url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
				toastr.success(response.data.success);
            });
    	},
    	colocarCerrado()
    	{
    		$("#preloader").show();
    		var id = "{{$actividad->id}}";
    		var url = "{{route('actividad.cerrado',':id')}}";
    		url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
				toastr.success(response.data.success);
            });
    	},
    	colocarOculto()
    	{
    		$("#preloader").show();
    		var id = "{{$actividad->id}}";
    		var url = "{{route('actividad.oculto',':id')}}";
    		url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
				toastr.success(response.data.success);
            });
    	},
    	colocarSuspendido()
    	{
    		$("#preloader").show();
    		var id = "{{$actividad->id}}";
    		var url = "{{route('actividad.suspendido',':id')}}";
    		url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
				toastr.success(response.data.success);
            });
    	},
    	colocarAsistio(b_id)
    	{
    		var a_id = {{$actividad->id}};
    		var url = '{{route('actividad.colocar.asistio',array('a_id'=>':a_id','b_id'=>':b_id'))}}';
    		url = url.replace(':b_id', b_id);
    		url = url.replace(':a_id', a_id);
    		$("#preloader").show();
            axios.get(url).then(response => 
            {
            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
				toastr.success(response.data.success);
            });
    		//console.log("Asitio "+b_id);
    	},
    	colocarNoAsistio(b_id)
    	{
    		var a_id = {{$actividad->id}};
    		var url = '{{route('actividad.colocar.noasistio',array('a_id'=>':a_id','b_id'=>':b_id'))}}';
    		url = url.replace(':b_id', b_id);
    		url = url.replace(':a_id', a_id);
    		$("#preloader").show();
            axios.get(url).then(response => 
            {
            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
				toastr.success(response.data.success);
            });
    		//console.log("No Asitio "+b_id);
    	},
    	getRutaEditarJustificativos: function(b_id)
    	{
    		var a_id = {{$actividad->id}};
    		var url = "{{ route('actividad.editarjustificacion',array('actividad_id'=>':a_id', 'becario_id'=>':b_id') ) }}";
    		url = url.replace(':a_id', a_id);
    		url = url.replace(':b_id', b_id);
    		return url;
    	},
    	actualizarestatusbecario: function(estatus,b_id)
    	{
    		var a_id = {{$actividad->id}};
    		var url = "{{ route('actividad.actulizarestatus',array('actividad_id'=>':a_id', 'becario_id'=>':b_id') ) }}";
    		url = url.replace(':a_id', a_id);
            url = url.replace(':b_id', b_id);
            var dataform = new FormData();
            dataform.append('estatus', estatus);
			axios.post(url,dataform).then(response => 
			{
				this.obtenerdetallesactividad();
				toastr.success(response.data.success);
			});
    	},
    	obtenerRutaJustificacion: function(b_id)
    	{
    		var a_id = {{$actividad->id}};
    		var url = "{{ route('actividad.subirjustificacion',array('actividad_id'=>':a_id', 'becario_id'=>':b_id') ) }}";
            url = url.replace(':a_id', a_id);
            url = url.replace(':b_id', b_id);
            return url;
    	},
    	desinscribirme: function()
    	{
    		$("#preloader").show();
    		var actividad_id = '{{$actividad->id}}';
    		var becario_id = '{{Auth::user()->id}}';
    		var url = '{{route('actividad.desinscribir',array('actividad_id'=>':a_id','becario_id'=>':b_id'))}}';
    		url = url.replace(':a_id', actividad_id);
    		url = url.replace(':b_id', becario_id);
            axios.get(url).then(response => 
            {
            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
            	console.log(response);
            	if(response.data.tipo=='danger')
            	{
            		toastr.error(response.data.mensaje);
            	}
                else
                {
                	toastr.success(response.data.mensaje);
                }
            });
    	},
    	desinscribirModal: function(id,becario)
    	{	
    		this.id = id;
    		this.nombrebecario = becario;
    		$('#desinscribirModal').modal('show');
    	},
    	desinscribir: function(id)
    	{
    		$('#desinscribirModal').modal('hide');
    		$("#preloader").show();
    		var actividad_id = '{{$actividad->id}}';
    		var becario_id = id;
    		var url = '{{route('actividad.desinscribir',array('actividad_id'=>':a_id','becario_id'=>':b_id'))}}';
    		url = url.replace(':a_id', actividad_id);
    		url = url.replace(':b_id', becario_id);
            axios.get(url).then(response => 
            {
            	$("#preloader").hide();
            	this.obtenerdetallesactividad();
            	if(response.data.tipo=='danger')
            	{
            		toastr.error(response.data.mensaje);
            	}
                else
                {
                	toastr.success(response.data.mensaje);
                }
            });
    	},
    	actividadinscribirme: function()
    	{
    		$("#preloader").show();
    		var actividad_id = '{{$actividad->id}}';
    		var becario_id = '{{Auth::user()->id}}';
    		var url = '{{route('actividad.inscribirme',array('actividad_id'=>':a_id','becario_id'=>':b_id'))}}';
    		url = url.replace(':a_id', actividad_id);
    		url = url.replace(':b_id', becario_id);
            axios.get(url).then(response => 
            {
            	/*console.log("Hola");
            	console.log(response);
            	url = '{{route('send.mail')}}';
            	let data = JSON.stringify({
					data: response.data.info,
				});

            	axios.post(url,data).then(response => 
	            {
	            	console.log(response.data.nombre);
	            });*/

            	this.obtenerdetallesactividad();
            	$("#preloader").hide();
            	if(response.data.tipo=='danger')
            	{
            		toastr.error(response.data.mensaje);
            	}
                else
                {
                	toastr.success(response.data.mensaje);
                }
            });
    	},
    	obtenerdetallesactividad()
    	{
    		var url = '{{route('actividad.detalles.servicio',$actividad->id)}}';
            axios.get(url).then(response => 
            {
                this.actividad = response.data.actividad;
                this.facilitadores = response.data.facilitadores;
                this.becarios = response.data.becarios;
                this.inscrito = response.data.inscrito;
                this.estatus = response.data.estatus;
                this.id_autenticado = response.data.id_autenticado;
                this.lapso_justificar = response.data.lapso_justificar;
                this.estatus_becario = response.data.estatus_becario;
                this.estatus_aval = response.data.estatus_aval;
                $("#preloader").hide();
            });
    	},
    	obtenerRutaEditarActividad: function()
    	{
    		var a_id = {{$actividad->id}};
    		var url = "{{ route('actividad.editar',':a_id') }}";
            url = url.replace(':a_id', a_id);
            return url;
    	},
    	eliminarActividad: function()
    	{
    		$('#mostrareliminar').modal('show');
    	},
    	fechaformartear: function (fecha)
		{
			var d = new Date(fecha);
			var dia = d.getDate();
		    var mes = d.getMonth() + 1;
		    var anho = d.getFullYear();
		    var fecha = this.zfill(dia,2) + "/" + this.zfill(mes,2) + "/" + anho;
		    return fecha;
		},
		horaformatear: function (hora)
		{
			var cadena = "2018-11-11 "+hora;
			var dia = new Date (cadena);
			return moment(dia).format('hh:mm A');
		},
		zfill: function(number, width)
		{
			var numberOutput = Math.abs(number); /* Valor absoluto del número */
			var length = number.toString().length; /* Largo del número */ 
		    var zero = "0"; /* String de cero */  
		    
		    if (width <= length) {
		        if (number < 0) {
		             return ("-" + numberOutput.toString()); 
		        } else {
		             return numberOutput.toString(); 
		        }
		    } else {
		        if (number < 0) {
		            return ("-" + (zero.repeat(width - length)) + numberOutput.toString()); 
		        } else {
		            return ((zero.repeat(width - length)) + numberOutput.toString()); 
		        }
		    }
		},
		diaSemana: function(fecha)
		{
		    var dias=["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
		    var dt = new Date(fecha);
		    return dias[dt.getUTCDay()];    
		}
    }
});
</script>
@endsection