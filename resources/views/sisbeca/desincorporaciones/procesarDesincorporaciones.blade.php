@extends('sisbeca.layouts.main')
@section('title','Gestiones')
@section('subtitle','Listar Desincorporaciones')
@section('content')
<div class="col">

    <div class="panel-group Material-default-accordion" id="Material-accordion" role="tablist" aria-multiselectable="true">

        <div class="panel panel-default-accordion mb-3">
            <div class="panel-accordion" role="tab" id="heading">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion" href="#collapse" aria-expanded="false" aria-controls="collapse">
                        Desincorporaciones Recomendadas por Solicitudes Internas
                    </a>
                </h4>
            </div>
            <div id="collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading">
                <div align="justify" class="panel-body">
                    @if($desincorporacionesSolicitud->count()>0)
                        <table id="myTable" data-order='[[ 4, "desc" ]]' data-page-length='10' class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center">Descripcion</th>
                                <th class="text-center">Solicitada por</th>
                                <th class="text-center">Gestionada por</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Fecha Gestionada</th>
                                <th class="text-center">Desincorporar</th>
                            </tr>
                            </thead>
                            <tbody >
                            @foreach($desincorporacionesSolicitud as $d)
                                <tr>
                                    <td class="text-center">{{$d->observacion }}</td>


                                    <td class="text-center">{{$d->datos_nombres.' '.$d->datos_apellidos}}<br> Rol: {{ucwords($d->datos_rol)}} <br> CI: {{$d->datos_cedula}} <br> Email:<strong>{{$d->datos_email}}</strong></td>
                                    @if(!is_null(\avaa\User::find($d->gestionada_por)))
                                        <td class="text-center">{{ \avaa\User::find($d->gestionada_por)->name}} <br> <strong>({{\avaa\User::find($d->gestionada_por)->email}})</strong></td>
                                    @else
                                        <td></td>
                                    @endif

                                    @if($d->status==='sin ejecutar')
                                        <td class="text-center"><span class="label label-light-danger"><strong>{{strtoupper( $d->status )}}</strong></span></td>
                                    @else
                                            <td class="text-center"><span class="label label-light-info"><strong>{{strtoupper( $d->status )}}</strong></span></td>
                                    @endif

                                    @if(!is_null($d->fecha_gestionada))
                                          <td class="text-center">{{ date("d/m/Y h:i:s A", strtotime($d->created_at)) }}</td>
                                    @else
                                        <td></td>
                                        @endif

                                    @if($d->status==='sin ejecutar')
                                        <td class="text-center">
                                            <a href="{{route('desincorporacion.procesar',array('user_id'=>$d->user_id,'id'=>$d->id))}}"
                                               class='btn btn-primary' title="Desincorporar"><i class='fa fa-share-square-o'></i></a>
                                        </td>
                                    @else
                                        <td class="text-center"><span class="label label-light-info"><strong>USUARIO DESINCORPORADO el {{date("d/m/Y h:i:s A", strtotime($d->updated_at))}}</strong></span></td>
                                    @endif

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert  alert-warning alert-important" role="alert">
                            Disculpe, actualmente no tiene desincorporaciones recomendadas por solicitudes por procesar.
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <div class="panel-group Material-default-accordion" id="Material-accordion3" role="tablist" aria-multiselectable="true">

        <div class="panel panel-default-accordion mb-3">
            <div class="panel-accordion" role="tab" id="heading2">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion3" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        Desincorporaciones Recomendadas por el Sistema
                    </a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                <div align="justify" class="panel-body">


                    @if($desincorporacionesSistema->count()>0)
                        <table id="myTable2" data-order='[[ 2, "desc" ]]' data-page-length='10' class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center">Descripcion</th>
                                <th class="text-center">Usuario a Desincorporar</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Fecha Accion</th>
                                <th class="text-center">Desincorporar</th>
                            </tr>
                            </thead>
                            <tbody >
                            @foreach($desincorporacionesSistema as $d)
                                <tr>
                                    <td class="text-center">{{$d->observacion }}</td>


                                    <td class="text-center">{{$d->datos_nombres.' '.$d->datos_apellidos}}<br> Rol: {{$d->datos_rol}} <br> CI: {{$d->datos_cedula}} <br> Email:<strong>{{$d->datos_email}}</strong></td>

                                    @if($d->status==='sin ejecutar')
                                        <td class="text-center"><span class="label label-light-danger"><strong>{{strtoupper( $d->status )}}</strong></span></td>
                                    @else
                                        <td class="text-center"><span class="label label-light-info"><strong>{{strtoupper( $d->status )}}</strong></span></td>
                                    @endif

                                    @if(!is_null($d->fecha_gestionada))
                                        <td class="text-center">{{ date("d/m/Y h:i:s A", strtotime($d->created_at)) }}</td>
                                    @else
                                        <td></td>
                                    @endif

                                    @if($d->status==='sin ejecutar')
                                        <td class="text-center">
                                            <a href="{{route('desincorporacion.procesar',array('user_id'=>$d->user_id,'id'=>$d->id))}}"
                                               class='btn btn-primary' title="Desincorporar"><i class='fa fa-trash-o -square-o'></i></a>
                                        </td>
                                    @else
                                        <td class="text-center"><span class="label label-light-info"><strong>USUARIO DESINCORPORADO el {{date("d/m/Y h:i:s A", strtotime($d->updated_at))}}</strong></span></td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert  alert-warning alert-important" role="alert">
                            Disculpe, actualmente no tiene desincorporaciones recomendadas por sistema por procesar.

                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection


@section('personaljs')
    <script type="text/javascript">

        $(document).ready(function(){

            $('#myTable').DataTable();

            $('#myTable2').DataTable();



        });

        $('#myTable')
            .removeClass( 'display' )
            .addClass('table table-hover');

        $('#myTable2')
            .removeClass( 'display' )
            .addClass('table table-hover');
    </script>
@endsection