@extends('sisbeca.layouts.main')
@section('title','Gestión de Entrevista')
@section('content')

<div class="col-lg-12" id="app">
	<br>
	<div class="table-responsive">
		<table class="table table-bordered ">
			<thead>
				<tr>
					<th class="text-center">Estatus</th>
					<th class="text-center">Postulante Becario</th>
					<th class="text-center">Entrevistadores</th>
					<th class="text-center">Fecha Hora Lugar</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="postulante in postulantes">
				<td class="text-center" v-if="postulante.status == 'entrevista'">
					<span class="label label-default">Pendiente</span>
				</td>
				<td class="text-center" v-else-if="postulante.status == 'entrevistado'">
						<span class="label label-success">E.Aprobada</span>
				</td>
				<td class="text-center">@{{ postulante.user.name}} @{{ postulante.user.last_name}}</td>
				<td class="text-center">
					<template v-if="postulante.entrevistadores">
						<template v-for="entrevistador in postulante.entrevistadores">
								@{{entrevistador.name}} @{{ entrevistador.last_name}};&nbsp;
						</template>
					</template>
					<template v-if="postulante.entrevistadores.length==0">
						<span class="label label-default">sin entrevistadores</span>
					</template>
				</td>
				<td class="text-center">
					<div class="col-lg-12">
						<template v-if="postulante.fecha_entrevista!=null">
							<span class="label label-inverse">@{{fechaformartear(postulante.fecha_entrevista)}}</span>
						</template>
						<template v-else>
							<span class="label label-default">Sin Fecha</span>
						</template>
						&nbsp;
						<template v-if="postulante.hora_entrevista!=null">
							<span class="label label-inverse">@{{ horaformatear(postulante.hora_entrevista) }}</span>
						</template>
						<template v-else>
							<span class="label label-default">Sin Hora</span>
						</template>
						&nbsp;
						<br>
						<template v-if="postulante.lugar_entrevista!=null">
							<span class="label label-inverse">@{{ postulante.lugar_entrevista }}</span>
						</template>
						<template v-else>
							<span class="label label-default">Sin Lugar</span>
						</template>
						<br>
						<template v-if="postulante.notificando_entrevista=='1'">
							<p class="h6">Notificado el: @{{fechacompletaformatear(postulante.fecha_notificacion_entrevista)}}</p>
						</template>
						<template v-else>
							<span class="label label-default">Sin notificar</span>
						</template>
					</div>
				</td>
				<td class="text-center">
				<template v-if="postulante.documento_final_entrevista==null">
					<a v-b-popover.hover="'Cargar Resumen Final de Entrevista'" :href="getRutaCargarDocumentoConjunto(postulante.user.id)" class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-upload"></i>
					</a>
				</template>
				<template v-else>
					<a v-b-popover.hover="'Editar Resumen Final de Entrevista'" :href="getRutaEditarDocumentoConjunto(postulante.user.id)" class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-pencil"></i>
					</a>
				</template>
				<template v-if="postulante.status == 'entrevista'">
					<span v-b-popover.hover="'Gestionar datos entrevista'">
						<button type="button" class="btn btn-xs sisbeca-btn-primary"  @click.prevent="mostrarModal(postulante,postulante.entrevistadores)">
							<i class="fa fa-user"></i>
						</button>
					</span>
				</template>
				<template v-else>
					<span v-b-popover.hover="'Este postulante ya fue entrevistado'">
						<button type="button" class="btn btn-xs sisbeca-btn-primary disabled">
							<i class="fa fa-user"></i>
						</button>
					</span>
				</template>

				<template v-if="postulante.status=='entrevista'">
					<span v-b-popover.hover="'Notificar por correo día de la entrevista'">
						<button type="button" class="btn btn-xs sisbeca-btn-primary" @click="modalenviarcorreo(postulante)">
							<i class="fa fa-envelope"></i>
						</button>
					</span>
				</template>
				<template v-else>
					<span v-b-popover.hover="'Notificar por correo día de la entrevista'">
						<button type="button" class="btn btn-xs sisbeca-btn-primary" disabled="disabled">
							<i class="fa fa-envelope"></i>
						</button>
					</span>
				</template>
				</td>
			</tr>
			<tr v-if="postulantes.length==0">
				<td colspan="5" class="text-center">No hay <strong>postulantes a entrevistas</strong></td>
			</tr>
			</tbody>
		</table>
	</div>
	<!-- Modal para añadir entrevistadores -->
	<form method="POST" @submit.prevent="asignarentrevistadores(id,seleccionados)">
	{{ csrf_field() }}
		<div class="modal fade" id="asignarmodal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title pull-left"><strong>Asignar Entrevistadores @{{id}}</strong></h5>
						<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
				    </div>
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0px !important">
								<label class="control-label " for="nombreyapellido" style="margin-bottom: 0px !important">Postulante Becario</label>
								<input type="text" name="nombreyapellido" class="sisbeca-input input-sm sisbeca-disabled" :value="nombreyapellido" style="margin-bottom: 5px" disabled="disabled">
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
								<label class="control-label " style="margin-bottom: 5px !important">Fecha</label>
									<date-picker class="sisbeca-input input-sm" name="fecha" v-model="fecha" placeholder="DD/MM/AAAA" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
							</div>

						  	<div class="col-lg- col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 5px !important">Hora</label>
								<!--<input type="text" autocomplete="off" class="sisbeca-input input-sm" > -->
								<date-picker class="sisbeca-input input-sm" v-model="hora" placeholder="HH:MM AA" placeholder="HH:MM AA" :config="{ enableTime: true, enableSeconds: false, noCalendar: true,  dateFormat: 'h:i K'}"></date-picker>
							</div>

							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0px !important">
								<label class="control-label " style="margin-bottom: 0px !important">Lugar</label>
								<select v-model="lugar" class="sisbeca-input input-sm" style="margin-bottom: 5px">
									<option>Centro Empresarial Miranda, Sede AVAA</option>
								  	<option>MultiCentro Empresarial del Este, Chacao</option>
								</select>
						  	</div>
						</div>

						<div class="row">
							<div class="col-lg-12">
								<label class="control-label" for="nota">Entrevistadores</label>
								<div class="row">
									<template v-if="entrevistadores.length!=0">
										<template v-for="entrevistador in todosentrevistadores">
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
													<div class="checkbox">
														<input type="checkbox" :id="entrevistador.id" :value="entrevistador.id" v-model="seleccionados" >
												  		<label :for="entrevistador.nombre_apellido" class="label label-default">@{{ entrevistador.name}} @{{ entrevistador.last_name}}</label>
													</div>
											  	</div>
										</template>
									</template>
									<template v-else>
										<template v-for="entrevistador in todosentrevistadores">
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
													<div class="checkbox">
														<input type="checkbox" :id="entrevistador.id" :value="entrevistador.id" v-model="seleccionados" >
												  		<label :for="entrevistador.nombre_apellido" class="label label-default">@{{ entrevistador.name}} @{{ entrevistador.last_name}}</label>
													</div>
											  	</div>
										</template>
									</template>

								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cancelar</button>
						<button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- Modal para añadir entrevistadores -->

	<!-- Modal para confirmar al enviar correo-->
	<div class="modal fade" id="enviarcorreo">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left"><strong>Confirmación</strong></h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="col-lg-12">
						<br>
						<p class="h6 text-center">
							¿Está seguro que desea enviar un correo a <strong>@{{nombre_becario_correo}}</strong> y a sus <strong>entrevistadores</strong> notificándole los datos de la entrevista?
						</p>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click="enviarcorreo(id_becario_correo)">Sí</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para confirmar al enviar correo-->

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
	components:{DatePicker},
	el: '#app',
	created: function()
	{
		this.obtenerpostulantes();
		this.obtenerentrevistadores();
	},
	data:
	{
		postulantes:[],
		todosentrevistadores:[],
		entrevistadores:[],
		nombreyapellido:'',
		id:0,
		seleccionados:[],
		fecha:'',
		hora:'',
		lugar:'',
		fechaseleccionada:'',
		id_becario_correo:'',
		nombre_becario_correo:''
	},

	methods:
	{
		fechacompletaformatear: function (fecha)
		{
			var dia = new Date (fecha);
			return moment(dia).format('DD/MM/YYYY hh:mm A');
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
		obtenerpostulantes: function()
		{
			var url = '{{route('becario.obtenerpostulantes')}}';
			axios.get(url).then(response =>
			{
				$("#preloader").hide();
				this.postulantes = response.data.becarios;
			});
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
		mostrarModal: function(postulante,entrevistadores)
		{
			if(postulante.fecha_entrevista!=null)
	        {
				var d = new Date(postulante.fecha_entrevista);
			}
			else
			{
				var d = new Date();
			}
	        var dia = d.getDate();
	        var mes = d.getMonth() + 1;
	        var anho = d.getFullYear();
	        var fecha = dia + "/" + this.zfill(mes,2) + "/" + anho;
	        if(postulante.hora_entrevista!=null)
	        {
	        	var cadena = "2018-11-11 "+postulante.hora_entrevista;
	        }
	        else
	        {
	        	var cadena = "2018-11-11 12:00:00";
	        }
			var diahora = new Date (cadena);
			this.id = postulante.user_id;
			this.fecha = fecha;
			this.nombreyapellido = postulante.user.name+' '+postulante.user.last_name;
			this.entrevistadores = entrevistadores;
			this.seleccionados = [];
	        this.hora = moment(diahora).format('hh:mm A');
	        this.lugar = postulante.lugar_entrevista;
	        //$('#fecha').datepicker('setDate', new Date(anho, d.getMonth(), this.zfill(dia)));
			//$('#fecha').datepicker('update');
			for (var i = 0; i < this.entrevistadores.length; i++)
			{
				this.seleccionados[i] = entrevistadores[i].id;
			}
			$('#asignarmodal').modal('show');
		},
		asignarentrevistadores: function(id,seleccionados)
		{
			this.seleccionados = seleccionados;
			if(this.lugar==null)
			{
				this.lugar="";
			}
			var dataform = new FormData();
        	dataform.append('seleccionados', this.seleccionados);
        	dataform.append('fecha', this.fecha);
            dataform.append('hora', this.hora);
			dataform.append('lugar', this.lugar);
            var url = '{{route('entrevistador.asignar.guardar',':id')}}';
            url = url.replace(':id', id);
            $("#preloader").show();
            $('#asignarmodal').modal('hide');
			axios.post(url,dataform).then(response =>
			{
				$("#preloader").hide();
				this.obtenerpostulantes();
				toastr.success(response.data.success);
			});
		},
		modalenviarcorreo(postulante)
		{
			this.id_becario_correo=postulante.user.id;
			this.nombre_becario_correo=postulante.user.name+' '+postulante.user.last_name;
			$('#enviarcorreo').modal('show');
		},
		enviarcorreo: function(id)
		{
			var url = '{{route('entrevistador.enviarcorreo',':id')}}';
			url = url.replace(':id', id);
			$('#enviarcorreo').modal('hide');
			$("#preloader").show();
			axios.get(url).then(response =>
			{
				$("#preloader").hide();
				this.obtenerpostulantes();
				toastr.success(response.data.success);
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
		},
		obtenerentrevistadores: function()
		{
			var url = '{{route('entrevistador.obtener')}}';
			axios.get(url).then(response =>
			{
				this.todosentrevistadores = response.data.entrevistadores;
			});
		},
		zfill: function(number, width)
		{
			var numberOutput = Math.abs(number); /* Valor absoluto del número */
    		var length = number.toString().length; /* Largo del número */
		    var zero = "0"; /* String de cero */

		    if (width <= length){
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
		}
	},

});
</script>


@endsection