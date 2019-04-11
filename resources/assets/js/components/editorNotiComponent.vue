<style  lang="scss" scoped>
.loading {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1050;
  background-color: rgba(0, 0, 0, 0.2);
}
.overflowM {
    overflow: hidden !important;
  }
</style>
<template>
    <div>
      <create-publicaciones v-if="newP" @close="close" :idEdit="idEdit" :notice="notice" :route="route" :rimg="route_img" :urlInsertImg="routeinsertimg" :storagedelete="storagedelete" :getimage="getimage" @save="save"></create-publicaciones>
      <div  class="col-lg-12"  v-if="listar">
        <view-notice v-if="showModal" @close2="closeM" :title="notice.titulo" :content2="notice.contenido"></view-notice>

      <div class="table-responsive">
         <div class="text-right">
            <a @click="newPublicacion" class="btn btn-sm sisbeca-btn-primary">Crear publicación</a>
        </div>
				<div id="becarios_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
      <div class="row"><div class="col-sm-12 col-md-6">
        <div class="dataTables_length">
          <label>Mostrar 
            <select aria-controls="becarios" v-model="perPage" class="custom-select custom-select-sm form-control form-control-sm">
              <option v-for="(value, key) in pageOptions" :key="key">
                {{value}}
              </option>
            </select> Entradas</label>
            </div></div>
            <div class="col-sm-12 col-md-6">
              <div class="dataTables_filter pull-right">
                 <b-input-group-append>
                <label>Buscar:<input type="search" v-model="filter" class="form-control form-control-sm" placeholder="" >
                    
                </label>
                </b-input-group-append>
                </div>
              </div>
            </div>

         <b-table
                    show-empty
                    empty-text ="No hay registros para mostrar"
                    empty-filtered-text="
                    No hay registros que coincidan con su busqueda"
                    class="table table-bordered table-hover dataTable no-footer"
                    stacked="md"
                    :items="items"
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :sort-direction="sortDirection"
                    @filtered="onFiltered"
            >
              <template slot="titulo" slot-scope="row">
                    <span> {{row.item.publicacion.titulo}}</span>
                    
                </template>
                <template slot="tipo" slot-scope="row">
                    <span> {{row.item.publicacion.tipo.toUpperCase()}}
                        <span v-if="row.item.publicacion.tipo !== 'miembroins'"> 
                            <br>
                            <span class="label label-success" v-if="row.item.publicacion.al_carrousel">Destacada</span>
                            <span v-else class="label label-danger">No destacada</span>
                        </span>
                    </span>
                </template>
                <template slot="editor" slot-scope="row">
                    <span> {{row.item.editor}}</span>    
                </template>
                <template slot="fecha_actualizacion" slot-scope="row">
					<span v-if="row.item.fecha_actualizacion">
						{{ row.item.fecha_actualizacion }}
					</span>
				</template>
                <template slot="actions" slot-scope="row">
                  <a v-b-popover.hover.bottom="'Vista Previa'" @click="showPublicacion(row.item.publicacion, row.item.publicacion.id)" class="btn btn-xs sisbeca-btn-primary">
                            <i class="fa fa-eye"></i>
                    </a>
                    <a v-b-popover.hover.bottom="'Editar Publicación'" @click="isLoading = true;editPublicacion(row.item.publicacion, row.item.publicacion.id)" class="btn btn-xs sisbeca-btn-primary">
                            <i class="fa fa-pencil"></i>
                    </a>
                    <a v-b-popover.hover.bottom="'Eliminar Publicación'" @click.stop="modalDelete(row.item.publicacion,row.item.publicacion.id)" class="btn btn-xs sisbeca-btn-primary">
                        <i class="fa fa-trash"></i>
                    </a>
                </template>
            </b-table>
              <br/>
            <b-row>
              <b-col md="12">
                <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0  text-right pull-right" />
              </b-col>
            </b-row>
				</div>
       </div>
            
           
          <b-modal id="deletePID" hide-header-close ref="deletePRef" @hide="resetModal" :title="'Eliminar Publicación: '+notice.titulo" >
              ¿Estas Seguro que desea eliminar la publicación de "{{notice.titulo}}" ?
            <template slot="modal-footer">
                <b-btn size="sm" class="float-right sisbeca-btn-default" variant="sisbeca-btn-default" @click='$refs.deletePRef.hide()'> No</b-btn>
                <b-btn  size="sm" class="float-right sisbeca-btn-primary" @click="deletePublicacion()" variant="sisbeca-btn-primary" > Si </b-btn>
            </template>	
          </b-modal>
      </div>
      <section v-if="isLoading" class="loading" id="preloader">
            <div>
                <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
            </div>
      </section>

  </div>
</template>

<script>
import createPublicaciones from "../components/newPublicacionComponent.vue";
import ViewNotice from "../components/viewNoticeComponent.vue";


