@extends('sisbeca.layouts.main')
@section('title','Todos los tickets')
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
				empty-text ="No hay tickets"
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
				<template slot="nro" slot-scope="row">
					@{{row.item.nro}}
				</template>
				
				<template slot="usuariogenero" slot-scope="row">
					@{{row.item.usuariogenero}} (@{{row.item.rolgenero}})
				</template>

				<template slot="estatus" slot-scope="row">
		    		<span v-if="row.item.estatus=='Enviado'" class="label label-warning">@{{row.item.estatus}}</span>
					<span v-else-if="row.item.estatus=='En revisión'" class="label label-success">@{{row.item.estatus}}</span>
					<span v-else-if="row.item.estatus=='Cerrado'" class="label label-danger">@{{row.item.estatus}}</span>
					
				</template>

				<template slot="prioridad" slot-scope="row">
					@{{primeramayuscula(row.item.prioridad)}}
				</template>

				<template slot="tipo" slot-scope="row">
					@{{primeramayuscula(row.item.tipo)}}
				</template>

				
				<template slot="created_at" slot-scope="row">
					@{{fechaformatear(row.item.created_at.date)}}
				</template>

				<template slot="actions" slot-scope="row">
					<button v-b-popover.hover.bottom="'Detalles Ticket'" class="btn btn-xs sisbeca-btn-primary" @click="modalDetalles(row.item)">
						<i class="fa fa-eye"></i>
					</button>

					<template v-if="row.item.imagen!=null">
						<a v-b-popover.hover.bottom="'Ver imagen'" :href="urlVerImagen(row.item.imagen)" class="btn btn-xs sisbeca-btn-primary" target="_blank">
							<i class="fa fa-photo"></i>
						</a>
					</template>
					<template v-else>
						<a v-b-popover.hover.bottom="'Ver imagen'" class="btn btn-xs sisbeca-btn-primary" target="_blank" disabled="disabled">
							<i class="fa fa-photo"></i>
						</a>
					</template>
					<a v-b-popover.hover.bottom="'Ver detalles en una pestaña'" :href="urlVerDetalles(row.item.id)" class="btn btn-xs sisbeca-btn-primary" target="_blank">
						<i class="fa fa-external-link"></i>
					</a>
				</template>

			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>


		</div>
	</div>
	<hr>
	<p class="h6 text-right">@{{items.length}} ticket(s) </p>

	<!-- Modal para mostrar detalles Ticket -->
	<div class="modal fade" id="modalDetalles">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left">
			    		<strong>Ticket @{{ticket.nro}}</strong>
			    		<span v-if="ticket.estatus=='Enviado'" class="label label-warning">@{{ticket.estatus}}</span>
						<span v-else-if="ticket.estatus=='En revisión'" class="label label-success">@{{ticket.estatus}}</span>
						<span v-else-if="ticket.estatus=='Cerrado'" class="label label-danger">@{{ticket.estatus}}</span>
			    	</h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Pertenece a</label>
							<input type="text" class="sisbeca-input input-sm sisbeca-disabled" :value="ticket.pertenece" style="margin-bottom: 0px" disabled="disabled">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Cargado el</label>
							<input type="text" class="sisbeca-input input-sm sisbeca-disabled" :value="fechaformatear(ticket.created_at)" style="margin-bottom: 0px" disabled="disabled">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Prioridad</label>
							<input type="text" class="sisbeca-input input-sm sisbeca-disabled" :value="ticket.prioridad" style="margin-bottom: 0px" disabled="disabled">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Tipo</label>
							<input type="text" class="sisbeca-input input-sm sisbeca-disabled" :value="ticket.tipo" style="margin-bottom: 0px" disabled="disabled">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Asunto</label>
							<input type="text" class="sisbeca-input input-sm sisbeca-disabled" :value="ticket.asunto" style="margin-bottom: 0px" disabled="disabled">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">URL</label>
							<input type="text" class="sisbeca-input input-sm sisbeca-disabled" :value="ticket.url" style="margin-bottom: 0px" disabled="disabled">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Descripción</label>
							<textarea class="sisbeca-textarea sisbeca-input sisbeca-disabled" :value="ticket.descripcion" style="margin-bottom: 0px;height: 50px!important" disabled="disabled"></textarea> 
						</div>

					</div>
					<hr>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Respuesta</label>
							<textarea class="sisbeca-textarea sisbeca-input sisbeca-disabled" :value="ticket.respuesta" style="margin-bottom: 0px;height: 50px!important" disabled="disabled">
							</textarea>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 0px !important">
							<label class="control-label " style="margin-bottom: 0px !important;font-size: 12px">Respondido por</label>
							<input type="text" class="sisbeca-input input-sm sisbeca-disabled" :value="ticket.usuariorespuesta" style="margin-bottom: 0px" disabled="disabled">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cerrar</button>
					
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para mostrar detalles Ticket -->

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
		ticket: {
	    },
		items:
		[{
			"id": null,
			"nro": '',
			"estatus": "",
			"prioridad": "",
			"tipo":"",
			"asunto": "",
			"descripcion": "",
			"imagen": "",
			"url": "",
			"respuesta": "",
			"usuariorespuesta": "",
			"usuariogenero": "",
			"rolgenero": "",
			"created_at": "",
			"updated_at": ""
		}],
		fields: [
		{ key: 'nro', label: 'ID', sortable: true, 'class': 'text-center' },
		{ key: 'usuariogenero', label: 'Pertenece a', sortable: true, 'class': 'text-center' },
		{ key: 'estatus', label: 'Estatus', sortable: true, 'class': 'text-center' },
		{ key: 'prioridad', label: 'Prioridad', sortable: true, 'class': 'text-center' },
		{ key: 'tipo', label: 'Tipo', sortable: true, 'class': 'text-center' },
		{ key: 'created_at', label: 'Cargado el', sortable: true, 'class': 'text-center'},
		{ key: 'actions', label: 'Acciones','class': 'text-center'}
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
		this.obtenertodoslostickets();
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
		obtenertodoslostickets()
		{
			var url = '{{route('ticket.todos.servicio')}}';
			axios.get(url).then(response => 
			{
				this.items = response.data.tickets;
				this.totalRows = this.items.length;
				$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
		},
		modalDetalles(t)
		{
			Vue.set(app.ticket, 'id', t.id);
			Vue.set(app.ticket, 'nro', t.nro);
			Vue.set(app.ticket, 'estatus', t.estatus);
			Vue.set(app.ticket, 'prioridad', this.primeramayuscula(t.prioridad));
			Vue.set(app.ticket, 'tipo', this.primeramayuscula(t.tipo));
			Vue.set(app.ticket, 'asunto', t.asunto);
			Vue.set(app.ticket, 'descripcion', t.descripcion);
			Vue.set(app.ticket, 'imagen', t.imagen);
			Vue.set(app.ticket, 'url', t.url);
			Vue.set(app.ticket, 'respuesta', t.respuesta);
			Vue.set(app.ticket, 'usuariorespuesta', t.usuariorespuesta);
			Vue.set(app.ticket, 'usuariogenero', t.usuariogenero);
			Vue.set(app.ticket, 'rolgenero', t.rolgenero);
			Vue.set(app.ticket, 'created_at', t.created_at.date);
			Vue.set(app.ticket, 'updated_at', t.updated_at.date);
			Vue.set(app.ticket, 'pertenece', t.usuariogenero+' ('+t.rolgenero+')');
			$('#modalDetalles').modal('show');
		},
		urlVerImagen(slug)
		{
			var url = "{{url(':slug')}}";
    		url = url.replace(':slug', slug);
            return url;
		},
		urlVerDetalles(id)
		{
			var url = "{{route('ticket.detalles',':id')}}";
    		url = url.replace(':id', id);
            return url;
		},
		primeramayuscula(string)
		{
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
		fechaformatear(fecha)
		{
			return moment(new Date (fecha)).format('DD/MM/YYYY hh:mm A');
		},
	}
});
</script>

@endsection