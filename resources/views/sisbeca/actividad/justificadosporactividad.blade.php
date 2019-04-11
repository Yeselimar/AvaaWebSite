@extends('sisbeca.layouts.main')
@section('title',"Justificativos del ".ucwords($actividad->tipo).': '.$actividad->nombre)
@section('personalcss')
<style>

</style>
@endsection
@section('content')
<div class="col-lg-12" id="app">
	<div class="text-right">
		<a href="{{route('actividad.detalles',$actividad->id)}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
	</div>
	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>Becario</th>
					<th>Estatus</th>
					<th>Inscrito el</th>
					<th>Justificativo Actualizado el</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="justificativo in justificativos">
					<td>@{{ justificativo.user.name}} @{{ justificativo.user.last_name}}</td>
					<td>
						<template v-if="justificativo.estatus=='asistio'">
                            <span class="label label-success">
                                @{{ justificativo.estatus }}
                            </span>
                             
                            <template v-if="justificativo.aval_id!=null">
                                &nbsp;/&nbsp;
                                <span  class="label label-custom">justificación cargada</span>
                                
                                fue 

                                <span v-if="justificativo.aval.estatus=='aceptada'" class="label label-success">
                                    @{{ justificativo.aval.estatus }}
                                </span>
                            </template>
                        </template>

                        <template v-if="justificativo.estatus=='no asistio'">
                            <span class="label label-danger">
                                @{{ justificativo.estatus }}
                            </span>
                             
                            <template v-if="justificativo.aval_id!=null">
                                &nbsp;/&nbsp;
                                <span  class="label label-custom">justificación cargada</span>
                                
                                fue 

                                <span v-if="justificativo.aval.estatus=='negada'" class="label label-danger">
                                    @{{ justificativo.aval.estatus }}
                                </span>
                            </template>
                        </template>

                        <template v-if="justificativo.estatus=='justificacion cargada'">
                            <span  class="label label-custom">@{{ justificativo.estatus }}</span>

                            está

                            <span v-if="justificativo.aval.estatus=='pendiente'" class="label label-warning">
                                @{{ justificativo.aval.estatus }}
                            </span>

                            <span v-if="justificativo.aval.estatus=='devuelto'" class="label label-danger">
                                @{{ justificativo.aval.estatus }}
                            </span>
                        </template>

                        <!-- 
						<span v-if="justificativo.aval.estatus=='pendiente'" class="label label-warning">
							@{{ justificativo.aval.estatus }}</span>
						<span v-if="justificativo.aval.estatus=='aceptada'" class="label label-success">
							@{{ justificativo.aval.estatus }}</span>
						<span v-if="justificativo.aval.estatus=='negada'" class="label label-danger">
							@{{ justificativo.aval.estatus }}</span>
						-->

					</td>
					<td>@{{fechaformartear(justificativo.created_at)}}</td>
					<td>@{{fechaformartear(justificativo.aval.updated_at)}}</td>
					<td>
						<a v-b-popover.hover.bottom="'Ver Justificativo'" :href="getJustificativo(justificativo.aval.url)" class="btn btn-xs sisbeca-btn-primary" target="_blank">
                            <template v-if="justificativo.aval.extension=='imagen'">
                                <i class="fa fa-image"></i>
                            </template>
                            <template v-else>
                                <i class="fa fa-file-pdf-o"></i>
                            </template>
                        </a>
                        
                        <button v-b-popover.hover.bottom="'Aprobar/Negar/Devolver justificativo'" class="btn btn-xs sisbeca-btn-primary" @click="modalAccion(justificativo)">
                            <i class="fa fa-gavel"></i>
                        </button>
                        <!-- @click="aprobarJustificativo(justificativo.aval.id)"-->
                        <!--<button v-b-popover.hover.bottom="'Rechazar justificativo'" class="btn btn-xs sisbeca-btn-default" @click="modalRechazar(justificativo)">
                            <i class="fa fa-remove"></i>
                        </button>-->
                        <!--  @click="negarJustificativo(justificativo.aval.id)"-->
                        <!--<button v-b-popover.hover.bottom="'Devolver justificativo'" class="btn btn-xs sisbeca-btn-default" @click="modalDevolver(justificativo)">
                            <i class="fa fa-reply"></i>-->
                        </button>
                        <!--@click="devolverJustificativo(justificativo.aval.id)"-->
					</td>
				</tr>
				<tr v-if="justificativos.length==0">
					<td colspan="7" class="text-center">No hay <strong>justificativos</strong> para este <strong>{{$actividad->tipo}}</strong>.</td>
				</tr>
			</tbody>
		</table>
		<hr>
		<p class="text-right h6">@{{ justificativos.length}} justificativo(s)</p>
	</div>

    <!-- Modal para tomar accion -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><strong>Justificativo</strong></h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" for="observacion">Becario</label>
                         <input type="text" class="sisbeca-input sisbeca-disabled input-sm" disabled="disabled" :value="justificativo.becario">
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" for="observacion">Actividad</label>
                         <input type="text" class="sisbeca-input sisbeca-disabled input-sm" disabled="disabled" :value="justificativo.actividad">
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="nombre">Estatus Justificativo</label>
                        <select v-model="justificativo.estatus" class="sisbeca-input input-sm sisbeca-select">
                            <option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
                        </select>
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" for="observacion">Observación Justificativo</label>
                        <textarea name="observacion" class="sisbeca-input " v-model="justificativo.observacion" placeholder="EJ: No se observacion bien el archivo..." style="margin-bottom: 0px">
                        </textarea> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cancelar</button>
                    <button @click="actualizarEstatus(justificativo)" class="btn btn-sm sisbeca-btn-primary pull-right">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para tomar accion -->

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
        estatus:[],
        justificativo:{},
        justificativos:[],
    },
    created: function()
    {
        this.obtenerjustificativos();
        this.obtenerestatusaval();
    },
    mounted: function()
    {
    	$('[data-toggle="tooltip"]').tooltip(); 
    },
    methods: 
    {
    	getJustificativo(link)
        {
            var url = '{{url(':link')}}';
            url = url.replace(':link', link);
            return url;
        },
        obtenerestatusaval: function()
        {
            var url = '{{route('aval.getEstatus')}}';
            axios.get(url).then(response => 
            {
                this.estatus = response.data.estatus;
            }); 
        },
        modalAccion(justificativo)
        {
            Vue.set(app.justificativo, 'id', justificativo.aval.id);
            Vue.set(app.justificativo, 'estatus', justificativo.aval.estatus);
            Vue.set(app.justificativo, 'observacion', justificativo.aval.observacion);
            Vue.set(app.justificativo, 'becario', justificativo.user.name+' '+justificativo.user.last_name);
            Vue.set(app.justificativo, 'actividad', justificativo.actividad.nombre);
            $('#modal').modal('show');
        },
        actualizarEstatus(justificativo)
        {
            $('#modal').modal('hide');
            $("#preloader").show();
            var url = '{{route('aval.tomaraccion',':id')}}';
            url = url.replace(':id', justificativo.id);
            var dataform = new FormData();
            dataform.append('estatus', justificativo.estatus);
            dataform.append('observacion', justificativo.observacion);
            axios.post(url,dataform).then(response => 
            {
                this.obtenerjustificativos();
                $("#preloader").hide();
                toastr.success(response.data.success);
            });
        },
        aprobarJustificativo(id)
        {
            $("#preloader").show();
            var url = '{{route('aval.aceptar',':id')}}';
            url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
                this.obtenerjustificativos();
                $("#preloader").hide();
                toastr.success(response.data.success);
            });
        },
        negarJustificativo(id)
        {
            $("#preloader").show();
            var url = '{{route('aval.negar',':id')}}';
            url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
                this.obtenerjustificativos();
                $("#preloader").hide();
                toastr.success(response.data.success);
            });
        },
        devolverJustificativo(id)
        {
            $("#preloader").show();
            var url = '{{route('aval.devolver',':id')}}';
            url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
                this.obtenerjustificativos();
                $("#preloader").hide();
                toastr.success(response.data.success);
            });
            
        },
    	getRutaVerJustificativo: function(url_justificativo)
    	{
    		var url = "{{url(':slug')}}";
    		url = url.replace(':slug', url_justificativo);
            return url;
    	},
    	obtenerjustificativos: function()
    	{
            $("#preloader").show();
    		var url = '{{route('actividad.justificativos.servicio',$actividad->id)}}';
            axios.get(url).then(response => 
            {
            	this.justificativos = response.data.justificativos;
                $("#preloader").hide();
            });
    	},
    	fechaformartear: function (fecha)
		{
			var d = new Date(fecha);
			return moment(d).format('DD/MM/YYYY hh:mm A');
		}
    }
});
</script>
@endsection