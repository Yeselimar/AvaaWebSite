@extends('sisbeca.layouts.main')
@section('title','Editar Usuario: '.$user->nombreyapellido())
@section('content')
<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('mantenimientoUser.index')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
    <br>
    <div class="col sisbeca-container-formulario">
        <form method="POST" action={{route('mantenimientoUser.update',$user->id)}} accept-charset="UTF-8" class="form-horizontal">

            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="name"  class="control-label">Nombre</label>
                    <input class="sisbeca-input" placeholder="John" value="{{$user->name}}" name="name" type="text" id="name" required>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="last_name"  class="control-label">Apellido</label>
                    <input class="sisbeca-input" placeholder="Doe" value="{{$user->last_name}}" name="last_name" type="text" id="name" required>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="cedula"  class="control-label">Cédula</label>
                    <input class="sisbeca-input" placeholder="11222333" value="{{$user->cedula}}" name="cedula" type="text" id="cedula">
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="rol"  class="control-label">Rol</label>
                    <select class="sisbeca-input" required id="rol" name="rol">
                        <option value=''>Seleccione Rol</option>
                        @if($user->rol=='directivo')
                        <option selected value='directivo'>Directivo</option>
                        @else
                        <option value='directivo'>Directivo</option>
                        @endif

                        @if($user->rol=='editor')
                        <option selected value='editor'>Editor (Pasante)</option>
                        @else
                        <option value='editor'>Editor (Pasante)</option>
                        @endif

                        @if($user->rol=='coordinador')
                        <option selected value='coordinador'>Coordinador</option>
                        @else
                        <option value='coordinador'>Coordinador</option>
                        @endif

                        @if($user->rol=='entrevistador')
                        <option selected value='entrevistador'>Entrevistador</option>
                        @else
                        <option value='entrevistador'>Entrevistador</option>
                        @endif

                    </select>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="cedula"  class="control-label">Correo Electrónico</label>
                    <input class="sisbeca-input" placeholder="johndoe@dominio.com" value="{{$user->email}}"  name="email" type="email" id="email" required>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password"  class="control-label">Contraseña</label>
                    <input class="sisbeca-input" placeholder="******" required name="password" type="password" id="password">
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label for="password-repeat"  class="control-label">Repetir Contraseña</label>
                    <input class="sisbeca-input" placeholder="******" name="password-repeat" type="password" id="password-repeat" required>
                </div>
            </div>

            <hr>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 text-right" >
                        <a href="{{route('mantenimientoUser.index')}}" class="btn sisbeca-btn-default">Cancelar</a>
                        &nbsp;&nbsp;

                        <input class="btn sisbeca-btn-primary" type="submit" value="Guardar">
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection

@section('personaljs')
<script type="text/javascript">

    function Regresar() {

        var route = "{{route('mantenimientoUser.index')}}";


        location.assign(route);

    }
</script>
@endsection