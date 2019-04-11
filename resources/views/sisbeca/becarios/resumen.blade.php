@extends('sisbeca.layouts.main')
@section('title','Resumen Becario: '.$becario->user->nombreyapellido())
@section('personalcss')
<style>
	.ancho-sisbeca
	{
		width: 50px;
	}
	.cabecera-sisbeca
	{
		background-color: #eee;
	}
</style>
@endsection
@section('content')
<div class="col-lg-12" id ="app">
	<div class="text-right">
		<!--<a href="{{route('seguimiento.resumen.pdf',$becario->user->id)}}" class="btn btn-sm sisbeca-btn-primary">
        	<i class="fa fa-file-pdf-o"></i> PDF
    	</a>-->
    	<a target="_blank" :href="descargarpdf(anho,mes)" class="btn btn-sm sisbeca-btn-primary">
        	<i class="fa fa-file-pdf-o"></i> PDF (@{{obtenermescompleto(mes)}}-@{{anho}})
    	</a>
    	<a :href="descargarexcel(anho,mes)" class="btn btn-sm sisbeca-btn-primary">
        	<i class="fa fa-file-excel-o"></i> Excel (@{{obtenermescompleto(mes)}}-@{{anho}})
    	</a>
		<a href="{{URL::previous()}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
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
			  	<option>2016</option>
			</select>
			<!--<span>Año: @{{ anho }}</span>-->
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<button @click="consultarreportegeneral(anho,mes)" class="btn btn-md sisbeca-btn-primary btn-block" style="line-height: 1.80 !important">Consultar</button>
		</div>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="4" class="cabecera-sisbeca">VOLUNTARIADO</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left"><strong>Año</strong></td>
					<td class="text-left"><strong>Tipo Voluntariado</strong></td>
					<td class="text-left"><strong>Total</strong></td>
					<td class="text-right"><strong>Horas Voluntariado</strong></td>
				</tr>
				<template v-if="voluntariados.length!=0">
					<tr v-for="(voluntariado, index) in voluntariados">
						<td class="text-left">
							@{{obtenermes(voluntariado.fecha)}}-@{{obteneranho(voluntariado.fecha)}}
						</td>
						<td class="text-left">
							@{{primeraLetraEnMayusculas(voluntariado.tipo_voluntariado)}}
						</td>
						<td class="text-left">
							@{{voluntariado.total_voluntariado}}
						</td>
						<td class="text-right">
							@{{voluntariado.horas_voluntariado}}
						</td>
					</tr>
				</template>
				<template v-else>
					<tr>
						<td colspan="4" class="text-center">No hay <strong>voluntariados</strong></td>
					</tr>
				</template>

				<template v-if="actividades_facilitadas.id!=null">
					<tr>
						<td class="text-left">
							@{{obtenermes(actividades_facilitadas.created_at)}}-@{{obteneranho(actividades_facilitadas.created_at)}}
						</td>
						<td class="text-left">
							Facilitador en Chat Club
						</td>
						<td class="text-left">
							@{{actividades_facilitadas.total_actividades}}
						</td>
						<td class="text-right">
							@{{actividades_facilitadas.horas_voluntariado}}
						</td>
					</tr>
				</template>
				<template v-else>
					<tr>
						<td colspan="4" class="text-center">No ha sido facilitador de <strong>ningún Chat Club</strong></td>
					</tr>
				</template>
				<tr>
					<td colspan="2"></td>
					<td class="text-right"><strong>Total Horas</strong></td>
					<td class="text-right"><strong>@{{ total_horas_voluntariados(voluntariados,actividades_facilitadas) }}</strong></td>
				</tr>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="4" class="cabecera-sisbeca">CVA</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left"><strong>Año</strong></td>
					<td class="text-left"><strong>Nivel/Módulo</strong></td>
					<td class="text-left"><strong>Total por Nivel</strong></td>
					<td class="text-right"><strong>Promedio</strong></td>
				</tr>
				<template v-if="cursos.length!=0">
					<tr v-for="(curso, index) in cursos">
						<td class="text-left">
							@{{obtenermes(curso.fecha_inicio)}}-@{{obteneranho(curso.fecha_inicio)}}
						</td>
						<td class="text-left">
							<template v-if="curso.nivel=='basico'">
								Básico
							</template>
							<template v-if="curso.nivel=='intermedio'">
								Intermedio
							</template>
							<template v-if="curso.nivel=='avanzado'">
								Avanzado
							</template>
							<template v-if="curso.total_modulo==1">
								@{{curso.modulo}}
							</template>
						</td>
						<td class="text-left">
							@{{curso.total_modulo}}
						</td>
						<td class="text-right">
							@{{curso.promedio_modulo.toFixed(2)}}
						</td>
					</tr>
				</template>
				<template v-else>
					<tr>
						<td colspan="4" class="text-center">No hay <strong>CVA</strong></td>
					</tr>
				</template>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="5" class="cabecera-sisbeca">NOTAS ACADÉMICAS</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left"><strong>Año</strong></td>
					<td class="text-left"><strong>Número Periodo</strong></td>
					<td class="text-left"><strong>Año Lectivo</strong></td>
					<td class="text-left"><strong>Total Materias</strong></td>
					<td class="text-right"><strong>Promedio</strong></td>
				</tr>
				<template v-if="periodos.length!=0">
					<tr v-for="(periodo, index) in periodos">
						<template v-if="periodo.aval.estatus=='aceptada'">
							<td class="text-left">
								@{{obtenermes(periodo.fecha_inicio)}}-@{{obteneranho(periodo.fecha_inicio) }}
							</td>
							<td class="text-left">
								@{{periodo.numero_periodo}}
								<template v-if="regimen=='anual'">
									año
								</template>
								<template  v-else>
									<template v-if="regimen=='semestral'">
										semestre
									</template>
									<template v-else>
										trimestral
									</template>
								</template>
							</td>
							<td class="text-left">
								@{{periodo.anho_lectivo}}
							</td>
							<td>
								@{{periodo.materias.length}}
							</td>
							<td>
								@{{ calcularpromedioperiodo(periodo.materias)}}
							</td>
						</template>
					</tr>
				</template>
				<template v-else>
					<tr>
						<td colspan="5" class="text-center">No hay <strong>periodos</strong> <!--@{{periodos.length }}--></td>
					</tr>
				</template>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="3" class="cabecera-sisbeca">TALLER (@{{obtenermescompleto(mes)}}-@{{anho}})</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left">Modalidad</td>
					<td class="text-left"><strong>Asistencias</strong></td>
					<td class="text-left"><strong>Inasistencias</strong></td>
				</tr>
				<tr>
					<td class="text-left"><strong>Presenciales</strong></td>
					<td class="text-left">@{{asistio.a_t_p}}</td>
					<td class="text-left">@{{noasistio.na_t_p}}</td>
					
				</tr>
				<tr>
					<td class="text-left"><strong>Virtuales</strong></td>
					<td class="text-left">@{{asistio.a_t_v}}</td>
					<td class="text-left">@{{noasistio.na_t_v}}</td>
				</tr>
				<tr style="background-color: #eee">
					<td class="text-left">Totales de talleres</td>
					<td class="text-left">@{{asistio.a_t_p+asistio.a_t_v}}</td>
					<td class="text-left">@{{noasistio.na_t_p+noasistio.na_t_v}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="3" class="cabecera-sisbeca">CHAT CLUB (@{{obtenermescompleto(mes)}}-@{{anho}})</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left">Modalidad</td>
					<td class="text-left"><strong>Asistencias</strong></td>
					<td class="text-left"><strong>Inasistencias</strong></td>
				</tr>
				<tr>
					<td class="text-left"><strong>Presenciales</strong></td>
					<td class="text-left">@{{asistio.a_c_p}}</td>
					<td class="text-left">@{{noasistio.na_c_p}}</td>
					
				</tr>
				<tr>
					<td class="text-left"><strong>Virtuales</strong></td>
					<td class="text-left">@{{asistio.a_c_v}}</td>
					<td class="text-left">@{{noasistio.na_c_v}}</td>
				</tr>
				<tr style="background-color: #eee">
					<td class="text-left">Totales de chat club</td>
					<td class="text-left">@{{asistio.a_c_p+asistio.a_c_v}}</td>
					<td class="text-left">@{{noasistio.na_c_p+noasistio.na_c_v}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<!--
	<p>No borrar este código, aún funciona. Pero está hecho en PHP</p>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="4" class="cabecera-sisbeca">VOLUNTARIADO</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left"><strong>Año</strong></td>
					<td class="text-left"><strong>Tipo Voluntariado</strong></td>
					<td class="text-left"><strong>Total</strong></td>
					<td class="text-right"><strong>Horas Voluntariado</strong></td>
				</tr>
				@php ($horas = 0)
				@if($voluntariados->count()!=0)
					@foreach($voluntariados as $voluntariado)
					@php ($horas = $horas + $voluntariado->horas_voluntariado)
					<tr>
						<td class="text-left">{{$anho}}</td>
						<td class="text-left">{{ucwords($voluntariado->tipo_voluntariado)}}</td>
						<td class="text-left">{{$voluntariado->total_voluntariado}}</td>
						<td class="text-right">{{$voluntariado->horas_voluntariado}}</td>
					</tr>
					@endforeach
				@else
				<tr>
					<td colspan="4" class="text-center">No hay <strong>voluntariado</strong></td>
				</tr>
				@endif

				@if($actividades_facilitadas[0]->total_actividades!=0)
				<tr>
					@foreach($actividades_facilitadas as $af)
					@php ($horas = $horas + $af->horas_voluntariado)
						<td class="text-left">{{$anho}}</td>
						<td class="text-left">Facilitador en {{ucwords($af->tipo)}}</td>
						<td class="text-left">{{$af->total_actividades }}</td>
						<td class="text-right">{{$af->horas_voluntariado}}</td>
					@endforeach
				</tr>
				@else
				<tr>
					<td colspan="4" class="text-center">No ha sido facilitador de <strong>ningún chat club</strong></td>
				</tr>
				@endif
				<tr>
					<td colspan="2"></td>
					<td class="text-right"><strong>Total Horas</strong></td>
					<td class="text-right"><strong>{{ $horas }}</strong></td>
				</tr>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="4" class="cabecera-sisbeca">CVA</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left"><strong>Año</strong></td>
					<td class="text-left"><strong>Módulo</strong></td>
					<td class="text-left"><strong>Total por Nivel</strong></td>
					<td class="text-right"><strong>Promedio</strong></td>
				</tr>
				@if($cursos->count()!=0)
					@php ($promedio = 0)
					@php ($total_curso = 0)
					@foreach($cursos as $index=>$curso)
					@php ($promedio = $promedio + $curso->promedio_modulo)
					@php ($total_curso = $total_curso + $curso->total_modulo)
					<tr>
						<td class="text-left">{{$anho}}</td>
						<td class="text-left">{{$curso->modulo}} nivel</td>
						<td class="text-left">{{$curso->total_modulo}}</td>
						<td class="text-right">{{number_format($curso->promedio_modulo, 2, '.', ',')}}</td>
					</tr>
					@endforeach
				@else
				<tr>
					<td colspan="4" class="text-center">No hay <strong>CVA</strong></td>
				</tr>
				@endif
				<tr>	
					<td colspan="2"></td>
					<td class="text-right"><strong>Promedio General</strong></td>
					<td class="text-right">
						<strong>
						@if($cursos->count()==0)
							{{number_format(0, 2, '.', ',')}}
						@else
							{{number_format($promedio/($index+1), 2, '.', ',')}}
						@endif
						</strong>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="5" class="cabecera-sisbeca">NOTAS ACADÉMICAS</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-left"><strong>Año</strong></td>
					<td class="text-left"><strong>Número Periodo</strong></td>
					<td class="text-left"><strong>Año Lectivo</strong></td>
					<td class="text-left"><strong>Total Materias</strong></td>
					<td class="text-right"><strong>Promedio</strong></td>
				</tr>
				@php ($promedio_periodo = 0)
				@php ($j = 0)
				@if($periodos->count()!=0)

					@foreach($periodos as $periodo)
					@if($periodo->aval->estatus=='aceptada')
					@php ($j++)
					@php ($promedio_periodo = $promedio_periodo + $periodo->getPromedio())
					<tr>
						<td class="text-left">{{$anho}}</td>
						<td class="text-left">
							{{$periodo->getNumeroPeriodo()}} 
						</td>
						<td class="text-left">{{$periodo->anho_lectivo}}</td>
						<td class="text-left">{{$periodo->getTotalMaterias()}}</td>
						<td class="text-right">{{$periodo->getPromedio()}}</td>
					</tr>
					@endif
					@endforeach
				@else
				<tr>
					<td colspan="5" class="text-center">No hay <strong>periodos</strong></td>
				</tr>
				@endif
				<tr>	
					<td colspan="3"></td>
					<td class="text-right"><strong>Promedio General</strong></td>
					<td class="text-right">
						<strong>
						@if($promedio_periodo!=0)
						{{ number_format($promedio_periodo/($j), 2, '.', ',')}}
						@else
						{{ number_format(0, 2, '.', ',')}}
						@endif
						</strong>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th colspan="4" class="cabecera-sisbeca">TALLER Y CHAT CLUB</th>
				</tr>
			</thead>
			<tbody>
				
				@php ($total_taller=$a_t_p->total_actividades+$a_t_v->total_actividades)
				@php ($total_chat=$a_c_p->total_actividades+$a_c_v->total_actividades)
				@php ($total_presencial=$a_t_p->total_actividades+$a_c_p->total_actividades)
				@php ($total_virtual=$a_t_v->total_actividades+$a_c_v->total_actividades)
				<tr>
					<td><strong>ASISTIO</strong></td>
					<td><strong>Presencial</strong></td>
					<td><strong>Virtual</strong></td>
					<td><strong>Total</strong></td>
				</tr>
				<tr>
					<td><strong>Taller</strong></td>
					<td>{{$a_t_p->total_actividades}}</td>
					<td>{{$a_t_v->total_actividades}}</td>
					<td>{{$total_taller}}</td>
				</tr>
				<tr>
					<td><strong>Chat Club</strong></td>
					<td>{{$a_c_p->total_actividades}} </td>
					<td>{{$a_c_v->total_actividades}} </td>
					<td>{{$total_chat}}</td>
				</tr>
				<tr>
					<td><strong>Total</strong></td>
					<td>{{$total_presencial}}</td>
					<td>{{$total_virtual}}</td>
					<td>
						<strong>
						{{$a_t_p->total_actividades+$a_t_v->total_actividades
						+$a_c_p->total_actividades+$a_c_v->total_actividades}}
						</strong>
					</td>
				</tr>
				

				@php ($total_n_taller=$na_t_p->total_actividades+$na_t_v->total_actividades)
				@php ($total_n_chat=$na_c_p->total_actividades+$na_c_v->total_actividades)
				@php ($total_n_presencial=$na_t_p->total_actividades+$na_c_p->total_actividades)
				@php ($total_n_virtual=$na_t_v->total_actividades+$na_c_v->total_actividades)
				<tr>
					<td><strong>NO ASISTIO</strong></td>
					<td><strong>Presencial</strong></td>
					<td><strong>Virtual</strong></td>
					<td><strong>Total</strong></td>
				</tr>
				<tr>
					<td><strong>Taller</strong></td>
					<td>{{$na_t_p->total_actividades}}</td>
					<td>{{$na_t_v->total_actividades}}</td>
					<td>{{$total_n_taller}}</td>
				</tr>
				<tr>
					<td><strong>Chat Club</strong></td>
					<td>{{$na_c_p->total_actividades}} </td>
					<td>{{$na_c_v->total_actividades}} </td>
					<td>{{$total_n_chat}}</td>
				</tr>
				<tr>
					<td><strong>Total</strong></td>
					<td>{{$total_n_presencial}}</td>
					<td>{{$total_n_virtual}}</td>
					<td>
						<strong>
						{{$na_t_p->total_actividades+$na_t_v->total_actividades
						+$na_c_p->total_actividades+$na_c_v->total_actividades}}
						</strong>
					</td>
				</tr>
				
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
<script>
const app = new Vue({

	el: '#app',
	data:
	{
		anho:'',
		mes:'',
		regimen:'',
		periodos:[],
		voluntariados:[],
		cursos:[],
		actividades_facilitadas:'',
		asistio:[],
		noasistio:[],
	},
	created: function()
	{
		this.obtenerreportegeneral();
	},
	methods:
	{
		obtenerreportegeneral()
		{
			var url = '{{route('seguimiento.resumen.anhomes',array('id'=>':id','anho'=>':anho','mes'=>':mes'))}}';
			var id = '{{$becario->user->id}}';
			var anho = '{{date('Y')}}';
			var mes = '00';
			url = url.replace(':id', id);
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
			this.anho = anho;
			this.mes = mes;
            axios.get(url).then(response => 
            {
               	this.periodos = response.data.periodos;
               	this.cursos = response.data.cursos;
               	this.voluntariados = response.data.voluntariados;
               	this.actividades_facilitadas = response.data.actividades_facilitadas;
               	this.asistio = response.data.asistio;
               	this.noasistio = response.data.noasistio;
               	this.regimen = response.data.regimen;
			$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
		},
		consultarreportegeneral(anho,mes)
		{
			var url = '{{route('seguimiento.resumen.anhomes',array('id'=>':id','anho'=>':anho','mes'=>':mes'))}}';
			var id = '{{$becario->user->id}}';
			url = url.replace(':id', id);
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
			$("#preloader").show();
			axios.get(url).then(response => 
            {
               	this.periodos = response.data.periodos;
               	this.cursos = response.data.cursos;
               	this.voluntariados = response.data.voluntariados;
               	this.actividades_facilitadas = response.data.actividades_facilitadas;
               	this.asistio = response.data.asistio;
               	this.noasistio = response.data.noasistio;
               	this.regimen = response.data.regimen;
			$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
		},
		calcularpromedioperiodo(materias)
		{
			var total = 0;
            var length = materias.length;
            for (var i = 0; i < length; i++)
            {
                total += parseFloat(materias[i].nota);
            }
            if(length==0)
            {
            	return Number(0).toFixed(2);
            }
            return (total / length).toFixed(2);;
		},
		total_horas_voluntariados(voluntariados,actividades_facilitadas)
		{
			var total = 0;
			var length = voluntariados.length;
			for (var i = 0; i < length; i++)
            {
                total += parseFloat(voluntariados[i].horas_voluntariado);
            }
            if(actividades_facilitadas.horas_voluntariado!=null)
            {
            	return parseInt(total)+parseInt(actividades_facilitadas.horas_voluntariado);
            }
            return total;
		},
		descargarpdf(anho,mes)
		{
			var url = '{{route('seguimiento.resumen.anhomes.pdf',array('id'=>':id','anho'=>':anho','mes'=>':mes'))}}'
			var id = '{{$becario->user->id}}';
			url = url.replace(':id', id);
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
			return url;
		},
		descargarexcel(anho,mes)
		{
			var url = '{{route('seguimiento.resumen.anhomes.excel',array('id'=>':id','anho'=>':anho','mes'=>':mes'))}}'
			var id = '{{$becario->user->id}}';
			url = url.replace(':id', id);
			url = url.replace(':anho', anho);
			url = url.replace(':mes', mes);
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
		},
		obtenermes(fecha)
		{
			var dia = new Date (fecha);
			return moment(dia).format('MM');
		},
		obteneranho(fecha)
		{
			var dia = new Date (fecha);
			return moment(dia).format('YYYY');
		},
		primeraLetraEnMayusculas(cadena)
		{
  			return cadena.charAt(0).toUpperCase() + cadena.slice(1);
		}
	}

});
</script>
@endsection
