@extends('sisbeca.layouts.main')
@section('title','Nómina > Consultar Nómina')
@section('subtitle','Ver Facturas')
@section('content')
<div class="col-lg-12">

    <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
        Facturas del Becario: <strong>{{  $factlibros[0]->becario->user->name }} {{  $factlibros[0]->becario->user->last_name }}</strong>
        <a href="{{ route('nomina.procesar.detalle',array('$mes'=>$mes, 'anho'=>$anho)) }}" class="btn btn-sm sisbeca-btn-primary pull-right ">Atrás</a>
    </div>

    <div class="table-responsive" id="factura">

        <form class="form-horizontal" method="post" action ="{{ route('facturas.validar', array('mes'=>$mes, 'anho'=>$anho,'id'=> $factlibros[0]->becario->user->id) ) }}">
            {{csrf_field()}}
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Seleccione</th>
                        <th class="text-left">Nombre</th>
                        <th class="text-left">Curso</th>
                        <th class="text-center">Estatus</th>

                        <th class="text-center">Fecha <br>Registro</th>
                        <th class="text-center">Revisar</th>
                        <th class="text-right">Costo</th>

                    </tr>
                </thead>
                @if($factlibros->count() > 0)

                <tbody>
                    @foreach($factlibros as $facturas)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" value="1" id="{{ $facturas->id }}" name='{{ $facturas->id }}'>
                                <label for="{{ $facturas->id }}"><span></span> </label>
                            </td>
                            <td class="text-left"> {{ $facturas->name }} </td>
                            <td class="text-left"> {{ $facturas->curso }} </td>
                            <td class="text-center"> {{ ucwords($facturas->status) }} </td>

                            <td class="text-center">{{ date("d/m/Y", strtotime($facturas->created_at)) }}</td>
                            <td class="text-center">
                                <a  href="{{asset($facturas->url)}}" target="_blank" title="Ver Factura" class='btn btn-xs sisbeca-btn-primary pull-center '>Factura</a>
                            </td>
                            <td class="text-right"> {{number_format($facturas->costo, 2, ',', '.')}} </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right" colspan="6"><strong>Total Libros</strong></td>
                        <td class="text-right"><strong>{{number_format($total, 2, ',', '.')}}</strong></td>
                    </tr>
                </tbody>
                @else
                    <tr>
                        <td class="text-center" colspan="5">No hay <strong>facturas</strong></td>
                    </tr>
                @endif
            </table>

            <div style="padding-top:20px">
                <input class="btn btn-md sisbeca-btn-primary pull-right"  style="margin-left: 5px;" type="submit" value="Procesar Factura(s)">
            </div>
        </form>

</div>
</div>
@endsection

@section('personaljs')

    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#myTable').DataTable();



        } );

        $('#myTable')
            .removeClass( 'display' )
            .addClass('table table-hover');
    </script>
@endsection
