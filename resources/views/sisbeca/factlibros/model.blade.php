@extends('sisbeca.layouts.main')
@section('title','Libros - Cargar Factura')
@section('content')
<div class="col-lg-12" id="app">
    <div class="text-right">
        <a href="{{ route('facturas.listar') }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
    <br>
    <div class="col sisbeca-container-formulario">

        <form class="form-horizontal" enctype="multipart/form-data" accept-charset="UTF-8" method="POST" action="{{route('facturas.guardar')}}">

            {{csrf_field()}}

            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <label class="control-label" for="name">Nombre</label>
                    <input class="sisbeca-input" type="text" name="name" id="name" placeholder="Ingrese el Nombre del Libro" value="{{old('name')}}" required>
                </div>


                <div class="col-lg-3 col-md-4 col-sm-6">
                    <label class="control-label" for="curso">Curso</label>
                    <input class="sisbeca-input" type="text" name="curso" id="curso" placeholder="Ingrese el Nombre del Curso" value="{{old('curso')}}" required>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <label class="control-label" for="costo">Costo</label>
                    <input class="sisbeca-input" type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Ingrese el Costo del Libro" size=23 value="{{number_format(old('costo'), 2, ',', '.')}}"  name="costo" required id="costo" >
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6">
                    <label class="control-label" for="url_factura" >Factura(PDF)</label>
                    <input class="sisbeca-input" name="url_factura" accept="application/pdf" type="file" id="url_factura" required>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 text-right" >
                        <button type="submit" class="btn sisbeca-btn-primary">
                            Cargar Factura
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
    <section v-if="isLoading" class="loading" id="preloader">
      <div>
          <svg class="circular" viewBox="25 25 50 50">
              <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
      </div>
    </section>
</div>

@endsection

@section('personaljs')
<script>
$(window).on('load', function() {
	/*------------------
		Preloder
	--------------------*/
	console.log('hola como estas')

});

    function handleNumber(event, mask)
    {
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
        with (event)
        {
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
        txt = txt.replace(/[^-\d,]/g,'')

        var mask = mask.match(/^(\D*)\{(-)?(\d*|null)?(?:,(\d+|null))?\}(\D*)$/); if (!mask) return // meglio exception?
        var sign = !!mask[2], decimals = +mask[4], integers = Math.max(0, +mask[3] - (decimals || 0))
        if (!txt.match('^' + (!sign?'':'-?') + '\\d*' + (!decimals?'':'(,\\d*)?') + '$')) return

        txt = txt.split(',')
        if (integers && txt[0] && count(txt[0],/\d/) > integers) return
        if (decimals && txt[1] && txt[1].length > decimals) return
        txt[0] = txt[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.')

        with (event.target)
        {
            value = mask[1] + txt.join(',') + mask[5]
            selectionStart = selectionEnd = pos + (pos==1 ? mask[1].length : count(value, /\./, pos) - dot)
        }

        function count(str, c, e)
        {
            e = e || str.length
            for (var n=0, i=0; i<e; i+=1) if (str.charAt(i).match(c)) n+=1
            return n
        }
    }
const app = new Vue({
	el: '#app',
	data:
	{
		isLoading: false,
    } 
    
})
</script>
@endsection
