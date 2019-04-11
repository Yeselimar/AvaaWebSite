@extends('sisbeca.layouts.main') @section('title','Ver Solicitud/Reclamo')
@section('content')
<div class="col-lg-12">
  <div class="text-right">
      
    <a
      href="{{ route('solicitud.listar') }}"
      class="btn btn-sm sisbeca-btn-primary"
      >Atrás</a
    >
  </div>
  <div class="panel panel-default">
        <div class="col-md-12">
          <br />
          <strong> <span class="fa fa-users fa-fw"></span> Solicitud </strong>
          <hr />
          <div class=" col-sm-12 offset-md-2 col-md-8 offset-lg-3 col-lg-6">
            <div class="panel panel-default">
              <div align="center">
                <div class="panel-heading">
                  {{Auth::user()->name}} {{Auth::user()->last_name}}
                </div>
                <div class="panel-body">
                  @if($solicitud->status==='enviada')
                  <div class="alert  alert alert-info alert-important" role="alert">
                  Su solicitud fue enviada exitosamente, actualmente se
                    encuentra en proceso de ser revisada por nuestro equipo
                    técnico.
                  </div>
                  <p>
                    <b>Estatus: </b>
                    <span class="label label-default">Por Procesar</span>
                    <i class="fa fa-refresh fa-spin"></i>
                  </p>
                  <p>
                        <button
                        class="btn btn-sm sisbeca-btn-default"
                        data-toggle="modal"
                        data-target="#modal-default"
                      >
                        Cancelar Solicitud
                      </button>
                  </p>
  
                  @elseif($solicitud->status==='rechazada')
                  <div class="alert alert-danger" role="alert">
                    Su solicitud ha sido negada.
                  </div>
                  <p>
                    Estatus: <span class="label label-danger">Negada</span>
                  </p>
                  @elseif($solicitud->status==='aceptada')
                  <div class="alert  alert alert-success" role="alert">
                    Su solicitud ha sido aceptada
                  </div>
                  <p>
                    Estatus: <span class="label label-success"> Aceptada</span>
                  </p>
                  @endif
                  <p class="text-left"><b>Datos de Solicitud:</b></p>
                  <div class="d-flex flex-column">
                  <p class="text-left pl-5"><b>Fecha Solicitud: </b> {{date("d/m/Y", strtotime($solicitud->created_at))}}</p>
                  <p class="text-left pl-5"><b>Tipo: </b>{{strtoupper( $solicitud->titulo)}}</p>
                  <hr class="w-100">
                  <p class="text-left pl-5"><b>Descripción de Solicitud: </b>{{$solicitud->descripcion}}</p>
  
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
          </div>
        </div>
      </div>

</div>

<!-- Modal para Cancerlar -->
<div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Confirmación</h4>
            </div>
      
            <p align="center">
              ¿Está seguro que desea cancelar la siguiente solicitud?
            </p>
      
            <div class="form-group form-modal-none">
              <strong> Titulo:</strong> {{strtoupper( $solicitud->titulo)}}
            </div>
            <div class="form-group form-modal-none">
              <strong> Estatus:</strong> {{strtoupper( $solicitud->status)}}
            </div>
      
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-danger pull-left"
                data-dismiss="modal"
              >
                No
              </button>
              <button
                type="button"
                onclick="eliminar()"
                class="btn sisbeca-btn-primary pull-left"
                data-dismiss="modal"
              >
                Si
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal para Cancerlar -->
@endsection

@section('personaljs')
<script>

  function eliminar()
  {

      var route= "#";

      $.ajax({
          url: route,
          beforeSend: function() {
              $('.preloader').show();
          },
          complete: function(){
              location.assign("{{route('solicitud.cancelar',$solicitud->id)}}");
          }
      });

  }
</script>
@endsection



















