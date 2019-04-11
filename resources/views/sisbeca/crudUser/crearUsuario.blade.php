@extends('sisbeca.layouts.main')
@section('title','Crear Usuario')
@section('content')

<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('mantenimientoUser.index')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
    <br>
    <div class="col sisbeca-container-formulario">
        <form class="form-horizontal" action="{{route('mantenimientoUser.store')}}" accept-charset="UTF-8" method="POST">
            {{csrf_field()}}
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="name" class="control-label">Nombre</label>
                        <input class="sisbeca-input" placeholder="John" value="{{old('name')}}"
                            required name="name" type="text" id="name">
                        <span class="errors" style="color:#red">{{ $errors->first('name') }}</span>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="last_name" class="control-label">Apellido</label>
                        <input class="sisbeca-input" placeholder="Doe" value="{{old('name')}}"
                            required name="last_name" type="text" id="name">
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="cedula" class="control-label">Cédula</label>
                        <input class="sisbeca-input" placeholder="24888999" value="{{old('cedula')}}"
                                name="cedula" type="text" id="cedula">
                        <span class="errors" style="color:#red">{{ $errors->first('cedula') }}</span>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="rol" class="control-label">Rol</label>
                        <select class="sisbeca-input" required id="rol" name="rol">
                            <option value=''>Seleccione Rol</option>
                            <option value='directivo'>Directivo</option>
                            <option value='editor'>Editor (Pasante)</option>
                            <option value='coordinador'>Coordinador</option>
                            <option value='entrevistador'>Entrevistador</option>
                        </select>
                    </div>
                
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="email" class="control-label">Correo Electrónico</label>
                        <input class="sisbeca-input" placeholder="jonhdoe@dominio.com" value="{{old('email')}}"
                                name="email" type="email" id="email" required>
                        <span class="errors" style="color:#red">{{ $errors->first('email') }}</span>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="password" class="control-label">Contraseña</label>
                        <input class="sisbeca-input" placeholder="******" name="password"
                                type="password" id="password" required>
                        <span class="errors" style="color:#red">{{ $errors->first('password') }}</span>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="password-repeat"  class="control-label">Repetir Contraseña</label>
                        <input class="sisbeca-input" placeholder="*****" name="password-repeat"
                                type="password" id="password-repeat" required>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 text-right" >
                        <button onclick="Regresar()" class="btn sisbeca-btn-default" type="button">Cancelar</button>

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