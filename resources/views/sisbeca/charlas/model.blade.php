@extends('sisbeca.layouts.main')
@section('title',$model=='crear' ? 'Crear Charla' : 'Editar Charla: '.$charla->anho)
@section('content')
<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('charla.index')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
	<br>
	<div class="col sisbeca-container-formulario">

		@if($model=='crear')

			{{ Form::open(['route' => ['charla.store'], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

		@else
			{{ Form::model($charla,['route' => ['charla.update',$charla->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
		@endif

		@if($model=='editar')
		@endif
		<div class="form-group">
			<div class="row">

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="anho" class="control-label">*Año</label>
                    {{ Form::text('anho', ($model=='crear') ? null : $charla->anho, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 2018'])}}
                    <span class="errors" style="color:#red">{{ $errors->first('anho') }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                	<label for="imagen" class="control-label">
                		{{ $model=='crear' ? '*Imagen' : 'Actualizar Imagen' }}
                    </label>
                    {{ Form::file('imagen',['class' => 'sisbeca-input ', 'accept'=>'image/*' ] ) }}
                    <span class="errors">{{ $errors->first('imagen') }}</span>
                </div>


                @if($model=='editar')
                <div class="col-lg-4 col-md-4 col-sm-6">
                	<label for="imagen" class="control-label">Imagen Actual</label>
                    <button type="button" class="btn sisbeca-btn-primary btn-block" data-toggle="modal" data-target="#ver">
                    	<i class="fa fa-photo"></i> Ver
                	</button>
                </div>
                @endif

			</div>
		</div>

		<hr>	

		<div class="form-group">
			<div class="row">
				<div class="col-lg-12 text-right" >
					<a href="{{route('charla.index')}}" class="btn sisbeca-btn-default">Cancelar</a>
					&nbsp;&nbsp;

                    <input class="btn sisbeca-btn-primary" type="submit" value="Guardar">
				</div>
			</div>
		</div>		

		{{ Form::close() }}
	</div>
</div>

@if(isset($charla))
<!-- Modal para ver imagen -->
<div class="modal fade" id="ver">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><strong>Calendario Charlas</strong></h5>
                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
            </div>
            <div class="modal-body">
                <br>
                <img src="{{url($charla->imagen)}}" alt="{{$charla->titulo}}" class="img-responsive sisbeca-border">
                <br><br>
                <p class="text-center h6">{{$charla->titulo}}</p>
            </div>
            <div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
                <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal para ver imagen -->
@endif

@endsection

@section('personaljs')
<script>

</script>
@endsection
