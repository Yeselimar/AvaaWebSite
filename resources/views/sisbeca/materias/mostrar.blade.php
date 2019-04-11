@extends('sisbeca.layouts.main')
@section('title',$becario->user->nombreyapellido().' - Periodo: '.$periodo->anho_lectivo)
@section('content')
<div class="col-lg-12" id="app">
	<div class="text-right">
		<template v-if="estatus_periodo!='aceptada'">
			<a href="#" class="btn btn-sm sisbeca-btn-primary" @click.prevent="mostrarAnadir">Añadir Materia</a>
		</template>
		<template v-else>
			@if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
				<a href="#" class="btn btn-sm sisbeca-btn-primary" @click.prevent="mostrarAnadir">Añadir Materia</a>
			@else
				<a href="#" class="btn btn-sm sisbeca-btn-primary" disabled="disabled">Añadir Materia</a>
			@endif
		</template>
		@if(Auth::user()->esBecario())
			<a href="{{route('periodos.index')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
		@else
			<a href="{{route('periodos.editar',$periodo->id)}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
		@endif
	</div>
	<br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>Nombre de la Materia</th>
					<th class="text-center">Nota</th>
					<th class="text-right">Acciones</th>
				</tr>
			</thead>
			<tbody>

				<tr v-for="materia in materias">
					<td>@{{materia.nombre}}</td>
					<td class="text-center">@{{materia.nota.toFixed(2)}}</td>
					<td>
						<template v-if="estatus_periodo!='aceptada'">
							<span @click.prevent="mostrarEditar(materia)" >
								<a href="#" v-b-popover.hover.bottom="'Editar Materia'" class="btn btn-xs sisbeca-btn-primary"> 
									<i class="fa fa-pencil"></i>
								</a>
							</span>
							<a href="#" v-b-popover.hover.bottom="'Eliminar Materia'" class="btn btn-xs sisbeca-btn-default" @click.prevent="mostrarEliminar(materia)" >
								<i class="fa fa-trash"></i>
							</a>
						</template>
						<template v-else>
							<button class="btn btn-xs sisbeca-btn-primary" disabled="disabled">
								<i class="fa fa-pencil"></i>
							</button>
							<button class="btn btn-xs sisbeca-btn-default" disabled="disabled">
								<i class="fa fa-trash"></i>
							</button>
						</template>
					</td>
				</tr>
				<tr v-if="materias==0">
					<td class="text-center" colspan="3">No hay <strong>materias</strong> en este periodo.</td>
				</tr>
				<tr>
					<td class="text-right"><strong>Promedio</strong></td>
					<td class="text-center"><strong>@{{promedio}}</strong></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	<hr>
	<p class="h6 text-right">@{{total}} materia(s) </p>

	<!-- Modal para añadir materia -->
	<form method="POST" @submit.prevent="anadirmateria()" class="form-horizontal"  >
	{{ csrf_field() }}
		<div class="modal fade" id="anadirmateria">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title pull-left"><strong>Añadir Materia</strong></h5>
						<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
				    </div>
					<div class="modal-body">
						<div class="col" style="padding-left: 0px;padding-right: 0px;">
							<label class="control-label " for="nombre">Nombre</label>
							<input type="text" name="nombre" class="sisbeca-input input-sm" v-model="nombre" placeholder="Álgebra I" style="margin-bottom: 0px">
							<span v-if="errores.nombre" :class="['label label-danger']">@{{ errores.nombre[0] }}</span>
							
						</div>
						<div class="col" style="padding-left: 0px;padding-right: 0px;">
							<label class="control-label" for="nota">Nota</label>
							<input type="text" name="nota" class="sisbeca-input input-sm" v-model="nota" placeholder="10.3" style="margin-bottom: 0px">
							<span v-if="errores.nota" :class="['label label-danger']">@{{ errores.nota[0] }}</span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cancelar</button>
						<button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- Modal para añadir materia -->

	<!-- Modal para editar materia -->
	<form method="POST" @submit.prevent="editarMateria(id)" class="form-horizontal"  >
	{{ csrf_field() }}
		<div class="modal fade" id="editarmateria">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
				    	<h5 class="modal-title pull-left"><strong>Editar Materia</strong></h5>
				    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
				    </div>
					<div class="modal-body" style="padding-top: 0px;">
						<div class="col" style="padding-left: 0px;padding-right: 0px;">
							<label class="control-label " for="nombre">Nombre</label>
							<input type="text" name="nombre" class="sisbeca-input input-sm" v-model="nombre" placeholder="Álgebra I" style="margin-bottom: 0px">
							<span v-if="errores.nombre" :class="['label label-danger']">@{{ errores.nombre[0] }}</span>
							
						</div>
						<div class="col" style="padding-left: 0px;padding-right: 0px;">
							<label class="control-label" for="nota">Nota</label>
							<input type="text" name="nota" class="sisbeca-input input-sm" v-model="nota" placeholder="10.3" style="margin-bottom: 0px">
							<span v-if="errores.nota" :class="['label label-danger']">@{{ errores.nota[0] }}</span>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- Modal para editar materia -->

	<!-- Modal para eliminar materia -->
	<div class="modal fade" id="mostrareliminar">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title pull-left"><strong>Eliminar Materia</strong></h5>
			    	<a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
			    </div>
				<div class="modal-body">
					<div class="col-lg-12">
						<br>
						<p class="h6 text-center">¿Está seguro que desea eliminar la materia <strong>@{{nombre}}</strong>?</p>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click.prevent="eliminarMateria(id)">Si</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal para eliminar materia -->
