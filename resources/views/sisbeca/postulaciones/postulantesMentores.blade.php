@extends('sisbeca.layouts.main')
@section('title','Postulaciones a Mentor')
@section('content')
<div class="col-lg-12">

    <div class="table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th class="text-center">Nombre y Apellido</th>
                <th class="text-center">Cédula</th>
                <th class="text-center">Correo Electrónico</th>
                <th class="text-center">Estatus</th>
                <th class="text-center">Procesar</th>
            </tr>
            </thead>
            <tbody>
                @if($postulantes->count()>0)
                @foreach($postulantes as $postulante)
                    <tr>
                        <td class="text-center">{{ $postulante->user->name.' '.$postulante->user->last_name }}</td>
                        <td class="text-center">{{ $postulante->user->cedula }}</td>
                        <td class="text-center">{{ $postulante->user->email }}</td>
                         @if($postulante->status=='rechazado')

                        <td class="text-center">
                            <span class="label label-danger">Rechazado</span>
                        </td>
                        @else
                        <td class="text-center">
                            <span class="label label-default">Sin Revisar</span></td>
                        @endif

                        <td class="text-center">
                            <span data-toggle="tooltip" data-placement="bottom" title="Ver Postulación">
                                <a href="{{route('perfilPostulantesMentores', $postulante->user->id)}}" class='btn btn-xs sisbeca-btn-primary'>
                                    <i class='fa fa-eye -square-o'></i>
                                </a>
                            </span>
                        </td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">No hay <strong>postulantes a mentor</strong> </td>
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
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
