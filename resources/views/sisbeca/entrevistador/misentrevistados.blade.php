@extends('sisbeca.layouts.main')
@section('title','Postulantes Asignados')
@section('content')

<div class="col-lg-12" id="app">
	<div class="text-right">
		<a href="#" class="btn btn-sm sisbeca-btn-primary" target="_blank">
			<i class="fa fa-file-pdf-o"></i> Descargar Planilla
		</a>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered ">
			<thead>
				<tr>

					<th class="text-center">Postulante</th>
					<th class="text-center">Estatus</th>
					<!--<th class="text-center">Documento Cargado</th>-->
					<th class="text-center">Fecha Hora Lugar</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>

				<tr v-for="postulante in postulantes" v-if="postulante.user.rol != 'becario'">
					<template v-if="postulante.oculto!=1">

					<td class="text-center">@{{ postulante.user.name}} @{{ postulante.user.last_name}}</td>
					<td class="text-center" v-if="postulante.becario.status == 'entrevista'">
						<span class="label label-default">Pendiente</span>
					</td>
					<td class="text-center" v-else-if="postulante.becario.status == 'entrevistado'">
						<span class="label label-success">E.Aprobada</span>
					</td>
					<td class="text-center" v-else-if="postulante.becario.status == 'rechazado'">
						<span class="label label-danger">E.Rechazada</span>
					</td>
					<td class="text-center" v-else-if="postulante.becario.status == 'activo'">
						<span class="label label-success">Becario</span>
					</td>
					<td class="text-center" v-else>
						<span class="label label-default">No Disponible</span>
					</td>

					<td class="text-center">
						<div class="col-lg-12 ">
							<template v-if="postulante.becario.fecha_entrevista!=null">
								<span class="label label-inverse">@{{ fechaformartear(postulante.becario.fecha_entrevista) }}</span>
							</template>
							<template v-else>
								<span class="label label-default">Sin Fecha</span>
							</template>
							&nbsp;
							<template v-if="postulante.becario.hora_entrevista!=null">
								<span class="label label-inverse">@{{ horaformatear(postulante.becario.hora_entrevista) }}</span>
							</template>
							<template v-else>
								<span class="label label-default">Sin Hora</span>
							</template>
							&nbsp;
							<template v-if="postulante.becario.lugar_entrevista!=null">
								<span class="label label-inverse">@{{ postulante.becario.lugar_entrevista }}</span>
							</template>
							<template v-else>
								<span class="label label-default">Sin Lugar</span>
							</template>
						</div>
					</td>
					<td class="text-center">
						<template v-if="postulante.becario.status=='entrevista'">
							<template>
							<button v-b-popover.hover="'Aprobar proceso de entrevista'" class="btn btn-xs sisbeca-btn-success" @click.prevent="mostrarModal(postulante,1)">
								<i class="fa fa-check" data-target="modal-asignar"></i>
							</button>
							</template>
							<template>
								<button v-b-popover.hover="'Rechazar proceso de entrevista'" class="btn btn-xs sisbeca-btn-default" @click="mostrarModal(postulante,0)">
									<i class="fa fa-times" ></i>
								</button>
							</template>
						</template>
						<template v-else>
								<template>
										<button v-b-popover.hover="'Aprobar proceso de entrevista'" class="btn btn-xs sisbeca-btn-success disabled">
											<i class="fa fa-check" data-target="modal-asignar"></i>
										</button>
										</template>
										<template>
											<button v-b-popover.hover="'Rechazar proceso de entrevista'" class="btn btn-xs sisbeca-btn-default disabled">
												<i class="fa fa-times" ></i>
											</button>
										</template>
						</template>

						<template>
							<a :href="getRutaVerPerfil(postulante.user.id)" class='btn btn-xs sisbeca-btn-primary' v-b-popover.hover="'Ver Perfil'">
								<i class="fa fa-eye"></i>
							</a>
						</template>
						<template v-if="postulante.becario.status=='entrevista' || postulante.becario.status=='entrevistado'">
							<template v-if="postulante.documento==null">
								<a v-b-popover.hover="'Cargar Resumen de Entrevista'" :href="getRutaCargarDocumento(postulante.user.id)" class="btn btn-xs sisbeca-btn-primary">
									<i class="fa fa-upload"></i>
								</a>
							</template>
							<template  v-else>
								<a v-b-popover.hover="'Editar Resumen de Entrevista'" :href="getRutaEditarDocumento(postulante.user.id)" class="btn btn-xs sisbeca-btn-primary">
									<i class="fa fa-pencil"></i>
								</a>
							</template>
						</template>
						<template v-else>
								<template v-if="postulante.documento==null">
										<a v-b-popover.hover="'Cargar Resumen de Entrevista'"  class="btn btn-xs sisbeca-btn-primary disabled">
											<i class="fa fa-upload"></i>
										</a>
									</template>
									<template  v-else>
										<a v-b-popover.hover="'Editar Resumen de Entrevista'"  class="btn btn-xs sisbeca-btn-primary disabled">
											<i class="fa fa-pencil"></i>
										</a>
									</template>
						</template>

						<template>
							<button v-b-popover.hover="'Ocultar de mi lista'" class="btn btn-xs sisbeca-btn-primary" @click="ocultardemilista(postulante)">
	                            <i class="fa fa-eye-slash" ></i>
	                        </button>
						</template>


						<!-- <template v-if="postulante.becario.documento_final_entrevista==null">
							<a title="Cargar Resumen Entrevista Grupal" :href="getRutaCargarDocumentoConjunto(postulante.user.id)" class="btn btn-xs sisbeca-btn-primary">
								<i class="fa fa-upload"></i>
							</a>
						</template>
						<template v-else>
							<a title="Editar Resumen Entrevista Grupal" :href="getRutaEditarDocumentoConjunto(postulante.user.id)" class="btn btn-xs sisbeca-btn-primary">
								<i class="fa fa-pencil"></i>
							</a>
						</template> -->

						</template><!-- Etiqueta de cierre del template de oculto-->
					</td>
				</tr>
				<tr v-if="postulantes.length==0">
					<td colspan="5" class="text-center">No hay <strong>postulantes</strong></td>
				</tr>
			</tbody>
		</table>
		<hr>
		<p class="text-right">@{{postulantes.length}} Postulante(s)</p>
	</div>

	<!-- Modal para Marcar como entrevistado -->
	<form method="POST" @submit.prevent="marcarentrevistado(id)">
		{{ csrf_field() }}
		<div class="modal fade" id="modal-asignar">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title pull-left"><strong>Cambiar Estatus de la Entrevista</strong></h5>
						<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
					</div>
					<div class="modal-body">
						<br>
						<h5>¿Está seguro que desea <strong>cerrar el proceso de entrevista</strong> para el postulante <strong>@{{nombreyapellido}}?</strong>
						</h5>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >No</button>
						<button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Sí</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- Modal para Marcar como entrevistado -->

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
	created: function()
	{
		this.obtenerentrevistados();
	},
	data:
	{
		postulantes:[],
		imagenes:[],
        imagen_postulante:'',
		id:'0',
        nombreyapellido:'',
		funcion:'',
	},
	methods:
	{
		getRutaVerPerfil: function(id)
		{
			//var url = "{{route('entrevistador.documentoconjunto',array('id'=>':id'))}}";
			console.log(this.id);
			var url ="{{route('perfilPostulanteBecario', array('id'=>':id'))}}"
			url = url.replace(':id', id);
            return url;
		},
		getRutaCargarDocumentoConjunto: function(id)
		{
			var url = "{{route('entrevistador.documentoconjunto',array('id'=>':id'))}}";
			url = url.replace(':id', id);
            return url;
		},
		getRutaEditarDocumentoConjunto:function(id)
		{
			var url = "{{route('entrevistador.editardocumentoconjunto',array('id'=>':id'))}}";
			url = url.replace(':id', id);
            return url;
		},
		getRutaCargarDocumento: function(id)
		{
			var url = "{{route('entrevistador.cargardocumento',array('id'=>':id'))}}";
			url = url.replace(':id', id);
            return url;
		},
		getRutaEditarDocumento: function(id)
		{
			var url = "{{route('entrevistador.editardocumento',array('id'=>':id'))}}";
			url = url.replace(':id', id);
            return url;
		},
		obtenerentrevistados:function()
		{
			var url = '{{route('lista.Entrevistas.Postulantes')}}';
			axios.get(url).then(response =>
			{
				$("#preloader").hide();
				this.postulantes = response.data.becarios;
			});
		},
		marcarentrevistado:function(id)
		{
			var url = '{{route('fue.A.Entrevista')}}';
			$('#modal-asignar').modal('hide');
			$("#preloader").show();
			axios.post(url,{
				id:this.id,
				funcion:this.funcion,
				}).then(response=>{
				$("#preloader").hide();
				this.obtenerentrevistados();
				toastr.success(response.data.success);
			});
		},
		mostrarModal:function(postulante, funcion)
		{
			this.id = postulante.user.id;
			if(funcion=='1'){
				this.funcion='Aprobar';
			}else if(funcion=='0'){
				this.funcion='Rechazar';
			}
			console.log('ID2:');
			console.log(this.id);
			this.nombreyapellido = postulante.user.name+' '+postulante.user.last_name;
			$('#modal-asignar').modal('show');
		},
		ocultardemilista(postulante)
		{
			var e_id = '{{Auth::user()->id}}';
			var url = '{{route('entrevistador.ocultar.de.lista',array('b_id'=>':b_id','e_id'=>':e_id'))}}';
			url = url.replace(':b_id', postulante.user.id);
			url = url.replace(':e_id', e_id);
			$("#preloader").show();
			axios.get(url).then(response =>
			{
				$("#preloader").hide();
				this.obtenerentrevistados();
				toastr.success(response.data.success);
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
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
		}
	}
});
</script>
<script>
	$(document).ready(function(){
	$('[data-toggle="popover"]').popover();
	});
</script>
@endsection