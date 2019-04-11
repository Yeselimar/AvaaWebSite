@extends('sisbeca.layouts.main')
@section('title','Postulaciones')
@section('subtitle','Aperturar')
@section('content')

    <div class="row">
        <!-- Column -->
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><span class="fa  fa-clock-o fa-fw"></span>
                        Aperturar Nueva Postulación
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-validation">
                                        <form class="form-horizontal" method="post" action ="{{ route('mantenimientoConcurso.store') }}">

                                             {{csrf_field()}}
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="from">Fecha Inicio <span class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <input  name="fecha_inicio" value="{{old('fecha_inicio')}}" type="text" id="from" required placeholder="Ingrese Fecha Inicio..." class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="to">Fecha Final <span class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <input  name="fecha_final" type="text" value="{{old('fecha_final')}}"  id="to" required placeholder="Ingrese Fecha Final..." class="form-control">
                                                </div>
                                            </div>
                                          
                                         <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="tipo">Tipo <span class="text-danger">*</span></label>
                                                <div class="col-lg-6">
                                                    <select class="form-control " required id="tipo" name="tipo">
                                            @if(avaa\Concurso::query()->where('tipo','=','becarios')->where('status','=','abierto')->orWhere('status','=','cerrado')->get()->count()==0)
                                            <option value='becarios'>Becarios</option>
                                            @endif
                                            @if(avaa\Concurso::query()->where('tipo','=','mentores')->where('status','=','abierto')->orWhere('status','=','cerrado')->get()->count()==0)
                                            <option value='mentores'>Mentores</option>
                                            @endif
                                            </select>
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="form-group row">
                                                <div class="col-sm-6 ml-auto" align="right">
                                                    <button onclick="Regresar()" class="btn btn-default" type="button" >Atras</button>&nbsp;
                                                </div>
                                                <div class="col-sm-6 ml-auto">
                                                    <button type="submit" class="btn btn-primary">Aperturar Postulación</button>
                                                </div>
                                            </div>
                                        </form>
                                        
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Column -->
    </div>
@endsection

@section('personaljs')
    <script>
        $(function () {
                $("#from").datepicker({
                onClose: function (selectedDate) {
                $("#to").datepicker("option", "minDate", selectedDate);
                }
        });
        $("#to").datepicker({
            onClose: function (selectedDate) {
            $("#from").datepicker("option", "maxDate", selectedDate);
            }
        });
        });
        function Regresar() {

            var route= "{{route('mantenimientoConcurso.index')}}";


            location.assign(route);

        }
    </script>

@endsection