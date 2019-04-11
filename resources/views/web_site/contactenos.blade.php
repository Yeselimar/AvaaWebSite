
@extends('web_site.layouts.main')
@section('title', "Contáctenos")
@section('content')

   <!-- Principal -->
   <section id="x" class="x" data-stellar-background-ratio="0.2">
      <br/><br/><br/>
      <div class="container">


      </div>
   </section>
   <!-- Fi Principal -->

   <div class="linea-sobra"></div>

   <div class="container-cabecera position-relative">
      <img src="{{asset("info_sitio/img/cabeceras/contacto.png")}}" alt="AVAA - Contáctecnos" class="cabecera-imagen">
      <div class="cabecera-titulo">
         <p class="h1">Contáctanos</p>
      </div>
   </div>

   <div style="height: 50px" id="contacto"></div>

   <!-- Contactenos -->
   <section id="contactenos" class="section">
      <div class="container">
         <div class="section-header">
            <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Contáctanos</h2>
            <hr class="lines wow zoomIn" data-wow-delay="0.3s">
            <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">En esta seccion puede escribirnos a nuestros correo ademas se muestran nuestras redes sociales. </p>
         </div>

         <!-- Contacto -->
         <section id="contact" class="section" data-stellar-background-ratio="-0.2">
            <div class="contact-form">
               <div class="container">
                  <div class="row">
                     <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                        <div class="contact-us">


                           <h6><strong>Llámanos</strong></h6>
                           <div>
                              <ul class="contacto-lista">
                                 <li>
                                    <span class="contactenos-icon">
                                       <i class="lnr lnr-phone"></i>
                                    </span>
                                    &nbsp;&nbsp;
                                    <a href="tel:+582122357821">(+58) 0212-235.78.21</a>
                                 </li>
                              </ul>
                           </div>

                           <br>

                           <h6><strong>Escríbenos</strong></h6>
                           <div>
                              <ul class="contacto-lista">
                                 <li>
                                    <a href="mailto:info@avaa.org">
                                       <span class="contactenos-icon">
                                          <i class="lnr lnr-envelope"></i>
                                       </span>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="mailto:comunicaciones@avaa.org">comunicaciones@avaa.org</a>
                                 </li>
                                 <li>
                                    <a href="mailto:info@avaa.org">
                                       <span class="contactenos-icon">
                                          <i class="lnr lnr-envelope"></i>
                                       </span>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="mailto:prensa.avaa@gmail.com">prensa.avaa@gmail.com</a>
                                 </li>
                              </ul>
                           </div>
                           <br>
                           <h6><strong>Síguenos en las redes sociales</strong></h6>
                           <div>
                              <ul class="contacto-lista">
                                 <li class="contacto-facebook">
                                    <a target="_blank" href="https://www.facebook.com/avaa.org">
                                       <span class="contactenos-icon">
                                          <i class="fa fa-facebook"></i>
                                       </span>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a target="_blank" href="https://www.facebook.com/avaa.org">AVAA</a>
                                 </li>
                                 <li class="contacto-twitter">
                                    <a target="_blank" href="https://www.twitter.com/avaa_org">
                                       <span class="contactenos-icon">
                                          <i class="fa fa-twitter"></i>
                                       </span>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a target="_blank" href="https://www.twitter.com/avaa_org">@avaa_org</a>
                                 </li>
                                 <li class="contacto-instagram">
                                    <a target="_blank" href="https://www.instagram.com/avaa_org">
                                       <span class="contactenos-icon">
                                          <i class="fa fa-instagram"></i>
                                       </span>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a target="_blank" href="https://www.instagram.com/avaa_org">@avaa_org</a>
                                 </li>
                                 <li class="contacto-instagram">
                                    <a target="_blank" href="https://www.youtube.com/channel/UCf6ZTtj7ZXSUVqnOBj-k-wA">
                                       <span class="contactenos-icon">
                                          <i class="fa fa-youtube-play"></i>
                                       </span>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a target="_blank" href="https://www.youtube.com/channel/UCf6ZTtj7ZXSUVqnOBj-k-wA">AVAA TV</a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>

                     <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
                        <div class="" id="app">
                           <h3>Escríbenos</h3>
                           <form method="POST" @submit.prevent="guardar" class="form-horizontal"  >
                           {{ csrf_field() }}
                              <div class="row">
                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                       <input type="text" class="form-control contacto-formulario" name="nombre_completo" placeholder="Nombre Completo" v-model="nombre_completo">
                                    </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                       <input type="text" placeholder="Correo Electrónico" class="form-control contacto-formulario" name="correo" v-model="correo">
                                    </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                       <input type="text" placeholder="Teléfono" class="form-control contacto-formulario" name="telefono" v-model="telefono">
                                    </div>
                                 </div>
                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                       <input type="text" placeholder="Asunto"  class="form-control contacto-formulario" name="asunto" v-model="asunto">
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                       <textarea class="form-control contacto-formulario" placeholder="Ingrese su mensaje" rows="8" v-model="mensaje"></textarea>
                                    </div>

                                 </div>
                                 <div class="col-lg-12">
                                    <div class=" text-right">
                                       <button class="btn btn-common btn-contactenos" type="submit">Enviar Mensaje</button>
                                       <div class="clearfix"></div>
                                       <!--<span v-if="aviso" :class="['label label-danger']">@{{ aviso }}</span>-->
                                    </div>
                                 </div>
                              </div>
                           </form>
                           <section v-if="isLoading" id="preloader" class="loading">
                                 <div class="spinner">
                                    <div class="double-bounce1"></div>
                                    <div class="double-bounce2"></div>
                                 </div>
                           </section>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- Fin Contacto -->
      </div>
   </section>
   <!-- Fin Contactenos -->

   <!-- Ubicación -->

      <div class="section-header">
         <div class="container">
         <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Ubicación</h2>
         <hr class="lines wow zoomIn" data-wow-delay="0.3s">
         <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">
          <strong> Av. Francisco de Miranda esquina con Av. Diego Cisneros, Edif Centro Empresarial Miranda, piso 1 Ofic. D
            Los Ruíces, Caracas. </strong>
         </p>
      </div>
   </div>
   <div class="container">
      <!--
      <iframe src="https://www.google.com/maps/embed?pb=!1m21!1m12!1m3!1d125539.70418857712!2d-66.89954866626132!3d10.491540235005452!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m6!3e6!4m0!4m3!3m2!1d10.491145999999999!2d-66.829323!5e0!3m2!1sen!2sve!4v1519795372218" frameborder="0" style="border: 1px solid #eee;width: 100%;height: 400px" allowfullscreen>
      </iframe>-->
   </div>
 

   <!-- Fin Ubicación -->

