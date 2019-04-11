@extends('sisbeca.layouts.main')
@section('title','Nóminas Generadas')
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
				empty-text ="No hay nóminas generadas"
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
				<template slot="mes" slot-scope="row">
						@{{row.item.mes}}-@{{row.item.year}}
				</template>

				<template slot="total_becarios" slot-scope="row">
					<span>@{{row.item.total_becarios}}</span>
				</template>

				<template slot="sueldo_base" slot-scope="row">
					<span>@{{formatomoneda(row.item.sueldo_base)}}</span>
				</template>

				<template slot="total_pagado" slot-scope="row">
					<span>@{{formatomoneda(row.item.total_pagado)}}</span> 
				</template>

				<template slot="fecha_generada" slot-scope="row">
					<span v-if="row.item.fecha_generada">
						@{{ fechaformatear(row.item.fecha_generada) }}
					</span>

				</template>
				<template slot="fecha_pago" slot-scope="row">
					<span v-if="row.item.fecha_pago">
						@{{ fechaformatear(row.item.fecha_pago) }}
					</span>
					<span v-else class="label label-default">
						Sin fecha de pago
					</span>

				</template>
				
				
				<template slot="actions" slot-scope="row">
						
				
					<a v-if="row.item.status === 'generado' && row.item.url_edit" v-b-popover.hover.bottom="'Editar Nomina'" :href="row.item.url_edit" @click.stop="isLoading=true" class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-pencil"></i>
					</a>

					<a v-b-popover.hover.bottom="'Generar Nómina en PDF'" target="_blank" :href="row.item.url_pdf" @click.stop="isLoading=true" class="btn btn-xs sisbeca-btn-primary">
						<i class="fa fa-file-pdf-o"></i>
					</a>

					<a v-b-popover.hover.bottom="'Generar Nómina en Excel'" :href="row.item.url_excel"  class="btn btn-xs sisbeca-btn-primary">
							<i class="fa fa-file-excel-o"></i>
					</a>

					<a v-b-popover.hover.bottom="'Detalle de la Nómina'" :href="row.item.url_detalle" @click.stop="isLoading=true" class="btn btn-xs sisbeca-btn-primary">
							<i class="fa fa-eye"></i>
					</a>
					
					<a v-b-popover.hover.bottom="'Pagar Nómina'" :disabled="row.item.url_pagar == null"  @click.stop="nominaPagada(row.item.mes,row.item.year,row.item.url_pagar)" class="btn btn-xs sisbeca-btn-primary">
							<i class="fa fa-plus"></i>
					</a>
						
				</template>
						

			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>

		</div>
	</div>

	<b-modal id="nominaPagadaID" hide-header-close ref="nominaPagadaRef" @hide="resetModal" :title="'Pagar Nomina '+mes+'/'+year" >
	  	¿Estas Seguro que desea pagar la Nomina correspondiente al @{{mes}}/@{{year}} ?
	  <template slot="modal-footer">
				<b-btn size="sm" class="float-right sisbeca-btn-default" variant="sisbeca-btn-default" @click='$refs.nominaPagadaRef.hide()'> No</b-btn>
				<b-btn  size="sm" class="float-right sisbeca-btn-primary" @click="pagarNomina(mes,year,url_pago)" variant="sisbeca-btn-primary" > Si </b-btn>
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
	mounted: function()
	{
		this.showDate = true
	},
	data:
	{
		isLoading: false,
		items:[],
		fields: [
		{ key: 'mes', label: 'Mes-Año', sortable: true, 'class': 'text-center' },
		{ key: 'total_becarios', label: 'N° Becarios', sortable: true, 'class': 'text-center' },
		{ key: 'sueldo_base', label: 'Estipendio Base', sortable: true, 'class': 'text-center' },
		{ key: 'total_pagado', label: 'Total a Pagar', sortable: true, 'class': 'text-center' },
		{ key: 'fecha_generada', label: 'Fecha Generada', sortable: true, 'class': 'text-center' },
		{ key: 'fecha_pago', label: 'Fecha Pagada', sortable: true, 'class': 'text-center' },
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
		mes: '',
		year: '',
		url_pago : ''
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
		this.getListarNominas()
	},
	methods:
	{
		resetModal() {
        	this.mes = ''
					this.year = ''
					this.url_pago= ''
		  },
		nominaPagada(mes,year,url) {
			if(url !== null)
			{
				this.url_pago=url
				this.mes = mes
				this.year = year
				this.$refs.nominaPagadaRef.show()
			}

		},
		pagarNomina(mes,year,url){
			this.$refs.nominaPagadaRef.hide()
			this.isLoading = true
			axios.get(url).then(response => 
			{
				if(response.data.res){
					this.getListarNominas()
					toastr.success('La nomina del '+mes+'/'+year+' ha sido pagada exitosamente!');
				}
			}).catch( error => {
				$("#preloader").hide();
				toastr.error('Ha ocurrido un error inesperado');
				this.isLoading = false
			});
			this.mes = ''
			this.year = ''
			this.url_pago = ''
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
		formatomoneda(monto)
		{
			if(monto)
			{
				return Number(monto).toLocaleString("es-ES", {minimumFractionDigits: 2});
			}
			else 
				return 0
		},
		getListarNominas()
		{
			url = "{{route('listar.nominas.api')}}"
		  	this.isLoading = true
		  	axios.get(url).then(response => 
			{
				if(response.data.res)
				{
					this.items = response.data.nominas
					this.items.forEach(function(nomina,i)
					{
						let url_d = "{{ route('nomina.listar.ver',array('mes'=>':m','anho'=>':y')) }}" 
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_detalle = url_d
						url_d = "{{ route('nomina.generado.pdf',array('mes'=>':m','anho'=>':y')) }}"
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_pdf = url_d
						url_d = "{{ route('nomina.generada.excel',array('mes'=>':m','anho'=>':y')) }}"
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_excel = url_d
						url_d = "{{ route('nomina.pagar',array('mes'=>':m','anho'=>':y')) }}"
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_pagar = url_d
						url_d = "{{ route('consultar.nomina.edit',array('mes'=>':m','anho'=>':y')) }}"
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_edit = url_d
					},this)
					let nominaP = response.data.nominasPagadas
					nominaP.forEach(function(nomina,i)
					{
						let url_d = "{{ route('nomina.listar.pagadas',array('mes'=>':m','anho'=>':y')) }}" 
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_detalle = url_d
						url_d = "{{ route('nomina.pagado.pdf',array('mes'=>':m','anho'=>':y')) }}"
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_pdf = url_d
						url_d = "{{ route('nomina.pagada.excel',array('mes'=>':m','anho'=>':y')) }}"
						url_d=url_d.replace(':m',nomina.mes)
						url_d=url_d.replace(':y',nomina.year)
						nomina.url_excel = url_d

						nomina.url_pagar = null
						nomina.url_edit = null

						this.items.push(nomina)
					},this)

					
				}
				else
				{
					toastr.warning('No existen nominas generadas recientemente');
				}
				this.isLoading = false
				$("#preloader").hide();
			}).catch( error => {
				$("#preloader").hide();
				toastr.error('Ha ocurrido un error inesperado');
				this.isLoading = false
			});
		},
		generarExcel(mes,anho)
		{
			var url = "{{route('nomina.generada.excel',array('mes'=>':mes','anho'=>':anho'))}}";
			url = url.replace(':mes', mes);
			url = url.replace(':anho', anho);
			return url;
		}
	}
	
});
</script>
@endsection
