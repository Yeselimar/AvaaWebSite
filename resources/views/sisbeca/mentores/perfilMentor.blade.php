@extends('sisbeca.layouts.main')
@if($mentor)
@section('title','Mentor: '.$mentor->user->nombreyapellido()) 
@else
@section('title','Mentor Asignado') 
@endif
@section('content')

<div class="text-right col-12" align="right" >
    @if((Auth::user()->rol==='directivo' || Auth::user()->rol==='coordinador' || Auth::user()->rol==='mentor') && !is_null($documento))
    <a href="{{asset($documento->url)}}" class=" btn btn-sm sisbeca-btn-primary">Ver Hoja de Vida</a>
    @endif
    <a href="{{  URL::previous() }}" class=" btn btn-sm sisbeca-btn-primary">Atrás</a>
</div>
@if($mentor)
<div class="container-fluid">
	
	<div class="card card-body bg-light border border-info p-2">
		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12">

		<p class="text-left"><strong>Perfil de {{$mentor->user->name.' '.$mentor->user->last_name}}</strong></p>
				<div class="row">
					<div class="col xs-6 col-md-8 col-lg-4 offset-md-5  offset-lg-0 p-t-20 text-center">
                        @if($img_perfil->count()>0)
                        <img src="{{asset($img_perfil[0]->url)}}" class="image-responsive img-circle img-fluid img-rounded img-thumbnail perfil-img w-50" >
									@else
										@if($mentor->user->sexo==='femenino')
											<img src="{{asset('images/perfil/femenino.png')}}" class="img-rounded img-responsive w-50">
										@else
											<img src="{{asset('images/perfil/masculino.png')}}" class="img-rounded img-responsive w-50">
										@endif
                                @endif
							<br>
						<span class="label label-inverse">
						@if($mentor->user->rol == 'postulante_mentor')
						Postulante Mentor
							@else
								@if($mentor->user->rol=='mentor')
									Mentor
								@else
									{{ucwords($mentor->user->rol)}}
								@endif
						@endif
						</span>
					<br/>
					</div>
					<div class="offset-md-5 offset-lg-0 col-md-12 col-lg-8 p-4">
						<strong>Datos Básicos:</strong>
						<br/>
						<h4> {{$mentor->user->name}} {{$mentor->user->last_name }}</h4>
						<p>
							<i class="fa fa-envelope"> &nbsp;</i><strong>Email:</strong> {{$mentor->user->email}}
							<br />
							<i class="fa fa-user"> &nbsp;</i><strong>Cedula: </strong>{{$mentor->user->cedula}}
							<br/>
							<i class="fa fa-venus-mars">&nbsp; </i><strong>Sexo:</strong> {{ucwords($mentor->user->sexo)}}
							<br/>
							<i class="fa fa-birthday-cake">&nbsp; </i><strong>Fecha de nacimiento: </strong>{{ date("d/m/Y", strtotime($mentor->user->fecha_nacimiento)) }}
							<br/>
							<strong>Status Actual:</strong>
							@if($mentor->status==='activo')
								<span class="label label-success"><strong>{{strtoupper( $mentor->status )}}</strong></span>
							@else
								@if($mentor->status==='inactivo')
								<span class="label label-warning"><strong>{{strtoupper( $mentor->status )}}</strong></span>
									@elseif($mentor->status === 'postulante')
										<span class="label label-info"><strong>{{strtoupper( $mentor->status )}}</strong></span>
										@else
											<span class="label label-danger"><strong>{{strtoupper( $mentor->status )}}</strong></span>
									@endif
							@endif
							@if($mentor->status==='inactivo')
								<br/>
								<hr class="w-100"/>
								<span class="label label-warning"><strong>Actualmente este mentor se encuentra Inactivo desde el {{date("d/m/Y", strtotime($mentor->fecha_inactivo))}} </strong></span><br/>
								<b>Observación del Status:</b> {{$mentor->observacion_inactivo}}
							@else
								@if($mentor->status=='desincorporado' )
								<br/>
								<hr class="w-100"/>
								<span class="label label-danger"><strong>Actualmente este mentor se encuentra Desincorporado desde el {{date("d/m/Y", strtotime($mentor->fecha_desincorporado))}} </strong></span><br/>
								<b>Observación del Status:</b> {{$mentor->observacion_desincorporado}}
                            @endif
                            @endif
							<br/>
						</p>
					</div>

				</div>
		
		</div>
	</div>
