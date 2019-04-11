@extends('sisbeca.layouts.main')
@section('title',$model=='crear' ? $becario->user->nombreyapellido().': Cargar Periodo' : $becario->user->nombreyapellido().' - Editar Periodo: '.$periodo->anho_lectivo)
@section('content')
<div class="col-lg-12">
    <div class="text-right">
        @if($model=="editar")
        <a href="{{ route('materias.mostrar',$periodo->id)}}" class="btn btn-sm sisbeca-btn-primary">Ver Materias</a>
        @endif
        
        <a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
	<br>
	<div class="col sisbeca-container-formulario">

		@if($model=='crear')

			{{ Form::open(['route' => ['guardar.periodo',$becario->user->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

		@else
			
		@endif

		
		<div class="form-group">
			<div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">Becario</label>
                    {{ Form::text('becario', $becario->user->nombreyapellido(), ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Año Lectivo</label>
                    {{ Form::text('anho_lectivo', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 2-2018'])}}
                    <span class="errors" style="color:#red">{{ $errors->first('anho_lectivo') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Número Periodo</label>
                    @if($model=='crear')
                        @if($becario->esAnual())
                        	{{ Form::select('numero_periodo', array('1'=>'1 año','2'=>'2 año','3'=>'3 año','4'=>'4 año','5'=>'5 año'),($model=='crear') ? 1 : $periodo->numero_periodo,['class' =>'sisbeca-input']) }}
                        @else
                        	@if($becario->esSemestral())
                        		{{ Form::select('numero_periodo', array('1'=>'1 semestre','2'=>'2 semestre','3'=>'3 semestre','4'=>'4 semestre','5'=>'5 semestre','6'=>'6 semestre','7'=>'7 semestre','8'=>'8 semestre','9'=>'9 semestre','10'=>'10 semestre'),($model=='crear') ? 1 : $periodo->numero_periodo,['class' =>'sisbeca-input']) }}
                        	@else
                        		{{ Form::select('numero_periodo', array('1'=>'1 trimestre','2'=>'2 trimestre','3'=>'3 trimestre','4'=>'4 trimestre','5'=>'5 trimestre','6'=>'6 trimestre','7'=>'7 trimestre','8'=>'8 trimestre','9'=>'9 trimestre','10'=>'10 trimestre','11'=>'11 trimestre','12'=>'12 trimestre','13'=>'13 trimestre','14'=>'14 trimestre','15'=>'15 trimestre','16'=>'16 trimestre','17'=>'17 trimestre','18'=>'18 trimestre','19'=>'19 trimestre','20'=>'20 trimestre'),($model=='crear') ? 1 : $periodo->numero_periodo,['class' =>'sisbeca-input']) }}
                        	@endif
                        @endif
                    @else
                        @if($periodo->esAnual())
                            {{ Form::select('numero_periodo', array('1'=>'1 año','2'=>'2 año','3'=>'3 año','4'=>'4 año','5'=>'5 año'),($model=='crear') ? 1 : $periodo->numero_periodo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                        @else
                            @if($periodo->esSemestral())
                                {{ Form::select('numero_periodo', array('1'=>'1 semestre','2'=>'2 semestre','3'=>'3 semestre','4'=>'4 semestre','5'=>'5 semestre','6'=>'6 semestre','7'=>'7 semestre','8'=>'8 semestre','9'=>'9 semestre','10'=>'10 semestre'),($model=='crear') ? 1 : $periodo->numero_periodo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                            @else
                                {{ Form::select('numero_periodo', array('1'=>'1 trimestre','2'=>'2 trimestre','3'=>'3 trimestre','4'=>'4 trimestre','5'=>'5 trimestre','6'=>'6 trimestre','7'=>'7 trimestre','8'=>'8 trimestre','9'=>'9 trimestre','10'=>'10 trimestre','11'=>'11 trimestre','12'=>'12 trimestre','13'=>'13 trimestre','14'=>'14 trimestre','15'=>'15 trimestre','16'=>'16 trimestre','17'=>'17 trimestre','18'=>'18 trimestre','19'=>'19 trimestre','20'=>'20 trimestre'),($model=='crear') ? 1 : $periodo->numero_periodo,['class' =>'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled']) }}
                            @endif
                        @endif
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha Inicio</label>
                    {{ Form::text('fecha_inicio', ($model=='crear') ? null : date("d/m/Y", strtotime($periodo->fecha_inicio)) , ['class' => 'sisbeca-input', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechainicio"])}}
                    <span class="errors" >{{ $errors->first('fecha_inicio') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha Fin</label>
                    {{ Form::text('fecha_fin', ($model=='crear') ? null : date("d/m/Y", strtotime($periodo->fecha_fin)) , ['class' => 'sisbeca-input', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechafin"])}}
                    <span class="errors">{{ $errors->first('fecha_fin') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                	<label for="constancia" class="control-label">
                		{{ $model=='crear' ? '*Constancia' : 'Actualizar Constancia' }}
                    </label>
                    {{ Form::file('constancia',['class' => 'sisbeca-input ', 'accept'=>'image/jpeg,image/jpg/image/png,application/pdf' ] ) }}
                    <span class="errors">{{ $errors->first('constancia') }}</span>
                </div>
                @if($model=='editar')
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
                @endif

                @if(Auth::user()->rol==="admin")
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="estatus" class="control-label">Estatus Periodo</label>
                    {{ Form::select('estatus', array('pendiente'=>'pendiente','aceptada'=>'aceptada','negada'=>'negada'), $periodo->aval->estatus,['class' =>'sisbeca-input']) }}
                </div>
                @endif
			</div>
		</div>

		<hr>	

		<div class="form-group">
			<div class="row">
				<div class="col-lg-12 text-right" >
                   
                    <a href="{{route('periodos.todos')}}" class="btn sisbeca-btn-default">Cancelar</a>
                   
                    <button class="btn sisbeca-btn-primary" type="submit">Guardar</button>
                    
				</div>
			</div>
		</div>		

		{{ Form::close() }}
	</div>
</div>
@endsection

@section('personaljs')

<script>

	$('#fechainicio').datepicker({
		format: 'dd/mm/yyyy',
		language: 'es',
		orientation: 'bottom',
		autoclose: true,
	});

	$('#fechafin').datepicker({
		format: 'dd/mm/yyyy',
		language: 'es',
		orientation: 'bottom',
		autoclose: true,
	});
</script>
@endsection
