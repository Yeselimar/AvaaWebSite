@extends('sisbeca.layouts.main')
@section('title','Reporte General')
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
		<a target="_blank" :href="descargarpdf(anho,mes)" class="btn btn-sm sisbeca-btn-primary">
        	<i class="fa fa-file-pdf-o"></i> PDF (@{{obtenermescompleto(mes)}}-@{{anho}})
    	</a>
    	<a :href="descargarexcel(anho,mes)" class="btn btn-sm sisbeca-btn-primary">
        	<i class="fa fa-file-excel-o"></i> Excel (@{{obtenermescompleto(mes)}}-@{{anho}})
    	</a>
	</div>
	<br>
	<div class="row" style="border:1px solid #fff">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<select class="form-control sisbeca-input" v-model="mes">
			  	<option disabled value="">Mes</option>
			  	<option value="00">Todos</option>
			  	<option value="01">Enero</option>
			  	<option value="02">Febrero</option>
			  	<option value="03">Marzo</option>
			  	<option value="04">Abril</option>
			  	<option value="05">Mayo</option>
			  	<option value="06">Junio</option>
			  	<option value="07">Julio</option>
			  	<option value="08">Agosto</option>
			  	<option value="09">Septiembre</option>
			  	<option value="10">Octubre</option>
			  	<option value="11">Noviembre</option>
			  	<option value="12">Diciembre</option>
			</select>
			<!--<span>Año: @{{ mes }}</span>-->
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<select class="form-control sisbeca-input" v-model="anho">
			  	<option disabled value="">Año</option>
			  	<option>2019</option>
			  	<option>2018</option>
			  	<option>2017</option>
			</select>
			<!--<span>Año: @{{ anho }}</span>-->
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<button @click="consultabecariosreportegeneral(anho,mes)" class="btn btn-md sisbeca-btn-primary btn-block" style="line-height: 1.80 !important">Consultar</button>
		</div>
	</div>
	
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
					@{{ row.item.becario.nombreyapellido}} (@{{ row.item.nivel_carrera}})
				</template>

				<template slot="actions" slot-scope="row">

					<a v-b-popover.hover.bottom="'Ver Taller/Chat Club'" class="btn btn-xs sisbeca-btn-primary" :href="verActividades(row.item.becario.id)">
						<i class="fa fa-commenting-o"></i>
					</a>
					<a v-b-popover.hover.bottom="'Ver Periodos'" class="btn btn-xs sisbeca-btn-primary" :href="verPeriodos(row.item.becario.id)">
						<i class="fa fa-sticky-note-o"></i>
					</a>
					<a v-b-popover.hover.bottom="'Ver CVA'" class="btn btn-xs sisbeca-btn-primary"  :href="verCVA(row.item.becario.id)">
						<i class="fa fa-book"></i>
					</a>
					<a v-b-popover.hover.bottom="'Ver Voluntariados'" class="btn btn-xs sisbeca-btn-primary" :href="verVoluntariados(row.item.becario.id)">
						<i class="fa fa-star"></i>
					</a>
					
				</template>
				
			</b-table>

			<b-row class="my-0 pull-right" >
				<b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
			</b-row>


		</div>
	</div>
	<hr>
	<p>Falta average de desempeño</p>
	<!--
	<div class="table-responsive">
		<table class="table table-hover table-bordered" id="reporte">
			<thead>
				<tr>
					<th>Becario</th>
					<th class="text-center">Año o Semestre</th>
					<th class="text-center">H. Vol.</th>
					<th class="text-center"># Taller</th>
					<th class="text-center"># Chat</th>
					<th class="text-center">CVA</th>
					<th class="text-center">AVG CVA</th>
					<th class="text-center">AVG Acad.</th>
					<th>AVG Desemp.</th>
				</tr>
			</thead>
			<tbody>
				@foreach($becarios as $becario)
				<tr>
					<th>
						{{$becario->user->nombreyapellido()}}
						<br>
						{{$becario->user->cedula}}
					</th>
					<th class="text-center">{{$becario->getAnhoSemestreCarrera()}}</th>
					<th class="text-center">{{$becario->getHorasVoluntariados()}}</th>
					<th class="text-center">{{$becario->getTotalTalleres()}}</th>
					<th class="text-center">{{$becario->getTotalChatClubs()}}</th>
					<th class="text-center">{{$becario->getNivelCVA()}}</th>
					<th class="text-center">{{$becario->promediotodoscva()}}</th>
					<th class="text-center">{{$becario->promediotodosperiodos()}}</th>
					<th></th>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	-->
	
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
<!--
<script>
$(document).ready(function() {
    $('#reporte').DataTable({

        "language": {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar",
        "zeroRecords": "No hay resultados encontrados",
        "paginate":
            {
                "first": "Primero",
                "last": "Ultimo",
                "next": '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                "previous": '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
            }
        }
    });
});
</script>
-->
<script>
	Vue.use(BootstrapVue);
	const app = new Vue({

	el: '#app',
	data:
	{
		anho:'',
		mes:'',
		becarios:[],
		items:
		[{
			"nivel_carrera": '',
			"horas_voluntariados": "",
			"asistio_t": "",
			"asistio_cc": "",
			"nivel_cva": "",
			"avg_cva": "",
			"avg_academico": "",
			"becario": {
				"id": null,
				"nombreyapellido": "",
			},
		}],
		fields: [
		{ key: 'becario', label: 'Becario', sortable: true, 'class': 'text-center' },
		{ key: 'horas_voluntariados', label: 'H. Vol', sortable: true, 'class': 'text-center' },
		{ key: 'asistio_t', label: '# Taller', sortable: true, 'class': 'text-center' },
		{ key: 'asistio_cc', label: '# Chat', sortable: true, 'class': 'text-center' },
		{ key: 'nivel_cva', label: 'CVA', sortable: true, 'class': 'text-center' },
		{ key: 'avg_cva', label: 'AVG CVA', sortable: true, 'class': 'text-center' },
		{ key: 'avg_academico', label: 'AVG Acad.', sortable: true, 'class': 'text-center' },
		{ key: 'actions', label: 'Acciones', 'class': 'text-center'}
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
		this.obtenerbecariosreporte();
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
		obtenerbecariosreporte()
		{
			var url = '{{route('becarios.reporte.general.api',array('anho'=>':anho','mes'=>':mes'))}}';
			var anho = '{{date('Y')}}';
			var mes = '00';
			this.anho=anho;
			this.mes=mes;
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
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
		consultabecariosreportegeneral(anho,mes)
		{
			var url = '{{route('becarios.reporte.general.api',array('anho'=>':anho','mes'=>':mes'))}}';
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
			$("#preloader").show();
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
		descargarpdf(anho,mes)
		{
			var url = '{{route('becarios.reporte.general.pdf',array('anho'=>':anho','mes'=>':mes'))}}'
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
			return url;
		},
		descargarexcel(anho,mes)
		{
			var url = '{{route('becarios.reporte.general.excel',array('anho'=>':anho','mes'=>':mes'))}}'
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
			return url;
		},
		verActividades(id)
		{
			var url = '{{route('actividades.becario',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		verPeriodos(id)
		{
			var url = '{{route('periodos.becario',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		verCVA(id)
		{
			var url = '{{route('cursos.becario',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		verVoluntariados(id)
		{
			var url = '{{route('voluntariados.becario',':id')}}';
			url = url.replace(':id', id);
			return url;
		},
		obtenermescompleto(mes)
		{
			switch(mes)
			{
				case '00':
			    	return "Todos";
			    break;
			 	case '01':
			    	return "Enero";
			    break;
			  	case '02':
			    	return "Febrero";
			    break;
			    case '03':
			    	return "Marzo";
			    break;
			    case '04':
			    	return "Abril";
			    break;
			    case '05':
			    	return "Mayo";
			    break;
			    case '06':
			    	return "Junio";
			    break;
			    case '07':
			    	return "Julio";
			    break;
			    case '08':
			    	return "Agosto";
			    break;
			    case '09':
			    	return "Septiembre";
			    break;
			    case '10':
			    	return "Octubre";
			    break;
			    case '11':
			    	return "Noviembre";
			    break;
			    case '12':
			    	return "Diciembre";
			    break;
			}
		}
	}

});
</script>
@endsection