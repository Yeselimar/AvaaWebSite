@extends('sisbeca.layouts.main')
@section('title','Inicio')
@section('subtitle','Mantenimiento de Noticias')
@section('content')
<div class="col-lg-12">
    <div class="text-right">
        <a href="{{ route('mantenimientoUser.create') }}" class="btn btn-sm sisbeca-btn-primary">Registrar Nuevo Usuario</a>
    </div>
    
    <div clas="table-responsive">
        <table class="table table-hover table-bordered" id="usuarios">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo Electrónico</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
                
            </thead>
            <tbody>
                @if($usuarios->count()!=0)
                @foreach($usuarios as $usuario)
                <tr>
                    <td>{{$usuario->cedula}}</td>
                    <td>{{$usuario->name}}</td>
                    <td>{{$usuario->last_name}}</td>
                    <td>{{$usuario->email}}</td>
                    <td>{{$usuario->rol}}</td>
                    <td>
                        <a href="{{route('mantenimientoUser.edit',$usuario->id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar" > 
                                <i class="fa fa-pencil"></i>
                        </a>

                        <span data-toggle="modal" data-target="#eliminar{{$usuario->id}}">
                            <button type="button" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                <i class="fa fa-trash"></i>
                            </button>
                        </span>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6">No se encontró <strong>usuarios</strong></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

</div>

@foreach($usuarios as $usuario)
<!-- Moda para eliminar -->
<div class="modal fade" id="eliminar{{$usuario->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title pull-left">Usuarios</h4>
                <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
            </div>
            <br>
            <div class="modal-body">
                <p class="text-left h6">¿Esta seguro que desea eliminar el siguiente usuario?</p>

                <strong>Nombre y Apellido:</strong> <input value="{{$usuario->nombreyapellido()}}" class="input-modal-none" disabled>
                <br>
                <strong>Cédula:</strong> <input id="cedula" class="input-modal-none" value="{{$usuario->cedula}}" disabled>
            </div>
            <div class="modal-footer">
                <a href="{{route('mantenimientoUser.destroy',$usuario->id)}}" class="btn btn-sm sisbeca-btn-primary pull-left">Si</a>
                <button type="button" class="btn btn-sm sisbeca-btn-default pull-left" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- Moda para eliminar -->
@endforeach

@endsection

@section('personaljs')
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

<script>
$(document).ready(function(){
    $('#usuarios').DataTable({
        "ordering": true,

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