@endsection


@section('personaljs')
<script>

const app = new Vue({

   el: '#app',

   data:
   {
      nombre_completo:'',
      correo:'',
      telefono:'',
      asunto:'',
      mensaje:'',
      errores:[],
      aviso:'',
      isLoading: false,
   },
   methods:
   {
      guardar: function()
      {
         this.isLoading = true
         var dataform = new FormData();
         dataform.append('nombre_completo', this.nombre_completo);
         dataform.append('correo', this.correo);
         dataform.append('telefono', this.telefono);
         dataform.append('asunto', this.asunto);
         dataform.append('mensaje', this.mensaje);
         var url = "{{route('contacto.store')}}";
         toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "showDuration": "500",
            "hideDuration": "600",
            "hideEasing": "linear",
         };
         axios.post(url, dataform).then(response =>
         {
            this.nombre_completo = '';
            this.correo = '';
            this.telefono = '';
            this.asunto = '';
            this.mensaje = '';
            this.errores = [];
            this.aviso = '';
            this.isLoading  = false
            toastr.success(response.data.success);
         }).catch( error =>
         {
            this.isLoading  = false
            this.errores = error.response.data.errors;
            console.log(error.response.data);
            if(this.errores.nombre_completo)
            {
               this.aviso = this.errores.nombre_completo[0];
            }
            else
            {
               if(this.errores.correo)
               {
                  this.aviso = this.errores.correo[0];
               }
               else
               {
                  if(this.errores.telefono)
                  {
                     this.aviso = this.errores.telefono[0];
                  }
                  else
                  {
                     if(this.errores.asunto)
                     {
                        this.aviso = this.errores.asunto[0];
                     }
                     else
                     {
                        if(this.errores.mensaje)
                        {
                           this.aviso = this.errores.mensaje[0];
                        }
                        else
                        {
                           this.aviso = null;
                        }
                     }
                  }
               }
            }
            toastr.error(this.aviso);
           console.log(this.aviso);
         });
      }
   }
});
</script>
@endsection