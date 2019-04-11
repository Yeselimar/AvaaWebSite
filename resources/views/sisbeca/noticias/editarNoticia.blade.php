@extends('sisbeca.layouts.main')
@section('title','Editar publicación')
@section('content')

<div class="col-lg-12">
    <div class="text-right">
        <a href="{{ route('noticia.index') }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
    <br>

    <div class="col sisbeca-container-formulario">

        <form action="{{route('noticia.update',$noticia->id)}}" accept-charset="UTF-8" enctype="multipart/form-data" method="POST" class="form-horizontal">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label class="control-label" for="titulo">*Titulo</label>
                    <input class="sisbeca-input" placeholder="AVAA tiene nuevo sitio web" value="{{$noticia->titulo}}" name="titulo" type="text" id="titulo" required>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="informacion_contacto" class="control-label">*Publicador</label>
                    <input class="sisbeca-input" placeholder="John Doe" value="{{$noticia->informacion_contacto}}" name="informacion_contacto" type="text" id="informacion_contacto" required>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="tipo" class="control-label">Tipo</label>
                    <select class="sisbeca-input " id="tipo" name="tipo">
                        @if($noticia->tipo==='noticia')
                        <option value='noticia' selected>Noticia</option>
                        <option value='miembroins'>Miembro Institucional</option>
                        @else
                        <option value='noticia'>Noticia</option>
                        <option value='miembroins' selected>Miembro Institucional</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="row rendered">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="url_articulo" class="control-label">URL Miembro Institucional</label>
                    <input class="sisbeca-input" required placeholder="https://www.avaa.org" value="{{$noticia->url_articulo}}"
                        name="url_articulo" type="url" id="url_articulo">

                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="email_contacto" class="control-label">Email del contacto</label>
                    <input class="sisbeca-input" placeholder="johndoe@dominio.com" value="{{$noticia->email_contacto}}"
                        name="email_contacto" type="email" id="email_contacto">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="telefono_contacto" class="control-label">Telefono del contacto</label>
                    <input class="sisbeca-input" placeholder="0212-888.99.00" value="{{$noticia->telefono_contacto}}"
                        name="telefono_contacto" type="tel" id="telefono_contacto">
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="image" class="control-label">Imagen</label>
                    <input name="url_imagen" accept="image/*" type="file" id="url_imagen" class="sisbeca-input">
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <label for="imagen" class="control-label">Imagen Actual</label>
                    <button type="button" class="btn  sisbeca-btn-primary btn-block" data-toggle="modal" data-target="#ver">
                        <i class="fa fa-photo"></i> Ver
                    </button>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 destacada">
                    <label for="destacada" class="control-label">Destacada</label>
                    <div class="col-lg-12" style="border-radius: 5px;border:1px solid #021f3a;height: 40px;">
                        <div class="checkbox text-center" >
                            @if($noticia->esDestacada())
                            <input type="checkbox" value="1" name='destacada' id="destacada" checked="checked">
                            ¿Destacado en carrousel?
                            @else
                            <input type="checkbox" value="1" name='destacada' id="destacada">
                            ¿Destacado en carrousel?
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="contenido" class="control-label">*Contenido</label>
                <textarea class="textarea " placeholder="Ingrese el contenido" name="contenido" id="contenido" style="width: 100%; height: 400px;" required>
                    {{$noticia->contenido}}
                </textarea>
            </div>


            <hr>
            <div class="form-group text-right">
                <input class="btn sisbeca-btn-primary" type="submit" value="Guardar">
            </div>

        </form>
    </div>
</div>

<!-- Modal para ver imagen -->
<div class="modal fade" id="ver">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><strong>Publicación</strong></h5>
                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
            </div>
            <div class="modal-body">
                <br>
                <img src="{{url($noticia->url_imagen)}}" alt="{{$noticia->titulo}}" class="img-responsive sisbeca-border">
                <br><br>
                <p class="text-center h6">{{$noticia->titulo}}</p>
            </div>
            <div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
                <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal para ver imagen -->

@endsection

@section('personaljs')
<script>

    $(document).ready(function () {

        var mivalor = $("#tipo").val();


        $("#tipo").change(function () {
            mivalor = $("#tipo").val();
            if (mivalor == 'miembroins') {

                $(".rendered").show();
                $(".destacada").hide();
                document.getElementById("destacada").checked = 0
                $("#url_articulo").attr("required", "required")


            }
            else {
                $("#url_articulo").removeAttr('required');
                $(".destacada").show();
                document.getElementById("destacada").checked = 0
                document.getElementById("url_articulo").value = null;
                document.getElementById("email_contacto").value = null;
                document.getElementById("telefono_contacto").value = null;
                $(".rendered").hide();


            }
        });


        if (mivalor == 'miembroins') {
            $(".rendered").show();
            $(".destacada").hide();
            document.getElementById("destacada").checked = 0
            $("#url_articulo").attr("required", "required")
        }
        else {
            $("#url_articulo").removeAttr('required');
            $(".destacada").show();
            document.getElementById("destacada").checked = 0
            document.getElementById("url_articulo").value = null;
            document.getElementById("email_contacto").value = null;
            document.getElementById("telefono_contacto").value = null;
            $(".rendered").hide();

        }



    });
    
</script>
<script>
    $(document).ready(function()
    {
        textboxio.replace('textarea');

    });
</script>

@endsection