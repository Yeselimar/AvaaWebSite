<style scoped>
.loading {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1050;
  background-color: rgba(0, 0, 0, 0.2);
}
</style>
<template>
    <div>
      <div  class="col-lg-12">
      <div class="table-responsive">
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
                <label>Buscar:<input type="search" v-model="filter" class="form-control form-control-sm" placeholder="" ><b-btn :disabled="!filter" @click="filter = ''">Limpiar</b-btn>
                    
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
                    :becarios="becarios"
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
              <template slot="becario" slot-scope="row" >
              {{row.value.name}}
              </template>
              <template slot="mentor" slot-scope="row">
                  <template v-if="idShow == vShow(row)">
                    <!--
                    <select class="form-control select-mentor" id="mentor" name="mentor" v-model="updateMentors[row.index]">

                     <option :value=null>Sin Mentor</option>
                     <option v-for="(mentor,i) in mentores" :key="i" v-bind:value="mentor.id">
                                    {{ mentor.name + ' ' + mentor.last_name}}
                    </option>

                    </select>
              -->
                    <v-select :options="mentores" v-model="selected" label="name">
                      <template slot="option" slot-scope="option">
                          {{ option.name}}
                      </template>
                  </v-select>
                </template>
              <template v-else>
              {{(row.value.id != null) ? row.value.name : 'Sin Mentor' }}
              </template>

              </template>
              <template slot="actions" slot-scope="row">
                <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
               
                  <div v-if="idShow == vShow(row)">
                    <!--
                     <b-button size="sm" @click.stop="edit(row.index)">
                       Guardar 
                    </b-button>  -->
                      <button class="btn btn-sm sisbeca-btn-primary" @click.stop="save(row.index,row)" data-toggle="tooltip" data-placement="bottom" title="Guardar">
								      <i class="fa fa-check"></i>
                    </button>
                    <button class="btn btn-sm sisbeca-btn-default" @click.stop="cancel(row.index,row)" data-toggle="tooltip" data-placement="bottom" title="Cancelar">
								      <i class="fa fa-times"></i>
                    </button>
                  </div>
                  <div v-else>
                    <button class="btn btn-sm sisbeca-btn-primary" @click.stop="edit(row.index,row,$event)" data-toggle="tooltip" data-placement="bottom" title="Asignar Relación">
								      <i class="fa fa-pencil"></i>
                    </button>
                  </div> 
                
              </template>
              <template slot="row-details" slot-scope="row">
                <b-card>
                  <ul>
                    <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value}}</li>
                  </ul>
                </b-card>
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
      </div>
    <section class="loading" id="preloader">
      <div>
          <svg class="circular" viewBox="25 25 50 50">
              <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
      </div>
    </section>


		<div class="modal fade" id="msgModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
				    	<h5 class="modal-title"><strong>{{msgTitle}}</strong></h5>
				    </div>
					<div class="modal-body">
						<div class="col-lg-12">
							<p class="h6 text-center">{{msgBody}}</p>
						</div>
						
					</div>
				</div>
			</div>
		</div>

  </div>
</template>

