@extends('sisbeca.layouts.main')
@section('title','Aliados')
@section('content')
<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('aliado.create')}}" class="btn btn-sm sisbeca-btn-primary">Crear Aliado</a>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Título</th>
                    <th class="text-center">URL</th>
                    <th class="text-center">Actualizada el</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($aliados->count()==0)
                    <td colspan="7" class="text-center">No hay <strong>Aliados</strong></td>
                @else
                    @foreach($aliados as $aliado)
                    <tr>
                        @if($aliado->tipo==='empresas')
                        <td class="text-center"><span class="label label-default">
                            Empresa</span></td>
                        @elseif($aliado->tipo==='organizaciones')
                        <td class="text-center"><span class="label label-default">
                        Orgnanización</span></td>
                        @elseif($aliado->tipo==='instituciones')
                        <td class="text-center"><span class="label label-default">
                        Institución</span></td>
                        @else
                        <td class="text-center"><span class="label label-default">
                        Sin tipo</span></td>
                        @endif
                        <td class="text-center">{{$aliado->titulo}}</td>
                        <td class="text-center">
                            <a href="{{$aliado->url}}" class="btn btn-xs sisbeca-btn-primary" target="_blank">{{$aliado->url}}</a>
                        </td>
                        <td class="text-center">{{$aliado->fechaActualizacion()}}</td>
                        <td>
                            <span data-toggle="modal" data-target="#veraliado{{$aliado->id}}">
                                <button type="button" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </span>

                            <a href="{{ route('aliado.edit',$aliado->id) }}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <span data-toggle="modal" data-target="#eliminaraliado{{$aliado->id}}">
                                <button type="button" class="btn btn-xs sisbeca-btn-default" data-toggle="tooltip" data-placement="bottom" title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

@foreach($aliados as $aliado)
<!-- Modal para eliminar -->
<div class="modal fade" id="eliminaraliado{{$aliado->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Banner</strong></h5>
            </div>
            <div class="modal-body" style="padding-top: 0px;">
                <br>
                <p>¿Está seguro que desea eliminar al aliado: <strong>{{$aliado->titulo}}</strong>?</p>
            </div>
            <div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
                <a href="{{route('aliado.destroy',$aliado->id)}}" class="btn btn-sm sisbeca-btn-default pull-right">Si</a>
                <button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal para eliminar -->

<!-- Modal para ver imagen -->
<div class="modal fade" id="veraliado{{$aliado->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Banner</strong></h5>
            </div>
            <div class="modal-body">
                <br>
                <img src="{{url($aliado->imagen)}}" alt="{{$aliado->titulo}}" class="img-responsive sisbeca-border" >
                <br><br>
                <p class="text-center h6">{{$aliado->titulo}}</p>
            </div>
            <div class="modal-footer" style="padding-left: 5px;padding-right: 5px;">
                <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Fin Modal para ver imagen -->
@endforeach

@endsection

@section('personaljs')

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
@endsection
