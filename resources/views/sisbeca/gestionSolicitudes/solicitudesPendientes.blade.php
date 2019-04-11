@extends('sisbeca.layouts.main')
@section('title','Cambio de status por solicitudes')
@section('content')
<div class="col-lg-12" id="app">

	<div class="table-responsive">
		<div  class="dataTables_wrapper dt-bootstrap4 no-footer">
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
				empty-text ="No hay usuarios pendientes por cambios de status"
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
				<template slot="user" slot-scope="row">
						@{{row.item.nombre}} @{{row.item.apellido}}
				</template>
                <template slot="cedula" slot-scope="row">
					<span>@{{row.item.cedula}}</span>
				</template>
				<template slot="rol" slot-scope="row">
					<span>@{{row.item.rol.toUpperCase()}}</span>
				</template>
				<template slot="status" slot-scope="row">
					<span>@{{row.item.status.toUpperCase()}}</span>
				</template>

				<template slot="statusAprobar" slot-scope="row">
                    <span class="label label-info">@{{row.item.statusAprobar.toUpperCase()}}</span>
				</template>

				<template slot="procesarDesde" slot-scope="row">
					<span v-if="row.item.fechaAprocesar">
						@{{ fechaformatear(row.item.fechaAprocesar) }}
					</span>
                    <span v-else class="label label-default">
						Sin fecha
					</span>

				</template>
				
				<template slot="actions" slot-scope="row">
				
					<a v-b-popover.hover.bottom="'Autorizar Cambio de Status'"  @click.stop="cambiarStatusModal(row.item)" class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-check"></i>
					</a>
						
				</template>
						

			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>

		</div>
	</div>

	<b-modal id="cambioStatusID" hide-header-close ref="cambioStatusRef" @hide="resetModal" :title="'Cambio de Status'" >
	  	¿Estas Seguro que desea autorizar el cambio de Status?
	  <template slot="modal-footer">
				<b-btn size="sm" class="float-right sisbeca-btn-default" variant="sisbeca-btn-default" @click='$refs.cambioStatusRef.hide()'> No</b-btn>
				<b-btn  size="sm" class="float-right sisbeca-btn-primary" @click="cambiarStatus(userSelect)" variant="sisbeca-btn-primary" > Si </b-btn>
	   </template>	
    </b-modal>
	<!-- Cargando.. -->
	<section v-if="isLoading" class="loading" id="preloader">
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
		isLoading: false,
		items:[],
		fields: [
		{ key: 'user', label: 'Nombre y Apellido', sortable: true, 'class': 'text-center' },
		{ key: 'cedula', label: 'Cedula', sortable: true, 'class': 'text-center' },
		{ key: 'rol', label: 'Rol', sortable: true, 'class': 'text-center' },
		{ key: 'status', label: 'Status', sortable: true, 'class': 'text-center' },
		{ key: 'statusAprobar', label: 'Status por Aprobar', sortable: true, 'class': 'text-center' },
		{ key: 'procesarDesde', label: 'Fecha Proc Desde', sortable: true, 'class': 'text-center' },
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
		userSelect: {},
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
	created()
	{
		this.isLoading=true
		this.getSolProcesar()
	},
	methods:
	{
		resetModal() {
        	this.userSelect = {}
		  },
        cambiarStatusModal(item) {

            this.userSelect = item
            this.$refs.cambioStatusRef.show()
		},
		cambiarStatus(user){
			this.$refs.cambioStatusRef.hide()
			this.isLoading = true
            var url = "{{route('cambiar.status.pendiente')}}";
			this.isLoading = true
			var dataform = new FormData();
			let data = JSON.stringify({
				items: {...user}
			}); 
			dataform.append("data", data);
		  	axios.post(url,dataform).then(response => 
			{
				if(response.data.res){
					this.getSolProcesar()
					toastr.success(response.data.msg);
				} else {
					toastr.error(response.data.msg)
				}
			}).catch( error => {
				toastr.error('Ha ocurrido un error inesperado');
				this.isLoading = false
			});
            
			this.user = {}
		},
		onFiltered (filteredItems)
		{
			// Trigger pagination to update the number of buttons/pages due to filtering
			this.totalRows = filteredItems.length
			this.currentPage = 1
		},
		fechaformatear(fecha)
		{
			if(fecha)
			{
				return moment(new Date (fecha)).format('DD/MM/YYYY')
			}
			return '-'
		},
		getSolProcesar()
		{
            var url = "{{route('get.solicitudes.pendientes')}}";
		  	this.isLoading = true
		  	axios.get(url).then(response => 
			{
				this.items = []
				if(response.data.res)
				{
					this.items = response.data.items
				}

				this.isLoading = false
			}).catch( error => {
				toastr.error('Ha ocurrido un error inesperado');
				this.isLoading = false
			});
		},

	}
	
});
</script>
@endsection
