@extends('web_site.layouts.main')
@section('title', "Nosotros")
@section('personalcss')
<style>
   .cortar
   {
      white-space: pre-line;
      word-break: break-all;
   }
   .blockquote-box  h6
   {
      text-align: left;
   }
</style>
@endsection
@section('content')

   <!-- Principal -->
   <section id="x" class="x" data-stellar-background-ratio="0.2">
      <br/><br/><br/>
      <div class="container">

      </div>
   </section>
   <!-- Fin Principal-->

   <div class="linea-sobra"></div>

   <div class="container-cabecera position-relative">
      <img src="{{asset("info_sitio/img/cabeceras/nosotros.png")}}" alt="AVAA - Nosotros" class="cabecera-imagen">
      <div class="cabecera-titulo">
         <p class="h1">Nosotros</p>
      </div>
   </div>

   <div style="height: 50px" id="objetivos"></div>

   <!-- Objetivos -->
   <section class="section">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Misión, Visión y Valores</h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">ProExcelencia busca no sólo proveer de ayuda financiera sino, sobre todo, preparar a los estudiantes para que tengan una visión más amplia de la vida, apoyando y potenciando sus competencias para que sean futuros líderes en Venezuela.</p>
         </div>
         <div class="row letrasResponsive">
            <div class="col-md-4 col-sm-6">
               <div data-mh="nosotros-cuadros" class="item-boxes wow fadeInDown" data-wow-delay="0.2s">
                  <div class="icon icon-contenedor-nosotros">
                     <i class="fa fa-flag icon-nosotros"></i>
                  </div>
                  <h4>Misión</h4>
                  <p>Promover y apoyar, la educación integral y la formación de jóvenes venezolanos con sentido de superación y elevados valores sociales y morales, que contribuyan al desarrollo armónico del país, en un clima de convivencia y amistad internacional, en alianza con personas y organizaciones comprometidas con Venezuela.</p>
               </div>
            </div>
            <div class="col-md-4 col-sm-6">
               <div data-mh="nosotros-cuadros" class="item-boxes wow fadeInDown" data-wow-delay="0.8s">
                  <div class="icon icon-contenedor-nosotros">
                     <i class="fa fa-eye icon-nosotros"></i>
                  </div>
                  <h4>Visión</h4>
                  <p>Ser reconocidos como líderes en la ejecución de  iniciativas de formación de jóvenes profesionales comprometidos con el país, con valores asociados al progreso.</p>
               </div>
            </div>
            <div class="col-md-4 col-sm-6">
               <div data-mh="nosotros-cuadros" class="item-boxes wow fadeInDown" data-wow-delay="1.2s">
                  <div class="icon icon-contenedor-nosotros">
                     <i class="fa fa-balance-scale icon-nosotros"></i>
                  </div>
                  <h4>Valores</h4>
                  <p align="center">Vocación social.<br/>
                     Pasión por la excelencia.<br/>
                     Compromiso con nuestra misión.<br/>
                     Visión multicultural.<br/>
                     Imparcialidad y tolerancia.<br/>
                     Responsabilidad por las acciones y resultados.<br/>
                     Ética.<br/>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Fin  Objetivos -->

   <div class="linea-sobra" id="estructura-organizativa"></div>

   <!-- Estructura Organizativa -->
   <section class="section">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Estructura Organizativa </h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">En esta seccion se listan las autoridades directores y demas miembros del STAF de AVAA</p>
         </div>
         <div id="portfolio" class="row">
            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 mix development print">&nbsp;</div>
            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 mix design print">
               <div class="portfolio-item">
                  <div class="shot-item">
                     <img src="{{asset('info_sitio/img/team/estructuraorganizativa.png')}}" alt="Estructura Organizativa" />
                     <a class="overlay lightbox" href="{{asset('info_sitio/img/team/estructuraorganizativa.png')}}">
                     <i class="lnr lnr-eye item-icon"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <br/><br/>
         <section class="welcome-section section-padding section-dark">
            <div class="">
               <div class="row letrasResponsive">
                  <div class="col-lg-12">
                     <div class="Material-tab">
                        <!-- Nav tabs -->

                        <!-- Tab panes -->
                        <div class="tab-content">

                           <div  align="justify" class="wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s" id="staff" >
                              <br/>
                              <div class="container">
                                 <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 nosotros-team">
                                       <div class="blockquote-box animated fadeInLeft clearfix wow" data-wow-delay="0.2s" style="visibility: visible; animation-name: fadeInLeft;">
                                          <div class="square pull-left">
                                             <a href="mailto:claudia.gonzalez@avaa.org">  <img src="{{asset('info_sitio/img/team/default.png')}}" alt="user" height="80" width="80"></a>
                                          </div>
                                          <div class="blockquote-box-directivo">
                                             <h6>
                                                <strong>Claudia González.</strong> <span>Directora Ejecutiva</span>
                                             </h6>
                                             <i class="fa fa-envelope"></i> <a class="text-email cortar" href="mailto:claudia.gonzalez@avaa.org"><strong> claudia.gonzalez@avaa.org</strong></a>
                                          </div>
                                       </div>

                                       <div class="blockquote-box animated fadeInLeft clearfix animated wow" data-wow-delay="0.4s"  style="visibility: visible; animation-name: fadeInLeft;">
                                          <div class="square pull-left">
                                             <a href="mailto:coordinadora.proexcelencia@avaa.org">  <img src="{{asset('info_sitio/img/team/default.png')}}" alt="user" height="80" width="80"></a>
                                          </div>
                                          <div class="blockquote-box-directivo">
                                             <h6>
                                                <strong>Coordinación de Programas Educativos</strong>
                                             </h6>
                                             <i class="fa fa-envelope"></i> <a class="text-email cortar" href="mailto:coordinadora.proexcelencia@avaa.org"><strong> coordinadora.proexcelencia@avaa.org</strong></a>
                                          </div>
                                       </div>
                                       <div class="blockquote-box animated fadeInLeft clearfix animated wow" data-wow-delay="0.6s"  style="visibility: visible; animation-name: fadeInLeft;">
                                          <div class="square pull-left">
                                             <a href="mailto:yvonne.abogado@avaa.org">  <img src="{{asset('info_sitio/img/team/default.png')}}" alt="user" height="80" width="80"></a>
                                          </div>
                                          <div class="blockquote-box-directivo">
                                             <h6>
                                                <strong>Yvonne Abogado,</strong> <span>Gerente de Administración y Finanzas</span>
                                             </h6>
                                             <i class="fa fa-envelope"></i> <a class="text-email cortar" href="mailto:yvonne.abogado@avaa.org.com"><strong>  yvonne.abogado@avaa.org.com</strong></a>
                                          </div>
                                       </div>

                                       <div class="blockquote-box animated fadeInLeft clearfix animated wow" data-wow-delay="0.8s"  style="visibility: visible; animation-name: fadeInLeft;">
                                          <div class="square pull-left">
                                             <a href="mailto:cbt@avaa.org">  <img src="{{asset('info_sitio/img/team/default.png')}}" alt="user" height="80" width="80"></a>
                                          </div>
                                          <div class="blockquote-box-directivo">
                                             <h6>
                                                <strong>Miguel Caraballo,</strong> <span>Coordinador de Servicios Generales</span>
                                             </h6>
                                             <i class="fa fa-envelope"></i> <a class="text-email cortar" href="mailto:cbt@avaa.org"><strong>  cbt@avaa.org</strong></a>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 nosotros-team">
                                       <div class="blockquote-box animated fadeInRight clearfix animated wow" data-wow-delay="1s"  style="visibility: visible; animation-name: fadeInRight;">
                                          <div class="square pull-left">
                                             <a href="mailto:gerencia.proexcelencia@avaa.org">  <img src="{{asset('info_sitio/img/team/default.png')}}" alt="user" height="80" width="80"></a>
                                          </div>
                                          <div class="blockquote-box-directivo">
                                             <h6>
                                                <strong>Bapssy Meneses,</strong> <span>Gerente de Programas Educativos</span>
                                             </h6>
                                             <i class="fa fa-envelope"></i> <a class="text-email cortar" href="mailto:gerencia.proexcelencia@avaa.org"><strong>  gerencia.proexcelencia@avaa.org</strong></a>
                                          </div>
                                       </div>
                                       <div class="blockquote-box animated fadeInRight clearfix animated wow" data-wow-delay="1.2s"  style="visibility: visible; animation-name: fadeInRight;">
                                          <div class="square pull-left">
                                             <a href="mailto:programa.proexcelencia@gmail.com">  <img src="{{asset('info_sitio/img/team/default.png')}}" alt="user" height="80" width="80"></a>
                                          </div>
                                          <div class="blockquote-box-directivo">
                                             <h6>
                                                <strong>Bárbara Narbona,</strong> <span>Asistente de Programas Educativos</span>
                                             </h6>
                                             <i class="fa fa-envelope"></i> <a class="text-email cortar" href="mailto:programa.proexcelencia@gmail.com"><strong>  programa.proexcelencia@gmail.com</strong></a>
                                          </div>
                                       </div>
                                       <div class="blockquote-box animated fadeInRight clearfix animated wow" data-wow-delay="1.4s"  style="visibility: visible; animation-name: fadeInRight;">
                                          <div class="square pull-left">
                                             <a href="mailto:martha.aguilar@avaa.org">  <img src="{{asset('info_sitio/img/team/default.png')}}" alt="user" height="80" width="80"></a>
                                          </div>
                                          <div class="blockquote-box-directivo">
                                             <h6>
                                                <strong>Martha Aguilar,</strong> <span>Coordinadora de Administración</span>
                                             </h6>
                                             <i class="fa fa-envelope"></i> <a class="text-email cortar" href="mailto:martha.aguilar@avaa.org" style="color:#3d91d6;"><strong>  martha.aguilar@avaa.org</strong></a>
                                          </div>
                                       </div>

                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </section>
   <!-- Fin Estructura Organizativa-->
@endsection

<!-- Footer Section Start -->

