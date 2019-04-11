@extends('sisbeca.layouts.main')
@section('title','Procesar Nómina')
@section('content')
<div class="col-lg-12" id="app">
    <strong>Procesar Nómina: {{ $mes.'-'.$anho }}</strong>
    <a href="{{route('nomina.procesar')}}" class="btn btn-sm sisbeca-btn-primary pull-right ">Atrás</a>
    
    <br>

    <div class="table-responsive">

        <!-- data-order='[[ 5, "asc" ],[2,"desc"],[0,"asc"]]'-->
        <table id="nomina"  class="table table-bordered table-hover" >
            <thead>
                <tr>
                    <th class="text-center">Nombre y Apellido</th>
                    <th class="text-center">CVA</th>
                    <th class="text-right">Retroactivo</th>
                    <th class="text-right">Libros</th>
                    <th class="text-right">Estipendio</th>
                    <th class="text-right" style="background-color: #eee;">Total a Pagar</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="nomina in nominas">
                    <td> @{{nomina.user.name }} @{{nomina.user.last_name }} </td>
                    <td>
                        <button type="button" class=" btn btn-xs btn-default btn-block" @click.prevent="modalCVA(nomina)"> @{{ formatomoneda(nomina.cva) }}
                        </button>

                    </td>
                    <td>
                        <button type="button" class=" btn btn-xs btn-default btn-block" @click.prevent="modalRetroactivo(nomina)"> @{{ formatomoneda(nomina.retroactivo) }}
                        </button>
                    </td>
                    <td class="text-right"> 
                        @{{ formatomoneda(nomina.monto_libros) }} 
                        <!--<span class="label label-default">@{{nomina.numero_facturas }} facturas</span>-->
                    </td>
                    <td class="text-right"> 
                        @{{ formatomoneda(nomina.sueldo_base) }}
                    </td>
                    <td  class="text-right"> 
                        @{{ formatomoneda(nomina.cva + nomina.retroactivo + nomina.monto_libros + nomina.sueldo_base) }}
                     </td>
                    <td> 
                        <a :href="getRuta(nomina.user.id)" class="btn btn-xs sisbeca-btn-primary">Aprobar Facturas</a>
                    </td>

                </tr>
            </tbody>

        </table>
    </div>

    <br>
    <br>
    <a class="btn btn-md sisbeca-btn-primary pull-right"  style="margin-left: 5px;" href="{{ route('nomina.generar', array('mes'=>$mes, 'anho'=>$anho) ) }}" >Generar o Procesar</a>
    

    <!-- Modal para CVA -->
    <div class="modal fade" id="modalCVA">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><strong>Modificar CVA</strong></h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="becario">Nombre</label>
                        <input type="text" name="becario" class="sisbeca-input input-sm sisbeca-disabled" v-model="becario"  style="margin-bottom: 0px" disabled="disabled">
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="cva">CVA</label>
                        <input type="text" name="cva" class="sisbeca-input input-sm" v-model="cva" placeholder="0" style="margin-bottom: 0px">
                        <span v-if="errores.cva" :class="['label label-danger']">@{{ errores.cva[0] }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click.prevent="guardarCVA(id)">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para CVA -->

    <!-- Modal para Retroactivo -->
    <div class="modal fade" id="modalRetroactivo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><strong>Modificar Retroactivo</strong></h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="becario">Nombre</label>
                        <input type="text" name="becario" class="sisbeca-input input-sm sisbeca-disabled" v-model="becario"  style="margin-bottom: 0px" disabled="disabled">
                    </div>
                    <div class="col" style="padding-left: 0px;padding-right: 0px;">
                        <label class="control-label " for="retroactivo">Retroactivo</label>
                        <input type="text" name="retroactivo" class="sisbeca-input input-sm" v-model="retroactivo" placeholder="0" style="margin-bottom: 0px">
                        <span v-if="errores.retroactivo" :class="['label label-danger']">@{{ errores.retroactivo[0] }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click.prevent="guardarRetroactivo(id)">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para Retroactivo -->
   
</div>
@endsection
@section('personaljs')


<script>
    const app = new Vue({

    el: '#app',
    created: function()
    {
        this.getNomina();

    },
    data:
    {
        id: 0,
        becario: '',
        cva: 0,
        retroactivo: 0,
        nominas:[],
        errores:[],
    },
    methods:
    {
        formatomoneda(monto)
        {
            return Number(monto).toLocaleString("es-ES", {minimumFractionDigits: 2});
        },
        getRuta: function(id)
        {
            var url = "{{ route('factlibros.verfacturas',array('id'=>':id', 'mes'=>$mes, 'anho'=>$anho) ) }}";
            url = url.replace(':id', id);
            return url;
        },
        getNomina: function()
        {
            var url = "{{route('nomina.procesar.detalle.servicio',array('mes'=>$mes, 'anho'=>$anho))}}";
            axios.get(url).then(response => 
            {
                this.nominas = response.data.nominas;
            });
        },
        modalCVA: function(nomina)
        {
            this.id = nomina.id;
            this.becario = nomina.user.name+' '+nomina.user.last_name;
            this.cva = nomina.cva;
            this.errores = [];
            $('#modalCVA').modal('show');
        },
        guardarCVA: function(id)
        {
            var url = '{{route('nomina.guardarcva',':id')}}';
            url = url.replace(':id', id);
            var dataform = new FormData();
            dataform.append('cva', this.cva);
            axios.post(url,dataform).then(response => 
            {
                this.errores = [];
                this.getNomina();
                $('#modalCVA').modal('hide');
                toastr.success(response.data.success);

            }).catch( error =>
            {
                this.errores = error.response.data.errors;
            });
        },
        modalRetroactivo: function(nomina)
        {
            this.id = nomina.id;
            this.becario = nomina.user.name+' '+nomina.user.last_name;
            this.retroactivo = nomina.retroactivo;
            this.errores = [];
            $('#modalRetroactivo').modal('show');
        },
        guardarRetroactivo: function(id)
        {
            var url = '{{route('nomina.guardarretroactivo',':id')}}';
            url = url.replace(':id', id);
            var dataform = new FormData();
            dataform.append('retroactivo', this.retroactivo);
            axios.post(url,dataform).then(response => 
            {
                this.errores = [];
                this.getNomina();
                $('#modalRetroactivo').modal('hide');
                toastr.success(response.data.success);

            }).catch( error =>
            {
                this.errores = error.response.data.errors;
            });
        }
    }
});
</script>
@endsection
