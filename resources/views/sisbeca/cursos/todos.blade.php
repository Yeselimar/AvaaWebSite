@extends('sisbeca.layouts.main')
@section('title','Todos los CVA')
@section('content')
<div class="col-lg-12" id="app">
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
				empty-text ="No hay CVA"
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
				<template slot="fecha_inicio" slot-scope="row">
					@{{ mesanho(row.item.fecha_inicio)}}
				</template>
				<template slot="modulo" slot-scope="row">
					@{{ row.item.modo}} - @{{ row.item.nivel}} - @{{ row.item.modulo}}
				</template>
				<template slot="nota" slot-scope="row">
					@{{ formatearnota(row.item.nota)}}
				</template>
				
				<template slot="aval" slot-scope="row">
					<span v-if="row.value.estatus=='pendiente'" class="label label-warning">pendiente</span>
					<span v-else-if="row.value.estatus=='aceptada'" class="label label-success">aceptada</span>
					<span v-else-if="row.value.estatus=='negada'" class="label label-danger">negada</span>
					<span v-else-if="row.value.estatus=='devuelto'" class="label label-danger">devuelto</span>
				</template>
				
				<template slot="actions" slot-scope="row">
					<a v-b-popover.hover.bottom="'Detalles CVA'" :href="urlDetallesCurso(row.item.id)" class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-eye"></i>
					</a>

					<a v-b-popover.hover.bottom="'Ver Nota'" :href="urlVerNota(row.item.aval.url)" class="btn btn-xs sisbeca-btn-primary" target="_blank">
						<template v-if="row.item.aval.extension=='imagen'">
							<i class="fa fa-photo"></i>
						</template>
						<template v-else>
							<i class="fa fa-file-pdf-o"></i>
						</template>
						
					</a>
					
					<template v-if="row.item.aval.estatus!='aceptada'">
						<a v-b-popover.hover.bottom="'Editar CVA'" :href="urlEditarCurso(row.item.id)" class="btn btn-xs sisbeca-btn-primary">
							<i class="fa fa-pencil"></i>
						</a>
					</template>
					<template v-else>
						<a v-b-popover.hover.bottom="'Editar CVA'" class="btn btn-xs sisbeca-btn-primary" disabled="disabled">
							<i class="fa fa-pencil"></i>
						</a>
					</template>
					
					<template v-if="row.item.aval.estatus!='aceptada'">
						<button v-b-popover.hover.bottom="'Eliminar CVA'" class="btn btn-xs sisbeca-btn-default" @click="modalEliminar(row.item,row.item.becario)">
							<i class="fa fa-trash"></i>
						</button>
					</template>
					<template v-else>
						<button v-b-popover.hover.bottom="'Eliminar CVA'" class="btn btn-xs sisbeca-btn-default" disabled="disabled">
							<i class="fa fa-trash"></i>
						</button>
					</template>
					
					<!--
					<select v-model="row.item.aval.estatus" @change="actualizarestatus(row.item.aval.estatus,row.item.aval.id)" class="sisbeca-input input-sm sisbeca-select">
						<option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
					</select>-->
				</template>

			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>


		</div>
	</div>
	<hr>
	<p class="h6 text-right">@{{cursos.length}} CVA cargados </p>

	<!-- Modal para eliminar curso -->
	<div class="modal fade" id="eliminarcurso">
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
							¿Está seguro que desea eliminar permanentemente el CVA del becario <strong>@{{becario_curso}}</strong> módulo <strong>@{{id_curso}}</strong> ?
						</p>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click="eliminarCurso(id)">Si</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para eliminar curso -->

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
	Vue.use(BootstrapVue);

	const app = new Vue({
	el: '#app',
	data:
	{
		id:0,
		id_curso:'',
		becario_curso:'',
		cursos:[],
		estatus:[],
		seleccionado:'',
		tmp:'',
		items:
		[{
			"id": null,
			"modulo": "",
			"modo": "",
			"nivel":"",
			"fecha_inicio": "",
			"nota": "",
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
		{ key: 'fecha_inicio', label: 'Mes-Año', sortable: true, 'class': 'text-center' },
		{ key: 'modulo', label: 'Modulo-Modo', sortable: true, 'class': 'text-center' },
		{ key: 'nota', label: 'Nota', sortable: true, 'class': 'text-center' },
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
		this.obtenercursos();
		this.obtenercursosapi();
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
		modalEliminar(curso,becario)
		{
			this.id=curso.id;
			this.id_curso=curso.modulo+' nivel'+' - '+curso.modo;
			this.becario_curso=curso.becario;
			$('#eliminarcurso').modal('show');
		},
		eliminarCurso(id)
		{
			var url = '{{route('cursos.eliminarservicio',':id')}}';
			url = url.replace(':id', id);
			axios.get(url).then(response => 
			{
				$('#eliminarcurso').modal('hide');
				toastr.success(response.data.success);
				this.obtenercursosapi();			
			});
		},
		urlVerNota(slug)
		{
			var url = "{{url(':slug')}}";
    		url = url.replace(':slug', slug);
            return url;
		},
		urlEditarCurso(id)
		{
			var url = '{{route('cursos.editar',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		urlDetallesCurso(id)
		{
			var url = '{{route('cursos.detalles',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		obtenercursos: function()
		{
			var url = '{{route('cursos.obtenertodos')}}';
			axios.get(url).then(response => 
			{
				this.cursos = response.data.cursos;
			});
		},
		obtenercursosapi: function()
		{
			var url = '{{route('cursos.obtenertodos.api')}}';
			axios.get(url).then(response => 
			{
				this.items = response.data.cursos;
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
			var dataform = new FormData();
            dataform.append('estatus', estatu);
            var url = '{{route('aval.actualizarestatus',':id')}}';
            url = url.replace(':id', id);
            $("#preloader").show();
			axios.post(url,dataform).then(response => 
			{
				this.obtenercursosapi();
				$("#preloader").hide();
				toastr.success(response.data.success);
			});
		},
		fechaformatear(fecha)
		{
			return moment(new Date (fecha)).format('DD/MM/YYYY hh:mm A');
		},
		formatearnota(nota)
		{
			var Low = parseFloat(nota).toFixed(2);
			return Low;
		},
		mesanho(fecha)
		{
			return moment(new Date (fecha)).format('MM-YYYY');
		}
	}
});
</script>

@endsection
