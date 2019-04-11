@extends('sisbeca.layouts.main')
@section('title','Concurso')
@section('subtitle','Create')
@section('content')

    <div class="row">
        <!-- Column -->
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="fa  fa-clock-o fa-fw"></span>
                    Editar Concurso
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">


                                <div class="form-validation">
                                    <form class="form-horizontal" method="post" action ="{{ route('mantenimientoConcurso.update',$concurso->id) }}">

                                        {{method_field('PUT')}}
                                        {{csrf_field()}}
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="from">Fecha Inicio <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input  name="fecha_inicio" value="{{date('d/m/Y', strtotime($concurso->fecha_inicio))}}" type="text" id="from" required placeholder="Ingrese Fecha Inicio..." class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label" for="to">Fecha Final <span class="text-danger">*</span></label>
                                            <div class="col-lg-6">
                                                <input  name="fecha_final" type="text" value="{{date('d/m/Y', strtotime($concurso->fecha_final))}}"  id="to" required placeholder="Ingrese Fecha Final..." class="form-control">
                                            </div>
                                        </div>

                                        <br/>
                                        <div class="form-group row">
                                            <div class="col-lg-6 ml-auto"  align="right">

                                                <button onclick="Regresar()" class="btn btn-default" type="button" >Cancelar</button>&nbsp;&nbsp;
                                            </div>
                                            <div class="col-lg-6 ml-auto">
                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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