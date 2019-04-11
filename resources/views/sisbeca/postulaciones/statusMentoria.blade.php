@extends('sisbeca.layouts.main')
@section('title','Inicio')
@section('content')
<div class="col-12">
    <div class="panel panel-default">
        <div class="col-md-12">
            <br>
            <strong>
                <span class="fa fa-users fa-fw"></span> Estatus de Postulación
            </strong>
            <hr>
            <div class="alert  alert-info alert-important" role="alert">
                Su postulación fue enviada exitosamente, actualmente se encuentra en proceso de ser revisada por nuestro equipo técnico.
            </div>
            <hr>
            <div class="box box-danger box-solid">

                
                <div  class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>

                </div>
                <div align="center">
                    <span style="font-size: 25px" class="label label-sisbeca-azul"><strong>EN PROCESO...</strong></span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection


