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
    <title>AVAA - Recuperación de Contraseña</title>

    @include('sisbeca.layouts.partials.filescss')
</head>

<body class="fix-header fix-sidebar">
<!-- Preloader - style you can find in spinners.css -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- Main wrapper  -->
<div id="main-wrapper">
    <div class="header">
        @include('sisbeca.layouts.partials.navtop')
    </div>
    <br>
    <div class="unix-login">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6 col-sm-7" style="padding-left: 0px;padding-right: 0px;">
                    <div class="login-content panel panel-default">
                        <div class="panel-heading" align="center">
                            <h2 style="color: white"> Recuperar Contraseña</h2>
                        </div>
                        <div class="login-form panel-body" style="padding-top: 0px!important">

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <h6 class="text-left">
                                <strong>Ingresa tu correo electrónico en donde te enviaremos los pasos a seguir para recuperar la contraseña.</strong>
                            </h6>

                            <form class="form-horizontal"  method="POST" action="{{ route('enviar.correo') }}">
                                {{ csrf_field() }}
                                 @include('flash::message')
                                <div class="form-group">
                                    <label for="correo" class="control-label">Correo Electrónico</label>

                                    <div >
                                        <input type="email" class="sisbeca-input" name="correo" value="{{ old('email') }}" required>

                                        <span class="errors" style="color:#red">{{ $errors->first('correo') }}</span>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-12 text-right" >
                                        <a href="/login" class="btn btn-previous sisbeca-btn-default ">Atrás</a>
                                        <button type="submit" class="btn sisbeca-btn-primary" >Recuperar Contraseña</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Wrapper -->
<!-- All Jquery -->

@include('sisbeca.layouts.partials.filesjs')

</body>

</html>