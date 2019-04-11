@extends('sisbeca.layouts.main')
@section('title',($model=="crear") ? ucwords($actividad->tipo).': '.$actividad->nombre.', Cargar Justificativo a '.$becario->user->nombreyapellido() :  ucwords($actividad->tipo).': '.$actividad->nombre.', Editar Justificativo a '.$becario->user->nombreyapellido())
@section('content').
	<div class="col-lg-12">
        <div class="text-right">
            <a href="{{route('actividad.detalles',$actividad->id)}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
        </div>
		<br>
		<div class="col sisbeca-container-formulario">

			@if($model=='crear')
				{{ Form::open(['route' => ['actividad.guardarjustificacion',$actividad->id,$becario->user->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
			@else
                {{ Form::open(['route' => ['actividad.actualizarjustificacion',$actividad->id,$becario->user->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
			@endif

			<div class="form-group">
				<div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">Actividad</label>
                        {{ Form::text('actividad', $actividad->nombre, ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">Becario</label>
                        {{ Form::text('actividad', $becario->user->nombreyapellido(), ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                    </div>
                    <!--
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Estatus</label>
                        {{ Form::select('estatus', array('asistira'=>'Asistira','en espera'=>'En Espera','por justificar'=>'Por Justificar','asistio'=>'Asistio','no asistio'=>'No Asistio'),($model=='crear') ? 'basico' : null,['class' =>'sisbeca-input']) }}
                    </div> 
                    -->
                    <div class="col-lg-4 col-md-4 col-sm-6">
                    	<label for="justificativo" class="control-label">
                    		{{ $model=='crear' ? 'Justificativo' : 'Actualizar Justificativo' }}
                        </label>
                        {{ Form::file('justificativo',['class' => 'sisbeca-input ', 'accept'=>'image/*,application/pdf' ] ) }}
                        <span class="errors">{{ $errors->first('justificativo') }}</span>
                    </div>
                    
                    @if($model=='editar')
                    <div class="col-lg-4 col-md-4 col-sm-6">
                    	<label for="justificativo" class="control-label">Justificativo Actual</label>
                        <a href="{{url($justificativo->aval->url)}}" target="_blank" class="btn sisbeca-btn-primary btn-block">
                        	@if( $justificativo->aval->esImagen() )
                        		<i class="fa fa-photo"></i>
                        	@else
                        		<i class="fa fa-file-pdf-o"></i>
                        	@endif
                        	Ver
                    	</a>
                    </div>
                    @endif

				</div>
			</div>

			<hr>	

			<div class="form-group">
				<div class="row">
					<div class="col-lg-12 text-right" >
						<a href="#" class="btn sisbeca-btn-default">Cancelar</a>
                        <input class="btn sisbeca-btn-primary" type="submit" value="Guardar">
					</div>
				</div>
			</div>		

			{{ Form::close() }}
		</div>
	</div>
@endsection

@section('personaljs')

<script>

	
</script>
@endsection
