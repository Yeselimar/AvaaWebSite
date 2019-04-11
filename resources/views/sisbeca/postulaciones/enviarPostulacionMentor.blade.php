@extends('sisbeca.layouts.main')
@section('title','Postulación a Mentoría')
@section('subtitle','Postulación a Mentoría')
@section('content')

<div class="offset-md-2 col-md-2 col-lg-8 ">
    <div class="panel panel-default">
        <div class="panel-body">
            <strong>Ingrese la información para su Postulación</strong>
            <hr>
            <form role="form" class="form-horizontal" method="post" action="{{ route('enviarPostulancionMentorGuardar') }}" enctype="multipart/form-data" style="padding: 0px!important">
                {{ csrf_field() }}

                <div class="form-group col-12">
                    <label for="profesion">Profesión:</label>
                    <input type="text" class="sisbeca-input" id="profesion" placeholder="Ej. Abogado" name="profesion">
                </div>
                <div class="form-group col-12">
                    <label for="empresa">Empresa:</label>
                    <input type="text" class="sisbeca-input" id="empresa" placeholder="Ej. Ford Motors" name="empresa">
                </div>
                <div class="form-group col-12">
                    <label for="cargo_actual">Cargo Actual:</label>
                    <input type="text" class="sisbeca-input" id="cargo_actual" placeholder="Ej. Ejecutivo de Ventas" name="cargo_actual">
                </div>
                <div class="form-group col-12">
                    <label for="fecha_ingreso">Fecha de Ingreso a la Empresa:</label>
                    <input type="text" class="sisbeca-input" id="fecha_ingreso" placeholder="DD/MM/AAAA" required="" name="fecha_ingreso">
                </div>
                <div class="form-group col-12">
                    <label for="areas_interes">Áreas de Intéres:</label>
                    <input type="text" class="sisbeca-input" id="areas_interes" placeholder="Ej. Finanzas, Yoga, etc." name="areas_interes">
                </div>
                <div class="form-group col-12">
                    <label for="url_pdf">Cargue su Hoja de Vida</label>
                    <input type="file" class="sisbeca-input" id="url_pdf" name="url_pdf" accept="application/pdf" required>
                    @if ($errors->has('url_pdf'))
                        <span class="help-block">
                        <strong>{{ $errors->first('url_pdf') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group col-12">
                    <button type="submit" class="btn sisbeca-btn-primary pull-right">Enviar</button>
                </div>  
            </form>
        </div>
    </div>
</div>

@endsection

@section('personaljs')

<script>
    $('#fecha_ingreso').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        orientation: 'bottom',
        autoclose: true,
    });
</script>

@endsection

