@extends('sisbeca.layouts.main')
@section('title','Cuenta Bancaria')
@section('subtitle','Cuenta Bancaria')
@section('content')

<div class="col-lg-12">
    <div class="row">
        <div class="col-md-2 col-sm-4 col-xs-12" ></div>
        <div class="col-md-8 col-sm-6 col-xs-12" align="center">
            <div class="info-box">
                <span class="info-box-icon "><i class="fa fa-file"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Cuenta Bancaria</span>
                    <span class="info-box-number">{{$becario->cuenta_bancaria}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-2 col-sm-4 col-xs-12" ></div>
    </div>

    <div class="panel-group Material-default-accordion" id="Material-accordion3" role="tablist" aria-multiselectable="true">

        <div class="panel panel-default-accordion mb-3">
            <div class="panel-accordion" role="tab" id="heading2">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion3" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                         Cargar/Actualizar Cuenta Bancaria
                    </a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                <div align="justify" class="panel-body">
               <form class="form-horizontal" accept-charset="UTF-8" method="POST" action="{{route('cuentaBancaria.update',$becario->user_id)}}">

                        {{csrf_field()}}
                        {{method_field('PUT')}}

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <label class="control-label" align="right">Cuenta Bancaria</label>
                                    <input class="sisbeca-input" type="text" placeholder="Ingrese Cuenta Bancaria" value="{{$becario->cuenta_bancaria}}" name="cuenta_bancaria"  required>
                                    <!--<span class=" fa fa-check form-control-feedback"></span>-->
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12 text-right" >
                                    <button type="submit" class="btn sisbeca-btn-primary">
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
