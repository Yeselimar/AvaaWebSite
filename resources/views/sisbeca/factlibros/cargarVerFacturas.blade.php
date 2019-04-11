@extends('sisbeca.layouts.main')
@section('title','Listar Facturas')
@section('content')

<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('facturas.crear')}}" class="btn btn-sm sisbeca-btn-primary">Cargar Factura</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="facturas">
            <thead>
            <tr>
                <th class="text-center">Nombre</th>
                <th class="text-center">Curso</th>
                <th class="text-center">Costo</th>
                <th class="text-center">Estatus <br/> actual</th>
                <th class="text-center">Fecha <br/> registro</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
                @foreach($facturas as $factura)
                    <tr>
                        <td class="text-center">{{$factura->name }}</td>

                        <td class="text-center">{{ $factura->curso }}</td>

                        <td class="text-center">{{ $factura->obtenerCosto() }}</td>

                        <td class="text-center">
                            @if($factura->status==='cargada')
                                <span class="label label-warning">Cargada</span>
                            @else
                                @if($factura->status==='por procesar')
                                    <span class="label label-primary">Aprobada</span>
                                @else
                                    @if($factura->status==='revisada')
                                        <span class="label label-info">Revisada</span>
                                    @else
                                        @if($factura->status==='pagada')
                                            <span class="label label-success">Pagada</span>
                                        @else
                                            @if($factura->status==='rechazada')
                                                <span class="label label-danger">Rechazada</span>
                                            @else
                                                <span class="label label-default">{{ ucwords($factura->status) }}</span>
                                            @endif
                                        @endif

                                    @endif
                                @endif
                            @endif
                        </td>
                        
                        <td class="text-center">{{ date("d/m/Y", strtotime($factura->created_at))}}</td>

                        <td class="text-center">

                            <a class="btn btn-xs sisbeca-btn-primary" target="_blank" href="{{asset($factura->url)}}" data-toggle="tooltip" data-placement="bottom" title="Ver Factura">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('personaljs')
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<script >
$(document).ready(function(){
    $('#facturas').DataTable({

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