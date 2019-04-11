@extends('sisbeca.layouts.main')
@section('title','Becarios')
@section('subtitle','Asignación de Fecha de Entrevistas')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="panel panel-default">
                <div class="panel-heading"><span class="fa fa-users fa-fw"></span> Becarios Aprobados para ir a Entrevista</div>


                <br/>
                <div class="col-lg-12 table-responsive">
 				@if($becarios->count() > 0)
                    <form class="form-horizontal" method="post" action ="{{route('asignacionEntrevistas')}}">
                     {{csrf_field()}}
                    <div class="container">
                        <div class="row">
                            <div class='col-sm-6'>
                                <div class="form-group">
                                    <p>Seleccione Fecha de Entrevista: <input type="text" id="datepicker" name="fechaentrevista" required></p>
                                </div>
                            </div>
                        </div>
                    </div>

                   
                        <table id="myTable" data-order='[[ 0, "asc" ]]' data-page-length='10' class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center" ><input type="checkbox" value="1" id="select_all" name='all' onclick="check(this)">Seleccionar</th>
                                <th class="text-center">Nombre y Apellido</th>
                                <th class="text-center">Cedula</th>
                                <th class="text-center">E-mail</th>

                            </tr>
                            </thead>
                            <tbody >
                            @foreach($becarios as $becario)

                                <tr class="tr">
                                    <td class="text-center">
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="1" name='{{$becario->user_id}}' id="select_all[]"></label>
                                        </div>
                                    </td>
                                  
                                    <td class="text-center">{{ $becario->user->name.' '.$becario->user->lastname }}</td>
                                    <td class="text-center">{{ $becario->user->cedula }}</td>
                                    <td class="text-center">{{ $becario->user->email }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <input class="btn btn-md btn-primary pull-right"  style="margin-left: 5px;" type="submit" value="Asignar">
                        </form>
                    @else
                        <div class="alert  alert-warning alert-important" role="alert">
                            Actualmente no hay Postulantes aprobados para entrevistas...
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
                    { targets: [0], searchable: false}
                ]
            } );
        } );

        $('#myTable')
            .removeClass( 'display' )
            .addClass('table table-hover');

        $( function() {
            $( "#datepicker" ).datepicker({ minDate: 0, maxDate: "+2M +10D",orientation: "bottom" });
        } );

/*Checkbox all*/
        $('#select_all').change(function() {
    var checkboxes = $(this).closest('form').find(':checkbox');
    checkboxes.prop('checked', $(this).is(':checked'));
});


    </script>




@endsection
