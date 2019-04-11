@extends('sisbeca.layouts.main')
@section('personaljs')
<script src="{{asset('public_sisbeca/js/summernote.js')}}"></script>  

<link src="{{asset('public_sisbeca/css/summernote.css')}}"/>

<script>
        $(document).ready(function() {
            $('.summernote').summernote();
        });
</script>
@endsection
@section('title','Crear publicación')
@section('content')

<div class="col-lg-12">
    <div class="text-right">
        <a href="{{ route('noticia.index') }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
    <br>
    <div class="col sisbeca-container-formulario">
        <form action="{{route('noticia.store')}}" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" class="form-horizontal" files="true">
            {{csrf_field()}}
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label class="control-label" for="titulo">*Titulo</label>
                    <input class="sisbeca-input" placeholder="AVAA tiene nuevo sitio web" value="{{old('titulo')}}" name="titulo" type="text" id="titulo" >
                    <span class="errors">{{ $errors->first('titulo') }}</span>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="informacion_contacto" class="control-label">*Publicador</label>
                    <input class="sisbeca-input" placeholder="John Doe" value="{{old('informacion_contacto')}}" name="informacion_contacto" type="text" id="informacion_contacto" >
                    <span class="errors">{{ $errors->first('informacion_contacto') }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="tipo" class="control-label">Tipo</label>
                    <select class="sisbeca-input " id="tipo" name="tipo" >
                        <option value='noticia'>Noticia</option>
                        <option value='miembroins'>Miembro Institucional</option>
                    </select>
                </div>
            </div>
            <div class="row rendered">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="url_articulo" class="control-label">URL Miembro Institucional</label>
                    <input class="sisbeca-input"  placeholder="https://www.avaa.org" value="{{old('url_articulo')}}" name="url_articulo" id="url_articulo" >
                        <span class="errors" style="color:#red">{{ $errors->first('url_articulo') }}</span>
                </div>


                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="email_contacto" class="control-label">Email del contacto</label>
                    <input class="sisbeca-input" placeholder="johndoe@dominio.com" value="{{old('email_contacto')}}" name="email_contacto" id="email_contacto" >
                    <span class="errors" style="color:#red">{{ $errors->first('email_contacto') }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="telefono_contacto" class="control-label">Telefono del contacto</label>
                    <input class="sisbeca-input" placeholder="0212-8880099" value="{{old('telefono_contacto')}}" name="telefono_contacto" id="telefono_contacto" >
                    <span class="errors">{{ $errors->first('telefono_contacto') }}</span>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="image" class="control-label">*Imagen</label>
                    <input name="url_imagen" accept="image/*" type="file" id="url_imagen" class="sisbeca-input">
                    <span class="errors" style="color:red">{{ $errors->first('url_imagen') }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12 destacar">
                    <label for="destacada" class="control-label">Destacada</label>
                    <div class="col-lg-12" style="border-radius: 5px;border:1px solid #021f3a;height: 40px;">
                        <div class="checkbox text-center" >
                            <input type="checkbox" value="1" id="destacada" name='destacada'>
                            ¿Destacado en carrousel?
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="contenido" class="control-label">*Contenido</label>
                <textarea name="summernoteInput" class="summernote">
                    {{old('contenido')}}
                </textarea>
                
                <span class="errors" style="color:red">{{ $errors->first('contenido') }}</span>
            </div>
            <hr>
            <div class="form-group text-right">
                <input class="btn sisbeca-btn-primary" type="submit" value="Guardar">
            </div>


        </form>
    </div>
</div>
@endsection

@section('personaljs')

<!-- include summernote css/js-->

<script>

    $(document).ready(function () {

        var mivalor = $("#tipo").val();


        $("#tipo").change(function () {
            mivalor = $("#tipo").val();
            if (mivalor == 'miembroins') {
                document.getElementById("destacada").checked = 0
                $(".rendered").show();
                $(".destacar").hide();
                $("#url_articulo").attr("required", "required")

            }
            else {
                $("#url_articulo").removeAttr('required');
                $(".destacar").show();
                document.getElementById("destacada").checked = 0
                document.getElementById("url_articulo").value = null;
                document.getElementById("email_contacto").value = null;
                document.getElementById("telefono_contacto").value = null;
                $(".rendered").hide();


            }
        });


        if (mivalor == 'miembroins') {
            document.getElementById("destacada").checked = 0
            $(".rendered").show();
            $(".destacar").hide();
            $("#url_articulo").attr("required", "required");
        }
        else {
            $("#url_articulo").removeAttr('required');
            $(".destacar").show();
            document.getElementById("destacada").checked = 0
            document.getElementById("url_articulo").value = null;
            document.getElementById("email_contacto").value = null;
            document.getElementById("telefono_contacto").value = null;
            $(".rendered").hide();

        }


    });

</script>

@endsection

