@extends('sisbeca.layouts.main')
@section('title',$model=='crear' ? 'Crear Banner' : 'Editar Banner: '.$banner->titulo)
@section('content')
<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('banner.index')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
	<br>
	<div class="col sisbeca-container-formulario">

		@if($model=='crear')

			{{ Form::open(['route' => ['banner.store'], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

		@else
			{{ Form::model($banner,['route' => ['banner.update',$banner->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
		@endif

		@if($model=='editar')
		@endif
		<div class="form-group">
			<div class="row">

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="titulo" class="control-label">*Año</label>
                    {{ Form::text('titulo', ($model=='crear') ? null : $banner->titulo, ['class' => 'sisbeca-input', 'placeholder'=>'Feria Internacional de Becas'])}}
                    <span class="errors" style="color:#red">{{ $errors->first('titulo') }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="url" class="control-label">*URL</label>
                    {{ Form::text('url', ($model=='crear') ? null : $banner->url, ['class' => 'sisbeca-input', 'placeholder'=>'https://www.avaa.org'])}}
                    <span class="errors" style="color:#red">{{ $errors->first('url') }}</span>
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
					<a href="{{route('banner.index')}}" class="btn sisbeca-btn-default">Cancelar</a>
					&nbsp;&nbsp;

                    <input class="btn sisbeca-btn-primary" type="submit" value="Guardar">
				</div>
			</div>
		</div>

		{{ Form::close() }}
	</div>
</div>

@if($model=="editar")
<!-- Modal para ver imagen -->
<div class="modal fade" id="ver">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><strong>Banner</strong></h5>
                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
            </div>
            <div class="modal-body">
                <br>
                <img src="{{url($banner->imagen)}}" alt="{{$banner->titulo}}" class="img-responsive sisbeca-border">
                <br><br>
                <p class="text-center h6">{{$banner->titulo}}</p>
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

</script>
@endsection
