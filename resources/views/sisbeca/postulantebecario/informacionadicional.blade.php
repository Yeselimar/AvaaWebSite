@extends('sisbeca.layouts.main')
@section('title','Mi Perfil')
@section('subtitle','Información Adicional')
@section('content')

<div class="col-lg-12">
	<div class="alert  alert-warning alert-important" role="alert">
	 	Recuerde que debe enviar su postulación en la opción "Enviar Postulación" del menú lateral para que la misma sea válida.
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<strong>Ingresar Información Adicional</strong>
			<hr>

			{{ Form::model($becario, ['route' => ['postulantebecario.informacionadicionalguardar'], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="medio_proexcelencia">*¿Como se enteró del Programa de Proexcelencia?:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						<select class="sisbeca-input" name="medio_proexcelencia">
							<option value="amigo/pariente" @if($becario->medio_proexcelencia=='amigo/pariente') selected="selected" @endif>Amigo</option>
							<option value="internet" @if($becario->medio_proexcelencia=='internet') selected="selected" @endif>Internet</option>
							<option value="medios_comunicacion" @if($becario->medio_proexcelencia=='medios_comunicacion') selected="selected" @endif>Medios de Comunicación</option>
							<option value="otros" @if($becario->medio_proexcelencia=='otros') selected="selected" @endif>Otros</option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="otro_medio_proexcelencia">*¿Especifique el otro medio?:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('otro_medio_proexcelencia', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Periódico'])}}
						<span class="errors">{{ $errors->becario->first('otro_medio_proexcelencia') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="motivo_beca">*¿Por qué solicita la beca?:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('motivo_beca', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Solicito la beca porque...'])}}
						<span class="errors">{{ $errors->becario->first('motivo_beca') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="link_video">*Link Video</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('link_video', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: https://www.youtube.com/channel/v/qnOBj-k-wA'])}}
						<span class="errors">{{ $errors->becario->first('link_video') }}</span>
					</div>
				</div>
			</div>

			<hr>	

			<div class="form-group ">
				<div class="row">
					<div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
						<input class="btn btn-md sisbeca-btn-primary pull-right" type="submit" value="Guardar">
					</div>
				</div>
			</div>		

			{{ Form::close() }}
			
			<div class="alert  alert-warning alert-important" role="alert">
			 	Recuerde que debe enviar su postulación en la opción "Enviar Postulación" del menú lateral para que la misma sea válida.
			</div>
		</div>
	</div>
</div>

@endsection

@section('personaljs')

@endsection
