@extends('sisbeca.layouts.main')
@section('title','Inicio')
@section('subtitle','Mantenimiento de Postulaciones')
@section('content')

    <div class="row">
    <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="fa fa-clock-o fa-fw"></span> Postulaciones</div>

                @if(avaa\Concurso::query()->where('status','=','abierto')->orWhere('status','=','cerrado')->get()->count()<2)
                     <div class="col-md-6"> <a href="{{ route('mantenimientoConcurso.create') }}" class="btn btn-info">Aperturar Nueva Postulación</a> </div>
                @endif

                 <div class="col-lg-12 table-responsive">

              @if($concursos->count()>0)
                    <table id="myTable" data-order='[[ 3, "asc" ]]' data-page-length='10' class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th class="text-center">Tipo </th>
                            <th class="text-center">Fecha Inicio</th>
                            <th class="text-center">Fecha Final</th>
                            
                            <th class="text-center">Status Actual</th>
                            <th class="text-center">Acciones</th>

                        </tr>
                        </thead>
                        <tbody >

                            @foreach($concursos as $concurso)
                                <tr class="tr">
                                    @if($concurso->tipo=='becarios')
                                    <td class="text-center"><span class="label label-info"><strong>{{ucwords($concurso->tipo)}}</strong></span></td>
                                    @else
                                    <td class="text-center"><span class="label label-warning"><strong>{{ucwords($concurso->tipo)}}</strong></span></td>
                                    @endif

                                    <td class="text-center">{{ date("d/m/Y", strtotime($concurso->fecha_inicio)) }}</td>
                                    <td class="text-center">{{ date("d/m/Y", strtotime($concurso->fecha_final)) }}</td>

                                    <td class="text-center">
                                    @if($concurso->status==='abierto')

                                           <span class="label label-light-success"><strong>{{ucwords($concurso->status)}}</strong></span>

                                    @else
                                         @if($concurso->status==='cerrado')
                                             <span class="label label-light-warning"><strong>{{ucwords($concurso->status)}}</strong></span>

                                        @else
                                           <span class="label label-light-danger"><strong>{{ucwords($concurso->status)}}</strong></span>
                                        @endif
                                    @endif
                                    </td>

                                    <td class="text-center">
                                        @if($concurso->status !=='finalizado')
                                       <a href="{{route('mantenimientoConcurso.edit',$concurso->id)}}" class='edit btn btn-primary' title="Editar"><i class='fa fa-pencil-square-o'></i></a>
                                        @else
                                            <span class="label label-light-info"><strong>Concurso Finalizado</strong></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  @else
                  <br/><br/>
                  <div class="alert  alert-warning alert-important" role="alert">
                            Actualmente no tiene registrado ningun concurso
                        </div>
                  @endif

                </div>

        </div>
        </div>
        </div>




@endsection

@section('personaljs')
     <script>
        $(document).ready(function() {
            $('#myTable').DataTable( {
                columnDefs: [
                    { targets: [3], searchable: false}
                ]
            } );
        } );

        $('#myTable')
            .removeClass( 'display' )
            .addClass('table table-hover');


    </script>
    @endsection

