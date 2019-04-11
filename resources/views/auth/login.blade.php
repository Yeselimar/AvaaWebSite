{{Auth::check()}}
{{-- aqui en este extends esta  la funcion Auth::check() que autentifica si el usuario ya inicio sesion y lo redirige a la pagina principal del sistema --}}
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- Tell the browser to be responsive to screen width -->
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="author" content="">
   <meta name="csrf-token" content="{{ csrf_token() }}">


   <link rel="icon" type="image/png" sizes="16x16" href="{{asset('info_sitio/img/favicon.png')}}" >
   <title>AVAA - Iniciar Sesión</title>

   @include('sisbeca.layouts.partials.filescss')
</head>

<body class="fix-header fix-sidebar">
   <!-- Preloader - style you can find in spinners.css -->
   <div class="preloader">
       <svg class="circular" viewBox="25 25 50 50">
           <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
   </div>
   <!-- Main wrapper  -->
   <div id="main-wrapper">
        <div class="header">
           @include('sisbeca.layouts.partials.navtop')
        </div>
        <br>
        <div class="unix-login">
          <div class="container-fluid" > 
            <div class="row justify-content-center">
              <div class="col-lg-5 col-md-6 col-sm-7" style="padding-left: 0px;padding-right: 0px;">
                  <div class="login-content panel panel-default">
                    <div class="panel-heading" align="center">
                      <h2 style="color:white"> Iniciar Sesión</h2>
                    </div>
                    <div class="login-form panel-body" style="padding-top: 0px!important" id="app">
                          <!-- Cargando.. -->
                          <section v-if="isLoading" class="loading" id="preloader">
                            <div>
                                <svg class="circular" viewBox="25 25 50 50">
                                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
                            </div>
                          </section>
                        <!-- Cargando.. -->
                      @include('flash::message')
                      <div v-if="res" class="alert alert-danger alert-dismissible">
                        <strong>@{{msg}}</strong> 
                      </div>
                      <!-- Antes estaba la ruta 'login', Att. Rafael -->

                          <!--<div class=" {{ $errors->has('email') ? ' has-error' : '' }}">-->
                          <div class='pb-1'>
                             <label for="email" class="control-label">Correo Electrónico</label>
                                <input id="email" type="email" class="sisbeca-input" v-model="email" required name="email" value="{{ old('email') }}" >
                                <span v-if="errores.email" :class="['label label-danger']">@{{ errores.email[0] }}</span>
                          </div>

                          <!--</div>-->
                          <!--<div class="{{ $errors->has('password') ? ' has-error' : '' }}">-->
                          <div class='pb-1'>
                              <label for="password" class="control-label">Contraseña</label>
                              <input id="password" required v-model="password" type="password" class="sisbeca-input" name="password">
                              <span v-if="errores.password" :class="['label label-danger']">@{{ errores.password[0] }}</span>
                          </div>
                          <!--</div>-->
                          
                          <div class="row">
                            <div class="col-lg-6">
                              <label class="pull-left">
                              <a href="{{ route('recuperar.contrasena') }}" style="color:#424242" >¿Olvidaste tu contraseña?</a>
                              </label>
                            </div>
                            
                            <div class="col-lg-6">
                              <button  @click.stop.prevent="login" class="btn sisbeca-btn-default btn-block pull-right">Ingresar</button>
                            </div>
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                              <a href="{{ route('registro.postulante.becario') }}" class="btn sisbeca-btn-primary btn-block">Postularse a ProExcelencia</a>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                              <a href="{{route('registro.postulante.mentor')}}" class="btn sisbeca-btn-primary btn-block">Postularse como Mentor</a>
                            </div>
                          </div>
                          
                            
                          <!--  route('registerMentor') -->
                          
                          

                          @include('flash::message')
                          {{--  <p>¿No estas Registrado? <a href="{{ route('register') }}"> Registrate Aqui!</a></p> --}}

                    </div>
                  </div>
              </div>
            </div>
          </div>
          
       </div>
 

   </div>

   <!-- End Wrapper -->
   <!-- All Jquery -->

   @include('sisbeca.layouts.partials.filesjs')
   <script>
  const app = new Vue({
    el: '#app',
    data:
    {
      isLoading: false,
      password: '',
      email: '',
      res: false,
      errores: []
    },
    methods: {
      login() {
        this.isLoading = true
        var dataform = new FormData();
        dataform.append('password', this.password);
        dataform.append('email',this.email);
				var url = "{{route('post.login')}}";
				axios.post(url,dataform).then(response => 
				{

          if(response.data.res){
            this.res = true
            this.msg = response.data.msg
            this.isLoading= false
          } else {
            let url = "{{route('seb')}}"
						location.replace(url)
          }
				}).catch( error =>
            {
              console.clear()
              this.errores = error.response.data.errors;
              this.isLoading= false
              toastr.error("Disculpe, verifique el formulario");               
            });
      }
    }
  })
  </script>

</body>

</html>