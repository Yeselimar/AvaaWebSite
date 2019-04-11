@extends('sisbeca.layouts.main')
@section('title','Becarios con facturas pendientes')
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
				empty-text ="No hay becarios con facturas pendientes"
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
				<template slot="estatus_becario" slot-scope="row">
					<span v-if="row.item.estatus_becario=='probatorio1'" class="label label-warning"> En Probatorio 1</span>
					<span v-else-if="row.item.estatus_becario=='activo'" class="label label-success">Activo</span>
					<span v-else-if="row.item.estatus_becario=='egresado'" class="label label-info">En Probatorio 2</span>
					<span v-else-if="row.item.estatus_becario=='probatorio2'" class="label label-danger">Egresado</span>
				</template>

				<template slot="total_pendientes" slot-scope="row">
					@{{row.item.total_pendientes}}
				</template>

				<template slot="actions" slot-scope="row">
					<a v-b-popover.hover.bottom="'Detalles'" @click.stop="row.toggleDetails"class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-eye" v-if="!row.detailsShowing"></i> <i v-else class=" fa fa-eye-slash"></i>
					</a>
					
				</template>

				<template slot="row-details" slot-scope="row">
					<div class="table-responsive">
						<table  class="table striped">
							<thead>
								<tr>
									<th class="text-left">Nombre del Libro</th>
									<th class="text-left">Curso</th>
									<th class="text-left">Costo</th>
									<th class="text-left">Estatus</th>
									<th class="text-center">Acciones</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="factura in row.item.facturas">
									<td class="text-left">@{{factura.name}}</td>
									<td class="text-left">@{{factura.curso}}</td>
									<td class="text-left">@{{formatomoneda(factura.costo)}}</td>
									<td class="text-left">
										<span v-if="factura.status=='por procesar'" class="label label-primary">Aprobada</span>
										<span v-else-if="factura.status=='procesada'" class="label label-success">Procesada</span>
										<span v-else-if="factura.status=='cargada'" class="label label-info">Pendiente</span>
										<span v-else-if="factura.status=='rechazada'" class="label label-danger">Rechazada</span>
										<span v-else-if="factura.status=='revisada'" class="label label-danger">Revisada</span>
										<span v-else-if="factura.status=='pagada'" class="label label-danger">Pagada</span>
										<template v-if="factura.status=='pagada'">
											<p class="h6">@{{fechaformatear(factura.fecha_pagada)}}</p>
										</template>
										<template v-if="factura.status=='cargada'">
											<p class="h6">@{{fechaformatear(factura.fecha_cargada)}}</p>
										</template>
										<template v-if="factura.status=='por procesar'">
											<p class="h6">@{{fechaformatear(factura.fecha_procesada)}}</p>
										</template>
									</td>
									<td class="text-center">
										<a :href="verArchivo(factura.url)" target="_blank" class="btn btn-xs sisbeca-btn-primary">
											<i class="fa fa-file-pdf-o"></i>
										</a>
										<template v-if="factura.status!='pagada' && factura.status!='procesada' &&  factura.status!='revisada'">
											<button v-b-popover.hover.bottom="'Actualizar Factura'" class="btn btn-xs sisbeca-btn-primary" @click="habilitara(factura)">
												<i class="fa fa-edit"></i>
											</button>
										</template>
										<template v-else>
											<button v-b-popover.hover.bottom="'Actualizar Factura'" class="btn btn-xs sisbeca-btn-primary" disabled>
												<i class="fa fa-edit"></i>
											</button>
										</template>
										
										<template v-if="habilitar==1 && factura.status!='pagada' && factura.status!='procesada' && factura.status!='revisada'">
											
											<select v-model="estatus_factura" class="sisbeca-input input-sm sisbeca-select" @change="actualizarestatus(factura.id,estatus_factura)">
												<option value="cargada">Pendiente</option>
												<option value="por procesar" >Aprobada</option>
												<option value="rechazada">Rechazada</option>
											</select>
										</template>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</template>
			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>


		</div>
	</div>
	<br>
	<p class="text-right h6">@{{items.length}} becarios</p>

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
	Vue.use(BootstrapVue);

	const app = new Vue({

		el: '#app',
		data:
		{
			estatus_factura:'',
			habilitar:0,
			estatus:[],
			items:
			[{
				"id": null,
				"estatus_becario": "",
				"nombre_becario": "",
				"total_pendientes": "",
				"facturas": []
			}],
			fields: [
			{ key: 'nombre_becario', label: 'Becario', sortable: true, 'class': 'text-center' },
			{ key: 'estatus_becario', label: 'Estatus', sortable: true, 'class': 'text-center' },
			{ key: 'total_pendientes', label: '# F. Pendiente', sortable: true, 'class': 'text-center' },
			{ key: 'actions', label: 'Acciones', 'class': 'text-center' }
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
		created: function()
		{
			this.obtenerbecarios();
			this.habilitar=0;
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
				var url = '{{route('modulo.facturas.pendientes.servicio')}}';
				axios.get(url).then(response => 
				{
					this.items = response.data.becarios;
					this.totalRows = this.items.length;
					$("#preloader").hide();
				}).catch( error => {
					console.log(error);
					$("#preloader").hide();
				});
			},
			formatomoneda(monto)
			{
				return Number(monto).toLocaleString("es-ES", {minimumFractionDigits: 2});
			},
			verArchivo(slug)
			{
				var url = "{{url(':slug')}}";
	    		url = url.replace(':slug', slug);
	            return url;
			},
			habilitara(factura)
			{
				this.estatus_factura =  factura.status;
				if(this.habilitar==1)
				{
					this.habilitar=0;
				}
				else
				{
					this.habilitar=1;
				}
			},
			actualizarestatus(id,estatus)
			{
				this.habilitar=0;
				var dataform = new FormData();
	            dataform.append('status', estatus);
	            var url = '{{route('modulo.facturas.actualizar.servicio',':id')}}';
	            url = url.replace(':id', id);
	            $("#preloader").show();
				axios.post(url,dataform).then(response => 
				{
					this.obtenerbecarios();
					$("#preloader").hide();
					toastr.success(response.data.success);
				}).catch( error => {
					console.log(error);
					toastr.error('Disculpe, se generó un error.');
				});
			},
			fechaformatear(fecha)
			{
				return moment(new Date (fecha)).format('DD/MM/YYYY hh:mm A');
			}
		}
	});
</script>
@endsection