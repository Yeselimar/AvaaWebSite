@extends('sisbeca.layouts.main')
@section('title','Costos')
@section('content')

<div class="col-lg-12">
    <div class="text-right">
        <a href="{{route('costos.edit',$id )}}" class="btn btn-sm sisbeca-btn-primary">Actualizar Costos</a>
    </div>
    <br>

    <div class="row">

        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box costos-border">
                <span class="info-box-icon costos-icon">
                    <img src="{{asset('images/bs_white.png')}}" style="height: 50px !important;">
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Costo Asesoria<br />(Inicial)</span>
                    <span class="info-box-number">
                    @if(isset($costo))
                        {{number_format($costo->costo_ases_basica, 2, ',', '.')}}
                    @else
                        {{number_format(0, 2, ',', '.')}}
                    @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box costos-border">
                <span class="info-box-icon costos-icon">
                    <img src="{{asset('images/bs_white.png')}}" style="height: 50px !important;">
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Costo Asesoria<br />(Inicial Grupal)</span>
                    <span class="info-box-number">
                    @if(isset($costo))
                        {{number_format($costo->costo_ases_intermedia, 2, ',', '.')}}
                    @else
                        {{number_format(0, 2, ',', '.')}}
                    @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <div class="info-box costos-border">
                <span class="info-box-icon costos-icon">
                    <img src="{{asset('images/bs_white.png')}}" style="height: 50px !important;">
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Costo Asesoria<br/>(Acompañamiento posterior)</span>
                    <span class="info-box-number">
                    @if(isset($costo))
                        {{number_format($costo->costo_ases_completa, 2, ',', '.')}}
                    @else
                        {{number_format(0, 2, ',', '.')}}
                    @endif
                    </span>
                </div>
            </div>
        </div>

        <hr>
    </div>
    <div class="text-right">
        <p>Fecha válida de Asesorias: 
            @if(isset($costo))
                <strong>{{ $costo->getFechaValido() }}</strong>
            @else
                <strong>DD/MM/AAAA</strong>
            @endif
        </p>
        </div>
    <div class="row">
      <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box costos-border">
            <span class="info-box-icon costos-icon">
                <img src="{{asset('images/bs_white.png')}}" style="height: 50px !important;">
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Costo Membresias<br />(ind./VIP)</span>
                <span class="info-box-number">
                    @if(isset($costo))
                        {{number_format($costo->costo_membresia, 2, ',', '.')}}
                    @else
                        {{number_format(0, 2, ',', '.')}}
                    @endif
                </span>
            </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box costos-border">
            <span class="info-box-icon costos-icon">
                <img src="{{asset('images/bs_white.png')}}" style="height: 50px !important;">
            </span>

            <div class="info-box-content">
                <span class="info-box-text">Costo Adicionales<br />(Documentos Educativos)</span>
                <span class="info-box-number">
                    @if(isset($costo))
                        {{number_format($costo->costo_adicional1, 2, ',', '.')}}
                    @else
                        {{number_format(0, 2, ',', '.')}}
                    @endif
                </span>
            </div>
        </div>
      </div>
    </div>

</div>



@endsection

@section('personaljs')

@endsection