</div>

@endsection

@section('personaljs')

<script>

const app = new Vue({

	el: '#app',
	created: function()
	{
		this.getMaterias();
		this.estatus_periodo = '{{$periodo->aval->estatus}}';
	},
	data:
	{
		estatus_periodo:'',
		postBody: '',
		materias:[],
		id:0,
		nombre:'',
		nota:'',
		errores:[],
		total:0,
		promedio: 0,
		variable:"hhh",
	},
	methods:
	{
		getMaterias: function()
		{
			var url = '{{route('materias.obtener',$periodo->id)}}';
			axios.get(url).then(response => 
			{
				this.materias = response.data.materias;
				this.getTotalMateria();
				this.getPromedioMaterias();
			});
			
		},
		
		getTotalMateria: function()
		{
			this.total = this.materias.length;
		},

		getPromedioMaterias: function()
		{
			if(this.materias.length!=0)
			{
				var suma = 0;
				this.materias.forEach(function (materia, index)
				{
				    suma = suma + materia.nota;
				});
				this.promedio = (suma/this.materias.length).toFixed(2);
			}
			else
			{
				Number(0).toFixed(2);
			}
		},

		mostrarAnadir:function()
		{
			this.errores = [];
			this.nombre='';
			this.nota='';
			$('#anadirmateria').modal('show');
		},

		anadirmateria: function() 
		{
			var dataform = new FormData();
            dataform.append('nombre', this.nombre);
            dataform.append('nota', this.nota);
			var url = "{{route('materias.anadir',$periodo->id)}}";
            axios.post(url, dataform).then(response =>
			{
				this.nombre='';
				this.nota='';
				this.errores = [];
				this.getMaterias();
				$('#anadirmateria').modal('hide');
                toastr.success(response.data.success);
			}).catch( error =>
			{
				this.errores = error.response.data.errors;
			});
		},

		mostrarEditar: function(materia)
		{
			this.errores = [];
			this.id = materia.id;
			this.nombre = materia.nombre;
			this.nota = materia.nota;
			$('#editarmateria').modal('show');
		},

		editarMateria: function(id)
		{
			dataform = new FormData();
            dataform.append('nombre', this.nombre);
            dataform.append('nota', this.nota);
            var id = id;
            var url = '{{route('materias.editar',':id')}}';
            url = url.replace(':id', id);
			axios.post(url,dataform).then(response =>
			{
				this.nombre='';
				this.nota='';
				this.errores = [];
				this.getMaterias();
				$('#editarmateria').modal('hide');
                toastr.success(response.data.success);
			}).catch( error =>
			{
				this.errores = error.response.data.errors;
			});
		},

		mostrarEliminar: function(materia)
		{
			this.id = materia.id;
			this.nombre = materia.nombre;
			$('#mostrareliminar').modal('show');
		},

		eliminarMateria: function(id)
		{
			var id = id;
			var url = '{{route('materias.eliminar',':id')}}';
			url = url.replace(':id', id);
			$('#mostrareliminar').modal('hide');
			axios.get(url).then(response => 
			{
				toastr.success(response.data.success);
				this.getMaterias();			
			});
			this.id = 0;
			this.nombre = '';
		},
	}
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

@endsection
