

@if(Auth::user()->rol==='postulante_becario'&& Auth::user()->becario->status==='activo' &&  Auth::user()->becario->acepto_terminos== 0 && Auth::user()->becario->getfechabienvenida()=='true')
	@section('title','Terminos y Condiciones ProExcelencia')
	@section('subtitle','Terminos y Condiciones')
	@section('content')
		@include('sisbeca.becarios.terminosCondiciones')
	@endsection
@else
@extends('sisbeca.layouts.main')
@section('title','Inicio')
	@if((Auth::user()->rol==='postulante_becario')||(Auth::user()->rol==='postulante_mentor'))
		@section('subtitle','Pasos')
	@else
			@section('subtitle','Bienvenido')
	@endif

@section('content')
<div class="container-fluid" id="app">
<div v-if="numSol !== 0" class="alert alert-warning alert-dismissible cursor" @click="viewSol">
	<strong>Actualmente existen usuarios pendientes por autorizar cambios de status. Haga Click Aqui</strong> 
</div>
	<div class="container-fluid" style="border:1px solid #dedede;padding: 10px;border-radius: 10px;">
		<h3 class="text-center" >
			<strong>!Bienvenid@, {{ Auth::user()->nombreyapellido()}}!</strong>
		</h3>
	</div>
	<br>
	<div class="container-fluid">
		<div class="row">
			<div class='col-sm-12' align="center" >
				@if((Auth::user()->rol==='postulante_becario')||(Auth::user()->rol==='postulante_mentor'))
					<p> Pasos a Seguir </p>
					@if(Auth::user()->rol==='postulante_becario')
						<div class="col-lg-4"></div>
						<div class="col-lg-4">
							<img src="{{asset('images/postulacion-becario.png')}}" class="img-responsive">
						</div>
						<div class="col-lg-4"></div>
					@else
						<div class="col-lg-4"></div>
						<div class="col-lg-4">
							<img src="{{asset('images/postulacion-mentor.png')}}" class="img-responsive">
						</div>
						<div class="col-lg-4"></div>
					@endif
				@else
					@if(Auth::user()->esEditor())
						<p class="text-center" style="color:#1b1b1b"> Bienvenido al Panel de Administración Web </p>
						<div class="col-lg-3"></div>
						<div class="col-lg-6">
							<img src="{{asset('info_sitio/logo_nuevo.png')}}" class="img-responsive">
						</div>
						<div class="col-lg-3"></div>
					@else
						<!-- <p class="text-center" style="color:#1b1b1b"> Bienvenido al Sistema de Becarios AVAA </p> -->

						<div class="header-seb">
							<img src="{{asset('images/becarios.png')}}" class="img-responsive">
						</div>


					@endif
				@endif
			</div>
		</div>
	</div>

	@if(Auth::user()->esBecario() or Auth::user()->esDirectivo() or Auth::user()->esCoordinador() )
	<div class="container-fluid" style="border:1px solid #dedede;padding: 10px;border-radius: 10px;">
		<div class="row">
			<div class='col-sm-12'>
				<h3 class="text-center">
					Próximas Actividades
					
				</h3>
			</div>
		</div>
	</div>
	<br>
	<div class="container-fluid">
		<div class="row">
			@if($actividades->count()!=0)
				@foreach($actividades as $actividad)
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="border: 1px solid #eee;padding-top: 10px;padding-bottom: 5px" >
					<div data-mh="actividad">
						<h4>{{$actividad->getDia()}} {{$actividad->getMes()}} {{$actividad->getAnho()}}</h4>
						<h5 style="color:#424242">{{$actividad->getHoraInicio()}} a {{$actividad->getHoraFin()}}</h5>
						<div>
							@if($actividad->modalidad=='virtual')
					            <i class="fa fa-laptop"></i>
					        @else
					            <i class='fa fa-male'></i>
					        @endif
					        {{$actividad->getModalidad()}}
						</div>
						{{ucwords($actividad->tipo)}}: {{$actividad->nombre}}
						@if($actividad->status=='disponible')
						<span class="label label-success">
							Disponible</span>
						@elseif($actividad->status=='suspendido')
						<span class="label label-danger">
							Suspendido</span>
						@elseif($actividad->status=='oculto')
						<span class="label label-warning">
							Oculto</span>
						@elseif($actividad->status=='cerrado')
						<span class="label label-danger">
							Cerrado</span>
						@endif
					</div>
			        <a href="{{route('actividad.detalles',$actividad->id)}}" class="btn btn-xs btn-block sisbeca-btn-primary">
			        	<i class="fa fa-info"> </i> Detalles
			    	</a>
			        @if(Auth::user()->esDirectivo() or Auth::user()->esCoordinador() or Auth::user()->esEntrevistador())
			         <a href="{{route('actividad.editar',$actividad->id)}}" class="btn btn-xs btn-block sisbeca-btn-primary">
			         	<i class="fa fa-pencil"> </i> Editar
			         </a>
			        @endif
				</div>
				@endforeach
			@else
				<div class="col-lg-12" style="border: 1px solid #dedede;border-radius: 10px;padding-top: 10px;">
					<p class="h6 text-center"><strong>No hay actividades próximas</strong></p>
				</div>
			@endif
		</div>
	</div>
	<br>
		@if(Auth::user()->esDirectivo() || Auth::user()->esCoordinador())
			<div class="container-fluid">
				<!-- Cargando.. -->
				<section v-if="isLoading" class="loading" id="preloader">
				<div>
					<svg class="circular" viewBox="25 25 50 50">
						<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
				</div>
				</section>

				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 reporte-contenedor">
						<a href="{{route('cursos.todos')}}">
							<div class="col-lg-12 reporte-caja">
								<div data-mh="reporte-contenido" class="repote-contenido">
									<p class="h1 text-center">{{$cva_pendiente}}</p>
								</div>
								<hr class="reporte-linea">
								<div class="caja-subtitulo" data-mh="reporte-titulo">
									<p class="h6 text-center reporte-subtitulo">
									<strong>CVA pendiente</strong>
									</p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 reporte-contenedor">
						<a href="{{route('voluntariados.todos')}}">
							<div class="col-lg-12 reporte-caja">
								<div data-mh="reporte-contenido" class="repote-contenido">
									<p class="h1 text-center">{{$voluntariados_pendiente}}</p>
								</div>
								<hr class="reporte-linea">
								<div class="caja-subtitulo" data-mh="reporte-titulo">
									<p class="h6 text-center reporte-subtitulo">
									<strong>Voluntariado pendiente</strong>
									</p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 reporte-contenedor">
						<a href="{{route('periodos.todos')}}">
							<div class="col-lg-12 reporte-caja">
								<div data-mh="reporte-contenido" class="repote-contenido">
									<p class="h1 text-center">{{$periodos_pendiente}}</p>
								</div>
								<hr class="reporte-linea">
								<div class="caja-subtitulo" data-mh="reporte-titulo">
									<p class="h6 text-center reporte-subtitulo">
									<strong>Nota Académica pendiente</strong>
									</p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 reporte-contenedor">
						<a href="{{route('actividad.listarjustificativos')}}">
							<div class="col-lg-12 reporte-caja">
								<div data-mh="reporte-contenido" class="repote-contenido">
									<p class="h1 text-center">{{$justificativos_pendiente }}</p>
								</div>
								<hr class="reporte-linea">
								<div class="caja-subtitulo" data-mh="reporte-titulo">
									<p class="h6 text-center reporte-subtitulo">
									<strong>Justificativo pendiente</strong>
									</p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 reporte-contenedor">
						<a href="{{route('gestionSolicitudes.listar')}}">
							<div class="col-lg-12 reporte-caja">
								<div data-mh="reporte-contenido" class="repote-contenido">
									<p class="h1 text-center">{{$solicitudes_pendiente }}</p>
								</div>
								<hr class="reporte-linea">
								<div class="caja-subtitulo" data-mh="reporte-titulo">
									<p class="h6 text-center reporte-subtitulo">
									<strong>Solicitud pendiente</strong>
									</p>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 reporte-contenedor">
						<!-- route('modulo.facturas.pendientes')-->
						<a href="{{route('modulo.facturas.pendientes')}}">
							<div class="col-lg-12 reporte-caja">
								<div data-mh="reporte-contenido" class="repote-contenido">
									<p class="h1 text-center">{{$facturas_pendiente }}</p>
								</div>
								<hr class="reporte-linea">
								<div class="caja-subtitulo" data-mh="reporte-titulo">
									<p class="h6 text-center reporte-subtitulo">
									<strong>Facturas Libro pendiente</strong>
									</p>
								</div>
							</div>
						</a>
					</div>

				</div>
			</div>
		@endif
	@endif
