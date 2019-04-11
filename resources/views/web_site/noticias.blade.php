@extends('web_site.layouts.main')
@section('title', "Noticias")
@section('content')
  <!-- Principal -->
  <section id="x" class="x" data-stellar-background-ratio="0.2">
      <br/><br/><br/>
      <div class="container">


      </div>
  </section>
  <!-- Fin Principal -->

  <div class="linea-sobra"></div>
  <div class="container-cabecera position-relative">
    <img src="{{asset("info_sitio/img/cabeceras/noticias.png")}}" alt="AVAA - Noticias" class="cabecera-imagen">
    <div class="cabecera-titulo">
      <p class="h1">Noticias</p>
    </div>
  </div>

  <div style="height: 50px" id="noticias"></div>

  <!-- Noticias -->
  <div id="Noticias" class="section">

      <div class="container">
        <div class="section-header">
          <h2 class="section-title wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s">Noticias</h2>
          <hr class="lines wow zoomIn" data-wow-delay="0.3s">
          <p class="section-subtitle wow fadeIn" data-wow-duration="1000ms" data-wow-delay="0.3s"><strong>Artículos relacionados con Proexcelencia AVAA de educación integral en Venezuela</strong></p>
        </div>

        <div class="row" >
              <div
                class="d-flex w-100 flex-md-row flex-column align-items-center justify-content-md-between pb-4"
              >
              <div class="input-group input-group-flat w-25">
                                                <span class="input-group-btn"><button class="btn" type="submit" style="background-color:#003865;padding:.375rem .75rem"><i class="fa fa-search"></i></button></span>
                                                <input type="text" class="form-control" placeholder="Buscar Noticia"  style="color:black" @keyup="doFilter()"
                                                v-model="filterText">
                                            </div>
              </div>
          <div v-for="(noticia,i) in noticiasC" :key="i" class="col-lg-4 col-md-6 col-sm-6 col-xs-12"  style="margin-bottom: 10px;" v-if="(i > (currentPageMovil*perPageMovil)-1 && i < ((currentPageMovil*perPageMovil)+perPageMovil)  )">
            <a :href="noticia.slug">
            <div data-mh="content-notice" style="border: 1px solid #021f3a;padding-right: 0px;padding-left: 0px; height:455px">
              <img data-mh="img-notice" style="width: 100%;height: 60%;" :src="noticia.url_image" :alt="noticia.titulo">
              <div data-mh="content-h-notice" style="height:40%;padding:10px; ">
                <p data-mh="title" class="h4 d-flex text-center justify-content-center align-items-center" style="color:#021f3a;height:50%" data-mh="noticia-titulo">
                  <strong>@{{noticia.titulo}}</strong>
                </p>
                <hr>
                <div data-mh="noticia-informacion">
                <p data-mh="hnf" class="h6" style="color:#9E9E9E">
                  @{{noticia.fecha_actualizacion}} - @{{noticia.informacion_contacto}}
                </p>
                </div>
              </div>
            </div>
            </a>
          </div>


          <div class="col-lg-12 mt-4">
            <p style="color:#424242" class="text-right">@{{ noticiasC.length }}  noticia(s)</p>
          </div>
          <div class="pt-3">
            <ul role="menubar" aria-disabled="false" aria-label="Pagination" class="pagination b-pagination pagination-md justify-content-end">
                <li role="none presentation" class="page-item"  :class="[currentPageMovil === 0 ? 'disabled-page' : 'cursor']" @click="prevPage()" aria-hidden="true">
                    <span class="page-link page-link-seb" :class="[currentPageMovil === 0 ? 'disabled-page' : 'cursor']">
                    <img :src="url_img.replace('reemplazar-img-url','info_sitio/img/recursos/left.png')"></span>
                </li>
                <li role="none presentation" class="page-item"  :class="[isMax ? 'disabled-page' : 'cursor']" @click="nextPage()" aria-hidden="true">
                  <span class="page-link page-link-seb" :class="[isMax ? 'disabled-page' : 'cursor']">
                  <img :src="url_img.replace('reemplazar-img-url','info_sitio/img/recursos/right.png')"></span>
              </li>

            </ul>

           </div>
        </div>
      </div>
  </div>
  <!-- Fin Noticias -->
@endsection

@section('personaljs')
<script>

var link = "{{route('showNoticia','reemplazar-notiavaa-url')}}"
var link_image = "{{asset('reemplazar-img-url')}}"
var api_noticias = "{{route('noticias.obtenertodas.api')}}"
const app = new Vue({

   el: '#Noticias',

   data:
   {
     noticias: [],
     url: link,
     url_img: link_image,
     filterText: '',
     currentPageMovil: 0,
     perPageMovil:6,
     totalRowsMovil: 0,


   },
   computed: {
    noticiasC: function() {
        let notiAux = []
        if(this.noticias  && this.noticias.length)
        {
          this.noticias.forEach(function(noticia,index){
                if(!this.filterText || (noticia.titulo.toLowerCase().indexOf(this.filterText.toLowerCase())>=0)){
                  notiAux.push(noticia)
                }
          },this);
        }
        this.totalRowsMovil = notiAux.length
				return notiAux
      },
      isMax: function() {
          let m = true;
          if(this.totalRowsMovil > (this.currentPageMovil+1)*this.perPageMovil) {
              m = false
          }
          return m;
      }
   },
   methods:
   {
    obtenerperiodosapi: function()
		{
			var url = api_noticias;
			axios.get(url).then(response =>
			{
				this.noticias = response.data.noticias;
        this.noticias.forEach(function(element,i) {
          element.slug = this.url.replace('reemplazar-notiavaa-url', element.slug);
          element.url_image = this.url_img.replace('/reemplazar-img-url',element.url_image);
        },this);
        this.totalRowsMovil = this.noticias.length
        $('#loader').hide()
			}).catch( error => {
        console.log(error);
        $('#loader').hide()
			});
    },
    doFilter(){
      this.currentPageMovil = 0;
    },
    prevPage () {
        if(this.currentPageMovil !== 0) {
            this.currentPageMovil =  this.currentPageMovil - 1;
        }
    },
    nextPage () {
        if(this.totalRowsMovil > (this.currentPageMovil+1)*this.perPageMovil) {
            this.currentPageMovil = this.currentPageMovil + 1
        }
    },
   },
   created(){
    $('#loader').show()
    this.obtenerperiodosapi()
   }
});
</script>
@endsection