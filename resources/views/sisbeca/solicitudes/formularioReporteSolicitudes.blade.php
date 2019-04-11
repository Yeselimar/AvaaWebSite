@extends('sisbeca.layouts.main')
@section('title','Reporte de Solicitudes')
@section('content')
<div class="col-lg-12" id="app">
    <p class="text-left">Reporte de Solicitud</p>
    <div class="col sisbeca-container-formulario">
        <div class="form-validation">
            <form class="form-horizontal" method="get" action ="{{ route('reporteSolicitudes.pdf') }}">

                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label class="control-label" for="datepicker">Fecha Desde *</label>
                            @if(isset($fechaD))
                            <input  name="fechaDesde" value="{{$fechaD}}" type="text" id="datepicker" placeholder="DD/MM/AAA" class="sisbeca-input" required>

                            @else
                            <input  name="fechaDesde" value="{{old('fechaDesde')}}" type="text" id="datepicker" placeholder="DD/MM/AAA" class="sisbeca-input" required>

                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label class="control-label" for="datepicker2">Fecha Hasta*</label>
                            @if(isset($fechaH))
                            <input  name="fechaHasta" type="text" value="{{$fechaH}}"  id="datepicker2" placeholder="DD/MM/AAA" class="sisbeca-input" required>

                            @else
                            <input  name="fechaHasta" type="text" value="{{old('fechaHasta')}}"  id="datepicker2" placeholder="DD/MM/AAA" class="sisbeca-input" required>

                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label class="control-label" for="user_id">Usuario</label>
                            <select class="sisbeca-input sisbeca-select" id="user_id" name="user_id">
                                @if($user_id == 0)
                                <option value=0 >Todos</option>
                                @endif
                                @foreach($users as $user)
                                   @if($user->id === $user_id)
                                   <option value={{$user->id}} selected >
                                        {{$user->name.' '.$user->last_name}}- Email: {{$user->email}} - Rol: {{$user->rol}}
                                    </option>
                                   @else
                                   <option value={{$user->id}}>
                                        {{$user->name.' '.$user->last_name}}- Email: {{$user->email}} - Rol: {{$user->rol}}
                                    </option>
                                   @endif
                                   @if($user_id != 0)
                                   <option value=0 >Todos</option>
                                   @endif
                                   

                                    
                                @endforeach
                            </select>
                        </div>
                    </div>
                        
                @if(isset($user_id) && isset($fechaDesde) && isset($fechaHasta)&& isset($pdf))
                    <a href="{{ route('solicitudes.pdf',array('fechaDesde'=>$fechaDesde,'fechaHasta'=>$fechaHasta,'user_id'=>$user_id)) }}" class="btn btn-xs btn-danger pull-right" target="_blank">Generar PDF</a>

                @endif
                </div>
                <hr>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-12 text-right" >
                            <button type="submit" class="btn sisbeca-btn-primary" @click="isLoading=true">Cargar Datos</button>
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
	}
})
</script>
<script>
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        orientation: 'bottom',
        autoclose: true,
    });
    $('#datepicker2').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        orientation: 'bottom',
        autoclose: true,
    });
    $('#user_id').chosen({

        placeholder_text_single:'Seleccione un usuario',
        no_results_text: 'No se encontraron resultados'

    });
</script>
@endsection