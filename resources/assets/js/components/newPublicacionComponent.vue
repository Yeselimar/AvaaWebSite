<style lang="scss" scope>
.loading {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1050;
  background-color: rgba(0, 0, 0, 0.2);
}
 p.error {
    position: absolute;
    right: 16px;
    color: red;
    top: 16px;
    font-size: 12px;
  }
  .bred {
      border-color: red;
  }
  .error-content {
    border: 1px solid red !important;
  }
  .errorc {
    top: -16px !important;
  }
  .hw-200{
    height: 200px;
    width: 200px;
  }
  .border-img {
    border: 1px solid turquoise;
  }
  .fa-plus::before{
    font-size: 200%;
    color: turquoise;
  }
  .cursor {
    cursor: pointer;
  }
  .note-btn.active {
    color: #333 !important;
    background-color: #bebebe !important;
    border-color: #c0bdbd !important;
  }
  .f-wrap {
        flex-wrap: wrap;
  }
  .balimg {
            max-height: 280px;
  }
  .balimg .img {
    width: 100%;
    height: 280px; /* antes 200 */
    
  }
  .panel-heading {
    background-color: #f5f5f5;
    border-color: #ddd;
    background: linear-gradient(to bottom right, #f5f5f5 , #f5f5f5 ) !important; 
  }
  .note-popover .popover-content .dropdown-menu, .panel-heading.note-toolbar .dropdown-menu {
    min-width: 175px;
  }
 .note-toolbar {
    z-index: 48;
  }
  .note-popover.popover {
    z-index: 49;
  }
  .overflowM {
    overflow: hidden !important;
  }
  .z-indexFull {
    z-index: 2000 !important;
  }

</style>
<template>
    <div>
      <view-notice v-if="showNoticeModal" @close2="close2" :title="noticeObj.titulo" :content2="content"></view-notice>
      <div  class="col-lg-12">
          <div class="d-flex justify-content-between">
            <h3 v-if="isEdit">Editar Publicación</h3><h3 v-else>Nueva Publicación</h3>
            <a @click="atras" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
          </div>
          <div class="col sisbeca-container-formulario"  id="formulario">
              <div class="row">
                <div class="align-items-center d-flex flex-column justify-content-center w-100 pb-2" style="background-color: #f5f9fd;">
                    <div><label for="image" class="control-label">*Imagen Destacada</label></div>
                    <div class="align-items-center d-flex flex-column justify-content-center">    
                        <img class="img-fluid py-2" v-if="newImagenUrl != null && newImagenUrl != '' && newImagenUrl!='image'" :src="newImagenUrl" width="300" height="300">
                        <button class="btn btn-micro sisbeca-btn-primary" v-if="newImagenUrl == '' &&  newImagen == ''" @click="openInputFile()">Subir</button>
                        <button v-else class="btn btn-micro sisbeca-btn-default " @click="newImagenUrl='';newImagen=''">Borrar</button>
                        <input accept="image/*" v-if="newImagenUrl == '' &&  newImagen == ''"  class="d-none" type="file" ref="imagenInput" @change="changeAttachment($event)">
                    </div>
                </div>
                        
                </div>
                <hr/>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label class="control-label" for="titulo">*Titulo</label>
                        <input class="sisbeca-input" :class="{'bred': errors.first('titulo','form-user')}" data-vv-scope="form-user" v-validate data-vv-rules="required:true|min:3" placeholder="AVAA tiene nuevo sitio web" v-model="noticeObj.titulo" autocomplete="off" name="titulo" type="text" id="titulo" >
                         <p
                              class="error"
                              v-if="errors.firstByRule('titulo', 'required','form-user')"
                            >Campo requerido</p>
                        <p
                            class="error"
                            v-else-if="errors.firstByRule('titulo','min','form-user')"
                        >Minimo 3 caracteres</p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label for="informacion_contacto" class="control-label">*Publicador</label>
                        <input class="sisbeca-input" :class="{'bred': errors.first('informacion_contacto','form-user')}" data-vv-scope="form-user" v-validate data-vv-rules="required:true|min:3" placeholder="John Doe" v-model="noticeObj.informacion_contacto" name="informacion_contacto"  autocomplete="off"  type="text" id="informacion_contacto" >
                        <p
                              class="error"
                              v-if="errors.firstByRule('informacion_contacto', 'required','form-user')"
                            >Campo requerido</p>
                        <p
                            class="error"
                            v-else-if="errors.firstByRule('informacion_contacto','min','form-user')"
                        >Minimo 3 caracteres</p>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="tipo" class="control-label">Tipo</label>
                        <select class="sisbeca-input " id="tipo" name="tipo"  autocomplete="off" v-model="noticeObj.tipo" >
                            <option value='noticia'>Noticia</option>
                            <option value='miembroins'>Miembro Institucional</option>
                        </select>
                    </div>
                </div>
                <div class="row" v-if="noticeObj.tipo !== 'noticia'">
                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label for="url_articulo" class="control-label">URL Miembro Institucional</label>
                        <input class="sisbeca-input"  :class="{'bred': errors.firstByRule('url_articulo','url','form-user')}" data-vv-scope="form-user" v-validate data-vv-rules="url" v-model="noticeObj.url_articulo"  autocomplete="off"  placeholder="https://www.avaa.org"  name="url_articulo" id="url_articulo" >
                            
                            <p
                                class="error"
                                v-if="errors.firstByRule('url_articulo','url','form-user')"
                            >Url no válida</p>
                    </div>


                    <div class="col-lg-4 col-md-4 col-sm-12 position-relative">
                        <label for="email_contacto" class="control-label">Email del contacto</label>
                        <input class="sisbeca-input" :class="{'bred': errors.firstByRule('email_contacto','email','form-user')}" v-model="noticeObj.email_contacto"  data-vv-scope="form-user" v-validate data-vv-rules="email" autocomplete="off"  placeholder="johndoe@dominio.com"  name="email_contacto" id="email_contacto" >
                        <p
                                class="error"
                                v-if="errors.firstByRule('email_contacto','email','form-user')"
                            >Email no válido</p>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <label for="telefono_contacto" class="control-label">Telefono del contacto</label>
                        <input class="sisbeca-input" placeholder="0212-8880099"  autocomplete="off"  v-model="noticeObj.telefono_contacto" name="telefono_contacto" id="telefono_contacto" >
                    </div>

                </div>

                <div class="row">
                   

                    <div class="col-lg-4 col-md-4 col-sm-12" v-if="noticeObj.tipo=='noticia'">
                        <label for="destacada" class="control-label">Destacada</label>
                        <div class="col-lg-12" style="border-radius: 5px;border:1px solid #021f3a;height: 40px;">
                            <div class="checkbox text-center" >
                                <input type="checkbox" value="1" id="destacada"  autocomplete="off"  v-model="noticeObj.al_carrousel" name='destacada'>
                                ¿Destacado en carrousel?
                            </div>
                        </div>
                    </div>
                     
                </div>

                <div class="form-group pt-3">
                    <label for="contenido" class="control-label">*Contenido</label>
                    <div class="position-relative" >
                     <!--<textarea  class="summernote" ref="noticia_content" v-model="content"  name="summernoteInput" :class="{'error-content':error_content || error_content_required}"  ></textarea> -->
                     <vue-editor v-model="content" :editorToolbar="editorToolbar"  :class="{'error-content':error_content || error_content_required}" ></vue-editor>

                    <p
                      class="error errorc"
                       v-if="error_content_required"
                        >Campo requerido</p>
                      <p
                        class="error errorc"
                        v-else-if="error_content"
                      >Minimo 60 caracteres</p>
                      </div>
                </div>
                <hr>
                
                <div class="form-group text-right">
                    <a @click="viewPreview" class="btn btn-sm sisbeca-btn-primary">Vista Previa</a>
                    <a @click="save" class="btn btn-sm sisbeca-btn-primary">Guardar</a>
                </div>

        </div>
      </div>
    <section v-if="isLoading2" class="loading" id="preloader">
            <div>
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
            </div>
    </section>


  </div>
</template>
<script>

</script>
<script>

/*
import $ from 'jquery' // summernote needs it
import Popper from 'popper.js'
import 'popper.js'
import 'bootstrap'
require('summernote/dist/summernote.js');
require('summernote/dist/summernote.css');
require('summernote');
*/

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate, { fieldsBagName: 'veeFields' });
import VueScrollTo from 'vue-scrollto';
Vue.use(VueScrollTo);
import VueCarousel from 'vue-carousel';
Vue.use(VueCarousel);
import ViewNotice from "../components/viewNoticeComponent.vue";
import { VueEditor } from 'vue2-editor'