export default {
  props: {
    editnotice: {
      type: String,
      required: false
    },
    deletenotice: {
      type: String,
      required: false
    },
    vernotice: {
      type: String,
      required: false
    },
    createnotice: {
      type: String,
      required: false
    },
    getnotices: {
        type: String,
        required: false
    },
    getimage: {
        type: String,
        required: false
    },
    routeinsertimg: {
        type: String,
        required: false
    },
    storagedelete:{
        type: String,
        required: false,
    },
  },
  components: {
    createPublicaciones,
    ViewNotice
  },
  data() {
    return {
      viewN: this.$props.vernotice,
      editN : this.$props.editnotice,
      deleteN: this.$props.deletenotice,
      createN: this.$props.createnotice,
      getN: this.$props.getnotices,
      notice: {},
      route_img: '',
      idEdit: -1,
      isLoading: true,
      listar: true,
      newP: false,
      editP: false,
      items: [
      ],
      fields: [
			{ key: 'titulo', label: 'Titulo', sortable: true, 'class': 'text-center' },
            { key: 'tipo', label: 'Tipo', sortable: true, 'class': 'text-center' },
            { key: 'editor', label: 'Creado por', sortable: true, 'class': 'text-center' },
			{ key: 'fecha_actualizacion', label: 'Fecha Actualización', sortable: true, 'class': 'text-center' },
			{ key: 'actions', label: 'Acciones', 'class': 'text-center' }
      ],
      currentPage: 1,
      perPage: 10,
      totalRows: 0,
      pageOptions: [5, 10, 15, 20, 50, 100,500,1000],
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      showModal: false
    };
  },
  create() {
    this.isLoading = true;
  },
  mounted() {
    /*document.getElementById("myBtn").addEventListener("click", function(){
      alert("The paragraph was clicked.");
    });*/
    this.deleteStorage(-1)
    document.getElementById("myBtn").addEventListener("click", function(event){
        event.preventDefault()
        var cond = $('#mydrop').hasClass('show')
        var element = document.getElementById("mydrop")
        if(cond){
          element.classList.remove('show')

        } else {
          if($('#myAlert').hasClass('show')){
            document.getElementById("myAlert").classList.remove('show')
          }
          element.classList.add('show')
        }
    });
    document.getElementById("myBtnAlert").addEventListener("click", function(event){
        event.preventDefault()
        var cond = $('#myAlert').hasClass('show')
        var element = document.getElementById("myAlert")
        if(cond){
          element.classList.remove('show')

        } else {
          if($('#mydrop').hasClass('show')){
            document.getElementById("mydrop").classList.remove('show')
          }
          element.classList.add('show')

        }
    });
       window.addEventListener("click",(e) => {
            var cond = $('#mydrop').hasClass('show')
            var element = document.getElementById("mydrop")
            if(cond){
              element.classList.remove('show')

            } 
            var cond = $('#myAlert').hasClass('show')
            var element = document.getElementById("myAlert")
            if(cond){
              element.classList.remove('show')

            }
        });

    
    this.getDataTable();
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields.filter(f => f.sortable).map(f => {
        return { text: f.label, value: f.key };
      });
    }
  },
  methods: {
  deleteStorage(notice){
        this.isLoading2 = true
        var data = new FormData();
        data.append('id_noticia',notice);
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
    closeM(){
        var cond = $('#main_body').hasClass('overflowM')
        var element = document.getElementById("main_body")
        if(cond) {
            element.classList.remove('overflowM')
        }
        this.showModal = false
    },
    showPublicacion(publicacion,id){
      this.notice = publicacion
      this.showModal = true
    },
    editPublicacion(publicacion,id){
          this.isLoading = true
          let url = this.editN
          this.route = url.replace(':id_edit',parseInt(id))
          this.idEdit = id
          this.notice = publicacion
          url = this.getimage
          this.route_img = url.replace(':id_img',publicacion.url_imagen.replace('/','',1))
          setTimeout(e => { 
            this.isLoading = false;
            this.listar=false;
            this.newP=true;
          },1)

    },
    modalDelete(publicacion,id){
      let url = this.deleteN
      this.route = url.replace(':id_delete',parseInt(id))
      this.idEdit = id
      this.notice = publicacion
      this.$refs.deletePRef.show()

    },
    resetModal(){
      this.route = ''
      this.notice = {}
    },
    deletePublicacion() {
      this.$refs.deletePRef.hide()
      this.isLoading = true
        axios
        .get(this.route)
        .then(response => {
          
          if(response.data.res){
            this.deleteStorage(idEdit)
            toastr.success(response.data.msg);
            this.getDataTable();
          } else {
            toastr.warning(response.data.msg);
            this.isLoading = false
          }
        })
        .catch(err => {
          console.error("error reject", err);
          toastr.success('Hubo un error inesperado');
           this.isLoading = false

          return err;
        });
    },
    newPublicacion(){
      this.route = this.createN
      this.idEdit = -1
      this.notice = {}
      this.route_img = ''
      this.listar=false;
      this.newP=true
    },
    close(){
          this.newP=false;
          this.listar=true
          this.isLoading = false
          this.loading = false
    },
    save(){
        this.items = []
        this.newP = false;
        this.listar = true;
        this.isLoading = true
        this.getDataTable();
    },
    fechaformatear(fecha)
    {
        if(fecha)
        {
            return moment(new Date (fecha)).format('DD/MM/YYYY')
        }
        return '-'
    },
    getDataTable() {
      axios
        .get(this.getN)
        .then(response => {
          this.items = response.data.publicaciones;
          this.totalRows = this.items.length;
          this.isLoading = false

        })
        .catch(err => {
          console.error("error reject", err);
           this.isLoading = false

          return err;
        });
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    }
  }
};
</script>