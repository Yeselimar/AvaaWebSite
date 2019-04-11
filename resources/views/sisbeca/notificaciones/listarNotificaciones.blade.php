@extends('sisbeca.layouts.main')
@section('title','Notificaciones')
@section('content')

    <div class="col-lg-12">

        @if($notificaciones->count() > 0)
            <div class="table-responsive">
                <table id="myTable" data-order='[[ 2, "desc" ]]' data-page-length='10' class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-right" style="width: 50px!important;display: none;">Imagen</th>
                            <th class="text-left" style="display: none;">Descripcion</th>
                            <th class="text-center" style="display: none;">Fecha</th>
                        </tr>
                    </thead>
                    <tbody >
                    @foreach($notificaciones as $alerta)
                        <tr class="tr">
                            @if(Auth::user()->rol==='coordinador')
                                @if($alerta->solicitud)
                                    <td class="text-right" style="width: 50px!important;"><a href="{{route('solicitud.revisar',$alerta->solicitud)}}"> <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div></a></td>
                                    <td class="text-left" style="font-size: 16px;font-weight: bold"><a style="color: #3d91d6;" href="{{route('solicitud.revisar',$alerta->solicitud)}}">{{$alerta->descripcion}}</a></td>

                                @else
                                    <td class="text-right" style="width: 50px!important;"><a href="javascript:void(0)"> <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div></a></td>
                                    <td class="text-left" style="font-size: 16px;font-weight: bold"><a style="color: #3d91d6;" href="javascript:void(0)">{{$alerta->descripcion}}</a></td>

                                @endif
                            @else
                                @if(Auth::user()->rol==='becario'||Auth::user()->rol==='mentor')
                                <td class="text-right" style="width: 50px!important;">
                                        <a href="
                       @if(is_null($alerta->solicitud))
                                        @if(Auth::user()->rol==='becario')
                                        {{route('ver.mentorAsignado')}}
                                        @else
                                        {{route('listar.becariosAsignados')}}
                                        @endif
                                        @else
                                        {{route('solicitud.edit',$alerta->solicitud)}}
                                        @endif
                                                "
                                        >
                                        <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div></a></td>

                                <td class="text-left" style="font-size: 16px;font-weight: bold">
                                    <a style="color: #3d91d6;"

                                       href="
                                       @if(is_null($alerta->solicitud))
                                                       @if(Auth::user()->rol==='becario')
                                                       {{route('ver.mentorAsignado')}}
                                                       @else
                                                       {{route('listar.becariosAsignados')}}
                                                       @endif
                                                       @else
                                                       {{route('solicitud.edit',$alerta->solicitud)}}
                                                       @endif
                                                               "
                                    >

                                        {{$alerta->descripcion}}


                                    </a></td>
                                @else
                                    @if(Auth::user()->rol==='directivo')
                                        @if($alerta->tipo == 'nomina')
                                            <td class="text-right" style="width: 50px!important;"><a href="{{route('nomina.procesar')}}"> <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div></a></td>
                                            <td class="text-left" style="font-size: 16px;font-weight: bold"><a style="color: #3d91d6;" href="{{route('nomina.procesar')}}">{{$alerta->descripcion}}</a></td>

                                        @else
                                            @if($alerta->tipo == 'solicitud' && $alerta->solicitud)
                                            <td class="text-right" style="width: 50px!important;"><a href="{{route('solicitud.revisar',$alerta->solicitud)}}"> <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div></a></td>
                                            <td class="text-left" style="font-size: 16px;font-weight: bold"><a style="color: #3d91d6;"href="{{route('solicitud.revisar',$alerta->solicitud)}}">{{$alerta->descripcion}}</a></td>

                                            @else
                                            <td class="text-right" style="width: 50px!important;"><a href="javascript:void(0)"> <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div></a></td>
                                            <td class="text-left" style="font-size: 16px;font-weight: bold"><a style="color: #3d91d6;"href="javascript:void(0)">{{$alerta->descripcion}}</a></td>

                                            @endif
                                        @endif
                                    @else
                                    <td class="text-right" style="width: 50px!important;"><a href="javascript:void(0)"> <div class="btn btn-danger btn-circle m-r-10"><i class="ti-user"></i></div></a></td>
                                    <td class="text-left" style="font-size: 16px;font-weight: bold"><a style="color: #3d91d6;" href="javascript:void(0)">{{$alerta->descripcion}}</a></td>
                                        @endif
                                @endif
                            @endif

                                <td class="text-left" style="font-size: 16px;font-weight: bold"><strong>
                                {{ date("d/m/Y h:i:s A", strtotime($alerta->created_at)) }}

                                </strong></td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert  alert-warning alert-important" role="alert">
                No hay notificaciones.
            </div>
        @endif
    </div>

@endsection

@section('personaljs')
<script>
$(document).ready(function(){
    $('#myTable').DataTable({
        "language": {
        "decimal": "",
        "emptyTable": "No hay notificaciones",
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
        },
        "order": [[ 2, 'desc' ]],
    });
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>

@endsection
