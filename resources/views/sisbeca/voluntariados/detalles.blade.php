@extends('sisbeca.layouts.main')
@section('title','Voluntarido: '.$becario->user->nombreyapellido())
@section('content')
<div class="col-lg-12" id="app">
    <div class="text-right">
        <button v-b-popover.hover.bottom="'Aprobar/Negar/Devolver Voluntariado'"  class="btn btn-sm sisbeca-btn-primary" @click="modalAccion()"><i class="fa fa-gavel"></i></button>
        <a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
	<br>
    
	<div class="col sisbeca-container-formulario">
		{{ Form::model($voluntariado,['route' => ['voluntariados.actualizar',$voluntariado->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

		<div class="form-group">
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">Becario</label>
                    {{ Form::text('becario', $becario->user->nombreyapellido(), ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha</label>
                    {{ Form::text('fecha', date("d/m/Y", strtotime($voluntariado->fecha)) , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fecha"])}}
                    <span class="errors" >{{ $errors->first('fecha') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Nombre</label>
                    {{ Form::text('nombre', $voluntariado->nombre , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'Voluntariado en Caracas', 'id'=>"fechafin"])}}
                    <span class="errors">{{ $errors->first('nombre') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Institución</label>
                    {{ Form::text('institucion', $voluntariado->institucion , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'Venacham'])}}
                    <span class="errors">{{ $errors->first('institucion') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Responsable</label>
                    {{ Form::text('responsable', $voluntariado->responsable , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'John Doe'])}}
                    <span class="errors">{{ $errors->first('responsable') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Tipo</label>
                    {{ Form::select('tipo', array('interno'=>'interno','externo'=>'externo'), $voluntariado->tipo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                    <span class="errors">{{ $errors->first('tipo') }}</span>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label class="control-label">*Observación</label>
                    {{ Form::textarea('observacion', $voluntariado->observacion , ['class' => 'sisbeca-input sisbeca-textarea sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'Ingrese observación'])}}
                    <span class="errors">{{ $errors->first('observacion') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Lugar</label>
                    {{ Form::text('lugar', $voluntariado->lugar , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'Los Ruices, Caracas', 'id'=>"fechafin"])}}
                    <span class="errors" >{{ $errors->first('lugar') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Horas</label>
                    {{ Form::text('horas', $voluntariado->horas , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'17', 'id'=>"horas"])}}
                    <span class="errors">{{ $errors->first('horas') }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                	<label for="comprobante" class="control-label">Comprobante Actual</label>
                    <a href="{{url($voluntariado->aval->url)}}" target="_blank" class="btn sisbeca-btn-primary btn-block">
                    	@if( $voluntariado->aval->esImagen() )
                    		<i class="fa fa-photo"></i>
                    	@else
                    		<i class="fa fa-file-pdf-o"></i>
                    	@endif
                    	Ver
                	</a>
                </div>
     
			</div>
            <hr>
			<div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <label class="control-label">Estatus Voluntariado</label>
                    <input type="text" class="sisbeca-input sisbeca-disabled" disabled="disabled" :value="estatus_voluntariado">
                </div>

                <div class="col-lg-9 col-md-9 col-sm-12">
                    <label class="control-label">Observación del Comprobante</label>
                    <input type="text" class="sisbeca-input sisbeca-disabled" disabled="disabled" :value="observacion_voluntariado">
                </div>
            </div>

		</div>

	</div>

	<!-- Modal para tomar accion -->
    <div class="modal fade" id="mostrarmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><strong>Voluntariado</strong></h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="nombre">Estatus</label>
                        <select v-model="estatus_voluntariado_asignar" class="sisbeca-input input-sm sisbeca-select">
                            <option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
                        </select>
                        
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" for="observacion">Observación</label>
                        <textarea name="observacion" class="sisbeca-input " v-model="observacion_voluntariado_asignar" placeholder="EJ: No se observacion bien el archivo..." style="margin-bottom: 0px">
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
    created: function()
    {
        this.obtenervoluntariado();
        this.obtenerestatusaval();
    },
    data:
    {
    	estatus:'',
    	voluntariado:[],
    	estatus_voluntariado:'',
    	observacion_voluntariado:'',
    	estatus_voluntariado_asignar:'',
    	observacion_voluntariado_asignar:''
    },
    methods:
    {
    	obtenervoluntariado()
        {
            var url = '{{route('voluntariado.detalles.servicio',$voluntariado->id)}}';
            axios.get(url).then(response => 
            {
                this.voluntariado = response.data.voluntariado;
                this.estatus_voluntariado = this.voluntariado.aval.estatus;
                this.observacion_voluntariado = this.voluntariado.aval.observacion;
                $("#preloader").hide();
            });
        },
        obtenerestatusaval: function()
        {
            var url = '{{route('aval.getEstatus')}}';
            axios.get(url).then(response => 
            {
                this.estatus = response.data.estatus;
            }); 
        },
        modalAccion()
        {
            this.estatus_voluntariado_asignar=this.estatus_voluntariado;
            this.observacion_voluntariado_asignar=this.observacion_voluntariado;
            $('#mostrarmodal').modal('show');
        },
        actualizarEstatus()
        {
        	var url = '{{route('voluntariado.actualizar.servicio',':id')}}';
            var id = '{{$voluntariado->id}}'
            url = url.replace(':id', id);
            var dataform = new FormData();
            dataform.append('estatus', this.estatus_voluntariado_asignar);
            dataform.append('observacion', this.observacion_voluntariado_asignar);
            $('#mostrarmodal').modal('hide');
            $("#preloader").show();
            axios.post(url,dataform).then(response => 
            {
                this.obtenervoluntariado();
                $("#preloader").hide();
                toastr.success(response.data.success);
            });
        }
    }

});
</script>

@endsection