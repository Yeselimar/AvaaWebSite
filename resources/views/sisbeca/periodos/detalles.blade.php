@extends('sisbeca.layouts.main')
@section('title','Periodo: '.$periodo->anho_lectivo.' - '.$becario->user->nombreyapellido())
@section('content')
<div class="col-lg-12" id="app">
    <div class="text-right">
        <button v-b-popover.hover.bottom="'Aprobar/Negar/Devolver Periodo'"  class="btn btn-sm sisbeca-btn-primary" @click="modalAccion()"><i class="fa fa-gavel"></i></button>
        <a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
	<br>
    
	<div class="col sisbeca-container-formulario">

        {{ Form::model($periodo,['route' => ['periodos.actualizar',$periodo->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
		
        <div class="form-group">
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">Becario</label>
                    {{ Form::text('becario', $becario->user->nombreyapellido(), ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Año Lectivo</label>
                    {{ Form::text('anho_lectivo', null, ['class' => 'sisbeca-input sisbeca-disabled', 'placeholder'=>'EJ: 2-2018', 'disabled'=>'disabled'])}}
                    <span class="errors" style="color:#red">{{ $errors->first('anho_lectivo') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Número Periodo</label>
                    @if($periodo->esAnual())
                    	{{ Form::select('numero_periodo', array('1'=>'1 año','2'=>'2 año','3'=>'3 año','4'=>'4 año','5'=>'5 año'),$periodo->numero_periodo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                    @else
                    	@if($periodo->esSemestral())
                    		{{ Form::select('numero_periodo', array('1'=>'1 semestre','2'=>'2 semestre','3'=>'3 semestre','4'=>'4 semestre','5'=>'5 semestre','6'=>'6 semestre','7'=>'7 semestre','8'=>'8 semestre','9'=>'9 semestre','10'=>'10 semestre'), $periodo->numero_periodo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                    	@else
                    		{{ Form::select('numero_periodo', array('1'=>'1 trimestre','2'=>'2 trimestre','3'=>'3 trimestre','4'=>'4 trimestre','5'=>'5 trimestre','6'=>'6 trimestre','7'=>'7 trimestre','8'=>'8 trimestre','9'=>'9 trimestre','10'=>'10 trimestre','11'=>'11 trimestre','12'=>'12 trimestre','13'=>'13 trimestre','14'=>'14 trimestre','15'=>'15 trimestre','16'=>'16 trimestre','17'=>'17 trimestre','18'=>'18 trimestre','19'=>'19 trimestre','20'=>'20 trimestre'),$periodo->numero_periodo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                    	@endif
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha Inicio</label>
                    {{ Form::text('fecha_inicio', date("d/m/Y", strtotime($periodo->fecha_inicio)) , ['class' => 'sisbeca-input sisbeca-disabled', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechainicio", 'disabled'=>'disabled'])}}
                    <span class="errors" >{{ $errors->first('fecha_inicio') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha Fin</label>
                    {{ Form::text('fecha_fin', date("d/m/Y", strtotime($periodo->fecha_fin)) , ['class' => 'sisbeca-input sisbeca-disabled', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechafin", 'disabled'=>'disabled'])}}
                    <span class="errors">{{ $errors->first('fecha_fin') }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                	<label for="constancia" class="control-label">Constancia Actual</label>
                    <a href="{{url($periodo->aval->url)}}" target="_blank" class="btn sisbeca-btn-primary btn-block">
                    	@if( $periodo->aval->esImagen() )
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
                    <label class="control-label">Estatus Periodo</label>
                    <input type="text" class="sisbeca-input sisbeca-disabled" disabled="disabled" :value="estatus_periodo">
                </div>

                <div class="col-lg-9 col-md-9 col-sm-12">
                    <label class="control-label">Observación</label>
                    <input type="text" class="sisbeca-input sisbeca-disabled" disabled="disabled" :value="observacion_periodo">
                </div>
            </div>
		</div>

        {{ Form::close() }}

        <hr>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Nombre de la Materia</th>
                        <th class="text-center">Nota</th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="materia in materias">
                        <td>@{{materia.nombre}}</td>
                        <td class="text-center">@{{materia.nota.toFixed(2)}}</td>
                    </tr>
                    <tr v-if="materias==0">
                        <td class="text-center" colspan="2">No hay <strong>materias</strong> en este periodo.</td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Promedio</strong></td>
                        <td class="text-center"><strong>@{{promedio}}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Modal para tomar accion -->
    <div class="modal fade" id="mostrarmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><strong>Periodo</strong></h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="nombre">Estatus</label>
                        <select v-model="estatus_periodo_asignar" class="sisbeca-input input-sm sisbeca-select">
                            <option v-for="estatu in estatus" :value="estatu">@{{ estatu}}</option>
                        </select>
                        
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label" for="observacion">Observación</label>
                        <textarea name="observacion" class="sisbeca-input " v-model="observacion_periodo_asignar" placeholder="EJ: No se observacion bien el archivo..." style="margin-bottom: 0px">
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
        this.getMaterias();
        this.obtenerperiodo();
        this.obtenerestatusaval();
    },
    data:
    {
        estatus_periodo_asignar:'',
        observacion_periodo_asignar:'',
        estatus_periodo:'',
        observacion_periodo:'',
        estatus: '',
        materias:[],
        id:0,
        total:0,
        promedio: 0,
        periodo:[],
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
        obtenerperiodo()
        {
            var url = '{{route('periodos.detalles.servicio',$periodo->id)}}';
            axios.get(url).then(response => 
            {
                this.periodo = response.data.periodo;
                this.estatus_periodo = this.periodo.aval.estatus;
                this.observacion_periodo = this.periodo.aval.observacion;
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
            this.estatus_periodo_asignar=this.estatus_periodo;
            this.observacion_periodo_asignar=this.observacion_periodo;
            $('#mostrarmodal').modal('show');
        },

        actualizarEstatus()
        {
            var url = '{{route('periodo.actualizar.servicio',':id')}}';
            var id = '{{$periodo->id}}'
            url = url.replace(':id', id);
            var dataform = new FormData();
            dataform.append('estatus', this.estatus_periodo_asignar);
            dataform.append('observacion', this.observacion_periodo_asignar);
            $('#mostrarmodal').modal('hide');
            $("#preloader").show();
            axios.post(url,dataform).then(response => 
            {
                this.obtenerperiodo();
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