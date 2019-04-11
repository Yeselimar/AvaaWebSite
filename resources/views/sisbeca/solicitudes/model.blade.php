@extends('sisbeca.layouts.main')
@section('title',$model=='crear' ? 'Crear Solicitud' : 'Editar Solicitud ')
@section('content')
<div id="app">
<div class=" col-lg-12">
    <div class="text-right
    ">
        <a href="{{ route('solicitud.listar')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
    </div>
    <br>
    <div class="col sisbeca-container-formulario">
        <form class="form-horizontal"  accept-charset="UTF-8" method="POST" action="{{route('solicitud.store')}}">

            {{csrf_field()}}

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-6">
                        <label class="control-label" for="titulo">Tipo</label>
                        <select class="sisbeca-input" id="titulo" name="titulo" v-model="titulo" required>
                            <option value="">Seleccione</option>
                            @if(Auth::user()->rol==='becario')
                            @if(Auth::user()->becario->status==='activo' || Auth::user()->becario->status==='probatorio1' ||Auth::user()->becario->status==='probatorio2' )
                                    <option value='desincorporacion temporal'>Desincorporación Temporal</option>

                                @endif
                            @else
                                    @if(Auth::user()->mentor->status==='activo')
                                    <option value='desincorporacion temporal'>Desincorporación Temporal</option>
                                        @endif
                            @endif
                            <option value='desincorporacion definitiva'>Desincorporación Definitiva</option>
                            <option value='reincorporacion'>Reincorporación</option>
                            <option value="otros">Otros</option>
                        </select>
                        <span v-if="errores.titulo" :class="['label label-danger']">@{{ errores.titulo[0] }}</span>

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-6 rendered1">
                        <label class="control-label" for="datepicker1">Fecha de Inactividad</label>
                        <input type="text" class="sisbeca-input" v-model="fecha_inactividad" autocomplete="off" id="datepicker1" name="fecha_inactividad" required>
                        <span v-if="errores.fecha_inactividad" :class="['label label-danger']">@{{ errores.fecha_inactividad[0] }}</span>

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-6 rendered2">
                        <label class="control-label" for="datepicker2">Fecha de Desincorporación</label>
                        <input type="text" class="sisbeca-input" v-model="fecha_desincorporado"  autocomplete="off" id="datepicker2" name="fecha_desincorporacion" required>
                        <span v-if="errores.fecha_desincorporacion" :class="['label label-danger']">@{{ errores.fecha_desincorporacion[0] }}</span>

                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-6 ">
                        <label class="control-label">Descripción</label>
                        <textarea class="sisbeca-input sisbeca-textarea" v-model="descripcion" name="descripcion" id="descripcion" placeholder="Ingrese la descripción de su solicitud">{{old('descripcion')}}</textarea required>
                        <span v-if="errores.descripcion" :class="['label label-danger']">@{{ errores.descripcion[0] }}</span>

                    </div>

                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="row">
                    <div class="col-lg-12 text-right" >
                        <button type="submit" class="btn sisbeca-btn-primary" @click.stop.prevent="sendSolicitud">
                            Registrar solicitud
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<!-- Cargando.. -->
<section v-if="isLoading" class="loading" id="preloader">
      <div>
          <svg class="circular" viewBox="25 25 50 50">
              <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
      </div>
    </section>
	<!-- Cargando.. -->
</div>
@endsection

@section('personaljs')
<script>
const app = new Vue({
	el: '#app',
	data:
	{
        isLoading: false,
        descripcion: '',
        fecha_desincorporado: '',
        fecha_inactividad: '',
        titulo: '',
        errores: []
    },
       methods: {
      sendSolicitud() {
        this.fecha_inactividad = $('#datepicker1').val()
        this.fecha_desincorporado = $('#datepicker2').val()
        this.isLoading = true
        var dataform = new FormData();
        dataform.append('titulo', this.titulo);
        dataform.append('descripcion',this.descripcion);
        dataform.append('fecha_desincorporado',this.fecha_desincorporado)
        dataform.append('fecha_inactividad',this.fecha_inactividad)
        var url = "{{route('solicitud.store')}}";
        axios.post(url,dataform).then(response => 
        {

          if(!response.data.res){
            this.msg = response.data.msg
            toastr.warning(response.data.msg);               
            this.isLoading= false
          } else {
            toastr.success(response.data.msg);               
            let url = "{{route('solicitud.listar')}}"
			location.replace(url)
          }
		}).catch( error =>
            {
              console.clear()
              this.errores = error.response.data.errors;
              this.isLoading= false
              toastr.error("Disculpe, verifique el formulario");               
            });
      }
    }
})
</script>
<script>
$(document).ready(function()
{

    var mivalor= $("#titulo").val();


    $("#titulo").change(function()
    {
        mivalor =   $("#titulo").val();
        if(mivalor=='desincorporacion temporal')
        {

            $(".rendered1").show();
            $(".rendered2").hide();
            document.getElementById("datepicker2").value = null;
            $("#datepicker2").removeAttr('required');
            $("#datepicker1").attr("required", "required")


        }
        else
        {
            if(mivalor=='desincorporacion definitiva')
            {
                $(".rendered2").show();
                $(".rendered1").hide();
                document.getElementById("datepicker1").value = null;
                $("#datepicker1").removeAttr('required');
                $("#datepicker2").attr("required", "required")
            }
            else {
                $("#datepicker1").removeAttr('required');
                $("#datepicker2").removeAttr('required');
                document.getElementById("datepicker1").value = null;
                document.getElementById("datepicker2").value = null;
                $(".rendered1").hide();
                $(".rendered2").hide();
            }



        }
    });


    if(mivalor=='desincorporacion temporal')
    {
        $(".rendered1").show();
        $(".rendered2").hide();
        $("#datepicker2").removeAttr('required');
        document.getElementById("datepicker2").value = null;
        $("#datepicker1").attr("required", "required")
    }
    else
    {
        if(mivalor=='desincorporacion definitiva')
        {
            $(".rendered2").show();
            $(".rendered1").hide();
            $("#datepicker1").removeAttr('required');
            document.getElementById("datepicker1").value = null;
            $("#datepicker2").attr("required", "required")
        }
        else {
            $("#datepicker1").removeAttr('required');
            $("#datepicker2").removeAttr('required');
            document.getElementById("datepicker1").value = null;
            document.getElementById("datepicker2").value = null;
            $(".rendered1").hide();
            $(".rendered2").hide();
        }

    }

});

$('#datepicker1').datepicker({
    format: 'dd/mm/yyyy',
    language: 'es',
    orientation: 'bottom',
    autoclose: true,
    minDate: "0M 0D 0Y",
    maxDate: "+1M +0D",
    orientation: "bottom"
});

$('#datepicker2').datepicker({
    format: 'dd/mm/yyyy',
    language: 'es',
    orientation: 'bottom',
    autoclose: true,
    minDate: "0M 0D 0Y",
    maxDate: "+1M +0D",
});

</script>
@endsection