@extends('sisbeca.layouts.main')
@section('title',$model=='crear' ? $becario->user->nombreyapellido().' - Cargar CVA' : $becario->user->nombreyapellido().' - Editar CVA: '.$curso->getIdCurso())
@section('content')
	<div class="col-lg-12">
        <div class="text-right">
            <a href="{{ URL::previous() }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
        </div>
		<br>
		<div class="col sisbeca-container-formulario">

			@if($model=='crear')

				{{ Form::open(['route' => ['guardar.curso',$becario->user->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

			@else
				
			@endif
			
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
                        {{ Form::select('modo', array('sabatino'=>'sabatino','interdiario'=>'interdiario','diario'=>'diario','intensivo'=>'intensivo'),($model=='crear') ? 'sabatino' : $curso->modo,['class' =>'sisbeca-input']) }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Nivel</label>
                        {{ Form::select('nivel', array('basico'=>'basico','intermedio'=>'intermedio','avanzado'=>'avanzado'),($model=='crear') ? 'basico' : $curso->nivel,['class' =>'sisbeca-input']) }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Módulo</label>
                        {{ Form::select('modulo', array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18'),($model=='crear') ? 1 : $curso->modulo,['class' =>'sisbeca-input']) }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Nota</label>
                        {{ Form::text('nota', ($model=='crear') ? null : $curso->nota, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 50'])}}
                        <span class="errors" style="color:#red">{{ $errors->first('nota') }}</span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Fecha Inicio</label>
                        {{ Form::text('fecha_inicio', ($model=='crear') ? null : date("d/m/Y", strtotime($curso->fecha_inicio)) , ['class' => 'sisbeca-input', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechainicio"])}}
                        <span class="errors" >{{ $errors->first('fecha_inicio') }}</span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Fecha Fin</label>
                        {{ Form::text('fecha_fin', ($model=='crear') ? null : date("d/m/Y", strtotime($curso->fecha_fin)) , ['class' => 'sisbeca-input', 'placeholder'=>'DD/MM/AAAA', 'id'=>"fechafin"])}}
                        <span class="errors">{{ $errors->first('fecha_fin') }}</span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                    	<label for="constancia_nota" class="control-label">
                    		{{ $model=='crear' ? '*Constancia Nota' : 'Actualizar Constancia Nota' }}
                        </label>
                        {{ Form::file('constancia_nota',['class' => 'sisbeca-input ', 'accept'=>'image/jpeg,image/jpg/image/png,application/pdf' ] ) }}
                        <span class="errors">{{ $errors->first('constancia_nota') }}</span>
                    </div>
                    @if($model=='editar')
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
                    @endif

                    @if(Auth::user()->rol==="admin")
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="estatus" class="control-label">Estatus Nota</label>
                        {{ Form::select('estatus', array('pendiente'=>'pendiente','aceptada'=>'aceptada','negada'=>'negada'), $curso->aval->estatus,['class' =>'sisbeca-input']) }}
                    </div>
                    @endif
				</div>
			</div>

			<hr>	

			<div class="form-group">
				<div class="row">
					<div class="col-lg-12 text-right">
                        
                        <a href="{{ URL::previous() }}" class="btn sisbeca-btn-default">Cancelar</a>
                        
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
