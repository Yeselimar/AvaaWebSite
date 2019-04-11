@extends('sisbeca.layouts.main')
@section('title','Todos los Postulantes a ProExcelencia')
@section('content')
<div class="col-12">
    <div class=" table-responsive">
        <form class="form-horizontal" method="post" action ="">
            {{csrf_field()}}
            <table id="postulantes" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Estatus</th>
                        <th class="text-center">Nombre y Apellido</th>
                        <th class="text-center">Cédula</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Intentos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @if($postulantes->count() > 0)
                    @foreach($postulantes as $postulante)
                            <tr>
                                <td class="text-center">
                                    <span class="label label-danger">Rechazado</span>
                                </td>
                                <td class="text-center">{{ $postulante->name.' '.$postulante->last_name }}</td>
                                <td class="text-center">{{ $postulante->cedula }}</td>
                                <td class="text-center">{{ $postulante->celular }}</td>
                                <td class="text-center">{{ $postulante->intentos }}</td>
                                <td class="text-center">
                                    <a href="{{route('postulante.eliminar',$postulante->cedula)}}" class='btn btn-xs sisbeca-btn-default' data-toggle="popover" data-trigger="hover" data-content="Borrar Data" data-placement="left" >
                                        <i class='fa fa-times' ></i>
                                    </a>
                                    <a href="{{route('perfilPostulanteBecario', $postulante->user_id)}}" class='btn btn-xs sisbeca-btn-default' data-toggle="popover" data-trigger="hover" data-content="Quitar de esta lista" data-placement="left" >
                                        <i class='fa fa-trash' ></i>
                                    </a>
                                </td>
                            </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="5">No hay <strong>postulantes a ProExcelencia</strong>.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </form>
    </div>
    <br>
    <div class="alert  alert-danger alert-important" role="alert">
    <a class="label label-danger letras-blancas">Antes</a> Postulantes que fueron Rechazados para ir a la Entrevista.
    </div>
    <div class="alert  alert-danger alert-important" role="alert">
    <a class="label label-danger letras-blancas">Durante</a> Postulantes que fueron rechazados durante la Entrevista.
    </div>
    <div class="alert  alert-danger alert-important" role="alert">
    <a class="label label-danger letras-blancas">Después</a> Postulantes que fueron rechazados después de la Entrevista.
    </div>


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
    $('#postulantes').DataTable({
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
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
});

    $( function() {
        $( "#datepicker" ).datepicker({ minDate: 0, maxDate: "+2M +10D",orientation: "bottom" });
    } );
</script>
<script>
$(document).ready(function(){
$('[data-toggle="popover"]').popover();
});
</script>
@endsection
