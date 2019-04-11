@extends('sisbeca.layouts.main')
@section('title','Mi Perfil')
@section('subtitle','Estudios Universitarios')
@section('content')

<div class="col-lg-12">
	<div class="alert  alert-warning alert-important" role="alert">
	 	Recuerde que debe enviar su postulación en la opción "Enviar Postulación" del menú lateral para que la misma sea válida.
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<strong>Ingresar Estudios Universitarios</strong>
			<hr>

			{{ Form::model($becario, ['route' => ['postulantebecario.estudiosuniversitariosguardar',Auth::user()->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="inicio_universidad">*Fecha de inicio de la Universidad:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						@if($becario->inicio_universidad==NULL)

						{{ Form::text('inicio_universidad',  date("d/m/Y"), ['class' => 'sisbeca-input','placeholder'=>'15/10/2012','id'=>"datepicker"])}}
						@else
						{{ Form::text('inicio_universidad',  date("d/m/Y", strtotime($becario->inicio_universidad)), ['class' => 'sisbeca-input','placeholder'=>'15/10/2012','id'=>"datepicker"])}}
						@endif
						<span class="errors">{{ $errors->becario->first('inicio_universidad') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="nombre_universidad">*Nombre de la Universidad:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('nombre_universidad', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: Universidad de Carabobo.'])}}
						<span class="errors">{{ $errors->becario->first('nombre_universidad') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="carrera_universidad">*Carrera:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('carrera_universidad', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: Lic. Computación'])}}
						<span class="errors">{{ $errors->becario->first('carrera_universidad') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="regimen">*Régimen:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::select('regimen', array('anual'=>'Anual','semestral'=>'Semestral','trimestral'=>'Trimestral'),$becario->regimen,['class' =>'sisbeca-input']) }}
						<span class="errors">{{ $errors->becario->first('regimen') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="costo_matricula">Costo de la matrícula académica:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('costo_matricula', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: 2500.00'])}}
						<span class="errors">{{ $errors->becario->first('costo_matricula') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="promedio_universidad">*Promedio Universidad (en puntos):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('promedio_universidad', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: 17.5'])}}
						<span class="errors">{{ $errors->becario->first('promedio_universidad') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="periodo_academico">Periódo Académico:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('periodo_academico', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: 2-2017'])}}
						<span class="errors">{{ $errors->becario->first('periodo_academico') }}</span>
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
<script>
	$('#datepicker').datepicker({
		format: 'dd/mm/yyyy',
		language: 'es',
		orientation: 'bottom',
		autoclose: true,
	});
</script>
@endsection