</div>

@endsection
@endif

@if(Auth::user()->esDirectivo() || Auth::user()->esCoordinador())
@section('personaljs')
<script>
    const app = new Vue({

    el: '#app',
    created: function()
    {
        this.getSolProcesar();

    },
    data:
    {
        isLoading: false,
		numSol: 0
    },
    methods:
    {
        getSolProcesar: function()
        {
			this.isLoading = true
            var url = "{{route('get.solicitudes.pendientes')}}";
            axios.get(url).then(response => 
            {
				this.numSol = response.data.res
				this.isLoading = false
			}).catch( error => {
				console.log(error);
				this.isLoading = false
			});
        },
		viewSol(){
			let url = "{{route('solicitudes.pendientes')}}"
			location.replace(url)
		}
    }
});
</script>
@endsection
@else
@section('personaljs')
<script>
    const app = new Vue({
    el: '#app',
    data:
    {
		numSol: 0
    },
    methods:
    {
		viewSol(){
			
		}
    }
});
</script>
@endsection
@endif
@section('personalcss')
<style>
	.cursor {
		cursor: pointer;
	}
    .repote-contenido
    {
    	padding-top:15px;
    	height: 55px;
    }
	.reporte-contenedor
	{
		margin-bottom: 10px;
		padding-right: 5px!important;
		padding-left: 5px!important;
	}
	.reporte-linea
	{
		display: block;
	    height: 1px;
	    border: 0;
	    border-top: 1px solid #dc3545 !important;
	    margin: 1em 0;
	    padding: 0;
	}
	.reporte-caja
	{
		border:1px solid #003865;
		border-radius: 5px;
		background-color: #fff;
		color:#212121 !important;
	}
	.reporte-caja:hover
	{
		background-color: #eee;
	}
	.reporte-subtitulo,.h5
	{
		color:#212121 !important;
	}

	.caja-subtitulo
	{
		padding-bottom: 5px;
	}
</style>
@endsection
