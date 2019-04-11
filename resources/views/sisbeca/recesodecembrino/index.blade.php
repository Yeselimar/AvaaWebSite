@extends('sisbeca.layouts.main')
@section('title','Receso Decembrino')
@section('content')
<div class="col-lg-12" id="app">
	<div class="text-right">
	</div>
	<br>
	<div class="table-response">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
					<th class="text-center">Estatus</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<template v-if="receso!=null">
						<td>@{{fechaformartear(receso.fecha_inicio)}}</td>
						<td>@{{fechaformartear(receso.fecha_fin)}}</td>
						<td class="text-center">
							<span v-if="receso.activo==1" class="label label-success">
                                activo
                            </span>

                            <span v-if="receso.activo==0" class="label label-danger">
                                inactivo
                            </span>
						</td>
						<td class="text-center">
							<button v-b-popover.hover.bottom="'Editar Receso Decembrino'" class="btn btn-xs sisbeca-btn-primary" @click="modalEditar()">
	                            <i class="fa fa-pencil"></i>
	                        </button>

	                        <a  @click="cambiar()">
	                        	<template v-if="receso.activo==0">
	                        		<i class="fa fa-toggle-off"></i>
	                        	</template>
	                            <template v-else>
	                            	<i class="fa fa-toggle-on"></i>
	                            </template>
	                        </a>
						</td>
					</template>
					<template v-else>
						<td colspan="4" class="text-center">No hay <strong>receso decembrino</strong></td>
					</template>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Modal para editar-->
	<div class="modal fade" id="modalEditar">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left"><strong>Receso Decembrino</strong></h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<label class="control-label " style="margin-bottom: 5px !important">Fecha Inicio</label>
								<date-picker class="sisbeca-input input-sm" name="fecha" v-model="fecha_inicio" placeholder="DD/MM/AAAA" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<label class="control-label " style="margin-bottom: 5px !important">Fecha Fin</label>
								<date-picker class="sisbeca-input input-sm" name="fecha" v-model="fecha_fin" placeholder="DD/MM/AAAA" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click="guardarReceso()">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para editar-->

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
<script type="text/javascript">

	const app = new Vue({
	components:{DatePicker},
    el: '#app',
    data:
    {
    	fecha_inicio:'',
    	fecha_fin:'',
    	receso:{},
    },
    created: function()
    {
        this.obtenerreceso();
    },
    methods:
    {
    	obtenerreceso()
    	{
			var url = '{{route('receso.decembrino.servicio')}}';
			axios.get(url).then(response => 
			{
				var t = response.data.receso;
				if(t!=null)
				{
					Vue.set(app.receso, 'id', t.id);
					Vue.set(app.receso, 'fecha_inicio', t.fecha_inicio);
					Vue.set(app.receso, 'fecha_fin', t.fecha_fin);
					Vue.set(app.receso, 'activo', t.activo);
				}
				else
				{
					this.receso=null;
				}
				$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
    	},
    	modalEditar()
    	{
    		var dia = new Date (this.receso.fecha_inicio);
            this.fecha_inicio = moment(dia).format('DD/MM/YYYY');
            var dia = new Date (this.receso.fecha_fin);
            this.fecha_fin = moment(dia).format('DD/MM/YYYY');
    		$('#modalEditar').modal('show');
    	},
    	guardarReceso()
    	{
    		$('#modalEditar').modal('hide');
            $("#preloader").show();
            var url = '{{route('receso.decembrino.guardar')}}';
            var dataform = new FormData();
            dataform.append('fecha_inicio', this.fecha_inicio);
            dataform.append('fecha_fin', this.fecha_fin);
            axios.post(url,dataform).then(response => 
            {
                this.obtenerreceso();
                $("#preloader").hide();
                toastr.success(response.data.success);
            }).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
    	},
    	cambiar()
    	{
    		$("#preloader").show();
    		var url = '{{route('receso.decembrino.cambiar')}}';
			axios.get(url).then(response => 
			{
				$("#preloader").hide();
				this.obtenerreceso();
                toastr.success(response.data.success);
            }).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
    	},
    	fechaformartear: function (fecha)
		{
			return moment(new Date(fecha)).format('DD/MM/YYYY');
		}
    }
});
</script>
@endsection