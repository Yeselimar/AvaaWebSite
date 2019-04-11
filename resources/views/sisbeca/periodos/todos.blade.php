@extends('sisbeca.layouts.main')
@section('title','Todos los Periodos')
@section('content')
<div class="col-lg-12" id="app">
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
				<template slot="numero_periodo" slot-scope="row">
					@{{row.item.anho_lectivo }} (@{{row.item.numero_periodo}})
				</template>
				<template slot="numero_materias" slot-scope="row">
					@{{row.item.numero_materias}} materias 
				</template>
				<template slot="promedio_periodo" slot-scope="row">
					@{{formatearpromedio(row.item.promedio_periodo)}}
				</template>
				<template slot="aval" slot-scope="row">
					<span v-if="row.value.estatus=='pendiente'" class="label label-warning">pendiente</span>
					<span v-else-if="row.value.estatus=='aceptada'" class="label label-success">aceptada</span>
					<span v-else-if="row.value.estatus=='negada'" class="label label-danger">negada</span>
					<span v-else-if="row.value.estatus=='devuelto'" class="label label-danger">devuelto</span>
				</template>

				<template slot="actions" slot-scope="row">
					<a v-b-popover.hover.bottom="'Detalles Periodo'" :href="urlDetallesPeriodo(row.item.id)" class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-eye"></i>
					</a>
					
					<a v-b-popover.hover.bottom="'Ver Constancia'" :href="urlVerConstancia(row.item.aval.url)" class="btn btn-xs sisbeca-btn-primary" target="_blank">
						<template v-if="row.item.aval.extension=='imagen'">
							<i class="fa fa-photo"></i>
						</template>
						<template v-else>
							<i class="fa fa-file-pdf-o"></i>
						</template>
						
					</a>
					<template v-if="row.item.aval.estatus!='aceptada'">
						<a v-b-popover.hover.bottom="'Editar Periodo'" :href="urlEditarPeriodo(row.item.id)" class="btn btn-xs sisbeca-btn-primary">
							<i class="fa fa-pencil"></i>
						</a>
					</template>
					<template v-else>
						<a v-b-popover.hover.bottom="'Editar Periodo'" class="btn btn-xs sisbeca-btn-primary" disabled="disabled">
							<i class="fa fa-pencil"></i>
						</a>
					</template>

					<template v-if="row.item.aval.estatus!='aceptada'">
						<button v-b-popover.hover.bottom="'Eliminar Periodo'" class="btn btn-xs sisbeca-btn-default" @click="modalEliminar(row.item,row.item.becario)">
							<i class="fa fa-trash"></i>
						</button>
					</template>
					<template v-else>
						<button v-b-popover.hover.bottom="'Eliminar Periodo'" class="btn btn-xs sisbeca-btn-default" disabled="disabled">
							<i class="fa fa-trash"></i>
						</button>
					</template>
					<!--
					<select v-model="row.item.aval.estatus" @change="actualizarestatus(row.item.aval.estatus,row.item.aval.id)" class="sisbeca-input input-sm sisbeca-select">
						<option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
					</select>
					-->
				</template>

			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>


		</div>
	</div>
	<hr>
	<p class="h6 text-right">@{{periodos.length}} periodo(s) </p>

	<!-- Modal para eliminar periodo -->
	<div class="modal fade" id="eliminarperiodo">
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
							¿Está seguro que desea eliminar permanentemente el periodo el becario <strong>@{{becario_periodo}}</strong> del año lectivo <strong>@{{anho_lectivo}}</strong>?
						</p>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click="eliminarPeriodo(id)">Si</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para eliminar periodo -->

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
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

<script>
	const app = new Vue({

	el: '#app',
	data:
	{
		id:0,
		anho_lectivo:'',
		becario_periodo:'',
		periodos:[],
		estatus:[],
		items:
		[{
			"id": null,
			"numero_periodo": "",
			"anho_lectivo": "",
			"numero_materias": "",
			"promedio_periodo": "",
			"aval": {
				"id": null,
				"url": "",
				"estatus":'',
				"extension":''
			},
			"becario": ""
		}],
		fields: [
		{ key: 'becario', label: 'Becario', sortable: true, 'class': 'text-center' },
		{ key: 'numero_periodo', label: 'Número periodo', sortable: true, 'class': 'text-center' },
		{ key: 'numero_materias', label: 'Materias', sortable: true, 'class': 'text-center' },
		{ key: 'promedio_periodo', label: 'Promedio', sortable: true, 'class': 'text-center' },
		{ key: 'aval', label: 'Estatus', sortable: true, 'class': 'text-center' },
		{ key: 'actions', label: 'Acciones' }
		],
		currentPage: 1,
		perPage: 5,
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
		this.obtenerperiodos();
		this.obtenerperiodosapi();
		this.obtenerestatusaval();
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
		modalEliminar(periodo,becario)
		{
			this.id=periodo.id;
			this.anho_lectivo=periodo.anho_lectivo;
			this.becario_periodo=periodo.becario;
			$('#eliminarperiodo').modal('show');
		},
		eliminarPeriodo(id)
		{
			var url = '{{route('periodos.eliminarservicio',':id')}}';
			url = url.replace(':id', id);
			axios.get(url).then(response => 
			{
				$('#eliminarperiodo').modal('hide');
				toastr.success(response.data.success);
				this.obtenerperiodosapi();			
			});
		},
		formatearpromedio(promedio)
		{
			var Low = parseFloat(promedio).toFixed(2);
			return Low;
		},
		materiaspromedio(materias)
		{
			var suma = 0;
			materias.forEach(function(materia)
			{
			  	suma = suma + materia.nota;
			});
			if(materias.length!=0)
				return (suma/materias.length).toFixed(2);
			else
				return (0).toFixed(2);
		},
		urlVerConstancia(slug)
		{
			var url = "{{url(':slug')}}";
    		url = url.replace(':slug', slug);
            return url;
		},
		urlMostrarMaterias(id)
		{
			var url = '{{route('materias.mostrar',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		urlEditarPeriodo(id)
		{
			var url = '{{route('periodos.editar',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		urlDetallesPeriodo(id)
		{
			var url = '{{route('periodos.detalles',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		obtenerperiodos: function()
		{
			var url = '{{route('periodos.obtenertodos')}}';
			axios.get(url).then(response => 
			{
				this.periodos = response.data.periodos;
			});
		},
		obtenerperiodosapi: function()
		{
			var url = '{{route('periodos.obtenertodos.api')}}';
			axios.get(url).then(response => 
			{
				this.items = response.data.periodos;
				this.totalRows = this.items.length;
				$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
		},
		obtenerestatusaval: function()
		{
			var url = '{{route('aval.getEstatus')}}';
			axios.get(url).then(response => 
			{
				this.estatus = response.data.estatus;
			});	
		},
		actualizarestatus(estatu,id)
		{
			//console.log("Actualiza el "+id+" con "+estatu);
			var dataform = new FormData();
            dataform.append('estatus', estatu);
            var url = '{{route('aval.actualizarestatus',':id')}}';
            url = url.replace(':id', id);
            $("#preloader").show();
			axios.post(url,dataform).then(response => 
			{
				this.obtenerperiodosapi();
				$("#preloader").hide();
				toastr.success(response.data.success);
			});
		},
		fechaformatear(fecha)
		{
			return moment(new Date (fecha)).format('DD/MM/YYYY hh:mm A');
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
		}
	}
});
</script>

@endsection
