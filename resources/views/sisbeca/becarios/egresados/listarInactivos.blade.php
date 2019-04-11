@extends('sisbeca.layouts.main')
@section('title','Becarios Inactivos')
@section('content')

<div class="col-lg-12">
  
    <p class="text-left"><strong>Becarios Inactivos</strong></p>

    <div class="table-responsive">
        
        <table id="becarios" class="table table-bordered table-hover" style="border: 1px solid #eee">
            <thead>
                <tr>
                    <th class="text-center">Nombre y Apellido</th>
                    <th class="text-center">Cédula</th>
                    <th class="text-center">Correo Electrónico</th>
                    <th class="text-center">Observación</th>
                    <th class="text-center">Fecha Inactivo</th>
                    <th class="text-center">Perfil</th>
                </tr>
            </thead>
            <tbody>
            @if($becarios->count() > 0)
                @foreach($becarios as $becario)
                    <tr>
                        <td class="text-center">{{ $becario->user->name.' '.$becario->user->last_name }}</td>
                        <td class="text-center">{{ $becario->user->cedula }}</td>
                        <td class="text-center">{{ $becario->user->email }}</td>
                        <td class="text-center">{{ $becario->observacion_inactivo }}</td>
                        <td class="text-center">{{ date("d/m/Y", strtotime($becario->fecha_inactivo)) }}</td>

                        <td class="text-center">
                            <a href="{{route('postulanteObecario.perfil',$becario->user_id)}}" class='btn btn-xs sisbeca-btn-primary'>
                                <i class='fa fa-eye'></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No hay <strong>becarios inactivos</strong></td>
                </tr>
            @endif
            </tbody>
        </table>
        
    </div>

</div>

@endsection

@section('personaljs')
<script>
 $(document).ready(function() {
    $('#becarios').DataTable({
        "ordering": false,

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
</script>

@endsection
