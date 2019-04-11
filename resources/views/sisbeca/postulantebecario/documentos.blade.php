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
			<strong>Ingresar Documentos</strong>
			<hr>

			{{ Form::model($becario, ['route' => ['postulantebecario.documentosguardar'], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

			@if(!isset($fotografia))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="fotografia">*Fotografía (.jpg o .png):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('fotografia',['class' => 'form-control filestyle', 'accept'=>'image/*' ] ) }}
						<span class="errors">{{ $errors->becario->first('fotografia') }}</span>
					</div>

				</div>
			</div>

			@else
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Fotografía Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($fotografia->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Imagen</a>
					</div>
				</div>
			</div>
			@endif

			@if(!isset($cedula))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="cedula">*Copia de Cédula (.jpg o .png):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('cedula',['class' => 'form-control filestyle', 'accept'=>'image/*' ] ) }}
						<span class="errors">{{ $errors->becario->first('cedula') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Copia de Cédula Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($cedula->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Imagen</a>
					</div>
				</div>
			</div>
			@endif

			@if(!isset($constancia_cnu))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right text-right label-xs" for="constancia_cnu">*Constancia CNU (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('constancia_cnu',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('constancia_cnu') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Constancia CNU Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($constancia_cnu->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
					</div>
				</div>
			@endif

			@if(!isset($calificaciones_bachillerato))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="calificaciones_bachillerato">*Calificaciones de Bachillerato (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('calificaciones_bachillerato',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('calificaciones_bachillerato') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Calificaciones de Bachillerato Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($calificaciones_bachillerato->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
					</div>
				</div>
			@endif

			@if(!isset($constancia_aceptacion))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="constancia_aceptacion">*Constancia de Aceptacion (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('constancia_aceptacion',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('constancia_aceptacion') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Constancia de Aceptación Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($constancia_aceptacion->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
					</div>
				</div>
			@endif

			@if(!isset($constancia_estudios))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="constancia_estudios">*Constancia de Estudios (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('constancia_estudios',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('constancia_estudios') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Constancia de Estudios Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($constancia_estudios->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
					</div>
				</div>
			@endif

			@if(!isset($calificaciones_universidad))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="calificaciones_universidad">*Calificaciones del Primer Año de la Universidad (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('calificaciones_universidad',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('calificaciones_universidad') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Calificaciones del Primer Año de la Universidad Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($calificaciones_universidad->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
					</div>
				</div>
			@endif

			@if(!isset($constancia_trabajo))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="constancia_trabajo">*Constancia de Trabajo (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('constancia_trabajo',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('constancia_trabajo') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs">Constancia de Trabajo Cargada:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
						<a href="{{ asset($constancia_trabajo->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
					</div>
				</div>
			@endif

			@if(!isset($declaracion_impuestos))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="declaracion_impuestos">*Declaración de Impuestos (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('declaracion_impuestos',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('declaracion_impuestos') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
				<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
					<label class="pull-right label-xs">Declaración de Impuestos Cargada:</label>
				</div>
				<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
					<a href="{{ asset($declaracion_impuestos->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
				</div>
			</div>
			@endif

			@if(!isset($recibo_pago))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="recibo_pago">*Recibo de pago de un servicio (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('recibo_pago',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('recibo_pago') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
				<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
					<label class="pull-right label-xs">Recibo de pago de un servicio Cargada:</label>
				</div>
				<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
					<a href="{{ asset($recibo_pago->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
				</div>
			</div>
			@endif

			@if(!isset($referencia_profesor1))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="referencia_profesor1">*Carta de Referencia del profesor 1 (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('referencia_profesor1',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('referencia_profesor1') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
				<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
					<label class="pull-right label-xs">Carta de Referencia del profesor 1 Cargada:</label>
				</div>
				<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
					<a href="{{ asset($referencia_profesor1->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
				</div>
			</div>
			@endif

			@if(!isset($referencia_profesor2))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="referencia_profesor2">*Carta de Referencia del profesor 2 (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('referencia_profesor2',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('referencia_profesor2') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
				<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
					<label class="pull-right label-xs">Carta de Referencia del profesor 2 Cargada:</label>
				</div>
				<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
					<a href="{{ asset($referencia_profesor2->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
				</div>
			</div>
			@endif

			@if(!isset($ensayo))
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="ensayo">*Ensayo (.pdf):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::file('ensayo',['class' => 'form-control filestyle', 'accept'=>'application/pdf' ] ) }}
						<span class="errors">{{ $errors->becario->first('ensayo') }}</span>
					</div>
				</div>
			</div>
			@else
			<div class="row" >
				<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
					<label class="pull-right label-xs">Ensayo Cargada:</label>
				</div>
				<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12" style="border:1px solid #eee;padding: 5px;margin-bottom: 5px;">
					<a href="{{ asset($ensayo->url) }}" target="_blank" class="btn btn-sm sisbeca-btn-primary">Ver Documento</a>
				</div>
			</div>
			@endif

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
<script>
	$('#datepicker').datepicker({
		format: 'dd/mm/yyyy',
		language: 'es',
		orientation: 'bottom',
		autoclose: true,
	});
</script>
@endsection