<script>
export default {
  props: {
    relacionbm: {
      type: String,
      required: false
    },
    asignarrbm: {
      type: String,
      required: false
    },
    getm: {
      type: String,
      required: false
    },
    getb: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      relacionBecarioMentor: this.$props.relacionbm,
      asignarRelacionBM : this.$props.asignarrbm,
      getMentores: this.$props.getm,
      getBecarios: this.$props.getb,
      msgBody: "",
      msgTitle: "",
      idShow: 0,
      becarios: [],
      mentores: [
        {
          id: null,
          name:''
        }
      ],
      selected: {
          id: null,
          name:''
      },
      items: [
        {
          becario: {
            id: null,
            name: ""
          },
          mentor: {
            id: null,
            name: ""
          },
          _rowVariant: ""
        }
      ],
      editItems: [],
      updateMentors: [],
      fields: [
        {
          key: "becario",
          label: "Becario",
          sortable: true,
          class: "text-center"
        },
        {
          key: "mentor",
          label: "Mentor",
          sortable: true,
          class: "text-center"
        },
        { key: "actions", label: "Acciones" }
      ],
      currentPage: 1,
      perPage: 10,
      totalRows: 0,
      pageOptions: [5, 10, 15, 20, 50, 100],
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      filter: null
    };
  },
  mounted() {
    console.log("Component mounted.");
    $("#preloader").show();
  },
  beforeMount() {
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
    showAlert(title,body){
            // Use sweetalert2
            swal(title,
             body,
            'success');
            
        },
    getDataTable() {
      axios
        .get(this.relacionBecarioMentor)
        .then(response => {
          this.llenarSelects();
          console.log("response: ", response.data)
          this.items = response.data;
          
          this.items.forEach(function(item) {
            this.editItems.push(false);
            //this.selected.push(item.mentor);
          }, this);
          console.log(this.items);
          this.totalRows = this.items.length;
          $("#preloader").hide();
        })
        .catch(err => {
          console.error("error reject", err);
          $("#preloader").hide();
          return err;
        });
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    edit(index, row,e) {
      //this.editItems.splice(index, 1, !this.editItems[index]);
      this.selected = row.item.mentor;
      if(row.item.mentor.id != null) {
        this.idShow = row.item.becario.id;
      }
      else {
         this.idShow = row.item.mentor.id+row.item.becario.id;
      }
},
    cancel(index, row) {
      if (this.selected.id== null){
        row.item._rowVariant = 'warning';
      }
      else{
         row.item._rowVariant = 'success';
      }
      this.selected= {};
      //this.editItems.splice(index, 1, !this.editItems[index]);
      this.idShow= 0;
    },
    save(index, row) {
      if(this.selected==null){
        this.selected = {
          id: null,
          name: 'Sin Mentor'
        };
        row.item._rowVariant = 'warning';
      }
      if (this.selected.id !== row.item.mentor.id) {
        $("#preloader").show();
        var dataform = new FormData();
        dataform.append("becarioId", row.item.becario.id);
        dataform.append("mentorId", this.selected.id);
        var url = this.asignarRelacionBM;
        axios
          .post(url, dataform)
          .then(response => {
            //let mentor = this.mentorFind(this.updateMentors[index]);
            row.item.mentor = this.selected
            if(this.selected.id == null){
              row.item._rowVariant = 'warning';
              row.item.mentor.id = null;
              row.item.mentor.name = "Sin Mentor";
            } else {
              row.item._rowVariant = 'success';
            }
            //this.editItems.splice(index, 1, !this.editItems[index]);
            this.idShow= 0;

            this.msgTitle = "Registro Actualizado con Exito";
            this.msgBody =
              "La relación Becario-Mentor ha sido actualizada exitosamente";
            this.showAlert(this.msgTitle,this.msgBody);
            $("#preloader").hide();
          })
          .catch(error => {
            $("#preloader").hide();
            console.log(error);
            this.msgTitle = "Ha ocurrido un error";
            this.msgBody =
              "Ocurrio un error inesperado al actualizar relación Becario-Mentor";
            $("#msgModal").modal("show");
          });
      } else {
        this.idShow = 0;
        //this.editItems.splice(index, 1, !this.editItems[index]);
      }
    },
    vShow(row) {
      let show = 0;
      if(row.item.mentor.id != null) {
        show = row.item.becario.id;
      }
      else {
         show = row.item.mentor.id+row.item.becario.id;
      }

      return show;
    },
    inicializarData() {
      this.items = [
        {
          becario: {
            id: null,
            name: ""
          },
          mentor: {
            id: null,
            name: ""
          },
          _rowVariant: ""
        }
      ];
      this.editItems = [];
      this.updateMentors = [];
      this.becarios = [];
      this.mentores = [];
    },
    llenarSelects() {
      axios
        .get(this.getMentores)
        .then(response => {
          let auxMentores = [];
          auxMentores = response.data;
          this.mentores = [];
          auxMentores.forEach(function(mentor, i) {
            this.mentores.push({
              id: mentor.user_id,
              name: mentor.name
            });
          }, this);
        })
        .catch(err => {
          console.error("error reject", err);
          return err;
        });
      axios
        .get(this.getBecarios)
        .then(response => {
          let auxBecarios = [];
          auxBecarios = response.data;
          console.log(auxBecarios[0]["user"]);
          auxBecarios.forEach(function(becario, i) {
            this.becarios.push({
              id: becario.user_id,
              name: becario.user.name + " " + becario.user.last_name
            });
          }, this);
          console.log(this.becarios);
        })
        .catch(err => {
          console.error("error reject", err);
          return err;
        });
    },
    mentorFind (value) {
        let mentorAux = {
          id: null,
          name: '',
          last_name: ''
        };
        this.mentores.forEach(function (mentor, index) {
          if (mentor.id === value) {
            mentorAux = mentor;
          }
        })
        return mentorAux;
      }
  }
};
</script>
