@extends('sisbeca.layouts.main')
@section('title','Nóminas Pagadas')
@section('content')

<div class="col-lg-12">

	<div class="table-responsive">

		<table id="nomina" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center">Mes/Año</th>
					<th class="text-center">N° Becarios</th>
					<th class="text-right">Estipendio Base</th>
					<th class="text-right">Total Pagado</th>
					<th class="text-center">Fecha Pago</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@if($nominas->count() > 0)
					@foreach($nominas as $nomina)
					<tr>
						<td class="text-center">{{ $nomina->mes.'-'.$nomina->year }}</td>
						<td class="text-center">{{ $nomina->total_becarios }}</td>
						<td class="text-right">{{ number_format($nomina->sueldo_base, 2, ',', '.') }}</td>
						<td class="text-right">{{ number_format($nomina->total_pagado, 2, ',', '.') }}</td>
						<td class="text-center">{{ date("d/m/Y", strtotime($nomina->fecha_pago)) }}</td>
						<td class="text-center">
							<a href="{{ route('nomina.pagado.pdf',array('mes'=>$nomina->mes,'anho'=>$nomina->year)) }}" class="btn btn-xs sisbeca-btn-primary" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Ver Nómina en PDF" >
								<i class="fa fa-file-pdf-o"></i>
							</a>

							<a href="{{ route('nomina.pagada.excel',array('mes'=>$nomina->mes,'anho'=>$nomina->year)) }}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Ver Nómina en Excel" >
								<i class="fa fa-file-excel-o"></i>
							</a>

							<a href="{{ route('nomina.listar.pagadas',array('mes'=>$nomina->mes,'anho'=>$nomina->year)) }}" class="btn btn-xs sisbeca-btn-primary" data-toggle="tooltip" data-placement="bottom" title="Detalle de la Nómina" >
								<i class="fa fa-eye"></i>
							</a>

						</td>
					</tr>
					@endforeach
				@else
					<tr>
						<td class="text-center" colspan="6">No hay <strong>nóminas pagadas</strong>.</td>
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
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
@endsection
