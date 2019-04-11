@extends('web_site.layouts.main')
@section('title', "Donaciones")
@section('content')
   <!-- Seccion Principal -->
   <section id="x" class="x" data-stellar-background-ratio="0.2">
      <br/><br/><br/>
   </section>
   <!-- Fin Seccion Principal -->

   <div class="linea-sobra"></div>
   <div class="container-cabecera position-relative">
      <img src="{{asset('info_sitio/img/cabeceras/donaciones.png')}}" alt="AVAA - Donaciones" class="cabecera-imagen">
      <div class="cabecera-titulo">
      <p class="h1">Donaciones</p>
    </div>
   </div>

   <div style="height: 50px" id="donantes-corporativos"></div>

   <!-- Donaciones Cooporativas-->
   <section id="donantesCorporativos" class="section">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Donantes Coorporativos</h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">En esta sección se describe las donaciones de Fondo de Alianzas y Becas Estratégicas (FABE) que existe actualmente en el programa AVAA.</p>
         </div>
         <div class="row letrasResponsive">
            <div class=" contenidoDeList side-center col-sm-12 col-md-12"  align="justify">

            <p>Las empresas pueden contribuir con la formación y educación integral de Becarios AVAA a través de convenios de inversión social (Fondo de Alianzas y Becas Estratégicas, FABE). Estos aportes son donaciones directas al Programa Excelencia AVAA y, son considerados deducibles de ISLR en Venezuela y Estados Unidos.
             </p>
            <p >
               Actualmente AVAA tiene 41 empresas como miembros corporativos en Venezuela y 7 en Estados Unidos.
            </p>
            <h3 class="lead">  <strong>Los convenios de inversión son: </strong> </h3>
            <hr/>
               <ul>
                     <p>
                        <strong>DONANTE PLATINUM</strong>
                     </p>
                     <li  >
                        <p >Formamos Talentos del Futuro </p>
                     </li>
                     <li  >
                        <p >Financia la Educación y Formación Integral de Becarios AVAA por 5 años. </p>
                     </li>
                  </ul>
                  <ul>
                     <p>
                        <strong>DONANTE DIAMANTE</strong>
                     </p>
                     <li  >
                        <p >Sembramos Futuro 2 x 2</p>
                     </li>
                     <li  >
                        <p>Financia la Educación y Formación Integral de Becarios AVAA por 2 años. </p>
                     </li>
                  </ul>
                  <ul>
                     <p  >
                        <strong>DONANTE ORO</strong>
                     </p>
                     <li  >
                        <p >Abonamos Futuro</p>
                     </li>
                     <li  >
                        <p>Financia la Educación y Formación Integral de Becarios AVAA por 1 año. </p>
                     </li>
                  </ul>
                  <ul>
                     <p>
                        <strong>DONANTE ESTRELLA</strong>
                     </p>
                     <li  >
                        <p >Patrocinantes de sueños educativos </p>
                     </li>
                     <li  >
                        <p>Invierte en la Educación y Formación Integral de Becarios AVAA por 5 años con un financiamiento del 10 % del total de los becarios cada año.</p>
                     </li>
               </ul>
               <br/>
               <hr/>
               <p>  <strong>¿Desea convertirse en un Donante FABE?</strong> <a href="{{asset('contactenos')}}" class="membresia-enlace"><strong>¡Contáctenos!</strong></a> </p>
               <hr/>
            </div>
         </div>
      </div>
   </section>
   <!-- Fin Membresias Cooporativas-->

   <div class="linea-sobra" id="donantes-individuales"></div>

   <!-- Miembros Institucionales -->
   <section  class="section">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Donantes Individuales </h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">En esta sección se describen las Donaciones Individuales que existe actualmente en el programa AVAA .</p>

         </div>
         <div class="row letrasResponsive">
            <div class=" contenidoDeList side-center col-sm-12 col-md-12"  align="justify">

            <p> Las Donaciones pueden realizarse de manera individual.
             </p>
             <p>
               Son ejecutivos que se afilian de manera personal con el fin de participar en las actividades de la Asociación, con un aporte de Bs.0,00 (+ IVA) anual.
             </p>
            <h3 class="lead">  <strong>Reciben los siguientes beneficios: </strong> </h3>
            <hr/>
               <ul>
                  <li  >
                     <p >Carta de afiliación que lo acredita como miembro de AVAA. </p>
                  </li>
                  <li  >
                     <p  >Descuentos especiales en nuestras asesorías.</p>
                  </li>
                  <li  >
                     <p  >Información directa sobre oportunidades de cursos y becas en el exterior. </p>
                  </li>
                  <li  >
                     <p  >Nombre en página web de AVAA.</p>
                  </li>
                  <li  >
                     <p  >Información directa de los eventos AVAA.</p>
                  </li>
                  <li  >
                     <p  >Mención pública de reconocimiento en eventos de la AVAA.</p>
                  </li>
                  <li  >
                     <p >Copia digital del anuario del Programa Excelencia.
                        Otros servicios.
                     </p>
                  </li>
               </ul>
               <br/>
               <hr/>
               <p>  <strong>¿Desea ser Miembro Individual de AVAA? </strong> Descargue la Planilla de Solicitud de Afiliación y <a href="{{asset('contactenos')}}" class="membresia-enlace"><strong>¡Contáctenos!</strong></a> </p>
               <hr/>
            </div>
         </div>
      </div>
   </section>
   <!-- Fin Membresia Institucionales-->

   @endsection