@extends('sisbeca.layouts.main')
@section('title','Nómina')
@section('subtitle','Nómina Generadas')
@section('content')

<div class="col-lg-12" style="padding-top: 15px; padding-bottom: 15px;">
    <strong>Nómina Generada: {{ $nominas[0]::getMes($mes).'-'.$anho }}</strong>
    <a href="{{ route('nomina.listar') }}" class="btn btn-sm sisbeca-btn-primary pull-right ">Atrás</a>

    
    {{csrf_field()}}

    <div class="table-responsive">
       
        <table id="nomina" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center">Nombre y Apellido</th>
                    <th class="text-right">CVA</th>
                    <th class="text-right">Retroactivo</th>
                    <th class="text-right">Libros</th>
                    <th class="text-right">Estipendio</th>
                    <th class="text-right" style="background-color: #eee">Total</th>
                    <th class="text-center">F. Generada</th>
                </tr>
            </thead>
            <tbody>
                @if($nominas->count() > 0)
                    @foreach($nominas as $nomina)
                        <tr>
                            <td class="text-center"> {{ $nomina->datos_nombres.' '.$nomina->datos_apellidos}}</td>
                             <td class="text-right">{{ number_format($nomina->cva, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($nomina->retroactivo, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($nomina->monto_libros, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($nomina->sueldo_base, 2, ',', '.')}}</td>
                            <td class="text-right" style="background-color: rgba(113,113,113,0.1);">{{ number_format($nomina->total, 2, ',', '.') }}</td>
                            <td class="text-center">{{ $nomina->getFechaGenerada() }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                         <td class="text-center" colspan="7">No hay registros en esta<strong>nómina</strong>.</td>
                    </tr>
                    
                @endif
            </tbody>
        </table>
        
	</div>
</div>
@endsection

@section('personaljs')
<script>
  
$(document).ready(function() {
    $('#nomina').DataTable({

        "language": {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No hay resultados encontrados",
        "paginate":
            {
                "first": "Primero",
                "last": "Ultimo",
                "next": '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                "previous": '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
            }
        }
    });
});
</script>
@endsection
