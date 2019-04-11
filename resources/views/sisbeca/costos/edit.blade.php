@extends('sisbeca.layouts.main')
@section('title','Editar Costos')
@section('content')

<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('costos.index')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
    <br>
    <div class="col sisbeca-container-formulario">
        <form class="form-horizontal" accept-charset="UTF-8" method="POST" action="{{route('costos.update',$id)}}">

            {{csrf_field()}}


            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Costo Asesoria<br />(Inicial)</label>
                        <input type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Inicial" size=23  @if(isset($costo)) value="{{number_format($costo->costo_ases_basica, 2, ',', '.')}}" @else value="{{number_format(0, 2, ',', '.')}}" @endif name="costo_ases_basica" class="sisbeca-input" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Costo Asesoria<br />(Inicial Grupal)</label>
                        <input type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Inicial Grupal" size=23  @if(isset($costo)) value="{{number_format($costo->costo_ases_intermedia, 2, ',', '.')}}" @else value="{{number_format(0, 2, ',', '.')}}" @endif name="costo_ases_intermedia" class="sisbeca-input" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Costo Asesoria<br/>(Acomp. Posterior)</label>
                        <input type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Acompañante Posterior" size=23 @if(isset($costo)) value="{{number_format($costo->costo_ases_completa, 2, ',', '.')}}" @else value="{{number_format(0, 2, ',', '.')}}" @endif name="costo_ases_completa" class="sisbeca-input" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="control-label">Fecha válida de<br/> Asesorias</label>
                        <input name="fecha_valido" type="text" id="datepicker" @if(isset($costo)) value="{{date('d/m/Y', strtotime($costo->fecha_valido))}}" @else value="{{date('d/m/Y', strtotime(date('Y-m-d')))}}" @endif placeholder="DD/MM/AAAA" class="sisbeca-input" required>
                    </div>
                </div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Costo Adicionales<br />(Doc. Educativos)</label>
                            <input type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Documentos Educativos" size=23 @if(isset($costo)) value="{{number_format($costo->costo_adicional1, 2, ',', '.')}}" @else value="{{number_format(0, 2, ',', '.')}}" @endif name="costo_adicional1" class="sisbeca-input" required>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Costo Membresia<br/> (Ind./VIP)</label>
                            <input type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Membresia Ind./VIP" size=23 @if(isset($costo)) value="{{number_format($costo->costo_membresia, 2, ',', '.')}}" @else value="{{number_format(0, 2, ',', '.')}}" @endif name="costo_membresia" class="sisbeca-input" required>
                        </div>
                    </div>

               </div>

            <div class="form-group">
                <div class="col-lg-12 text-right" >
                    <input class="btn sisbeca-btn-primary" type="submit" value="Actualizar Costos">
                </div>
            </div>

        </form>
    </div>
</div>


@endsection

@section('personaljs')
<script>
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        orientation: 'bottom',
        autoclose: true,
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function handleNumber(event, mask) {
        /* numeric mask with pre, post, minus sign, dots and comma as decimal separator
            {}: positive integer
            {10}: positive integer max 10 digit
            {,3}: positive float max 3 decimal
            {10,3}: positive float max 7 digit and 3 decimal
            {null,null}: positive integer
            {10,null}: positive integer max 10 digit
            {null,3}: positive float max 3 decimal
            {-}: positive or negative integer
            {-10}: positive or negative integer max 10 digit
            {-,3}: positive or negative float max 3 decimal
            {-10,3}: positive or negative float max 7 digit and 3 decimal
        */
        with (event) {
            stopPropagation()
            preventDefault()
            if (!charCode) return
            var c = String.fromCharCode(charCode)
            if (c.match(/[^-\d,]/)) return
            with (target) {
                var txt = value.substring(0, selectionStart) + c + value.substr(selectionEnd)
                var pos = selectionStart + 1
            }
        }
        var dot = count(txt, /\./, pos)
        txt = txt.replace(/[^-\d,]/g, '')

        var mask = mask.match(/^(\D*)\{(-)?(\d*|null)?(?:,(\d+|null))?\}(\D*)$/); if (!mask) return // meglio exception?
        var sign = !!mask[2], decimals = +mask[4], integers = Math.max(0, +mask[3] - (decimals || 0))
        if (!txt.match('^' + (!sign ? '' : '-?') + '\\d*' + (!decimals ? '' : '(,\\d*)?') + '$')) return

        txt = txt.split(',')
        if (integers && txt[0] && count(txt[0], /\d/) > integers) return
        if (decimals && txt[1] && txt[1].length > decimals) return
        txt[0] = txt[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.')

        with (event.target) {
            value = mask[1] + txt.join(',') + mask[5]
            selectionStart = selectionEnd = pos + (pos == 1 ? mask[1].length : count(value, /\./, pos) - dot)
        }

        function count(str, c, e) {
            e = e || str.length
            for (var n = 0, i = 0; i < e; i += 1) if (str.charAt(i).match(c)) n += 1
            return n
        }
    }
</script>
@endsection
