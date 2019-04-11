@extends('web_site.layouts.main')
@section('title','Inicio')
@section('content')
   <!-- Principal -->
   <section id="x" class="x" data-stellar-background-ratio="0.2">
      <br/><br/><br/>
   </section>
   <div class="linea-sobra "></div>

   <div class="container-cabecera position-relative">
     <img src="{{asset("info_sitio/img/cabeceras/inicio.png")}}" alt="AVAA - AVAA" class="d-sm-none d-md-block d-none cabecera-imagen">
     <img src="{{asset("info_sitio/img/cabeceras/inicio.png")}}" alt="AVAA - AVAA" class=" d-block d-sm-block d-md-none cabecera-imagen">

   <div class="cabecera-titulo">

         <p class="h1"> AVAA Asociación Venezolano Americana de Amistad</p>
   </div>

   </div>


<!--Banner-->
@if($banners->count()!=0)

<div style="height: 30px" id="noticias"></div>
<div class="container" >
   <div class="carousel-banner owl-carousel owl-theme">
      @foreach($banners as $banner)
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid #fff;border-top:1px solid #eee;border:1px solid #E0E0E0" data-mh="banner">
         <div class="row">
            <div class="col-lg-12" style="padding-left: 0px; padding-right: 0px;">
               <a href="{{$banner->url}}" target_="_blank">
               <img class="img-responsive img-banner"   src="{{url($banner->imagen)}}" alt="{{$banner->titulo}}" />
               </a>
            </div>

         </div>
      </div>
      @endforeach
   </div>
