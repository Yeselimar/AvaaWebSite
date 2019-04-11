@section('personalcss')
 <script src="{{asset('public_sisbeca/js/lib/bootstrap/js/popper.min.js')}}"></script> 
@endsection
@extends('sisbeca.layouts.main')
@section('title','Solicitudes/Reclamos')
@section('content')
<div class="col-lg-12" >
    <div class="table-responsive">

        <table class="table table-bordered table-hover" id="solicitudes" style="border: 1px solid #eee">
            <thead>
                <tr>
                    <th class="text-center" style="width:1px !important">Tipo</th>
                    <th class="text-center"  style="width:1px !important">Estatus <br> proceso</th>
                    <th class="text-center"  style="width:1px !important">Solicitada <br> Por</th>
                    <th class="text-center" style="width:1px !important">Usuario <br> Respuesta</th>
                    <th class="text-center"  style="width:1px !important">Fecha <br> Solicitud</th>
                    <th class="text-center"  style="width:1px !important">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($solicitudes->count() > 0)
                @foreach($solicitudes as $solicitud)
                    <tr>
                        <td class="text-center"  style="width:1px !important">{{strtoupper( $solicitud->titulo )}}</td>
                        @if($solicitud->status==='aceptada')
                            <td class="text-center">
                                <span class="label label-success">Aceptada
                                </span>
                            </td>
                        @else
                            @if($solicitud->status==='enviada')
                                <td class="text-center">
                                    <span class="label label-warning">Por Procesar</span>
                                </td>
                            @else
                                <td class="text-center"> 
                                    <span class="label label-danger">
                                        Rechazada
                                    </span>
                                </td>
                            @endif
                        @endif
                        <td class="text-center">
                            {{ $solicitud->user->name.' '.$solicitud->user->last_name}}<br/> Rol: {{ucwords($solicitud->user->rol)}}
                            <br/>
                            Cédula: {{$solicitud->user->cedula}}
                            
                        </td>


                    @if(\avaa\User::find($solicitud->usuario_respuesta))
                        <td class="text-center">
                            {{ \avaa\User::find($solicitud->usuario_respuesta)->nombreyapellido()}}
                            <br> ({{\avaa\User::find($solicitud->usuario_respuesta)->email}})
                        </td>
                    @else
                            <td class="text-center">
                                <span class="label label-warning">-</span>
                            </td>
                        @endif

                        <td class="text-center" data-order="{{date('d/m/Y H:i:s', strtotime($solicitud->created_at))}}">
                            {{ date("d/m/Y h:i a", strtotime($solicitud->created_at)) }}
                        </td>

                        <td class="text-center">
                            <a href="{{route('solicitud.revisar',$solicitud->id)}}" class='btn btn-xs sisbeca-btn-primary' data-toggle="tooltip" data-placement="bottom" title="Ver solicitud">
                                <i class='fa fa-eye'></i>
                            </a>

                            @if(Auth::user()->esDirectivo() && $solicitud->status!=='enviada')
                            <a href="{{route('solicitud.ocultar.admin',$solicitud->id)}}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ocultar solicitud">
                                <i class="fa fa-eye-slash"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="6" class="text-center">No hay <strong>solicitudes</strong></td>
                </tr>
                @endif
            </tbody>
        </table>

    </div>
</div>
@endsection

@section('personaljs')
<!--
<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
            columnDefs: [
                { targets: [5], searchable: false}
            ]
        } );
    } );

    $('#myTable')
        .removeClass( 'display' )
        .addClass('table table-hover');
</script>
-->

<script>
$(document).ready(function(){
    $('#solicitudes').DataTable({
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
        },
        "order": [[ 4, 'desc' ]],
    });
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@endsection
