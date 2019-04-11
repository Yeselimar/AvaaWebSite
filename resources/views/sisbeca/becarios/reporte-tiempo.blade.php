@extends('sisbeca.layouts.main')
@section('title','Reporte Tiempo')
@section('personalcss')
<style>
	.circulo-rojo
	{
		height: 15px;
		width: 15px;
		border-radius: 25px;
		background-color: #EF5350;
		border:1px solid #EEEEEE;
	}
	.circulo-verde
	{
		height: 15px;
		width: 15px;
		border-radius: 25px;
		background-color: #66BB6A;
		border:1px solid #EEEEEE;
	}
	.circulo-amarillo
	{
		height: 15px;
		width: 15px;
		border-radius: 25px;
		background-color: #FFEE58;
		border:1px solid #EEEEEE;
	}
	.circulo-texto
	{
		font-size: 5px;color:#fff;
	}
</style>
@endsection
@section('content')
<div class="col-lg-12" id="app">
	<div class="text-right">
		<a href="{{route('seguimiento.reportetiempo.excel')}}" class="btn btn-sm sisbeca-btn-primary">
        	<i class="fa fa-file-excel-o"></i> Descargar Excel 
    	</a>
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
				empty-text ="No hay becarios"
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
				
				<template slot="becario" slot-scope="row">
					@{{ row.item.becario.nombreyapellido}} (@{{ row.item.becario.cedula}})
				</template>

				<template slot="tiempo_actividades" slot-scope="row">
					@{{ row.item.tiempo_actividades}}
				</template>

				<template slot="tiempo_cva" slot-scope="row">
					@{{ row.item.tiempo_cva}}
				</template>

				<template slot="tiempo_voluntariado" slot-scope="row">
					@{{ row.item.tiempo_voluntariado}}
				</template>

				<template slot="tiempo_periodos" slot-scope="row">
					@{{ row.item.tiempo_periodos}}
				</template>
				
				<template slot="puntos" slot-scope="row">
					
					<template v-if="row.item.puntos==0">
						<template v-if="row.item.tiempo_actividades=='Nunca' && row.item.tiempo_cva=='Nunca' && row.item.tiempo_voluntariado=='Nunca' && row.item.tiempo_periodos=='Nunca'">
							<div class="circulo-rojo">
								<p class="circulo-texto">@{{ row.item.puntos}}</p>
							</div>
						</template>
						<template v-else>
							<div class="circulo-verde">
								<p class="circulo-texto">@{{ row.item.puntos}}</p>
							</div>
						</template>
					</template>
					<template v-if="row.item.puntos>0 && row.item.puntos<=4">
						<div class="circulo-verde">
							<p class="circulo-texto">@{{ row.item.puntos}}</p>
						</div>
					</template>
					<template v-if="row.item.puntos>4 && row.item.puntos<=8">
						<div class="circulo-verde">
							<p class="circulo-texto">@{{ row.item.puntos}}</p>
						</div>
					</template>
					<template v-if="row.item.puntos>8 && row.item.puntos<=20">
						<div class="circulo-amarillo">
							<p class="circulo-texto">@{{ row.item.puntos}}</p>
						</div>
					</template>
					<template v-if="row.item.puntos>20 && row.item.puntos<=40">
						<div class="circulo-rojo">
							<p class="circulo-texto">@{{ row.item.puntos}}</p>
						</div>
					</template>
					<template v-if="row.item.puntos>40">
						<div class="circulo-rojo">
							<p class="circulo-texto">@{{ row.item.puntos}}</p>
						</div>
					</template>
				</template>
			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>


		</div>
	</div>
	
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
		becarios:[],
		items:
		[{
			"tiempo_actividades": '',
			"tiempo_cva": "",
			"tiempo_voluntariado": "",
			"tiempo_periodos": "",
			"puntos": "",
			"becario": {
				"id": null,
				"cedula": '',
				"nombreyapellido": "",
			},
		}],
		fields: [
		{ key: 'puntos', label: '', sortable: true, 'class': 'text-center'},
		{ key: 'becario', label: 'Becario', sortable: true, 'class': 'text-center' },
		{ key: 'tiempo_actividades', label: 'Talleres/Chat Club', sortable: true, 'class': 'text-center' },
		{ key: 'tiempo_cva', label: 'CVA', sortable: true, 'class': 'text-center' },
		{ key: 'tiempo_voluntariado', label: 'Voluntariado', sortable: true, 'class': 'text-center' },
		{ key: 'tiempo_periodos', label: 'Nota Académica', sortable: true, 'class': 'text-center' },
		//{ key: 'actions', label: 'Acciones' }
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
		this.obtenerbecariosreportetiempo();
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
		obtenerbecariosreportetiempo()
		{
			var url = '{{route('seguimiento.reportetiempo.api')}}';
			axios.get(url).then(response => 
			{
				this.items = response.data.becarios;
				console.log(this.items);
				this.totalRows = this.items.length;
				$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
		}
	}
});
</script>

@endsection