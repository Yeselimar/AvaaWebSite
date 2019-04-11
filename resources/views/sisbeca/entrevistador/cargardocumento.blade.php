@extends('sisbeca.layouts.main')
@section('title',($model=="crear") ? "Cargar resumen entrevista del postulante: ".$becario->user->nombreyapellido():'Editar resumen entrevista del postulante: '.$becario->user->nombreyapellido())
@section('content').
	<div class="col-lg-12">
        <div class="text-right">
            <a href="{{route('entrevistador.misentrevistados')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
        </div>
		<br>
		<div class="col sisbeca-container-formulario">

			@if($model=='crear')
				{{ Form::open(['route' => ['entrevistador.guardardocumento',$becario->user->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
			@else
                {{ Form::open(['route' => ['entrevistador.actualizardocumento',$becario->user->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
			@endif

			<div class="form-group">
				<div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">Postulante Becario</label>
                        {{ Form::text('actividad', $becario->user->nombreyapellido(), ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                    	<label for="documento" class="control-label">
                    		{{ $model=='crear' ? '*Documento' : 'Actualizar Documento' }}
                        </label>
                        {{ Form::file('documento',['class' => 'sisbeca-input ', 'accept'=>'image/*,application/pdf' ] ) }}
                        <span class="errors">{{ $errors->first('documento') }}</span>
                    </div>
                    
                    @if($model=='editar')
                    <div class="col-lg-4 col-md-4 col-sm-6">
                    	<label for="documento" class="control-label">Documento Actual</label>
                        <a href="{{url($be->documento)}}" target="_blank" class="btn sisbeca-btn-primary btn-block">
                        	<i class="fa fa-photo"></i>
                    	</a>
                    </div>
                    @endif

				</div>
			</div>

			<hr>	

			<div class="form-group">
				<div class="row">
					<div class="col-lg-12 text-right" >
						<a href="{{route('entrevistador.misentrevistados')}}" class="btn sisbeca-btn-default">Cancelar</a>
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