</div>
@else
	<div class="container">
		<div class="alert alert-danger">
		 	Disculpe, ud. no tiene mentor asignado.
		</div>
	</div>
@endif

@if($mentor)
<!-- Mentor -->
<div class="col-lg-12">
    <div class="card">
        <!-- Nav tabs -->
        <ul class=" nav nav-tabs profile-tab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#datospersonales" role="tab">Datos Personales</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#informacionadicional" role="tab">Información Adicional</a> </li>
            @if($mentor->numeroBecarios() > 0 && ((Auth::user()->rol==='directivo' || Auth::user()->rol==='coordinador' || Auth::user()->rol==='mentor')))
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#becarios" role="tab">Becarios Asignados</a> </li>
            @endif


        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="datospersonales" role="tabpanel">
				<div class="table-responsive">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td class="text-left"><strong>Nombres</strong></td>
								<td class="text-left">{{ $mentor->user->name }}</td>
							</tr>
							<tr>
								<td class="text-left"><strong>Apellidos</strong></td>
								<td class="text-left">{{ $mentor->user->last_name }}</td>
							</tr>
							<tr>
								<td class="text-left"><strong>Fecha Nacimiento</strong></td>
								<td class="text-left">{{ $mentor->user->getFechaNacimiento() }}</td>
							</tr>
							<tr>
								<td class="text-left"><strong>Lugar de Nacimiento</strong></td>
								<td class="text-left">{{ $mentor->user->lugar_nacimiento }}</td>
							</tr>
							<tr>
								<td class="text-left"><strong>Edad</strong></td>
								<td class="text-left">{{ $mentor->user->getEdad() }}</td>
							</tr>
							<tr>
								<td class="text-left"><strong>Sexo</strong></td>
								<td class="text-left">{{ $mentor->user->sexo }}</td>
							</tr>
							<tr>
								<td class="text-left"><strong>Cédula</strong></td>
								<td class="text-left">{{ $mentor->user->cedula }}</td>
							</tr>
							<tr>
								<td class="text-left"><strong>Correo Electrónico</strong></td>
								<td class="text-left">{{ $mentor->user->email }}</td>
							</tr>
							
						</tbody>
					</table>
				</div>
            </div>

			<div class="tab-pane" id="informacionadicional" role="tabpanel">
				<div class="table-responsive">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<td class="text-left"><strong>Profesión</strong></td>
									<td class="text-left">{{ $mentor->profesion }}</td>
								</tr>
								<tr>
									<td class="text-left"><strong>Empresa</strong></td>
									<td class="text-left">{{ $mentor->empresa}}</td>
								</tr>
								<tr>
									<td class="text-left"><strong>Cargo Actual</strong></td>
									<td class="text-left">{{ $mentor->cargo_actual}}</td>
                                </tr>
                                <tr>
									<td class="text-left"><strong>Area de Interes</strong></td>
									<td class="text-left">{{ $mentor->area_de_interes}}</td>
                                </tr>
                                <tr>
									<td class="text-left"><strong>Fecha Ingreso Empresa</strong></td>
									<td class="text-left">{{ date("d/m/Y", strtotime($mentor->fecha_ingreso_empresa)) }}</td>
								</tr>
							</tbody>
						</table>
					</div>

            </div>
            
            @if($mentor->numeroBecarios() > 0 && ((Auth::user()->rol==='directivo' || Auth::user()->rol==='coordinador' || Auth::user()->rol==='mentor')))
                <div class="tab-pane" id="becarios" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover ">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nombre y Apellido</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Cedula</th>
                                        <th class="text-center">Edad</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mentor->becarios as $becario)

                                    <tr>
                                        <td scope="row" class="text-center">{{$becario->user->name.' '.$becario->user->last_name}}</td>
                                        <td class="text-center">{{$becario->user->email}}</td>
                                        <td class="text-center">{{$becario->user->cedula}}</td>
                                        <td class="text-center">{{$becario->user->edad}}</td>
                                        <td class="text-center">{{$becario->status}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Mentor -->
@endif

@endsection