</div>
@endif
<!--fin banner-->
   <div class="linea-sobra" id="programasAVAA"></div>
   <!-- Noticias -->
   <div class="container">
    	<div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid #fff">
            <section class="section" >
               <div class="section-header" style="margin-bottom: 0px !important">
                  <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Noticias
                  </h2>
                  <hr class="lines wow zoomIn" data-wow-delay="0.3s">
               </div>

               <br><br>

               <div class="carousel-noticias owl-carousel owl-theme">
                  @foreach($noticias as $noticia)
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border:1px solid #fff;border-top:1px solid #eee;border:1px solid #E0E0E0" >
                     <div class="row">
                     <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12" style="padding-left: 0px; padding-right: 0px;">
                        <a href="{{route('showNoticia',$noticia->slug)}}">
                        <img data-mh="imagen" class="img-responsive img-noticias" src="{{url($noticia->url_imagen)}}" alt="{{$noticia->titulo}}" />
                        </a>
                     </div>
                        <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
                           <div data-mh="titulo-noticia">
                              <br>
                              <a href="{{route('showNoticia',$noticia->slug)}}" style="color:#424242">
                                 <p class="h5"><strong>{{ $noticia->titulo }}</strong></p>
                              </a>
                           </div>
                           <hr>
                           <div class="resumen-noticias text-justify">
                              <p> {{strip_tags("$noticia->contenido")}}</p>
                           </div>
                           <br>
                           <a class="pull-right" href="{{route('showNoticia',$noticia->slug)}}" title="Leer Noticia"> Leer Más</a>
                           <br>
                           <hr>
                           <div class="pull-right">
                              <p><em>Por: {{$noticia->informacion_contacto}} - {{$noticia->fechaActualizacionCorta()}}</em></p>
                           </div>
                        </div>
                     </div>
                   </div>
                   @endforeach
               </div>
            </section>
         </div>
      </div>
   </div>
   <!-- Fin Noticias -->
   <div class="linea-sobra" id="programasAVAA"></div>
    <!-- Programas-->
   <section class="section ">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Programas AVAA </h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">La Asociación Venezolano Americana de Amistad desarrolla tres programas sociales en el área educativa.</p>
         </div>
         <div class="d-flex flex-sm-column flex-md-row programas-flex letrasResponsive">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
               <div class="programa-table">
                  <div class="programa-details">

                     <h2 class="title-programas " data-mh="programa-titulo">PROEXCELENCIA AVAA</h2>

                     <img src="{{asset('info_sitio/img/testimonial/proexcelencia.jpg')}}" alt="ProExcelencia AVAA" data-mh="img-programas" class="img-responsive img-fluid">

                     <div class="col-lg-12">
                        <br>
                        <p data-mh="text-programas" class="h" align="justify">Propuesta educativa que promueve la formación integral de jóvenes con potencial de liderazgo y probada sensibilidad social durante los cinco años de carrera universitaria.</p>
                     </div>

                  </div>
                  <hr>
                  <div class="xxxx-button">
                     <a href="{{asset(route('programas'))}}#ProExcelencia" title="Más Información" class="btn btn-common"><i class="fa fa-plus"></i> <span class="mas-informacion">Más Información</span></a>
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
               <div class="programa-table">
                  <div class="programa-details">

                     <h2 class="title-programas " data-mh="programa-titulo"><img class="img-responsive" src="{{asset('info_sitio/img/usa-ico.png')}}" alt="Asesorias educativas" style="width: 50px;height:50px"  /> EducationUSA</h2>

                     <img src="{{asset('info_sitio/img/testimonial/educationusa.png')}}" alt="ProExcelencia AVAA" data-mh="img-programas" class="img-responsive img-fluid">

                    <div class="col-lg-12">
                        <br>

                        <p data-mh="text-programas" class="h" align="justify">Propuesta educativa que promueve la formación integral de jóvenes venezolanos de medianos o bajos recursos económicos durante los cinco años de carrera universitaria.</p>
                     </div>

                  </div>
                  <hr>
                 <!--  <div class="xxxx-button">
                     <a href="{{asset(route('programas'))}}#ProExcelencia" title="Mas Información" class="btn btn-common"><i class="fa fa-plus"></i> <span class="mas-informacion">Mas Información</span></a>
                  </div>-->
               </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12">
               <div class="programa-table">
                  <div class="programa-details">
                     <h2 class="title-programas " data-mh="programa-titulo"><img class="img-responsive" src="{{asset('info_sitio/img/canada-ico.png')}}" alt="Asesorias educativas" style="width: 50px;height:50px"  /> Educanada</h2>
                     <img src="{{asset('info_sitio/img/testimonial/educanada.png')}}" alt="Asesorias educativas" data-mh="img-programas" class="img-responsive img-fluid w-100" />

                     <div class="col-lg-12">
                        <br>
                        <p data-mh="text-programas" class="h" align="justify">El servicio de atención a interesados en cursar estudios en Estados Unidos o Canadá, prestado como centro oficial de asesorías de las embajadas de estos países.</p>
                     </div>
                  </div>
                  <hr>
               <!--     <div class="plan-button">
                     <a target="_blank" href="{{asset(route('programas'))}}#asesoria-educativa" title="Mas Información" class="btn btn-common"><i class="fa fa-plus"></i> <span class="mas-informacion">Mas Información</span></a>
                  </div>  -->
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Fin Programas -->

   <div class="linea-sobra" id="miembros-institucionales"></div>
   <!-- Aliados -->
   <section class="section">
         <div class="container">
            <div class="section-header">
               <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Aliados </h2>
               <hr class="lines wow zoomIn" data-wow-delay="0.3s">
               <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Conoce nuestros Aliados</p>
            </div>
         <div class="row">
            <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12">
               <h2 class="title-programas" data-wow-duration="1000ms" data-wow-delay="0.3s"> Organizaciones </h2>
               <div class="" style="border:1px solid #fff">
                  <div class="carousel-empresas owl-carousel owl-theme">
                  @if($organizaciones->count()==0)
                     <div class="col-lg-12">
                           <img  class="img-responsive img-fluid" src="{{asset('/images/aliados/organizaciones.png')}}" style="border:1px solid #eee "/>
                     </div>
                  @else
                     @foreach($organizaciones as $organizacion)
                        <div class="col-lg-12" >
                           <a class="miembro-enlace" href="{{$organizacion->url}}">
                              <img  class="img-responsive img-fluid" src="{{asset($organizacion->imagen)}}" alt="{{$organizacion->titulo}}" style="border:1px solid #eee "/>
                              <p class="title-miembros-institucionales pt-2">{{$organizacion->titulo}}</p>
                           </a>
                        </div>
                     @endforeach
                  @endif
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-md-4 col-xs-12">
               <h2 class="title-programas" data-wow-duration="1000ms" data-wow-delay="0.3s"> Empresas </h2>
               <div class="" style="border:1px solid #fff">
                  <div class="carousel-empresas owl-carousel owl-theme">
                     @if($empresas->count()==0)
                     <div class="col-lg-12" >
                           <img  class="img-responsive img-fluid" src="{{asset('/images/aliados/empresas.png')}}" style="border:1px solid #eee "/>
                     </div>
                     @else
                        @foreach($empresas as $empresa)
                           <div class="col-lg-12" >
                              <a class="miembro-enlace" target="_blank" href="{{$empresa->url}}">
                                 <img  class="img-responsive img-fluid" src="{{asset($empresa->imagen)}}" alt="{{$empresa->titulo}}" style="border:1px solid #eee "/>
                                 <p class="title-miembros-institucionales pt-2">{{$empresa->titulo}}</p>
                              </a>
                           </div>
                        @endforeach
                     @endif
                  </div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
               <h2 class="title-programas" data-wow-duration="1000ms" data-wow-delay="0.3s"> Instituciones </h2>
               <div class="" style="border:1px solid #fff">
                  <div class="carousel-empresas owl-carousel owl-theme">
                     @if($instituciones->count()==0)
                     <div class="col-lg-12">
                           <img  class="img-responsive img-fluid" src="{{asset('/images/aliados/instituciones.png')}}" style="border:1px solid #eee"/>
                     </div>
                     @else
                        @foreach($instituciones as $institucion)
                           <div class="col-lg-12" >
                              <a class="miembro-enlace" href="{{$institucion->url}}">
                                 <img  class="img-responsive img-fluid" src="{{asset($institucion->imagen)}}" alt="{{$institucion->titulo}}" style="border:1px solid #eee "/>
                                 <p class="title-miembros-institucionales pt-2">{{$institucion->titulo}}</p>
                              </a>
                           </div>
                        @endforeach
                     @endif
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <div class="linea-sobra"></div>

   <!-- Fin Aliados -->
