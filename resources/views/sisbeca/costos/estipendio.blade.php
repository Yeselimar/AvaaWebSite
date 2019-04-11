@extends('sisbeca.layouts.main')
@section('title','Estipendio')
@section('content')

<div class="col-lg-12" id="app">
    <div class="text-right">
        <button @click="actualizarEstipendio" class="btn btn-sm sisbeca-btn-primary"><span v-if="!actualizar">Actualizar Estipendio</span> <span v-else> Cancelar</span></button>
    </div>
    <br>

    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 offset-lg-4 offset-md-2 offset-sm-0 offset-xs-0">
            <div class="info-box costos-border">
                <span class="info-box-icon costos-icon">
                    <img src="{{asset('images/bs_white.png')}}" style="height: 50px !important;">
                </span>

                <div class="info-box-content">
                    <b><span class="info-box-text">Estipendio<br />(Becario)</span></b>
                    <span class="info-box-number">
                    @if(isset($costo))
                        <div class="form-group" v-if="actualizar">
                        <br/>
                            <input type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Ingrese Estipendio" size=23 v-model="estipendioVue"
                            name="estipendio" id="estipendio" class="sisbeca-input" required>

                        </div>
                        <span v-else>
                            <span v-if="isUpdate">@{{valueE}}</span>
                            <span v-else> {{number_format($costo->sueldo_becario, 2, ',', '.')}}</span>

                        </span>
                    @else
                        <div class="form-group" v-if="actualizar">
                        <br/>
                            <input type="text" onkeypress="handleNumber(event, '{20,2}')" placeholder="Ingrese Estipendio" size=23 v-model="estipendioVue"
                            name="estipendio" id="estipendio" class="sisbeca-input" required>

                        </div>
                        <span v-else>
                            <span v-if="isUpdate">@{{valueE}}</span>
                            <span v-else>{{number_format(0, 2, ',', '.')}}</span>
                        </span>
                    @endif

                    <div v-if="actualizar" class="form-group">
                        <div class="col-lg-12 text-right" >
                            <input class="btn sisbeca-btn-primary" type="submit" @click="saveEstipendio" value="Guardar">
                        </div>
                    </div>
                    </span>
                </div>
            </div>
        </div>


        <hr>
    </div>

</div>



@endsection

@section('personaljs')
<script>
    var estipendio = {{$costo->sueldo_becario}}

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
    const app = new Vue({

        el: '#app',
        data:
        {
            estipendioVue: estipendio,
            actualizar: false,
            isUpdate: false,
            valueE: estipendio
        },
        beforeCreate:function()
        {
        },
        mounted() {

        },
        methods:
        {
            actualizarEstipendio(){
            this.actualizar = !this.actualizar
            },
            saveEstipendio() {
                $(".preloader").show();
                let estipendio = document.getElementById('estipendio').value
                if(estipendio == ""){
                    estipendio = 0
                }
                console.log('estipendio',estipendio)
                var dataform = new FormData()
                dataform.append('estipendio', estipendio)
                var url = '{{route('estipendio.actualizar')}}'
                axios.post(url,dataform).then(response =>
                {
                    this.estipendioVue = response.data.costo
                    this.valueE = response.data.costoval
                    this.isUpdate = true
                    this.actualizarEstipendio()
                    $(".preloader").hide()
                    toastr.success(response.data.success)
                });
            }
        }
    });
</script>
@endsection