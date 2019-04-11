
{{Auth::check()}}
{{-- aqui en este extends esta  la funcion Auth::check() que autentifica si el usuario ya inicio sesion y lo redirige a la pagina principal del sistema --}}
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('info_sitio/img/favicon.png')}}" >
    <title>AVAA - Registrarse</title>

    @include('sisbeca.layouts.partials.filescss')
</head>

<body class="fix-header fix-sidebar">
<!-- Preloader - style you can find in spinners.css -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>

<div id="main-wrapper">
    <div class="header">
        @include('sisbeca.layouts.partials.navtop')
    </div>
    <br>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-5" style="padding-left: 0px;padding-right: 0px;">

                @include('flash::message')

                <div class="login-content panel panel-default">
                    <div class="panel-heading"  align="center">
                        <h2 style="color: white">Postalarse como Mentor</h2>
                    </div>

                    <div class="login-form panel-body" style="padding: 0px 30px 20px">

                        <h6 class="text-left">
                            <strong>Debes registrarte en el sistema para iniciar tu proceso de postulación como Mentor</strong>
                        </h6>
                        <form role="form" class="f1 formMultiple" method="post" action="{{ route('guardar.postulante.mentor') }}" enctype="multipart/form-data" style="padding: 0px!important">
                            {{ csrf_field() }}

                            <div class="f1-steps">
                                <div class="f1-progress">
                                    <div class="f1-progress-line" data-now-value="50" data-number-of-steps="2" style="width: 50%;"></div>
                                </div>
                                <div class="f1-step active" style="width: 50% !important">
                                    <div class="f1-step-icon"><i class="fa fa-key"></i></div>
                                    <p>Cuenta</p>
                                </div>

                                <div class="f1-step" style="width: 50% !important">
                                    <div class="f1-step-icon"><i class="fa fa-user"></i></div>
                                    <p>Personal</p>
                                </div>
                            </div>

                            <!-- Datos de Cuenta -->
                            <fieldset>
                                <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" placeholder="johndoe@dominio.com" class="f1-email sisbeca-input" id="f1-email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="{{ $errors->has('password') ? ' has-error' : '' }}">

                                    <label  for="password">Contraseña</label>
                                    <input type="password" name="password" placeholder="******" class="f1-password sisbeca-input" id="f1-password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div>
                                    <label for="password_confirmation">Repetir Contraseña</label>
                                    <input type="password" placeholder="******"
                                           class="f1-repeat-password sisbeca-input" id="f1-repeat-password" name="password_confirmation" required>
                                </div>

                                <div>
                                    <label for="image_perfil">Imagen Perfil</label>
                                    <input name="image_perfil" accept="image/*"  type="file" id="image" class="sisbeca-input">
                                    @if ($errors->has('image_perfil'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('image_perfil') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="f1-buttons">
                                    <a class="btn btn-next sisbeca-btn-default" href="/login">Atrás</a>
                                    <button type="button" class="btn btn-next sisbeca-btn-primary">Siguiente</button>
                                </div>
                            </fieldset>

                            <!-- Datos Personales -->
                            <fieldset>

                                <!--<h4>Complete sus Datos Personales:</h4>-->
                                <div class=" {{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Nombre</label>
                                    <input type="text" name="name" placeholder="John" class="f1-first-name sisbeca-input" id="f1-first-name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <label for="last_name">Apellido</label>
                                    <input type="text" name="last_name" placeholder="Doe" class="sisbeca-input" id="last_name" value="{{ old('last_name') }}" required autofocus>

                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                        <input  name="fecha_nacimiento" type="text" id="fecha_nacimiento"  placeholder="DD/MM/AAAA" class="sisbeca-input" required>
                                        @if ($errors->has('fecha_nacimiento'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('fecha_nacimiento') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="edad">Edad</label>
                                        <input name="edad" type="text" id="edad" class="sisbeca-input sisbeca-disabled" value="0" readonly="true">
                                        @if ($errors->has('edad'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('edad') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="cedula">Cédula</label>
                                        <input type="text" value="{{ old('cedula') }}" name="cedula" placeholder="11222333" class="sisbeca-input" id="cedula">
                                        @if ($errors->has('cedula'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('cedula') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="sexo">Sexo</label>
                                        <select class="sisbeca-input " id="sexo" name="sexo">
                                            <option value='masculino'>Masculino</option>
                                            <option value='femenino'>Femenino</option>
                                        </select>
                                        @if ($errors->has('sexo'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('sexo') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <!-- <div class="col-lg-12">
                                        <label for="url_pdf">Cargue su Hoja de Vida</label>
                                        <input name="url_pdf" accept="application/pdf" type="file" id="url_pdf" class="sisbeca-input" required>
                                        @if ($errors->has('url_pdf'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('url_pdf') }}</strong>
                                        </span>
                                        @endif
                                    </div> -->
                                </div>

                                <div class="f1-buttons">
                                    <button type="button" class="btn btn-previous sisbeca-btn-default">Anterior</button>
                                    <button type="submit" class="btn sisbeca-btn-primary">Registrar</button>
                                </div>
                            </fieldset>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content-ment">
                <div class="text-center">
                <img src="{{asset('images/postulacion-mentor.png')}}" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
    <!--Fin Modal -->

</div>

@include('sisbeca.layouts.partials.filesjs')
<script>
    $('#fecha_nacimiento').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        orientation: 'bottom',
        autoclose: true,
        endDate : '+0',
    });

    $(document).ready(function(){

        var mivalor= $("#fecha_nacimiento").val();


        $("#fecha_nacimiento").change(function() {
            mivalor =   $("#fecha_nacimiento").val();
            var valor=mivalor.split("/");

            var ano = valor[2];
            var mes = valor[1];
            var dia = valor[0];
            // cogemos los valores actuales
            var fecha_hoy = new Date();
            var ahora_ano = fecha_hoy.getFullYear();
            var ahora_mes = fecha_hoy.getMonth()+1;
            var ahora_dia = fecha_hoy.getDate();
            // realizamos el calculo
            var edad = ahora_ano  - ano;
            if ( ahora_mes < mes )
            {
                edad--;
            }
            if ((mes == ahora_mes) && (ahora_dia < dia))
            {
                edad--;
            }

            if(!isNaN(edad))
            {
                document.getElementById("edad").value=edad;
            }
            else
            {
                document.getElementById("edad").value=null;
            }
        });
    });


    $(document).ready(function()
    {
      $("#modal").modal("show");
    });

</script>
</body>

</html>
