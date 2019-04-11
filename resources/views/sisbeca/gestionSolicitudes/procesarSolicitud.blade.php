@extends('sisbeca.layouts.main')
@section('title','Gestion de Solicitudes')
@section('subtitle','Revisar Solicitud')

@section('content')

<div id="app">
<div class="container">
        <div class="text-right">
                <button  onclick="Regresar()" class="btn btn-sm sisbeca-btn-primary">Atrás</button>
            </div>
    <div class="card card-body bg-light border border-info p-2">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-12">
        <p class="text-left"><strong>Solicitud de {{$solicitud->user->name.' '.$solicitud->user->last_name}}</strong></p>
                <div class="row">
                    <div class="col xs-6 col-md-8 col-lg-4 offset-md-5  offset-lg-0 p-t-20 text-center">
                    @if($img_perfil_postulante->count()>0)
                        <img src="{{asset($img_perfil_postulante[0]->url)}}" class="image-responsive img-circle img-fluid img-rounded img-thumbnail perfil-img w-80" >
                            @else
                                @if($solicitud->user->sexo==='femenino')
                                    <img src="{{asset('images/perfil/femenino.png')}}" class="img-rounded img-responsive w-80">
                                @else
                                    <img src="{{asset('images/perfil/masculino.png')}}" class="img-rounded img-responsive w-80">
                                @endif
                            @endif
                        <span class="label label-inverse"> {{ucwords($solicitud->user->rol)}}</span>
                    <br/>
                    </div>
                    <div class="offset-md-5 offset-lg-0 col-md-12 col-lg-8 p-4">
                        <strong>Datos del Solicitante:</strong>
                        <br/>
                        <h3>{{$solicitud->user->name.' '.$solicitud->user->last_name}}</h3>
                        <p>

                            <br/>
                            <i class="fa fa-envelope"> &nbsp;</i>Email: {{$solicitud->user->email}}
                            <br />
                            <i class="fa fa-user"> &nbsp;</i>Cedula: {{$solicitud->user->cedula}}
                            <br/>
                            <i class="fa fa-venus-mars">&nbsp; </i>Sexo: {{ucwords($solicitud->user->sexo)}}
                            <br/>
                            <i class="fa fa-birthday-cake">&nbsp; </i>Fecha de nacimiento: {{ date("d/m/Y", strtotime($solicitud->user->fecha_nacimiento)) }}
                        </p>
                    </div>
                    <div style="border: 2px solid #424242" class="p-2 col-sm-12 col-lg-8 offset-sm-2 offset-md-4 ">
                            <strong>Status de Solicitud:</strong>
                            @if($solicitud->status==='aceptada')
                                <span class="label label-success"><strong>{{strtoupper( $solicitud->status )}}</strong></span>
                            @else
                                @if($solicitud->status==='enviada')
                                <span class="label label-warning"><strong>POR PROCESAR</strong></span>
                                    @else
                                <span class="label label-danger"><strong>{{strtoupper( $solicitud->status )}}</strong></span>
                                    @endif
                            @endif
                            <br/>
                            <strong>Tipo:</strong> {{strtoupper($solicitud->titulo)}}
                            <br/>
                            <strong>Descripción:</strong> {{$solicitud->descripcion}}
                            <br/>
                            <strong>Fecha de Solicitud:</strong> {{date("d/m/Y h:i:s A", strtotime($solicitud->updated_at))}}<br/>
                            
                            @if($solicitud->observacion)
                            <hr class="w-100"/>
                            <p class="text-left pl-5">
                            <b>Observación de la Respueta:</b> {{$solicitud->observacion}}
                            </p>
                            @endif
                        </div>

                </div>

        </div>
    </div>

    <div align="center">
            @if($solicitud->status==='enviada')
                <button type='button' title="Aprobar" class='btn sisbeca-btn-primary' data-toggle='modal' data-target='#modal' @click="observacion=''" >Aprobar</button>
                <button type='button' title="Rechazar" class='btn sisbeca-btn-default' data-toggle='modal' data-target='#modal-default'  @click="observacion=''"  >Rechazar</button>
            @endif
    </div>
</div>



