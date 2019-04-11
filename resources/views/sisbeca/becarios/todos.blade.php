@extends('sisbeca.layouts.main')
@section('title','Todos los becarios')
@section('content')
<div class="col-lg-12" id="app">
	<div class="text-right">
		<a href="{{route('becarios.todos')}}" class="btn btn-sm sisbeca-btn-primary">Listar Becarios</a>
	</div>
	<br>
	<div class="table-responsive">
		<div id="becarios_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
			<div class="row">
				<div class="col-sm-12 col-md-6">
					<div class="dataTables_length" style="">
						<label>Mostrar 
							<select aria-controls="dd" v-model="perPage" class="custom-select custom-select-sm form-control form-control-sm">
								<option v-for="(value, key) in pageOptions" :key="key">
									@{{value}}
								</option>
							</select> Entradas</label>
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="dataTables_filter pull-right">
							<b-input-group-append>
								<label>Buscar<input type="search" v-model="filter" class="form-control form-control-sm" placeholder="" >
								</label>
							</b-input-group-append>
						</div>
					</div>
				</div>

				<b-table 
				show-empty
				empty-text ="No hay periodos"
				empty-filtered-text="
				No hay registros que coincidan con su búsqueda"
				class="table table-bordered table-hover dataTable no-footer"
				stacked="md"
				:items="items"
				:fields="fields"
				:current-page="currentPage"
				:per-page="perPage"
				:filter="filter"
				:sort-by.sync="sortBy"
				:sort-desc.sync="sortDesc"
				:sort-direction="sortDirection"
				@filtered="onFiltered"
				>
				<template slot="final_carga_academica" slot-scope="row">
					<div v-if="row.item.final_carga_academica">
						@{{ fechaformatear(row.item.final_carga_academica)}} 
					</div>
					<div v-else>
						<span class="label label-danger">Sin fecha</span>
					</div>
				</template>
				<template slot="estatus" slot-scope="row">
					<span v-if="row.item.estatus=='activo'" class="label label-success">
						@{{row.item.estatus}}
					</span>
					<span v-if="row.item.estatus=='inactivo'" class="label label-danger">
						@{{row.item.estatus}}
					</span>
					<span v-if="row.item.estatus=='egresado'" class="label label-danger">
						@{{row.item.estatus}}
					</span>
					<div v-if="row.item.estatus=='probatorio1'">
						<span class="label label-warning">
							@{{row.item.estatus}}
						</span>
					</div>
					<div v-else-if="row.item.estatus=='probatorio2'">
						<span class="label label-danger">
							@{{row.item.estatus}}
						</span>
					</div>
					
				</template>
				<template slot="actions" slot-scope="row">
					<button v-b-popover.hover.bottom="'Asignar fecha final carga académica'" class="btn btn-xs sisbeca-btn-primary" @click="mostraModalFecha(row.item.id,row.item.becario,row.item.final_carga_academica)">
						<i class="fa fa-calendar"></i>
					</button>
					<select v-model="row.item.estatus" @change="actualizarestatusbecario(row.item.estatus,row.item.id)" class="sisbeca-input input-sm sisbeca-select" style="margin-bottom: 0px !important">
						<option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
					</select>
				</template>

			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>


		</div>
	</div>
	<hr>
	<p class="h6 text-right">@{{items.length}} becario(s)</p>

	<!-- Modal para fecha final carga academica -->
	<div class="modal fade" id="modalFechaFinalCargaAcademica">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left"><strong>Fecha Carga Académica</strong></h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="row">
						<br>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
							<label class="control-label" for="nombreyapellido" style="margin-bottom: 0px !important">Becario</label>
							<input type="text" name="nombreyapellido" class="sisbeca-input input-sm sisbeca-disabled" :value="nombrebecario" style="margin-bottom: 0px" disabled="disabled">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important">Fecha</label>
								<date-picker class="sisbeca-input input-sm" name="fecha" v-model="fecha" placeholder="DD/MM/AAAA" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
						</div>
						<div class="col-lg-12">
						<b-form-checkbox v-model="fecha_nula" value="1" unchecked-value="0">
						    Sin fecha
						</b-form-checkbox>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click="guardarFechaFinalCargaAcademica(id)">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para fecha final carga academica -->

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
    data:
    {
    	id:'',
    	nombrebecario:'',
    	fecha:'',
    	fecha_nula:'',
        becarios:[],
        estatus:[],
        items:
		[{
			"id": null,
			"estatus": "",
			"rol": "",
			"cedula": "",
			"becario": "",
			"final_carga_academica":"",
			"sinasignar":""
		}],
		fields: [
		{ key: 'becario', label: 'Becario', sortable: true, 'class': 'text-center' },
		{ key: 'cedula', label: 'Cédula', sortable: true, 'class': 'text-center' },
		{ key: 'rol', label: 'Rol', sortable: true, 'class': 'text-center' },
		{ key: 'estatus', label: 'Estatus', sortable: true, 'class': 'text-center' },
		{ key: 'final_carga_academica', label: 'Carga Academica', sortable: true, 'class': 'text-center' },
		{ key: 'actions', label: 'Acciones' }
		],
		currentPage: 1,
		perPage: 10,
		totalRows: 0,
		pageOptions: [ 5, 10, 15 ],
		sortBy: null,
		sortDesc: false,
		sortDirection: 'asc',
		filter: null,
		modalInfo: { title: '', content: '' }
    },
    beforeCreate:function()
	{
		$("#preloader").show();
	},
    created: function()
    {
        this.obtenerbecarios();
        this.obtenerestatusbecarios();
    },
    computed:
	{
		sortOptions ()
		{
			// Create an options list from our fields
			return this.fields
			.filter(f => f.sortable)
			.map(f => { return { text: f.label, value: f.key } })
		}
	},
    methods:
    {
    	onFiltered (filteredItems)
		{
			// Trigger pagination to update the number of buttons/pages due to filtering
			this.totalRows = filteredItems.length
			this.currentPage = 1
		},
    	obtenerbecarios()
    	{
    		var url = '{{route('becarios.todos.api')}}';
            axios.get(url).then(response => 
            {
               	this.items = response.data.becarios;
            	$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
    	},
    	obtenerestatusbecarios()
    	{
    		var url = '{{route('obtener.estatus.becarios')}}';
            axios.get(url).then(response => 
            {
               	this.estatus = response.data.estatus;
			}).catch( error => {
				console.log(error);
			});
    	},
    	actualizarestatusbecario(estatus,id)
    	{
    		var url = "{{ route('actualizar.estatus.becario',':id') }}";
    		url = url.replace(':id', id);
            var dataform = new FormData();
            dataform.append('estatus', estatus);
			axios.post(url,dataform).then(response => 
			{
				this.obtenerbecarios();
				toastr.success(response.data.success);
			}).catch( error => {
				console.log(error);
			});
    	},
    	mostraModalFecha(id,becario,fecha)
    	{
    		if(fecha!=null)
	        {
				var dia = new Date(fecha);
			}
			else
			{
				var dia = new Date("2018-01-01");
			}
			this.id = id;
    		this.nombrebecario = becario;
			this.fecha = moment(dia).format('DD/MM/YYYY');
			this.fecha_nula = 0;
    		$('#modalFechaFinalCargaAcademica').modal('show');
    	},
    	guardarFechaFinalCargaAcademica(id)
    	{
    		var url = "{{ route('guardar.fecha.academica',':id') }}";
    		url = url.replace(':id', id);
            var dataform = new FormData();
            dataform.append('fecha', this.fecha);
            dataform.append('fecha_nula', this.fecha_nula);
			axios.post(url,dataform).then(response => 
			{
				this.obtenerbecarios();
				$('#modalFechaFinalCargaAcademica').modal('hide');
				toastr.success(response.data.success);
			}).catch( error => {
				console.log(error);
			});
    	},
    	fechaformatear(fecha)
		{
			return moment(new Date (fecha)).format('DD/MM/YYYY');
		},
    }
});
</script>
@endsection