export default {
  props: {
    route: {
      type: String,
      required: false,
      default: null
    },
    idEdit: {
      type: Number,
      required: false,
      default: -1
    },
    notice:{
      type: Object,
      required: false,
      default: null
    },
    rimg: {
      type: String,
      required: false,
    },
    urlInsertImg: {
      type:String,
      required: false
    },
    getimage: {
      type: String,
      required: false
    },
    storagedelete: {
      type: String,
      required: false
    }
  },
 components: {
    ViewNotice,
    VueEditor,

  },
  data() {
    return {
        editorToolbar: [
          [{ 'header': [false, 1, 2, 3, 4, 5, 6, ] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
          ['blockquote'],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          [{ 'color': [] }],
          ['link'],
        ],
        isLoading2: false,
        newImagenUrl: '',
        showNoticeModal: false,
        summer: false,
        editGallery: [],
        newImagen: '',
        destacada: '',
        content: '',
        error_content: false,
        error_content_required: false,
        noticeObj: {
          id: null,
          al_carrousel: 0,
          contenido: '',
          email_contacto: '',
          informacion_contacto: '',
          telefono_contacto: '',
          tipo: 'noticia',
          titulo: '',
          url_articulo: '',
          url_imagen: '',
        },
        noticeEdit: {...this.notice},
        telf_contacto: '',
        email_contacto: '',
        title: '',
        type: 'noticia',
        publicator: '',
        url_miembroi: '',
        imagesSelectedEdit: [],
        isEdit: 0,
        galeria: [],
    };
  },
  created(){
    this.isLoading2 = true
  },
  mounted(){
    this.summer = true
    setTimeout(e => { 
     /* $('.summernote').summernote({
        lang: 'es-ES',
        placeholder: 'Redacta la noticia aqui',
        codemirror: {
					"theme": "ambiance"
				},
        dialogsInBody: true,
        disableDragAndDrop: false,
        dialogsFade: true,
        toolbar: [
          // [groupName, [list of button]]
          ['style', ['style']],
          ['font', ['fontname']],
          ['font', ['fontsize', 'color']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough', 'superscript', 'subscript']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['height', ['height']],
          ['insert', ['hr','table','link','picture','video', 'doc']], // image and doc are customized buttons
          ['misc', ['undo','redo','fullscreen']]
        ],
        height: 500,
        callbacks: {
            onImageUpload : (files) => {
              for(var i = 0; i < files.length; i++)
              {
                this.sendFile(files[i]);
              }
            },
            //onMediaDelete : (target) => {
             //   this.deleteStorage(target[0].src);
            //},
       }
      }); */
    },1)
    if(this.idEdit !== -1){
      this.isEdit = 1
      this.newImagenUrl = this.rimg
      this.noticeObj = this.noticeEdit
      this.content =this.noticeObj.contenido
       //$('.summernote').summernote('code',this.content)

    }
    this.isLoading2 =false
    window.addEventListener("click",(e) => {
       if(document.getElementsByClassName("fullscreen").length){
         var element = document.getElementsByClassName("note-popover popover")
         for (let i = 0; i < element.length; i++) {
            element[i].classList.add('z-indexFull')
        }
       }else {
         var element = document.getElementsByClassName("note-popover popover")
         for (let i = 0; i < element.length; i++) {
            element[i].classList.remove('z-indexFull')
        }
       }

    });

  },
  watch: {
    content: function() {
      if(this.content.length>0 && this.content.length<60)
      {
        this.error_content_required = false
        this.error_content = true
      } else{
        if(this.error_content && this.content.length === 0){
          this.error_content_required = true
        } else {
          this.error_content_required = false
          this.error_content = false
        }
      }
    }
  },
  methods: {
    /*sendFile(file) {
     if(file.type.includes('image'))
      {
        var name = file.name.split(".");
        name = name[0];
        var data = new FormData();
        data.append('file', file);
        this.isLoading2 = true
        axios.post(this.urlInsertImg,data,{
                headers:
                {
                    'Content-Type': 'application/json',
                }
        }).then(response =>
        {
          if(response.data.res){
             let url = this.getimage
             url = url.replace(':id_img',response.data.url.replace('/','',1))
             $('.summernote').summernote('insertImage', response.data.url, name);
          }else {
            toastr.warning('Hubo un error al subir Imagen');
          }
          this.isLoading2 = false
        }).catch( error => {
            toastr.error('Ocurrio un error inesperado al guardar Publicación')
            this.isLoading2 = false
        });  
      }
      
    },*/
    viewPreview() {
      //this.content = $('.summernote').summernote('code')
      if(this.content.length > 60)
      {
        this.showNoticeModal = true
      } else {
         toastr.warning('El contenido de la noticia debe ser mayor a 60 caracteres');
      }
    },
    save() {
        //this.content = $('.summernote').summernote('code')
        this.$validator.validateAll("form-user").then(resp => {
            if (resp && this.content.length && !this.error_content && !this.error_content_required) {
              if (this.newImagenUrl && (this.newImagen || this.isEdit))
              {
                this.noticeObj.contenido = this.content
                this.isLoading2 = true
                let url = this.route
                var dataform = new FormData();

                dataform.append('file_img', this.newImagen);
                dataform.append('isEdit',parseInt(this.isEdit))
                let data = JSON.stringify({
                        notice: this.noticeObj,
                    });
                dataform.append('notice',data)
                axios.post(url,dataform,{
                headers:
                {
                    'Content-Type': 'application/json',
                }
                }).then(response =>
                {
                  if(response.data.res){
                    toastr.success(response.data.msg);
                    this.isLoading2 = false;
                    setTimeout(e => { 
                      // $('.summernote').summernote("destroy");
                      this.deleteStorage();
                      this.$emit('save')
                    },1000)

                  }else {
                    toastr.warning('Hubo un error al guardar');
                  }
                  this.isLoading2 = false
                }).catch( error => {
                    toastr.error('Ocurrio un error inesperado al guardar Publicación')
                    this.isLoading2 = false
                });
              }
              else{
                 toastr.warning('Debe subir la imagen principal de la noticia');
                 let element = document.getElementById("formulario");
                  var options = {
                    offset: 0,
                    force: true
                  };
                  this.$scrollTo(element, 1000, options);
              }
              
                              
            }else{
              if(this.content.length === 0) {
                this.error_content_required = true
              }
              let element = document.getElementById("formulario");
              var options = {
                offset: 0,
                force: true
              };
              this.$scrollTo(element, 1000, options);
            }
        });

    },
    deleteStorage(){
        this.isLoading2 = true
        var data = new FormData();
        data.append('id_noticia',-1);
        axios.post(this.storagedelete,data,{
          headers:
          {
              'Content-Type': 'application/json',
          }
        }).then(response =>
        {
          this.isLoading2 = false
        }).catch( error => {
            this.isLoading2 = false
        }); 
    },
    close2(){
        var cond = $('#main_body').hasClass('overflowM')
        var element = document.getElementById("main_body")
        if(cond) {
            element.classList.remove('overflowM')
        }
        this.showNoticeModal=false
    },
    atras(){
      //$('.summernote').summernote("destroy");
      this.deleteStorage();
      this.$emit('close')
    },
    openInputFile () {
      let elem = this.$refs.imagenInput
      elem.click()
    },
    openInputGaleria () {
      let elem = this.$refs.galeriaInput
      elem.click()
    },
    onSelectEdit: function (data) {
      this.imagesSelectedEdit = data
    },
    showEditGallery () {
      this.$refs.selectEdit.resetMultipleSelection()
      this.$refs.editGalleryModal.open()
    },
    changeAttachment (event) {
      if (event.target.files[0].size / (1024*3) <= (1024*3) && event.target.files[0].type.split('/')[0] === 'image') {
        this.newImagen = event.target.files[0]
        var reader = new FileReader()
        reader.readAsDataURL(event.target.files[0])
        reader.onload = function () {
         this.newImagenUrl = reader.result
        }.bind(this)
        reader.onerror = function (error) {
          console.log('Error:', error)
        }
      } else {
        console.log('errooor')
        toastr.error('Error. La imagen supera los 3 Mb.')

      }
    },
    addAttachment (event) {
      if (event.target.files[0].size / (1024*3) <= (1024*3) && event.target.files[0].type.split('/')[0] === 'image') {
        let image= event.target.files[0]
        let url = ''
        var reader = new FileReader()
        reader.readAsDataURL(event.target.files[0])
        reader.onload = function () {
          url = reader.result
          this.galeria.push({
          'image': image,
          'url': url
          })
        }.bind(this)
        reader.onerror = function (error) {
          console.log('Error:', error)
        }
      } else {
        console.log('errooor')
        toastr.error('Error. La imagen supera los 3 Mb.')

      }
    }
  }
};
</script>
