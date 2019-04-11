@extends('web_site.layouts.main')
@section('title', $noticia->titulo)
@section('content')
    <!-- Principal -->
    <section id="x" class="x" data-stellar-background-ratio="0.2">
        <div class="container">
        </div>
    </section>
    <!-- Fin Principal -->

    <section class="section-princip-noticia" data-stellar-background-ratio="0.2">

        <!--Inicio de Sesion de Miembros institucionales -->
        <section id="Noticia" class="section">
            <div class="container">
                <div class="section-header">
                    <h1 class="font-black wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">{{$noticia->titulo}}</h1>
                    <hr class="lines wow zoomIn" data-wow-delay="0.3s">
                </div>
                <div id="portfolio" class="row">
                    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 mix development print">&nbsp;</div>
                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 mix design print">
                        <div class="portfolio-item">
                            <div class="shot-item">
                                <img src="{{asset($noticia->url_imagen)}}" alt="{{$noticia->titulo}}" />
                                <a class="overlay lightbox" href="{{asset($noticia->url_imagen)}}">
                                    <i class="lnr lnr-eye item-icon"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="lines">

                <p style="color:#424242;margin-bottom:0px">
                <i class="fa fa-user"></i> <strong>{{$noticia->informacion_contacto}}</strong> - 
                
                <i class="fa fa-calendar"></i> <strong>{{$noticia->fechaActualizacion()}}</strong>
                </p>
                

                <div class="container">
                    <div class="row">
                        <div id="notice-responsive" style="width: -webkit-fill-available; !important" >
                            <p class="h5">{!! $noticia->contenido !!}</p>
                        </div>
                    
                        @if($noticia->tipo=='miembroins')

                            <ul>
                                <li><strong>Información Adicional</strong></li>
                                @if(!is_null($noticia->url_articulo))
                                    <li>
                                        <i class="fa fa-link"></i>&nbsp;&nbsp;
                                        <a href="{{asset($noticia->url_articulo)}}" style="color: #424242">{{$noticia->url_articulo}}</a>
                                    </li>
                                @endif
                                <li>
                                    @if(!is_null($noticia->email_contacto))
                                        <i class="lnr lnr-envelope"></i>&nbsp;&nbsp;
                                        <a href="mailto:{{ $noticia->email_contacto }}" style="color: #424242">
                                            {{ $noticia->email_contacto }}
                                        </a>
                                    @else
                                        <i class="lnr lnr-envelope"></i>&nbsp;&nbsp;
                                        <a href="mailto:info@avaa.org" style="color: #424242">
                                            info@avaa.org
                                        </a>
                                    @endif
                                </li>
                                <li>
                                    @if(!is_null($noticia->telefono_contacto))
                                        <i class="lnr lnr-phone"></i>&nbsp;&nbsp;
                                        {{ $noticia->telefono_contacto }}
                                    @else
                                        <i class="fa fa-phone"></i>&nbsp;&nbsp;
                                        0212-235.78.21
                                    @endif
                                </li>
                            </ul>
                        @endif
                    </div>  
                </div>

            </div>
        </section>

    </section>

@endsection
@section('personaljs')
<script>
   $(document).ready(function()
   { 
        for (let i = 0; i < document.getElementsByTagName("img").length; i++) {
            document.getElementsByTagName("img")[i].classList.add('img-fluid')
        }
   })
</script>
@endsection