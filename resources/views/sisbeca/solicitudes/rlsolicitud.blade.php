@extends('sisbeca.layouts.main')
@section('title','Inicio')
@section('subtitle','Solicitudes y Reclamos')
@section('content')

    <div class="panel-group Material-default-accordion" id="Material-accordion" role="tablist" aria-multiselectable="true">

        <div class="panel panel-default-accordion mb-3">
            <div class="panel-accordion" role="tab" id="heading">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion" href="#collapse" aria-expanded="false" aria-controls="collapse">
                        Registrar Nueva Solicitud
                    </a>
                </h4>
            </div>
            <div id="collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading">
                <div align="justify" class="panel-body">
                    <form class="form-horizontal"   accept-charset="UTF-8" method="POST" action="{{route('solicitud.store')}}">

                        {{csrf_field()}}

                        <div class="form-group has-default">
                            <div class="row">
                                <label class="col-sm-4 control-label" for="titulo" align="right">Tipo</label>
                                <div class="col-sm-4">
                                    <select class="form-control " required id="titulo" required name="titulo">
                                        <option value="">Seleccione</option>
                                        @if(Auth::user()->rol==='becario')
                                           @if(Auth::user()->becario->status==='activo' || Auth::user()->becario->status==='probatorio1' ||Auth::user()->becario->status==='probatorio2' )
                                                <option value='retroactivo'>Retroactivo</option>
                                                <option value='desincorporacion temporal'>Desincorporacion Temporal</option>

                                            @endif
                                        @else
                                                @if(Auth::user()->mentor->status==='activo')
                                                <option value='desincorporacion temporal'>Desincorporacion Temporal</option>
                                                    @endif
                                        @endif
                                        <option value='desincorporacion definitiva'>Desincorporacion Definitiva</option>
                                        <option value='reincorporacion'>Reincorporacion</option>
                                        <option value="otros">Otros</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-default rendered1">
                            <div class="row">
                                <label for="datepicker1" class="col-sm-4 control-label" align="right">Fecha de Inactividad</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="datepicker1" name="fecha_inactividad" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group has-default rendered2">
                            <div class="row">
                                <label for="datepicker2" class="col-sm-4 control-label" align="right">Fecha de Desincorporación</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="datepicker2" name="fecha_desincorporacion" required>
                                </div>
                            </div>
                        </div>



                        <div class="form-group has-default">
                            <div class="row">
                                <label id="descripcion" class="col-sm-4 control-label" align="right">Descripcion</label>
                                <div class="col-sm-4">
                                    <textarea required class="form-control" name="descripcion" id="descripcion" rows="15" placeholder="Ingrese la Descripcion de su Solicitud" style="height:200px">{{old('descripcion')}}</textarea>
                                </div>
                            </div>
                        </div>




                        <button type="submit" class="btn btn-primary col-lg-12">
                            Registrar Solicitud
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>

    <div class="panel-group Material-default-accordion" id="Material-accordion3" role="tablist" aria-multiselectable="true">

        <div class="panel panel-default-accordion mb-3">
            <div class="panel-accordion" role="tab" id="heading2">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion3" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        Listar Solicitudes
                    </a>
                </h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                <div align="justify" class="panel-body">


                    @if($solicitudes->count()>0)
                        <table id="myTable" data-order='[[ 2, "desc" ]]' data-page-length='10' class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Status Actual</th>
                                <th class="text-center">Fecha Enviada</th>
                                <th class="text-center">Revisar</th>
                            </tr>
                            </thead>
                            <tbody >
                            @foreach($solicitudes as $solicitud)
                                <tr>
                                    <td class="text-center">{{strtoupper( $solicitud->titulo) }}</td>

                                    @if($solicitud->status==='enviada')
                                        <td class="text-center"><span class="label label-light-info"><strong>{{strtoupper( $solicitud->status )}}</strong></span></td>
                                    @else
                                        @if($solicitud->status==='aceptada')
                                            <td class="text-center"><span class="label label-light-success"><strong>{{strtoupper( $solicitud->status )}}</strong></span></td>
                                        @else
                                            <td class="text-center"><span class="label label-light-danger"><strong>{{strtoupper( $solicitud->status )}}</strong></span></td>
                                        @endif
                                    @endif

                                    <td class="text-center">{{ date("d/m/Y h:i:m A", strtotime($solicitud->updated_at)) }}</td>

                                    <td class="text-center">
                                        <a href="{{route('solicitud.edit',$solicitud->id)}}" class='btn btn-primary' title="Revisar Solicitud"><i class='fa fa-eye -square-o'></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert  alert-warning alert-important" role="alert">
                            Actualmente no tiene registrada ninguna solicitud!!
                        </div>
                    @endif

                </div>
            </div>
        </div>


    </div>
@endsection


@section('personaljs')
    <script >

        $(document).ready(function()
        {

           
            var mivalor= $("#titulo").val();


            $("#titulo").change(function()
            {
                mivalor =   $("#titulo").val();
                if(mivalor=='desincorporacion temporal')
                {

                    $(".rendered1").show();
                    $(".rendered2").hide();
                    document.getElementById("datepicker2").value = null;
                    $("#datepicker2").removeAttr('required');
                    $("#datepicker1").attr("required", "required")


                }
                else
                {
                    if(mivalor=='desincorporacion definitiva')
                    {
                        $(".rendered2").show();
                        $(".rendered1").hide();
                        document.getElementById("datepicker1").value = null;
                        $("#datepicker1").removeAttr('required');
                        $("#datepicker2").attr("required", "required")
                    }
                    else {
                        $("#datepicker1").removeAttr('required');
                        $("#datepicker2").removeAttr('required');
                        document.getElementById("datepicker1").value = null;
                        document.getElementById("datepicker2").value = null;
                        $(".rendered1").hide();
                        $(".rendered2").hide();
                    }



                }
            });


            if(mivalor=='desincorporacion temporal')
            {
                $(".rendered1").show();
                $(".rendered2").hide();
                $("#datepicker2").removeAttr('required');
                document.getElementById("datepicker2").value = null;
                $("#datepicker1").attr("required", "required")
            }
            else
            {
                if(mivalor=='desincorporacion definitiva')
                {
                    $(".rendered2").show();
                    $(".rendered1").hide();
                    $("#datepicker1").removeAttr('required');
                    document.getElementById("datepicker1").value = null;
                    $("#datepicker2").attr("required", "required")
                }
                else {
                    $("#datepicker1").removeAttr('required');
                    $("#datepicker2").removeAttr('required');
                    document.getElementById("datepicker1").value = null;
                    document.getElementById("datepicker2").value = null;
                    $(".rendered1").hide();
                    $(".rendered2").hide();
                }

            }

        });

        $('#datepicker1').datepicker({
            format: 'dd/mm/yyyy',
            language: 'es',
            orientation: 'bottom',
            autoclose: true,
            minDate: 0,
            maxDate: "+1M +0D",
            orientation: "bottom"
        });

        $('#datepicker2').datepicker({
            format: 'dd/mm/yyyy',
            language: 'es',
            orientation: 'bottom',
            autoclose: true,
            minDate: 0,
            maxDate: "+1M +0D",
        });
        
    </script>
@endsection