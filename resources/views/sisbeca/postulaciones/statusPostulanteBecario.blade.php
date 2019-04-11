@extends('sisbeca.layouts.main')
@section('title','Inicio')
@section('content')
<div class="col-12">
    <div class="panel panel-default">
        <div class="col-md-12">
            <br>
            <strong>
                <span class="fa fa-users fa-fw"></span> Postulación
            </strong>
            <hr>
            <div class=" col-sm-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6">
                    <div class="panel panel-default">
                        <div align="justify">
                            <div class="panel-heading"> {{$postulante->user->name}} {{$postulante->user->last_name}}</div>
                            <div class="panel-body">
                                @if($postulante->status==='entrevista')
                                <div align="center"><p> <b>Estatus: </b> <span class="label label-inverse">Aprobado para Entrevista</span></p></div>
                                <div class="alert  alert alert-info" role="alert">
                                        Su postulación ha sido revisada y usted ha sido seleccionado para ir a Entrevista.
                                </div>
                                    <p> <b>Datos para la Entrevista:</b></p>
                                    <p> <b>Fecha: </b> {{$postulante->fechaEntrevista()}}</p>
                                    <p> <b>Hora: </b>{{$postulante->horaEntrevista()}}</p>
                                    <p> <b>Lugar: </b>{{$postulante->lugar_entrevista}}</p>
                                    <p> <b>Entrevistadores:</b></p>
                                    @foreach($postulante->entrevistadores as $entrevistador)
                                    <p>{{$entrevistador->name}} {{$entrevistador->last_name}}</p>
                                    @endforeach
                                @elseif($postulante->status==='entrevistado')
                                <p> <b>Estatus: </b> <span class="label label-success">E.Aprobada</span></p>
                                <div class="alert  alert alert-success" role="alert">
                                        Usted ha aprobado la etapa de Entrevista y su postulación está a la espera del <b>Veredicto Final.</b>
                                </div>
                                <p> <b>Fecha: </b> {{$postulante->fechaEntrevista()}}</p>
                                <p> <b>Hora: </b>{{$postulante->horaEntrevista()}}</p>
                                <p> <b>Lugar: </b>{{$postulante->lugar_entrevista}}</p>
                                <p> <b>Entrevistadores:</b></p>
                                @foreach($entrevistadores as $entrevistador)
                                <p>{{$entrevistador->name}} {{$entrevistador->last_name}}</p>
                                @endforeach
                                @elseif($postulante->status==='rechazado')
                                <p align="center">Estatus: <span class="label label-danger">No Aprobado</span></p>
                                    @if(Auth::user()->becario->fecha_bienvenida==NULL)
                                        <div class="alert alert-danger" role="alert">
                                        Su postulación ha sido revisada y usted no quedó seleccionado para el proceso de entrevista. Agradecemos su participación.
                                        </div>
                                        <div align="center">
                                        <p>Si desea puede volver a realizar el proceso de Postulación desde cero: </p>
                                            <a href="{{ route('voler.a.postularse') }}" class="btn btn-sm sisbeca-btn-primary">Volver a Postularse</a>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" role="alert">
                                               Su Entrevista ha sido revisada y usted no quedó seleccionado en el proceso después de la entrevista. Agradecemos su participación.
                                                </div>
                                                <div align="center">
                                                <p>Si desea puede volver a realizar el proceso de Postulación desde cero: </p>
                                                    <a href="{{ route('voler.a.postularse') }}" class="btn btn-sm sisbeca-btn-primary">Volver a Postularse</a>
                                        </div>
                                    @endif
                                @elseif(($postulante->status==='activo') && (Auth::user()->becario->getfechabienvenida()=='false'))
                                    @if(Auth::user()->becario->fecha_bienvenida==NULL)
                                    <p align="center">Estatus: <span class="label label-success"> Aprobado</span></p>
                                    <div class="alert  alert alert-success" role="alert">
                                            ¡Felicidades! {{$postulante->user->name}} {{$postulante->user->last_name}} has sido asignad@ como Becario al programa ProExcelencia. Esta atent@ para la fecha de inicio de actividades.
                                    </div>
                                    @else
                                    <p align="center">Estatus: <span class="label label-success"> Aprobado</span></p>
                                    <div class="alert  alert alert-success" role="alert">
                                                ¡Felicidades! {{$postulante->user->name}} {{$postulante->user->last_name}} has sido asignad@ como Becario al programa ProExcelencia.
                                    </div >
                                    <div class="alert  alert alert-danger" role="alert">
                                            <p><b>ATENCIÓN: </b>Debe asistir el día {{ date("d/m/Y", strtotime($postulante->fecha_bienvenida)) }} a las {{$postulante->hora_bienvenida}} en "{{$postulante->lugar_bienvenida}}" a una reunión de bienvenida <b>obligatoria</b>, donde deberá firmar el reglamento de inicio al programa.</p>
                                    </div>
                                    @endif
                                @else
                                    <div class="alert  alert-info alert-important" role="alert">
                                            Su postulación fue enviada exitosamente, actualmente se encuentra en proceso de ser revisada por nuestro equipo técnico.
                                    </div>
                                    <p align="center"><b>Estatus: </b><span class="label label-default">Pendiente</span><i class="fa fa-refresh fa-spin"></i></p>
                                @endif
                            </div>
                        </div>
                </div>
                </div>
        </div>
    </div>
</div>
@endsection


