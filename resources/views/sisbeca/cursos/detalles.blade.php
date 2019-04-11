@extends('sisbeca.layouts.main')
@section('title','Curso: '.$curso->getNivel().' - '.$curso->modulo.' - '.$becario->user->nombreyapellido())
@section('content')
<div class="col-lg-12" id="app">
    <div class="text-right">
        <button v-b-popover.hover.bottom="'Aprobar/Negar/Devolver Curso'"  class="btn btn-sm sisbeca-btn-primary" @click="modalAccion()"><i class="fa fa-gavel"></i></button>
        <a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
	<br>
    
	<div class="col sisbeca-container-formulario">
		{{ Form::model($curso,['route' => ['cursos.actualizar',$curso->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
		<div class="form-group">
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">Becario</label>
                    {{ Form::text('becario', $becario->user->nombreyapellido(), ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                </div>
                {{ Form::hidden('tipocurso_id', 1) }}
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Tipo</label>
                    {{ Form::select('tipocurso_id', $tipocurso,1,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                    <span class="errors" style="color:#red">{{ $errors->first('tipocurso_id') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Modo</label>
                    {{ Form::select('modo', array('sabatino'=>'sabatino','interdiario'=>'interdiario','diario'=>'diario','intensivo'=>'intensivo'),$curso->modo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Nivel</label>
                    {{ Form::select('nivel', array('basico'=>'basico','intermedio'=>'intermedio','avanzado'=>'avanzado'), $curso->nivel,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Módulo</label>
                    {{ Form::select('modulo', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18'),$curso->modulo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Nota</label>
                    {{ Form::text('nota', $curso->nota, ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'EJ: 50'])}}
                    <span class="errors" style="color:#red">{{ $errors->first('nota') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha Inicio</label>
                    {{ Form::text('fecha_inicio', date("d/m/Y", strtotime($curso->fecha_inicio)) , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechainicio"])}}
                    <span class="errors" >{{ $errors->first('fecha_inicio') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha Fin</label>
                    {{ Form::text('fecha_fin', date("d/m/Y", strtotime($curso->fecha_fin)) , ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechafin"])}}
                    <span class="errors">{{ $errors->first('fecha_fin') }}</span>
                </div>
                
                <div class="col-lg-4 col-md-4 col-sm-6">
                	<label for="constancia" class="control-label">Nota Actual</label>
                    <a href="{{url($curso->aval->url)}}" target="_blank" class="btn sisbeca-btn-primary btn-block">
                    	@if( $curso->aval->esImagen() )
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
                    <label class="control-label">Estatus CVA</label>
                    <input type="text" class="sisbeca-input sisbeca-disabled" disabled="disabled" :value="estatus_cva">
                </div>

                <div class="col-lg-9 col-md-9 col-sm-12">
                    <label class="control-label">Observación</label>
                    <input type="text" class="sisbeca-input sisbeca-disabled" disabled="disabled" :value="observacion_cva">
                </div>
            </div>
		</div>

		{{ Form::close() }}

	</div>
    
	<!-- Modal para tomar accion -->
    <div class="modal fade" id="mostrarmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><strong>CVA</strong></h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="nombre">Estatus</label>
                        <select v-model="estatus_cva_asignar" class="sisbeca-input input-sm sisbeca-select">
                            <option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
                        </select>
                        
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" for="observacion">Observación</label>
                        <textarea name="observacion" class="sisbeca-input " v-model="observacion_cva_asignar" placeholder="EJ: No se observacion bien el archivo..." style="margin-bottom: 0px">
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
        this.obtenercva();
        this.obtenerestatusaval();
    },
    data:
    {
    	estatus:'',
    	cva:[],
    	estatus_cva:'',
    	observacion_cva:'',
    	estatus_cva_asignar:'',
    	observacion_cva_asignar:''
    },
    methods:
    {
    	obtenercva()
        {
            var url = '{{route('cursos.detalles.servicio',$curso->id)}}';
            axios.get(url).then(response => 
            {
                this.cva = response.data.cva;
                this.estatus_cva = this.cva.aval.estatus;
                this.observacion_cva = this.cva.aval.observacion;
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
            this.estatus_cva_asignar=this.estatus_cva;
            this.observacion_cva_asignar=this.observacion_cva;
            $('#mostrarmodal').modal('show');
        },
        actualizarEstatus()
        {
        	var url = '{{route('curso.actualizar.servicio',':id')}}';
            var id = '{{$curso->id}}'
            url = url.replace(':id', id);
            var dataform = new FormData();
            dataform.append('estatus', this.estatus_cva_asignar);
            dataform.append('observacion', this.observacion_cva_asignar);
            $('#mostrarmodal').modal('hide');
            $("#preloader").show();
            axios.post(url,dataform).then(response => 
            {
                this.obtenercva();
                $("#preloader").hide();
                toastr.success(response.data.success);
            });
        }
    }

});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@endsection