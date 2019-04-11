@extends('sisbeca.layouts.main')
@section('title','Mi Perfil')
@section('subtitle','Datos Personales')
@section('content')

<div class="col-lg-12">
	<div class="alert  alert-warning alert-important" role="alert">
	 	Recuerde que debe enviar su postulación en la opción "Enviar Postulación" del menú lateral para que la misma sea válida.
	</div>

	<div class="panel panel-default">
		<div class="panel-body">
			<strong>Ingresar Datos Personales</strong>
			<hr>
			{{ Form::model($becario, ['route' => ['postulantebecario.datospersonalesguardar'], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
						<label class="pull-right label-xs" for="nombre">*Nombre:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('nombre', $usuario->name, ['class' => 'sisbeca-input sisbeca-disabled','disabled'=>'disabled'])}}
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="last_name">*Apellido:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('last_name', $usuario->last_name, ['class' => 'sisbeca-input sisbeca-disabled','disabled'=>'disabled'])}}
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="email">*Email:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('email', $usuario->email, ['class' => 'sisbeca-input sisbeca-disabled','disabled'=>'disabled'])}}
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12">
						<label class="pull-right label-xs" for="cedula">*Cédula:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('cedula', $usuario->cedula, ['class' => 'sisbeca-input sisbeca-disabled','disabled'=>'disabled'])}}
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="fecha">*Fecha Nacimiento:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('fecha', date("d/m/Y", strtotime($usuario->fecha_nacimiento)) , ['class' => 'sisbeca-input sisbeca-disabled','disabled'=>'disabled'])}}
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="direccion_permanente">*Dirección Permanente:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('direccion_permanente', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Las Delicias, Aragua'])}}
						<span class="errors">{{ $errors->becario->first('direccion_permanente') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="direccion_temporal">*Dirección Temporal:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('direccion_temporal', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Los Guayos, Carabobo'])}}
						<span class="errors">{{ $errors->becario->first('direccion_temporal') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="celular">*Teléfono Celular:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('celular', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 04149996633'])}}
						<span class="errors">{{ $errors->becario->first('celular') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="telefono_habitacion">*Teléfono Habitación:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('telefono_habitacion', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 02418889900'])}}
						<span class="errors">{{ $errors->becario->first('telefono_habitacion') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="telefono_pariente">*Teléfono Pariente:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('telefono_pariente', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 04149990011'])}}
						<span class="errors">{{ $errors->becario->first('telefono_pariente') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="lugar_nacimiento">*Lugar de Nacimiento:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('lugar_nacimiento', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Naguanagua, Edo Carabobo'])}}
						<span class="errors">{{ $errors->becario->first('lugar_nacimiento') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="ingreso_familiar">*Promedio Ingreso Familiar:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('ingreso_familiar', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 200.00'])}}
						<span class="errors">{{ $errors->becario->first('ingreso_familiar') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="trabaja">*¿Trabaja?:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						<select class="sisbeca-input" name="trabaja" id="trabaja">
							<option value="1" @if($becario->trabaja==1) selected="selected" @endif >Si</option>
							<option value="0" @if($becario->trabaja==0) selected="selected" @endif>No</option>
						</select>
					</div>
				</div>
			</div>

			<div class="rendered">

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="lugar_trabajo">Lugar de Trabajo:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('lugar_trabajo', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Av. Bolívar de Valencia, Edo. Carabobo','id'=>'t1'])}}
						<span class="errors">{{ $errors->becario->first('lugar_trabajo') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="cargo_trabajo">Cargo que desempeña en el trabajo:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('cargo_trabajo', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Desarrollador Junior','id'=>'t2'])}}
						<span class="errors">{{ $errors->becario->first('cargo_trabajo') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs" for="horas_trabajo">Horas mensuales de Trabajo:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('horas_trabajo', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 200','id'=>'t3'])}}
						<span class="errors">{{ $errors->becario->first('horas_trabajo') }}</span>
					</div>
				</div>
			</div>

			</div>


			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="text-right label-xs" for="contribuye">Contribuye con el ingreso familiar:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						<select class="sisbeca-input" name="contribuye_ingreso_familiar" id="contribuye">
						<option value="1" @if($becario->contribuye_ingreso_familiar==1) selected="selected" @endif>Si</option>
						<option value="0" @if($becario->contribuye_ingreso_familiar==0) selected="selected" @endif>No</option>
					</select>
					</div>
				</div>
			</div>

			<div class="rendered2">

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="text-right label-xs" for="porcentaje_contribuye_ingreso">Contribuye al ingreso familiar (en porcentaje):</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('porcentaje_contribuye_ingreso', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 25.5','id'=>'c1'])}}
						<span class="errors">{{ $errors->becario->first('porcentaje_contribuye_ingreso') }}</span>
					</div>
				</div>
			</div>

			</div>
					
			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="pull-right label-xs" for="vives_con">*¿Con quien vives?:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						<select class="sisbeca-input" name="vives_con" id="vivescon" >
							<option value="otros" @if($becario->vives_con=='otros') selected="selected" @endif>Otros</option>
							<option value="padres" @if($becario->vives_con=='padres') selected="selected" @endif>Padres</option> 
						</select>
					</div>
				</div>
			</div>

			<div class="rendered3">

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="pull-right label-xs" for="vives_otros">*¿Vives con otros? Especifique:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('vives_otros', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Tios y abuelos','id'=>'v1'])}}
						<span class="errors">{{ $errors->becario->first('vives_otros') }}</span>
					</div>
				</div>
			</div>

			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="pull-right label-xs"for="tipo_vivienda">*Tipo de Vivienda:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						<select class="sisbeca-input" name="tipo_vivienda">
							<option value="propia">Propia</option>
							<option value="alquilada">Alquilada</option>
							<option value="hipotecada">Hipotecada</option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="text-right label-xs"  for="composicion_familiar">Composición del núcleo familiar:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('composicion_familiar', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 3'])}}
						<span class="errors">{{ $errors->becario->first('composicion_familiar') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="pull-right label-xs"  for="ocupacion_padre">*Ocupación del padre:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('ocupacion_padre', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Obrero'])}}
						<span class="errors">{{ $errors->becario->first('ocupacion_padre') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right">
						<label class="text-right label-xs"  for="nombre_empresa_padre">*Nombre de la empresa del padre:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('nombre_empresa_padre', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Ford'])}}
						<span class="errors">{{ $errors->becario->first('nombre_empresa_padre') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12"  align="right">
						<label class="text-right label-xs"  for="experiencias_padre">*Experencia laboral del padre: (en años)</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('experiencias_padre', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 25'])}}
						<span class="errors">{{ $errors->becario->first('experiencias_padre') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="pull-right label-xs"  for="ocupacion_madre">*Ocupación de la madre:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('ocupacion_madre', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Secretaria'])}}
						<span class="errors">{{ $errors->becario->first('ocupacion_madre') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="nombre_empresa_madre">*Nombre de la empresa de la madre:</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('nombre_empresa_madre', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Eleval'])}}
						<span class="errors">{{ $errors->becario->first('nombre_empresa_madre') }}</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row" >
					<div class="col-lg-3 col-md-5 col-sm-6 col-xs-12" align="right" >
						<label class="text-right label-xs" for="experiencias_madre">*Experiencia laboral de la madre: (en años)</label>
					</div>
					<div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
						{{ Form::text('experiencias_madre', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 25'])}}
						<span class="errors">{{ $errors->becario->first('experiencias_madre') }}</span>
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

</div>

@endsection

@section('personaljs')
<script>
	$(document).ready(function()
	{
        var trabaja = $("#trabaja").val();
        var contribuye = $("#contribuye").val();
        var vivescon = $("#vivescon").val();

        //--
        $("#trabaja").change(function()
        {
            trabaja =   $("#trabaja").val();
            if(trabaja==1)
            {

                $(".rendered").show();
                $("#t1").attr("required", "required");
                $("#t2").attr("required", "required");
                $("#t3").attr("required", "required");


            }
            else
            {
               	$("#t1").removeAttr('required');
               	$("#t2").removeAttr('required');
               	$("#t3").removeAttr('required');
               
                document.getElementById("t1").value=null;
                document.getElementById("t2").value=null;
                document.getElementById("t3").value=null;
                $(".rendered").hide();
            }
        });

        if(trabaja==1)
        {
            $(".rendered").show();
            $("#t1").attr("required", "required");
            $("#t2").attr("required", "required");
            $("#t3").attr("required", "required");
        }
        else
        {
            $("#t1").removeAttr('required');
            $("#t2").removeAttr('required');
            $("#t3").removeAttr('required');
              
            document.getElementById("t1").value=null;
            document.getElementById("t2").value=null;
            document.getElementById("t3").value=null;
            $(".rendered").hide();

        }

        //--
        $("#contribuye").change(function()
        {
            contribuye =   $("#contribuye").val();
            if(contribuye==1)
            {

                $(".rendered2").show();
                $("#c1").attr("required", "required");


            }
            else
            {
               	$("#c1").removeAttr('required');
               
                document.getElementById("c1").value=null;
                $(".rendered2").hide();
            }
        });

        if(contribuye==1)
        {
            $(".rendered2").show();
            $("#c1").attr("required", "required");
        }
        else
        {
            $("#c1").removeAttr('required');
              
            document.getElementById("c1").value=null;
            $(".rendered2").hide();
        }

        //--
        $("#vivescon").change(function()
        {
            vivescon =   $("#vivescon").val();
            if(vivescon=="otros")
            {

                $(".rendered3").show();
                $("#v1").attr("required", "required");


            }
            else
            {
               	$("#v1").removeAttr('required');
               
                document.getElementById("v1").value=null;
                $(".rendered3").hide();
            }
        });

        if(vivescon=="otros")
        {
            $(".rendered3").show();
            $("#v1").attr("required", "required");
        }
        else
        {
            $("#v1").removeAttr('required');
              
            document.getElementById("c1").value=null;
            $(".rendered3").hide();

        }
    });
</script>
@endsection