@extends('sisbeca.layouts.main')
@section('title','Publicaciones')
@section('content')

<div class="col-lg-12">
    <div class="text-right">
        <a href="{{ route('noticia.create') }}" class="btn btn-sm sisbeca-btn-primary">Crear publicación</a>
    </div>
    <div class="table-responsive" style="border:1px solid #eee">
        <table class="table table-bordered table-hover" id="noticias">
            <thead>
                <tr>
                    <th>Título</th>
                    <th class="text-center">Tipo</th>
                    <th>Creado por</th>
                    <th>Actualizado el</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($noticias->count()!=0)
                    @foreach($noticias as $noticia)
                    <tr>
                        <td>
                            {{$noticia->titulo}}

                        </td>
                        <td class="text-center">{{strtoupper($noticia->tipo)}}
                            @if($noticia->tipo !=="miembroins")
                                <br>
                                @if($noticia->esDestacada())
                                    <span class="label label-success">Destacada</span>
                                @else
                                    <span class="label label-danger">No destacada</span>
                                @endif    
                            @endif
                        </td>
                        <td>{{$noticia->editor->nombreyapellido()}}</td>
                        <td>{{$noticia->fechaActualizacion()}}</td>
                        <td class="text-center">
                            <a href="{{route('showNoticia',$noticia->slug)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver Publicación">
                                <i class="fa fa-eye"></i>
                            </a>
                            <span data-toggle="modal" data-target="#ver{{$noticia->id}}">
                                <button type="button" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver imagen">
                                    <i class="fa fa-photo"></i>
                                </button>
                            </span>
                            <a href="{{route('noticia.edit',$noticia->id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar" > 
                                <i class="fa fa-pencil"></i>
                            </a>
                            <span data-toggle="modal" data-target="#eliminar{{$noticia->id}}">
                                <button type="button" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="5" class="text-center">No hay <strong>publicaciones</strong></td>
                </tr>
                @endif
            </tbody>

        </table>

    </div>

</div>

@foreach($noticias as $noticia)
<!-- Modal para eliminar -->
<div class="modal fade" id="eliminar{{$noticia->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><strong>Publicación</strong></h5>
                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <br>
                <p>¿Está seguro que desea eliminar la publicación <strong>{{$noticia->titulo}}</strong>?</p>
            </div>
            <div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
                <a href="{{route('noticia.destroy',$noticia->id)}}" class="btn btn-sm sisbeca-btn-default pull-right">Si</a>
                <button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal para eliminar -->

<!-- Modal para ver imagen -->
<div class="modal fade" id="ver{{$noticia->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Publicación</strong></h5>
            </div>
            <div class="modal-body">
                <br>
                <img src="{{url($noticia->url_imagen)}}" alt="{{$noticia->titulo}}" class="img-responsive sisbeca-border" >
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
@endforeach

    <!-- /.modal-content -->
</div>

@endsection

@section('personaljs')
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

<script>
$(document).ready(function() {
    $('#noticias').DataTable({

        "language": {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No hay resultados encontrados",
        "paginate":
            {
                "first": "Primero",
                "last": "Ultimo",
                "next": '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                "previous": '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
            }
        }
    });
});
</script>

@endsection