@endsection

@section('personaljs')
<script>
   $(document).ready(function()
   {

      $('.carousel-banner').owlCarousel({
      items: 1,
      loop: true,
      margin: 0,
      autoplay: true,
      autoplayTimeout: 8000,
      autoplayHoverPause: true,
      mouseDrag: false,
      touchDrag: true,

      });

      $('.carousel-noticias').owlCarousel({
      items: 1,
      loop: true,
      margin: 0,
      autoplay: true,
      autoplayTimeout: 4000,
      autoplayHoverPause: true,
      mouseDrag: false,
      touchDrag: true,

      });

      $('.carousel-empresas').owlCarousel({
         items: 1,
         loop: true,
         margin: 0,
         autoplay: true,
         autoplayTimeout: 4000,
         autoplayHoverPause: true,
         mouseDrag: false,
         touchDrag: true,
      });

      $('.carousel-organizaciones').owlCarousel({
      loop: true,
      margin: 10,
      autoplay: true,
      mouseDrag: false,
      touchDrag: true,
      responsive:
      {
         0:
         {
            items:1,
            nav:false,
            dots:true,
            dotsEach: true,
            mouseDrag: false,
            touchDrag: true
         },
         600:
         {
            items:3,
            nav:false,
            dots:true,
            mouseDrag: false,
            touchDrag: true
         },
         1000:
         {
            items:3,
            nav:false,
            dots:true,
            mouseDrag: false,
            touchDrag: true
         }
      },
      autoplayTimeout: 4000,
      autoplayHoverPause: true,
      });
      var owl = $('.owl-carousel');
      owl.owlCarousel();
      $('.siguiente').click(function() {
          owl.trigger('next.owl.carousel', [1000]);
      })
      $('.anterior').click(function() {
          owl.trigger('prev.owl.carousel', [500]);
      })

   });
</script>


@endsection