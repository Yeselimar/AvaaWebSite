@extends('sisbeca.layouts.main')
@section('title','Mentores')
@section('content')

<div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="mentores" style="border:1px solid #eee">
            <thead>
                <tr>
                    <th class="text-center">Nombre y Apellido</th>
                    <th class="text-center">Cédula</th>
                    <th class="text-center">Correo Electrónico</th>
                    <th class="text-center">Becario(s) Asignado(s)</th>
                    <th class="text-center">Perfil</th>
                </tr>
            </thead>
            <tbody>
                @if($mentores->count()>0)
                    @foreach($mentores as $mentor)
                        <tr>
                            <td class="text-center">{{ $mentor->user->name.' '.$mentor->user->last_name }}</td>
                            <td class="text-center">{{ $mentor->user->cedula }}</td>
                            <td class="text-center">{{ $mentor->user->email }}</td>

                            @if($mentor->becarios->count()>0)
                                <td class="text-center">
                                    @foreach($mentor->becarios as $becario)
                                       <span class="label label-success">{{$becario->user->name.' '.$becario->user->last_name}}</span>,
                                    @endforeach
                                </td>
                            @else
                                <td class="text-center">
                                    <span class="label label-danger">sin asignar</span>
                                </td>
                            @endif

                            <td class="text-center">
                                <a href="{{ route('ver.perfilMentor',$mentor->user_id) }}" class='btn btn-xs sisbeca-btn-primary' title="Ver Perfil">
                                    <i class='fa fa-eye'></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="6">
                        No hay <strong>mentores</strong>.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('personaljs')
<script>
$(document).ready(function(){
    $('#mentores').DataTable({
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
                "last": "Último",
                "next": '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                "previous": '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
            }
        }
    });
});
</script>

@endsection