<!-- Modal para aprobar -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirmación</h4>
            </div>
            <br>
            <p class="text-center">¿Esta seguro que desea <strong>aprobar</strong> la solicitud de {{$solicitud->user->name}}?</p>
            @if(!is_null($solicitud->fecha_inactividad) && $solicitud->status == 'enviada')
                <span class="label label-warning p-2">El cambio de status podrá efectuarse a partir del <strong>{{ date("d/m/Y", strtotime($solicitud->fecha_inactividad))}}</strong></span><br/>
                @else
                    @if(!is_null($solicitud->fecha_desincorporacion) && $solicitud->status == 'enviada')
                        <p><span class="label label-warning p-2">
                        El cambio de status podrá efectuarse a partir del  <strong>{{ date("d/m/Y", strtotime($solicitud->fecha_desincorporacion))}}</strong>
                        </span></p>
                    @endif
            @endif
            <form >

                <input type="hidden" id='valor' v-model="valor" name="valor" value="1">
                <div class="container-fluid">
                    <textarea
                    class="sisbeca-input sisbeca-textarea"
                    name="observacion"
                    id="observacion"
                    v-model="observacion"
                    rows="15"
                    placeholder="Ingrese observación de la solicitud"
                    required
                    ></textarea>
                    <span v-if="errores.observacion" :class="['label label-danger']">@{{ errores.observacion[0] }}</span>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-sm sisbeca-btn-primary"  @click.stop.prevent="responseSolicitud('1',observacion)">Sí</button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- Modal para aprobar -->

<!-- Modal para rechazar -->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirmación</h4>
            </div>
            <br>
            <p class="text-center">¿Esta seguro que desea <strong>rechazar</strong> la solicitud de {{$solicitud->user->name}}?</p>

            <form>
                <input type="hidden" id='valor2' v-model="valor" name="valor"  value="0">
                <div class="container-fluid">
                        <textarea
                        v-model="observacion"
                        class="sisbeca-input sisbeca-textarea"
                        name="observacion"
                        id="observacion"
                        rows="15"
                        placeholder="Ingrese observación de la solicitud"
                        required
                        ></textarea>
                        <span v-if="errores.observacion" :class="['label label-danger']">@{{ errores.observacion[0] }}</span>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-sm sisbeca-btn-primary" @click.stop.prevent="responseSolicitud('0',observacion)" >Sí</button>
                </div>

            </form>

        </div>


    </div>
    <!-- /.modal-content -->
</div>
<!-- Modal para rechazar -->
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
        observacion: '',
        valor: '',
        errores: []
    },
    methods: {
       responseSolicitud(val,obs) {
        
        this.isLoading = true
        var url = "{{route('gestionSolicitud.update',$solicitud->id)}}";
        var dataform = new FormData();
        dataform.append('observacion', obs);
        dataform.append('valor',val);
        axios.post(url,dataform).then(response => 
        {
          if(!response.data.res){
            this.isLoading= false
            toastr.warning(response.data.msg);               
          } else {
            let url = "{{route('gestionSolicitudes.listar')}}"
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

    function Regresar() {

        var route= "{{route('gestionSolicitudes.listar')}}";


        location.assign(route);

    }

    function handleNumber(event, mask) {
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
        txt = txt.replace(/[^-\d,]/g,'')

        var mask = mask.match(/^(\D*)\{(-)?(\d*|null)?(?:,(\d+|null))?\}(\D*)$/); if (!mask) return // meglio exception?
        var sign = !!mask[2], decimals = +mask[4], integers = Math.max(0, +mask[3] - (decimals || 0))
        if (!txt.match('^' + (!sign?'':'-?') + '\\d*' + (!decimals?'':'(,\\d*)?') + '$')) return

        txt = txt.split(',')
        if (integers && txt[0] && count(txt[0],/\d/) > integers) return
        if (decimals && txt[1] && txt[1].length > decimals) return
        txt[0] = txt[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.')

        with (event.target) {
            value = mask[1] + txt.join(',') + mask[5]
            selectionStart = selectionEnd = pos + (pos==1 ? mask[1].length : count(value, /\./, pos) - dot)
        }

        function count(str, c, e) {
            e = e || str.length
            for (var n=0, i=0; i<e; i+=1) if (str.charAt(i).match(c)) n+=1
            return n
        }
    }
</script>
@endsection
