@extends('sisbeca.layouts.main')
@section('title','Editar Perfil: '.$becario->user->nombreyapellido())
@section('content')
<div class="col-lg-12" id="app">
	
	<ul class="nav nav-tabs" role="tablist">
	  	<li class="nav-item">
	    	<a class="nav-link active" href="#datos" role="tab" data-toggle="tab">Datos Personales</a>
	  	</li>
	  	<li class="nav-item">
	    	<a class="nav-link" href="#universidad" role="tab" data-toggle="tab">Estudios Universitarios</a>
	  	</li>

      @if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
	  	<li class="nav-item">
	    	<a class="nav-link" href="#estatus" role="tab" data-toggle="tab">Estatus Becarios</a>
	  	</li>
      @endif

      <li class="nav-item">
        <a class="nav-link" href="#foto-perfil" role="tab" data-toggle="tab">Foto Perfil</a>
      </li>


	</ul>
	
	<div class="tab-content">
	  <div class="tab-pane active" id="datos">
      
			<br>
      
			<div class="form-group" style="border: 1px solid #eee; padding: 10px; border-radius: 5px;">
				
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Nombres</label>
             	<input type="text" disabled="disabled" name="name" class="sisbeca-input sisbeca-disabled" v-model="name">
             	<span v-if="errores.name" :class="['label label-danger']">@{{ errores.name[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Apellidos</label>
             	<input type="text" disabled="disabled" name="last_name" class="sisbeca-input sisbeca-disabled" v-model="last_name">
             	<span v-if="errores.last_name" :class="['label label-danger']">@{{ errores.last_name[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Correo Electrónico</label>
              <input type="text" disabled="disabled" name="email" class="sisbeca-input sisbeca-disabled" v-model="email">
              <span v-if="errores.email" :class="['label label-danger']">@{{ errores.email[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Cédula</label>
             	<input type="text" disabled="disabled" name="nombreyapellido" class="sisbeca-input sisbeca-disabled" v-model="cedula">
             	<span v-if="errores.cedula" :class="['label label-danger']">@{{ errores.cedula[0] }}</span>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">*Sexo</label>
             	<select v-model="sexo" class="sisbeca-input">
                <option disabled value="">Sexo</option>
                <option>femenino</option>
                <option>masculino</option>
              </select>
             	<span v-if="errores.sexo" :class="['label label-danger']">@{{ errores.sexo[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
            <label class="control-label">*Fecha Nacimiento</label>
            <date-picker class="sisbeca-input" name="fecha_nacimiento" v-model="fecha_nacimiento" placeholder="DD/MM/AAAA" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
            <span v-if="errores.fecha_nacimiento" :class="['label label-danger']">@{{ errores.fecha_nacimiento[0] }}</span>
          </div>
                    
				</div>
        <hr>
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <label class="control-label">*Dirección Permanente</label>
            <input type="text" name="direccion_permanente" class="sisbeca-input" v-model="direccion_permanente" placeholder="EJ: Urb. Las Quintas, Naguanagua, Carabobo">
              <span v-if="errores.direccion_permanente" :class="['label label-danger']">@{{ errores.direccion_permanente[0] }}</span>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <label class="control-label">Dirección Temporal</label>
            <input type="text" name="direccion_temporal" class="sisbeca-input" v-model="direccion_temporal" placeholder="EJ: Urb. Araguaney, Los Guayos, Carabobo.">
              <span v-if="errores.direccion_temporal" :class="['label label-danger']">@{{ errores.direccion_temporal[0] }}</span>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-12">
            <label class="control-label">*Teléfono Celular</label>
            <input type="text" name="celular" class="sisbeca-input" v-model="celular" placeholder="EJ: 04247778822">
              <span v-if="errores.celular" :class="['label label-danger']">@{{ errores.celular[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12">
            <label class="control-label">*Teléfono Habitación</label>
            <input type="text" name="telefono_habitacion" class="sisbeca-input" v-model="telefono_habitacion" placeholder="EJ: 02415556600">
              <span v-if="errores.telefono_habitacion" :class="['label label-danger']">@{{ errores.telefono_habitacion[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-12">
            <label class="control-label">*Teléfono Pariente</label>
            <input type="text" name="telefono_pariente" class="sisbeca-input" v-model="telefono_pariente" placeholder="EJ: 04129996600">
              <span v-if="errores.telefono_pariente" :class="['label label-danger']">@{{ errores.telefono_pariente[0] }}</span>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-lg-2 col-md-2 col-sm-12">
            <label class="control-label">¿Trabaja?</label>
            <select v-model="trabaja" class="sisbeca-input">
              <option disabled value="">¿Trabaja?</option>
              <option value="1">si</option>
              <option value="0">no</option>
            </select>
              <span v-if="errores.trabaja" :class="['label label-danger']">@{{ errores.trabaja[0] }}</span>
          </div>
          <template v-if="trabaja=='1'">
            <div class="col-lg-4 col-md-4 col-sm-12">
              <label class="control-label">Lugar Trabajo</label>
              <input type="text" name="lugar_trabajo" class="sisbeca-input" v-model="lugar_trabajo" placeholder="EJ: Google">
                <span v-if="errores.lugar_trabajo" :class="['label label-danger']">@{{ errores.lugar_trabajo[0] }}</span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
              <label class="control-label">Cargo Trabajo</label>
              <input type="text" name="cargo_trabajo" class="sisbeca-input" v-model="cargo_trabajo" placeholder="EJ: Desarrollador Web">
                <span v-if="errores.cargo_trabajo" :class="['label label-danger']">@{{ errores.cargo_trabajo[0] }}</span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
              <label class="control-label">Horas Trabajo</label>
              <input type="text" name="horas_trabajo" class="sisbeca-input" v-model="horas_trabajo" placeholder="EJ: 2">
                <span v-if="errores.horas_trabajo" :class="['label label-danger']">@{{ errores.horas_trabajo[0] }}</span>
            </div>
          </template>
        </div>
        
			</div>

			<div class="form-group ">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						  <input class="btn sisbeca-btn-primary pull-right" type="button" value="Guardar" @click="guardar()">
					</div>
				</div>
			</div>		
			
		</div>

  	<div class="tab-pane" id="universidad">
  		<br>

      <div class="form-group" style="border: 1px solid #eee; padding: 10px; border-radius: 5px;">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Fecha Inicio Universidad</label>
              <date-picker class="sisbeca-input" name="inicio_universidad" v-model="inicio_universidad" placeholder="DD/MM/AAAA" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
            <span v-if="errores.inicio_universidad" :class="['label label-danger']">@{{ errores.inicio_universidad[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Universidad</label>
              <input type="text" name="name" class="sisbeca-input" v-model="nombre_universidad">
              <span v-if="errores.nombre_universidad" :class="['label label-danger']">@{{ errores.nombre_universidad[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Carrera</label>
              <input type="text" name="name" class="sisbeca-input" v-model="carrera_universidad">
              <span v-if="errores.carrera_universidad" :class="['label label-danger']">@{{ errores.carrera_universidad[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Régimen</label>
              <select v-model="regimen" class="sisbeca-input">
                <option disabled value="">Régimen</option>
                <option value="semestral">Semestral</option>
                <option value="trimestral">Trimestral</option>
                <option value="anual">Anual</option>
              </select>
              <span v-if="errores.regimen" :class="['label label-danger']">@{{ errores.regimen[0] }}</span>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Promedio Universidad</label>
              <input type="text" name="name" class="sisbeca-input" v-model="promedio_universidad">
              <span v-if="errores.promedio_universidad" :class="['label label-danger']">@{{ errores.promedio_universidad[0] }}</span>
          </div>
        </div>
      </div>

      <div class="form-group ">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <input class="btn sisbeca-btn-primary pull-right" type="button" value="Guardar" @click="guardaruniversidad()">
          </div>
        </div>
      </div>

  	</div>

    @if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
  	<div class="tab-pane" id="estatus">
  		<br>

      <div class="form-group" style="border: 1px solid #eee; padding:10px; border-radius:5px">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <label class="control-label">Becario Estatus</label>
              <select v-model="becario_estatus" class="sisbeca-input">
                <option disabled value="">Estatus Becario</option>
                <option value="desincorporado">Desincorporado</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
                <option value="probatorio1">Probatorio 1</option>
                <option value="probatorio2">Probatorio 2</option>
              </select>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <label class="control-label">Usuario Estatus</label>
              <select v-model="usuario_estatus" class="sisbeca-input">
                <option disabled value="">Estatus Usuario</option>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
          </div>
        </div>
      </div>
      <div class="alert alert-info alert-dismissible">
        <strong>Becarios Estatus:</strong> Este estatus es para el becario. Los estatus permitidos: Activo, Probatorio 1 y Probatorio 2.
      </div>
      <div class="alert alert-info alert-dismissible">
        <strong>Usuarios Estatus:</strong> Al actualizar el estatus del usuario, el usuario ya no tendrá acceso al sistema. Los estatus permitidos son: Activo e Inactivo.
      </div>
      <div class="alert alert-info alert-dismissible">
        <strong>Ejemplo:</strong> Un becario puede estar Probatorio 1 y activo. Un becario puede estar probatorio 2 e inactivo.
      </div>
      <div class="form-group ">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <input class="btn sisbeca-btn-primary pull-right" type="button" value="Guardar" @click="guardarestatus()">
          </div>
        </div>
      </div>

  	</div>
    @endif

    <div class="tab-pane" id="foto-perfil">
      <br>
      <form id="formulario" @submit.prevent="guardarfoto">
        <div class="form-group">
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <label class="control-label">Foto Actual</label>
                <img :src="urlFotoPerfil()" alt="Foto de Perfil" class="img-responsive" style="border:1px solid #eee">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <label class="control-label">Actualizar Foto</label>
              <input type="file" name="foto" class="sisbeca-input" accept="image/*" @change="cargafoto">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
          </div>
        </div>
        
        <div class="form-group">
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <button class="btn sisbeca-btn-primary pull-right" type="submit">Guardar</button>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
          </div>
        </div>
      </form>
      
    </div>

    <div class="tab-pane" id="contrasena">
      <br>

      <div class="form-group" style="border: 1px solid #eee; padding: 10px; border-radius: 5px;">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Contraseña Nueva</label>
              <input type="password" name="contrasena_nueva" class="sisbeca-input" v-model="contrasena_nueva">
              <span v-if="errores.contrasena_nueva" :class="['label label-danger']">@{{ errores.contrasena_nueva[0] }}</span>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-6">
              <label class="control-label">Contraseña Repite</label>
              <input type="password" name="contrasena_repite" class="sisbeca-input" v-model="contrasena_repite">
              <span v-if="errores.contrasena_repite" :class="['label label-danger']">@{{ errores.contrasena_repite[0] }}</span>
          </div>

        </div>
      </div>

      <div class="form-group ">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <input class="btn sisbeca-btn-primary pull-right" type="button" value="Guardar" @click="guardarContrasena()">
          </div>
        </div>
      </div>

    </div>

	</div>

	<!-- Cargando.. -->
	<section class="loading" id="preloader">
		<div>
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
		</div>
	</section>
	<!-- Cargando.. -->
</div>

@endsection

@section('personaljs')
<script>
	const app = new Vue({
	components:{DatePicker},
    el: '#app',
    data:
    {
      img_perfil:'',
      foto: null,
      becario_estatus:'',
      usuario_estatus:'',
      regimen:'',
    	errores:[],
    	becario:'',
    	usuario:'',
    	name:'',
    	last_name:'',
    	cedula:'',
    	sexo:'',
    	fecha_nacimiento:'',
    	email:'',
      direccion_permanente:'',
      direccion_temporal:'',
      celular:'',
      telefono_habitacion:'',
      telefono_pariente:'',
      trabaja:'',
      lugar_trabajo:'',
      cargo_trabajo:'',
      horas_trabajo:'',
      inicio_universidad:'',
      nombre_universidad:'',
      carrera_universidad:'',
      promedio_universidad:'',
      contrasena_actual:'',
      contrasena_nueva:'',
      contrasena_repite:''
    },
    beforeCreate:function()
	{
		$("#preloader").show();
	},
    created: function()
    {
        this.obtenerdatosbecario();
    },
    methods: 
    {
    	obtenerdatosbecario()
    	{
    		var id = "{{$becario->user_id}}";
    		var url = '{{route('becarios.obtener.datos',':id')}}';
    		url = url.replace(':id', id);
        axios.get(url).then(response => 
        {
           	this.becario = response.data.becario;
           	this.usuario = response.data.usuario;
            this.img_perfil = response.data.img_perfil;
            this.usuario_estatus = this.usuario.estatus;
            this.becario_estatus = this.becario.status;
           	this.name = this.usuario.name;
           	this.last_name = this.usuario.last_name;
           	this.cedula = this.usuario.cedula;
           	this.sexo = this.usuario.sexo;
           	this.email = this.usuario.email;
            this.direccion_permanente = this.becario.direccion_permanente;
            this.direccion_temporal = this.becario.direccion_temporal;
            this.celular = this.becario.celular;
            this.telefono_habitacion = this.becario.telefono_habitacion;
            this.telefono_pariente = this.becario.telefono_pariente;
            this.trabaja = this.becario.trabaja;
            if(this.trabaja==null)
            {
              this.trabaja=0;
            }
            this.lugar_trabajo = this.becario.lugar_trabajo;
            this.cargo_trabajo = this.becario.cargo_trabajo;
            this.horas_trabajo = this.becario.horas_trabajo;
            this.nombre_universidad = this.becario.nombre_universidad;
            this.carrera_universidad = this.becario.carrera_universidad;
            this.promedio_universidad = this.becario.promedio_universidad;
            this.regimen = this.becario.regimen;
            var dia = new Date (this.becario.inicio_universidad);
            this.inicio_universidad = moment(dia).format('DD/MM/YYYY');
           	var dia = new Date (this.usuario.fecha_nacimiento);
            this.fecha_nacimiento = moment(dia).format('DD/MM/YYYY');
        	 $("#preloader").hide();
	      }).catch( error => {
  				console.log(error);
  				$("#preloader").hide();
			  });
    	},
    	guardar()
    	{
        $("#preloader").show();
    		var id = '{{$becario->user_id}}';
        var url = "{{ route('becarios.actualizar.datos',':id' ) }}";
        url = url.replace(':id', id);
    		var dataform = new FormData();
        dataform.append('sexo', this.sexo);
        dataform.append('fecha_nacimiento', this.fecha_nacimiento);
        dataform.append('direccion_permanente', this.direccion_permanente);
        dataform.append('direccion_temporal', this.direccion_temporal);
        dataform.append('celular', this.celular);
        dataform.append('telefono_habitacion', this.telefono_habitacion);
        dataform.append('telefono_pariente', this.telefono_pariente);
        dataform.append('trabaja', this.trabaja);
        dataform.append('lugar_trabajo', this.lugar_trabajo);
        dataform.append('cargo_trabajo', this.cargo_trabajo);
        dataform.append('horas_trabajo', this.horas_trabajo);
        axios.post(url,dataform).then(response => 
        {
          $("#preloader").hide();
        	this.errores=[];
        	toastr.success(response.data.success);
          this.obtenerdatosbecario();
        }).catch( error =>
        {
          $("#preloader").hide();
        	this.errores = error.response.data.errors;
          toastr.error("Disculpe, verifique el formulario");
            
        });
    	},
      guardaruniversidad()
      {
        $("#preloader").show();
        var id = '{{$becario->user_id}}';
        var url = "{{ route('becarios.actualizar.universidad',':id' ) }}";
        url = url.replace(':id', id);
        var dataform = new FormData();
        dataform.append('inicio_universidad', this.inicio_universidad);
        dataform.append('nombre_universidad', this.nombre_universidad);
        dataform.append('carrera_universidad', this.carrera_universidad);
        dataform.append('promedio_universidad', this.promedio_universidad);
        dataform.append('regimen', this.regimen);
        axios.post(url,dataform).then(response => 
        {
          $("#preloader").hide();
          this.errores=[];
          toastr.success(response.data.success);
          this.obtenerdatosbecario();
        }).catch( error =>
        {
          $("#preloader").hide();
          this.errores = error.response.data.errors;
          toastr.error("Disculpe, verifique el formulario");
        });
      },
      guardarestatus()
      {
        $("#preloader").show();
        var id = '{{$becario->user_id}}';
        var url = "{{ route('becarios.actualizar.estatusbecario',':id' ) }}";
        url = url.replace(':id', id);
        var dataform = new FormData();
        dataform.append('becario_estatus', this.becario_estatus);
        dataform.append('usuario_estatus', this.usuario_estatus);
        axios.post(url,dataform).then(response => 
        {
          $("#preloader").hide();
          this.errores=[];
          toastr.success(response.data.success);
          this.obtenerdatosbecario();
        }).catch( error =>
        {
          $("#preloader").hide();
          this.errores = error.response.data.errors;
          toastr.error("Disculpe, verifique el formulario");
        });
      },
      guardarContrasena()
      {
        $("#preloader").show();
        var id = '{{$becario->user_id}}';
        var url = "{{ route('becarios.actualizar.contrasena',':id' ) }}";
        url = url.replace(':id', id);
        var dataform = new FormData();
        dataform.append('contrasena_actual', this.contrasena_actual);
        dataform.append('contrasena_nueva', this.contrasena_nueva);
        dataform.append('contrasena_repite', this.contrase_repite);
        axios.post(url,dataform).then(response => 
        {
          $("#preloader").hide();
          this.errores=[];
          toastr.success(response.data.success);
        }).catch( error =>
        {
          $("#preloader").hide();
          console.log(error);
          this.errores = error.response.data.errors;
          toastr.error("Disculpe, verifique el formulario");
        });
      },
      cargafoto(event)
      {
        this.foto = event.target.files[0];
      },
      guardarfoto(event)
      {
        $("#preloader").show();
        var id = '{{$becario->user_id}}';
        var url = "{{ route('becarios.actualizar.foto',':id' ) }}";
        url = url.replace(':id', id);
        var dataform = new FormData();
        dataform.append('foto', this.foto);
        axios.post(url,dataform).then(response => 
        {
          this.obtenerdatosbecario();
          $("#preloader").hide();
          toastr.success(response.data.success);
        }).catch( error =>
        {
          $("#preloader").hide();
          console.log(error);
        });
        event.target.reset();
      },
      urlFotoPerfil()
      {
        if(this.img_perfil==null)
        {
          if(this.usuario.sexo=='masculino')
          {
            var slug='images/perfil/masculino.png';
          }
          else
          {
            var slug='images/perfil/femenino.png';
          }
        }
        else
        {
          var slug=this.img_perfil.url;
        }
        var url = "{{url(':slug')}}";
        url = url.replace(':slug', slug);
        return url;
      },
    }

	});
</script>
@endsection