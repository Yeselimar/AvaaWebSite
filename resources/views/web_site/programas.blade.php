@extends('web_site.layouts.main')
@section('title', "Programas")
@section('content')
   <!-- Seccion Principal -->
   <section id="x" class="x" data-stellar-background-ratio="0.2">
      <br/><br/><br/>
      <div class="container">
         <!--
         <div class="section-header">
            <h2 class="section-title">Programas</h2>
            <hr class="lines">
            <p class="section-subtitle">La Asociación Venezolano Americana de Amistad desarrolla tres programas sociales en el área educativa. Nuestro principal esfuerzo está dirigido al Programa Excelencia AVAA (ProExcelencia), propuesta que ha beneficiado a centenares de jóvenes de escasos recursos económicos durante sus cinco años de estudios universitarios en Venezuela.</p>
            <p class="section-subtitle" >
               Adicionalmente, AVAA está respaldado por las embajadas de Estados Unidos y Canadá como el Centro de Asesorías Educativas para opciones de estudio en estos países.
            </p>
         </div>
         <div class="row">
            <div class="col-lg-8 col-md-6 col-xs-4">
            <div class="row" id="contentPrincipal">
               <div class="col-lg-6 col-sm-6 col-xs-6 box-item" >
                  <a class="page-scroll" href="#asesoria-educativa">
                     <span class="icon">
                     <i class="fa fa-newspaper-o icon-contenedor"></i>
                     </span>
                     <div class="text">
                        <p class="titulo-contenedor text-center h6">Asesorías Educativas</p>
                     </div>
                  </a>
               </div>
               <div class="col-lg-6 col-sm-6 col-xs-6 box-item" >
                  <a class="page-scroll" href="#proexcelencia">
                     <span class="icon">
                     <i class="fa fa-newspaper-o icon-contenedor"></i>
                     </span>
                     <div class="text">
                        <p class="titulo-contenedor text-center h6">ProExcelencia</p>
                     </div>
                  </a>
               </div>
            </div>
         </div>

         </div>
         -->
      </div>
   </section>
   <!-- Fin Seccion Principal -->

   <div class="linea-sobra"></div>

   <div class="container-cabecera position-relative">
      <img src="{{asset("info_sitio/img/cabeceras/programa.png")}}" alt="AVAA - Programas" class="cabecera-imagen">
      <div class="cabecera-titulo">
         <p class="h1">Programas</p>
      </div>
   </div>

   <div  style="height: 50px" id="proexcelencia"></div>

   <!--ProExelencia -->
   <section  class="section">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">ProExcelencia AVAA</h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">En esta seccion se describe el programa Exelencia AVAA</p>
         </div>
         <section class="welcome-section section-padding section-dark">
            <div class="container">
               <div class="row letrasResponsive">
                  <div class="">
                     <div class="Material-tab">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs justify-content-left" id="myTab" role="tablist">
                           <li class="nav-item">
                              <a class="nav-link active" data-toggle="tab" href="#programa-avaa" role="tab"><i class="fa fa-graduation-cap"></i><br/><strong>Programa Exelencia AVAA</strong></a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#componentes" role="tab"><i class="fa fa-puzzle-piece"></i><br/><strong>Componentes del Programa</strong></a>
                           </li>
                           <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#requisitos" role="tab"><i class="fa fa-list-alt"></i><br/><strong>Requisitos para Postularse</strong></a>
                           </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                           <div  align="justify" class="tab-pane fade show active" id="programa-avaa" role="tabpanel">
                              <ul class="list-group">
                                 <li class="list-group-item">
                                    <br/>
                                    <p> El Programa Excelencia AVAA promueve la educación y formación integral de jóvenes venezolanos con potencial de liderazgo y probada sensibilidad social durante los cinco años de carrera universitaria.</p>
                                    <p>Además de apoyar a los estudiantes beneficiarios a completar su carrera con los más altos índices académicos, ProExcelencia AVAA desarrolla una serie de componentes que permiten formarlos con valores asociados al progreso, con capacidades profesionales y personales.</p>
                                    <p>Actualmente, el programa tiene a 250 beneficiarios de alto índice académico, vocación social y potencial de liderazgo; todos cursantes de carreras de 5 años en universidades públicas o privadas en la Gran Caracas.</p>
                                 </li>
                              </ul>
                           </div>
                           <div class="contenidoDeList tab-pane fade" id="componentes" role="tabpanel" >
                              <li class="list-group-item">
                                 <br/>
                                 <ul>
                                    <li><p>Formación en áreas de competencias blandas y competencias organizacionales.</p></li>
                                    <li><p>Curso completo de inglés en el Centro Venezolano Americano.</p></li>
                                    <li><p>Participación en Conversación en inglés o chatclub. </p></li>
                                    <li><p>Refuerzo de consciencia social y ciudadana con la participación en actividades de voluntariado. </p></li>
                                    <li><p>Apoyo de un mentor, con mínimo 10 años de experiencia profesional, como modelo de vida y guía profesional.</p></li>
                                    <li><p>Estipendio Mensual.</p></li>
                                 </ul>
                              </li>
                           </div>
                           <div  class="contenidoDeList tab-pane fade" id="requisitos" role="tabpanel">
                              <li class="list-group-item">
                                 <br/>
                              <ul class="list-group">
                                 <li> <p>Ser Venezolano.</p></li>
                                 <li><p >Ser estudiante universitario y estar máximo iniciando segundo año de estudio, quienes cursen carreras de 5 años.</p></li>
                                 <li> <p >Haber culminado al menos el primer periodo académico universitario y consignar la constancia de notas del mismo.</p></li>
                                 <li> <p >Poseer promedio de calificaciones igual o superior a 16 puntos en la escala de 20.</p></li>
                                 <li> <p >Comprometerse a estudiar el idioma inglés en el Centro Venezolano Americano o en otra institución de idiomas que determine la Asociación.</p></li>
                                 <li> <p>Tener una conducta intachable.</p></li>
                                 <li>  <p>Mostrar dotes de liderazgo y espíritu comunitario.</p></li>
                                 <li> <p>Estar dispuesto a participar en las actividades educativas, culturales y deportivas de AVAA. </p></li>
                                 <li>  <p>Demostrar necesidad de ayuda económica. </p></li>

                              </ul>
                              </li>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>

         <section id="planillas" class="col-md-12 col-lg-12 col-xs-12" >
            <div class="col-lg-12 col-md-12 col-xs-12">
               <div class="row">
                  <div class="col-lg-6 col-sm-12 col-xs-12 box-item">
                     <a target="_blank" href="{{asset('planillas/pdf/Planilla1actsep2017.pdf')}}">
                        <span class="icon icon-contenedor-programas">
                           <i class="fa fa-file-pdf-o icon-programas"></i>
                        </span>
                        <div class="text letra-programas">
                           <p>Proceso de Postulación</p>
                        </div>
                     </a>
                  </div>
                  <div class="col-lg-6 col-sm-12 col-xs-12 box-item">
                     <a target="_blank" href="{{asset(route('register'))}}">
                        <span class="icon icon-contenedor-programas">
                           <i class="fa fa-registered icon-programas"></i>
                        </span>
                        <div class="text letra-programas">
                           <p>Postulate Aqui!</p>
                        </div>
                     </a>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </section>
   <!--fin ProExelencia AVAA -->

   <div class="linea-sobra" id="asesoria-educativa"></div>

   <!--Inicio Asesorias Educativas -->
   <section  class="section">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Asesorías Educativas</h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">En esta seccion se describe las Asesorías Educativas AVAA</p>
         </div>
         <section class="Material-accordion-section section-padding">
            <div class="container">
               <div class="row letrasResponsive">
                  <div class="wow animated fadeInUp animated" align="justify" data-wow-delay=".2s" style="visibility: visible;-webkit-animation-delay: .2s; -moz-animation-delay: .2s; animation-delay: .2s;">
                     <ul class="list-group">
                        <li class="list-group-item">
                           <p>
                              AVAA lleva adelante los <strong>Centros de Asesorías Educativas oficiales de las Embajadas de Estados Unidos y Canadá en Venezuela.</strong> Nuestros asesores son entrenados para aconsejar de manera objetiva y veraz, a los estudiantes o profesionales venezolanos sobre todas las propuestas educativas que ofrecen ambos países.
                           </p>

                           <p><strong>*Todos los montos incluyen IVA y deben ser cancelados a través de transferencia o depósito bancario 48 horas hábiles antes de concertar la cita. Una vez efectuado por favor notificar.</strong></p>
                           <p>Los <strong>montos* de inversión por asesoría, válidos a partir del {{$costos->getFechaValido()}},</strong> son los siguientes:</p>
                        </li>
                     </ul>
                     <div class="panel-group Material-default-accordion" id="Material-accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default mb-3">
                           <div class="panel-heading" role="tab" id="headingOne">
                              <h4 class="panel-title">
                                 <a role="button" data-toggle="collapse" data-parent="#Material-accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                 1. Asesoría Inicial: Bs. {{number_format($costos->costo_ases_basica, 2, ',', '.')}}
                                 </a>
                              </h4>
                           </div>
                           <div id="collapseOne" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="headingOne" style="">
                              <div class="panel-body">
                                 <p>Reunión presencial u online en la que se explica el proceso detallado para lograr estudiar en Estados Unidos. En esta sesión se determinará el proceso de búsqueda y selección de instituciones, costos, ayudas financieras y se explicará el proceso de visa de estudiante. </p>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default mb-3">
                           <div class="panel-heading" role="tab" id="headingTwo">
                              <h4 class="panel-title">
                                 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                 2.  Asesoría Inicial Grupal: Bs. {{number_format($costos->costo_ases_intermedia, 2, ',', '.')}}  por estudiante
                                 </a>
                              </h4>
                           </div>
                           <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" style="">
                              <div class="contenidoDeList panel-body" align="justify">
                                 <p>Dirigida a grupos de dos a cinco estudiantes.</p>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default mb-3">
                           <div class="panel-heading" role="tab" id="headingThree">
                              <h4 class="panel-title">
                                 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                 3. Acompañamiento posterior: Bs. {{number_format($costos->costo_ases_completa, 2, ',', '.')}}  (seguimiento durante 1 año)
                                 </a>
                              </h4>
                           </div>
                           <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" style="">
                              <div class="contenidoDeList panel-body" align="justify">
                                 <ul>
                                    <p >
                                       <strong>Luego de la Asesoría Inicial, se puede optar por un acompañamiento posterior que incluye:</strong>
                                    </p>
                                    <li >
                                       <p > Llamadas y correos para aclarar dudas durante el proceso</p>
                                    </li>
                                    <li >
                                       <p > Revisión de los ensayos de admisión.</p>
                                    </li>
                                    <li >
                                       <p > Certificación y sellado de un (1) juego de copias de los documentos académicos. </p>
                                    </li>
                                    <li >
                                       <p > Revisión de las cartas de recomendación. </p>
                                    </li>
                                    <li >
                                       <p > Acompañamiento durante el proceso de visa.</p>
                                    </li>
                                    <li >
                                       <p >  Acompañamiento pre partida.</p>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="panel panel-default mb-3">
                                <div class="panel-heading" role="tab" id="headingFour">
                                   <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        4. Certificación de documentos educativos: Bs. {{number_format($costos->costo_adicional1, 2, ',', '.')}} por pàgina
                                      </a>
                                   </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour" style="">
                                   <div class="contenidoDeList panel-body" align="justify">
                                      <p>Incluye la certificaciòn de documentos educativos por pàgina</p>
                                   </div>
                                </div>
                             </div>

                     </div>
                  </div>
               </div>
               <div class="mt-5"></div>
            </div>
         </section>
         <section class="Material-accordion-section section-padding">
            <div class="container">
               <div class="row letrasResponsive">
                  <div class="wow animated fadeInUp animated" data-wow-delay=".2s" style="visibility: visible;-webkit-animation-delay: .2s; -moz-animation-delay: .2s; animation-delay: .2s;">
                     <ul class="list-group">
                        <li class="list-group-item">
                           <p>
                              Durante el <strong>{{date('Y')}}</strong> se realizarán mensualmente <strong>charlas de orientación</strong> sobre oportunidades de estudio en ambos países de forma totalmente gratuita.
                           </p>
                        </li>
                     </ul>
                     <div class="panel-group Material-default-accordion" id="Material-accordion2" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default mb-3">
                           <div class="panel-heading" role="tab" id="heading1">
                              <h4 class="panel-title">
                                 <a role="button" data-toggle="collapse" data-parent="#Material-accordion2" href="#collapse1" aria-expanded="false" aria-controls="collapse1" class="collapsed">
                                 Calendario de charlas
                                 </a>
                              </h4>
                           </div>
                           <div id="collapse1" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="heading1">
                              <div class="col-lg-12">
                                 @if(isset($charla))
                                    <img src="{{$charla->imagen}}" alt="Calendario de Charla {{$charla->anho}}" style="width: 100%;height: auto;border:1px solid #eee">
                                    <br><br>
                                    <p class="h6 text-center">Calendario de Charlas del año {{$charla->anho}}</p>
                                    <br>
                                 @else
                                    <div class="col">
                                       <div class="text-center">
                                          <span class="badge badge-danger">No hay calendarios de charlas disponibles</span>
                                       </div>
                                       <br>
                                    </div>
                                 @endif
                              </div>

                           </div>
                        </div>
                     </div>


                  <div class="panel-group Material-default-accordion" id="Material-accordion3" role="tablist" aria-multiselectable="true">

                        <div class="panel panel-default mb-3">
                           <div class="panel-heading" role="tab" id="heading2">
                              <h4 class="panel-title">
                                 <a class="collapsed" role="button" data-toggle="collapse" data-parent="#Material-accordion3" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                 Ferias Educativas Expo-Estudiante
                                 </a>
                              </h4>
                           </div>
                           <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                              <div align="justify" class="panel-body">
                                 <p> Como parte de las actividades que se llevan a cabo desde el Centro de Asesorías Educativas, AVAA organiza la Feria Expo-Estudiante de la mano con <a  target="_blank" href="http://www.bmimedia.net/new/default.php">Business Marketing International (BMI)</a>. En esta feria participan diversas universidades e instituciones de todo el mundo que, de manera directa, ofrecen a los asistentes sus propuestas educativas.</p>
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
   <!--Fin Asesorias Educativas -->



@endsection