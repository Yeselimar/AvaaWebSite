@extends('sisbeca.layouts.main')
@section('title','Ticket: '.$ticket->getNro() )
@section('content')
<div class="col-lg-12" id="app">
	<div class="text-right">
		@if(Auth::user()->esSoporte())
        <template v-if="ticket.notificado!='1'">
	        <button v-b-popover.hover.bottom="'Notificar por correo'" class="btn btn-sm sisbeca-btn-primary" @click="modalEnviarCorreo()">
	        	<i class="fa fa-envelope"></i>
	        </button>
        </template>
        <template v-else>
        	<button v-b-popover.hover.bottom="'Notificar por correo'" class="btn btn-sm sisbeca-btn-primary" disabled="disabled">
	        	<i class="fa fa-envelope"></i>
	        </button>
        </template>
       	<button v-b-popover.hover.bottom="'Responder ticket'" class="btn btn-sm sisbeca-btn-primary" @click="modalActualizar()">
        	<i class="fa fa-gavel"></i>
        </button>
        @endif
    </div>
    <br>
	<div class="col sisbeca-container-formulario">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<label class="control-label ">Pertenece a</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="ticket.pertenece" disabled="disabled">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<label class="control-label ">Estatus</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="ticket.estatus" disabled="disabled">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<label class="control-label ">Cargado el</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="fechaformatear(ticket.created_at)" disabled="disabled">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<label class="control-label ">Prioridad</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="ticket.prioridad" disabled="disabled">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<label class="control-label ">Tipo</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="ticket.tipo" disabled="disabled">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<label class="control-label ">Asunto</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="ticket.asunto" disabled="disabled">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<label class="control-label ">URL</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="ticket.url" disabled="disabled">
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<label class="control-label ">Imagen</label>
				<template v-if="ticket.imagen!=null">
					<a :href="urlVerImagen(ticket.imagen)" target="_blank" class="btn sisbeca-btn-primary btn-block">
	                	Ver Imagen <i class="fa fa-photo"></i>
	            	</a>
            	</template>
            	<template v-else>
            		<a target="_blank" class="btn sisbeca-btn-primary btn-block" disabled="disabled">
	                	Ver Imagen <i class="fa fa-photo"></i>
	            	</a>
            	</template>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label class="control-label ">Descripción</label>
				<textarea class="sisbeca-textarea sisbeca-input sisbeca-disabled" :value="ticket.descripcion" disabled="disabled"></textarea> 
			</div>
		</div>

		<hr>
		
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label class="control-label ">Respuesta</label>
				<textarea class="sisbeca-textarea sisbeca-input sisbeca-disabled" :value="ticket.respuesta" disabled="disabled">
				</textarea>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<label class="control-label ">Respondido por</label>
				<input type="text" class="sisbeca-input sisbeca-disabled" :value="ticket.usuariorespuesta" disabled="disabled">
			</div>
		</div>
	</div>

	<!-- Modal para tomar accion -->
    <div class="modal fade" id="modalActualizar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">
                    	<strong>
                    		Ticket @{{ticket.nro}}
                    		<span v-if="ticket.estatus=='Enviado'" class="label label-warning">@{{ticket.estatus}}</span>
							<span v-else-if="ticket.estatus=='En revisión'" class="label label-success">@{{ticket.estatus}}</span>
							<span v-else-if="ticket.estatus=='Cerrado'" class="label label-danger">@{{ticket.estatus}}</span>
                    	</strong>
                    </h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="nombre" style="font-size: 12px">Estatus</label>
                       	<select v-model="estatus_actualizar" class="sisbeca-input input-sm sisbeca-select">
                            <option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
                        </select>
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" for="observacion" style="font-size: 12px">Respuesta</label>
                        <textarea name="observacion" class="sisbeca-input " v-model="respuesta_actualizar" placeholder="EJ: El error fue corregido..." style="margin-bottom: 0px;height: 80px!important">
                        </textarea> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cancelar</button>
                    <button @click="actualizarEstatus()" class="btn btn-sm sisbeca-btn-primary pull-right">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para tomar accion -->

    <!-- Modal para enviar correo al notificar -->
    <div class="modal fade" id="modalEnviarCorreo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">
                    	<strong>
                    		Ticket @{{ticket.nro}}
                    		<span v-if="ticket.estatus=='Enviado'" class="label label-warning">@{{ticket.estatus}}</span>
							<span v-else-if="ticket.estatus=='En revisión'" class="label label-success">@{{ticket.estatus}}</span>
							<span v-else-if="ticket.estatus=='Cerrado'" class="label label-danger">@{{ticket.estatus}}</span>
                    	</strong>
                    </h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                	<br>
                    <p class="text-center">¿Está seguro que desea enviar un correo al usuario que generó el <strong>Ticket @{{ticket.nro}}</strong> con su respuesta?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >No</button>
                    <button @click="enviarCorreo()" class="btn btn-sm sisbeca-btn-primary pull-right">Sí</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para enviar correo al notificar -->

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
		respuesta_actualizar:'',
		estatus_actualizar:'',
		estatus:'',
		ticket: {
	    },
	},
	created: function()
	{
		this.obtenerticket();
		this.obtenerestatusticket();
	},
	methods: 
	{
		obtenerticket()
		{
			var id = '{{$ticket->id}}';
			var url = '{{route('ticket.detalles.servicio',':id')}}';
			url = url.replace(':id', id);
			axios.get(url).then(response => 
			{
				var t = response.data.ticket;
				Vue.set(app.ticket, 'id', t.id);
				Vue.set(app.ticket, 'nro', t.nro);
				Vue.set(app.ticket, 'estatus', t.estatus);
				Vue.set(app.ticket, 'estatus_original', t.estatus_original);
				Vue.set(app.ticket, 'prioridad', this.primeramayuscula(t.prioridad));
				Vue.set(app.ticket, 'tipo', this.primeramayuscula(t.tipo));
				Vue.set(app.ticket, 'asunto', t.asunto);
				Vue.set(app.ticket, 'descripcion', t.descripcion);
				Vue.set(app.ticket, 'imagen', t.imagen);
				Vue.set(app.ticket, 'url', t.url);
				Vue.set(app.ticket, 'respuesta', t.respuesta);
				Vue.set(app.ticket, 'usuariogenero', t.usuariogenero);
				Vue.set(app.ticket, 'usuariorespuesta', t.usuariorespuesta);
				Vue.set(app.ticket, 'pertenece',  t.usuariogenero+' ('+this.primeramayuscula(t.rolgenero)+')');
				Vue.set(app.ticket, 'created_at', t.created_at.date);
				Vue.set(app.ticket, 'updated_at', t.updated_at.date);
				Vue.set(app.ticket, 'notificado', t.notificado);
				$("#preloader").hide();
			}).catch( error => {
				console.log(error);
				$("#preloader").hide();
			});
		},
		obtenerestatusticket()
        {
            var url = '{{route('ticket.estatus')}}';
            axios.get(url).then(response => 
            {
                this.estatus = response.data.estatus;
            });
        },
		modalActualizar()
		{
			this.respuesta_actualizar=this.ticket.respuesta;
			this.estatus_actualizar=this.ticket.estatus_original;
			$('#modalActualizar').modal('show');
		},
		modalEnviarCorreo()
		{
			$('#modalEnviarCorreo').modal('show');
		},
		enviarCorreo()
		{
			$('#modalEnviarCorreo').modal('hide');
			$("#preloader").show();
			var id = '{{$ticket->id}}';	
			var url = '{{route('ticket.enviarcorreo.servicio',':id')}}';
			url = url.replace(':id', id);
            axios.get(url).then(response => 
            {
            	this.obtenerticket();
				this.obtenerestatusticket();
				$("#preloader").hide();
                toastr.success(response.data.success);
            });
		},
		actualizarEstatus()
		{
			var url = '{{route('ticket.actualizar',':id')}}';
			var id = '{{$ticket->id}}';	
            url = url.replace(':id', id);
			var dataform = new FormData();
            dataform.append('estatus', this.estatus_actualizar);
            dataform.append('respuesta', this.respuesta_actualizar);
            $('#modalActualizar').modal('hide');
            $("#preloader").show();
			axios.post(url,dataform).then(response => 
			{
				this.obtenerticket();
				this.obtenerestatusticket();
				$("#preloader").hide();
				toastr.success(response.data.success);
			});
		},
		urlVerImagen(slug)
		{
			var url = "{{url(':slug')}}";
    		url = url.replace(':slug', slug);
            return url;
		},
		primeramayuscula(string)
		{
			return string.charAt(0).toUpperCase() + string.slice(1);
		},
		fechaformatear(fecha)
		{
			return moment(new Date (fecha)).format('DD/MM/YYYY hh:mm A');
		}
	}
});
</script>

@endsection