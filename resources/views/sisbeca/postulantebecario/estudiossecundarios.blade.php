@extends('sisbeca.layouts.main')
@section('title','Mi Perfil')
@section('subtitle','Estudios Secundarios')
@section('content')

<div class="col-lg-12">
	<div class="alert  alert-warning alert-important" role="alert">
	 	Recuerde que debe enviar su postulación en la opción "Enviar Postulación" del menú lateral para que la misma sea válida.
	</div>

	<div class="panel panel-default">
		<div class="panel-body">
			<strong>Ingresar Estudios Secundarios</strong>
			<hr>

			{{ Form::model($becario, ['route' => ['postulantebecario.estudiossecundariosguardar',Auth::user()->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="nombre_institucion">*Nombre de la Institución:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('nombre_institucion', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: U.E. José Austria'])}}
						<span class="errors">{{ $errors->becario->first('nombre_institucion') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="direccion_institucion">Dirección de la Institución:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('direccion_institucion', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: La Isabelica, Valencia.'])}}
						<span class="errors">{{ $errors->becario->first('direccion_institucion') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="director_institucion">Nombre del Director de la Institución:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('director_institucion', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: Pedro Pérez'])}}
						<span class="errors">{{ $errors->becario->first('director_institucion') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="bachiller_en">*Bachiller en:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('bachiller_en', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: Ciencias'])}}
						<span class="errors">{{ $errors->becario->first('bachiller_en') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="promedio_bachillerato">*Promedio Bachillerato (en puntos):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('promedio_bachillerato', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: 17.5'])}}
						<span class="errors">{{ $errors->becario->first('promedio_bachillerato') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="actividades_extracurriculares">Actividades extracurriculares:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('actividades_extracurriculares', null, ['class' => 'sisbeca-input','placeholder'=>'EJ: Deporte, Danza, etc.'])}}
						<span class="errors">{{ $errors->becario->first('actividades_extracurriculares') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="lugar_labor_social">*Lugar dónde realizó labor social:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('lugar_labor_social', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Cancha El Remanso'])}}
						<span class="errors">{{ $errors->becario->first('lugar_labor_social') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="direccion_labor_social">*Dirección Labor Social:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('direccion_labor_social', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: San Diego, Edo. Carabobo.'])}}
						<span class="errors">{{ $errors->becario->first('direccion_labor_social') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="supervisor_labor_social">Supervisor de la Labor Social:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('supervisor_labor_social', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Josefina Pérez'])}}
						<span class="errors">{{ $errors->becario->first('supervisor_labor_social') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="aprendio_labor_social">*¿Qué aprendió en la labor social?:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('aprendio_labor_social', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Aprendí a...'])}}
						<span class="errors">{{ $errors->becario->first('aprendio_labor_social') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="habla_otro_idioma">*¿Habla otro idioma?:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						<select class="sisbeca-input" name="habla_otro_idioma" id="hablaidioma">
						 @if($becario->habla_otro_idioma==1)
                            <option value=1 selected>Si</option>
                            <option value=0 >No</option>
                            @else
                               <option value=1 >Si</option>
                            <option value=0 selected>No</option>
                        @endif
						</select>
					</div>
				</div>
			</div>

			<div class="rendered">

				<div class="form-group">
					<div class="row" >
						<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
							<label class="pull-right label-xs" for="habla_idioma">*¿Cual idioma habla?:</label>
						</div>
						<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
							{{ Form::text('habla_idioma', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Inglés','id'=>'h1'])}}
							<span class="errors">{{ $errors->becario->first('habla_idioma') }}</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row" >
						<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
							<label class="pull-right label-xs" for="nivel_idioma">¿Nivel de Conocimiento del idioma?:</label>
						</div>
						<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
							<select class="sisbeca-input" name="nivel_idioma" id="h2">
								<option value="basico" @if($becario->nivel_idioma=='basico') selected="selected" @endif>Básico</option>
								<option value="medio" @if($becario->nivel_idioma=='medio') selected="selected" @endif>Medio</option>
								<option value="avanzando" @if($becario->nivel_idioma=='avanzando') selected="selected" @endif>Avanzado</option>
							</select>
						</div>
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
	$(document).ready(function()
	{
        var hablaidioma = $("#hablaidioma").val();

        //--
        $("#hablaidioma").change(function()
        {
            hablaidioma =   $("#hablaidioma").val();
            if(hablaidioma==1)
            {

                $(".rendered").show();
                $("#h1").attr("required", "required");
                $("#h2").attr("required", "required");


            }
            else
            {
               	$("#h1").attr("required", "required");
                $("#h2").attr("required", "required");
               
                document.getElementById("h1").value=null;
                document.getElementById("h2").value=null;
                $(".rendered").hide();
            }
        });

        if(hablaidioma==1)
        {
            $(".rendered").show();
            $("#h1").attr("required", "required");
            $("#h2").attr("required", "required");
        }
        else
        {
            $("#h1").removeAttr('required');
            $("#h2").removeAttr('required');
              
            document.getElementById("h1").value=null;
            document.getElementById("h2").value=null;
            $(".rendered").hide();

        }
    });
</script>
@endsection