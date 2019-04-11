@extends('sisbeca.layouts.main')
@if(Auth::user()->rol=='becario')
	@section('title','Becario:'.$becario->user->nombreyapellido())
@else
	@if(Auth::user()->rol=='postulante_becario')
		@section('title','Postulante a Becario: '.$becario->user->nombreyapellido())
	@else
			@if($becario->status== 'activo' || $becario->status== 'probatorio1' || $becario->status=='probatorio2' || $becario->status=='inactivo' 	|| $becario->status == 'desincorporado' || $becario->status == 'egresado')
				@section('title','Becario:'.$becario->user->nombreyapellido())
			@else
				@section('title','Postulante a Becario:'.$becario->user->nombreyapellido())
			@endif
	@endif
@endif
@section('content')

<div class="container-fluid">
		<div class="text-right">
				<a href="{{  URL::previous() }}" class=" btn btn-sm sisbeca-btn-primary">Atrás</a>
			</div>
	<div class="card card-body bg-light border border-info p-2">
		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12">

				<div class="row">
					<div class="col xs-6 col-md-8 col-lg-4 offset-md-5  offset-lg-0 p-t-20 text-center">
							@if(!is_null($img_perfil))
								<img src="{{asset($img_perfil->url)}}" class="image-responsive img-circle img-fluid img-rounded img-thumbnail perfil-img w-50" >
									@else
										@if($becario->user->sexo==='femenino')
											<img src="{{asset('images/perfil/femenino.png')}}" class="img-rounded img-responsive w-50">
										@else
											<img src="{{asset('images/perfil/masculino.png')}}" class="img-rounded img-responsive w-50">
										@endif
							@endif
							<br>
							<h4 class="m-t-10"> {{$becario->user->name}} {{$becario->user->last_name }}</h4>
						<!-- <span class="label label-inverse">
						@if($becario->user->rol == 'postulante_becario')
						Postulante Becario
							@else
								@if($becario->user->rol=='postulante_mentor')
									Postulante Mentor
								@else
									{{ucwords($becario->user->rol)}}
								@endif
						@endif
						</span> -->

					</div>
					<div class="offset-md-5 offset-lg-0 col-md-12 col-lg-8 p-4">
						<strong>Datos Básicos:</strong>
						<br/>
						<!-- <h4> {{$becario->user->name}} {{$becario->user->last_name }}</h4> -->
						<p>
							<i class="fa fa-envelope"> &nbsp;</i><strong>Email:</strong> {{$becario->user->email}}
							<br />
							<i class="fa fa-user"> &nbsp;</i><strong>Cedula: </strong>{{$becario->user->cedula}}
							<br/>
							<i class="fa fa-venus-mars">&nbsp; </i><strong>Sexo:</strong> {{ucwords($becario->user->sexo)}}
							<br/>
							<i class="fa fa-birthday-cake">&nbsp; </i><strong>Fecha de nacimiento: </strong>{{ date("d/m/Y", strtotime($becario->user->fecha_nacimiento)) }}
							<br/>
							<strong>Estatus Actual:</strong><br>
							@if($becario->status==='activo')
								<span class="label label-success"><strong>{{strtoupper( $becario->status )}}</strong></span>
							@else
								@if($becario->status==='probatorio1' || $becario->status==='inactivo' )
									<span class="label label-warning"><strong>{{strtoupper( $becario->status )}}</strong></span>
								@elseif($becario->status === 'egresado')
									<span class="label label-inverse"><strong>{{strtoupper( $becario->status )}}</strong></span>
								@elseif($becario->status==='entrevista')
								<span class="label label-inverse"><strong>{{strtoupper('Aprobado para ir a Entrevista' )}}</strong></span>
								@elseif($becario->status==='entrevistado')
								<span class="label label-success"><strong>{{strtoupper('Entrevista Aprobada' )}}</strong></span>
								@elseif($becario->status==='rechazado')
								<span class="label label-danger"><strong>{{strtoupper('No Seleccionado' )}}</strong></span>
								@elseif($becario->status==='postulante')
								<span class="label label-inverse"><strong>{{strtoupper('Postulante' )}}</strong></span>
								@elseif($becario->status==='prepostulante')
								<span class="label label-inverse"><strong>{{strtoupper('Pre-Postulante' )}}</strong></span>
								@else
									<span class="label label-danger"><strong>{{strtoupper( $becario->status )}}</strong></span>
								@endif
							@endif
							@if($becario->status==='inactivo')
								<br/>
								<hr class="w-100"/>
								<span class="label label-warning"><strong>Actualmente este becario se encuentra Inactivo desde el {{date("d/m/Y", strtotime($becario->fecha_inactivo))}} </strong></span><br/>
								<b>Observación del Status:</b> {{$becario->observacion_inactivo}}
							@else
								@if($becario->status=='desincorporado' )
								<br/>
								<hr class="w-100"/>
								<span class="label label-danger"><strong>Actualmente este becario se encuentra Desincorporado desde el {{date("d/m/Y", strtotime($becario->fecha_desincorporado))}} </strong></span><br/>
								<b>Observación del Status:</b> {{$becario->observacion_desincorporado}}
									@elseif($becario->status === 'egresado')
									<span class="label label-success"><strong>Actualmente este becario se encuentra Egresado desde el {{date("d/m/Y", strtotime($becario->fecha_egresado))}} </strong></span>

									@endif
							@endif
							<br/>
							@if($becario->user->rol=="becario")
							<strong>Mentor Asignado:</strong>
								@if($becario->mentor)
									{{$becario->mentor->user->name.' '.$becario->mentor->user->last_name }}|{{$becario->mentor->user->cedula}}|{{$becario->mentor->user->email}}
									@else
									<span class="label label-warning">Sin Mentor Asignado</span><br/>

								@endif
							@endif
						</p>
					</div>

				</div>

		</div>
	</div>

</div>


                    <!-- Column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="justify-content-between nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#datospersonales" role="tab">Datos Personales</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#estudiossecuandarios" role="tab">Estudios Secundarios</a> </li>
								<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#estudiosuniversitarios" role="tab">Estudios Universitarios</a> </li>
								<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#informacionadicional" role="tab">Información Adicional</a> </li>
								<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#documentos" role="tab">Documentos</a> </li>


                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="datospersonales" role="tabpanel">
									<div class="table-responsive">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<td class="text-left"><strong>Nombres</strong></td>
													<td class="text-left">{{ $usuario->name }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Apellidos</strong></td>
													<td class="text-left">{{ $usuario->last_name }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Fecha Nacimiento</strong></td>
													<td class="text-left">{{ $usuario->getFechaNacimiento() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Lugar de Nacimiento</strong></td>
													<td class="text-left">{{ $becario->lugar_nacimiento }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Edad</strong></td>
													<td class="text-left">{{ $usuario->getEdad() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Sexo</strong></td>
													<td class="text-left">{{ $usuario->sexo }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Cédula</strong></td>
													<td class="text-left">{{ $usuario->cedula }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Correo Electrónico</strong></td>
													<td class="text-left">{{ $usuario->email }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Dirección Permanente</strong></td>
													<td class="text-left">{{ $becario->direccion_permanente }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Dirección Temporal</strong></td>
													<td class="text-left">{{ $becario->direccion_temporal }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Teléfono Celular</strong></td>
													<td class="text-left">{{ $becario->celular }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Teléfono Habitación</strong></td>
													<td class="text-left">{{ $becario->telefono_habitacion }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Teléfono Pariente</strong></td>
													<td class="text-left">{{ $becario->telefono_pariente }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Promedio Ingreso Familiar</strong></td>
													<td class="text-left">{{ $becario->ingreso_familiar }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Trabaja</strong></td>
													<td class="text-left">{{ $becario->getTrabaja() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Lugar de Trabajo</strong></td>
													<td class="text-left">{{ $becario->lugar_trabajo }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Cargo que desempeña en el trabajo</strong></td>
													<td class="text-left">{{ $becario->cargo_trabajo }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Horas mensuales de trabajo</strong></td>
													<td class="text-left">{{ $becario->horas_trabajo }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Contribuye con el ingreso familiar</strong></td>
													<td class="text-left">{{ $becario->getContribuyeIngreso() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Contribuye con el ingreso familiar (en porcentaje) </strong></td>
													<td class="text-left">{{ $becario->getContribuyePorcentaje() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Vive con </strong></td>
													<td class="text-left">{{ $becario->vives_con }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Vive con otros (en específico) </strong></td>
													<td class="text-left">{{ $becario->vives_otros }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Tipo Vivienda </strong></td>
													<td class="text-left">{{ $becario->tipo_vivienda }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Composición Familiar</strong></td>
													<td class="text-left">{{ $becario->composicion_familiar }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Ocupación del Padre</strong></td>
													<td class="text-left">{{ $becario->ocupacion_padre }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Nombre de la empresa del Padre</strong></td>
													<td class="text-left">{{ $becario->nombre_empresa_padre }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Experiencia Laboral del Padre</strong></td>
													<td class="text-left">{{ $becario->getExperienciaPadre() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Ocupación del Madre</strong></td>
													<td class="text-left">{{ $becario->ocupacion_madre }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Nombre de la empresa del Madre</strong></td>
													<td class="text-left">{{ $becario->nombre_empresa_madre }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Experiencia Laboral del Madre</strong></td>
													<td class="text-left">{{ $becario->getExperienciaMadre() }}</td>
												</tr>
											</tbody>
										</table>
									</div>
                                </div>
                                <!--second tab-->
                                <div class="tab-pane" id="estudiossecundarios" role="tabpanel">
									<div class="table-responsive">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<td class="text-left"><strong>Nombre de la Institución</strong></td>
													<td class="text-left">{{ $becario->nombre_institucion }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Dirección de la institución</strong></td>
													<td class="text-left">{{ $becario->direccion_institucion }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Director de la institución</strong></td>
													<td class="text-left">{{ $becario->director_institucion }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Bachiller en</strong></td>
													<td class="text-left">{{ $becario->bachiller_en }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Promedio en Bachillerato</strong></td>
													<td class="text-left">{{ $becario->promedio_bachillerato }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Actividades Extracurriculares</strong></td>
													<td class="text-left">{{ $becario->actividades_extracurriculares }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Lugar de la Labor Social</strong></td>
													<td class="text-left">{{ $becario->lugar_labor_social }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Dirección de la Labor Social</strong></td>
													<td class="text-left">{{ $becario->direccion_labor_social }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Supervisor de la Labor Social</strong></td>
													<td class="text-left">{{ $becario->supervisor_labor_social }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Habla otro idioma</strong></td>
													<td class="text-left">{{ $becario->getHablaOtroIdioma() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Idioma que habla</strong></td>
													<td class="text-left">{{ $becario->habla_idioma }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Nivel de conocimiento del idioma</strong></td>
													<td class="text-left">{{ $becario->nivel_idioma }}</td>
												</tr>
											</tbody>
										</table>
									</div>
                                </div>
                                <div class="tab-pane" id="estudiosuniversitarios" role="tabpanel">
									<div class="table-responsive">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<td class="text-left"><strong>Fecha Inicio de la Universidad</strong></td>
													<td class="text-left">{{ $becario->getInicioUniversidad() }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Universidad</strong></td>
													<td class="text-left">{{ $becario->nombre_universidad }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Carrera</strong></td>
													<td class="text-left">{{ $becario->carrera_universidad }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Costo de la matrícula académica</strong></td>
													<td class="text-left">{{ $becario->costo_matricula }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Promedio Universidad</strong></td>
													<td class="text-left">{{ $becario->promedio_universidad }}</td>
												</tr>
												<tr>
													<td class="text-left"><strong>Periódo Académico</strong></td>
													<td class="text-left">{{ $becario->periodo_academico }}</td>
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
														<td class="text-left"><strong>Medio de que como enteró del Programa Proexcelencia</strong></td>
														<td class="text-left">{{ $becario->medio_proexcelencia }}</td>
													</tr>
													<tr>
														<td class="text-left"><strong>Otro medio de como se enteró del Programa Proexcelencia</strong></td>
														<td class="text-left">{{ $becario->otro_medio_proexcelencia }}</td>
													</tr>
													<tr>
														<td class="text-left"><strong>Motivo de la beca</strong></td>
														<td class="text-left">{{ $becario->motivo_beca }}</td>
													</tr>
												</tbody>
											</table>
										</div>

								</div>
								<div class="tab-pane" id="documentos" role="tabpanel">
										<div class="table-responsive">
												<table class="table table-bordered">
													<tbody>
														<tr>
														<td class="text-left"><strong>Foto Tipo Carnet</strong></td>
														<td class="text-left">
															@if(!is_null($fotografia))
																<a target="_blank" href="{{ asset($fotografia->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Imagen</a>
																@else
																<span class="label label-danger"><strong>Sin Fotografia</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Copia Cédula</strong></td>
														<td class="text-left">
															@if(!is_null($cedula))
																<a target="_blank" href="{{ asset($cedula->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Imagen</a>
															@else
																<span class="label label-danger"><strong>Sin Cedula</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Constancia CNU</strong></td>
														<td class="text-left">
															@if(!is_null($constancia_cnu))
																<a target="_blank" href="{{ asset($constancia_cnu->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Constancia CNU</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Calificaciones de Bachillerato</strong></td>
														<td class="text-left">
															@if(!is_null($calificaciones_bachillerato))
																<a target="_blank" href="{{ asset($calificaciones_bachillerato->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Calificaciones de Bachillerato</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Constancia de Aceptación</strong></td>
														<td class="text-left">
															@if(!is_null($constancia_aceptacion))
																<a target="_blank" href="{{ asset($constancia_aceptacion->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Constancia de Aceptación</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Constancia de Estudio</strong></td>
														<td class="text-left">
															@if(!is_null($constancia_estudios))
																<a target="_blank" href="{{ asset($constancia_estudios->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Constancia de Estudio</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Calificaciones de la Universidad</strong></td>
														<td class="text-left">
															@if(!is_null($calificaciones_universidad))
																<a target="_blank" href="{{ asset($calificaciones_universidad->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Calificaciones de la Universidad</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Constanca de Trabajo</strong></td>
														<td class="text-left">
															@if(!is_null($constancia_trabajo))
																<a target="_blank" href="{{ asset($constancia_trabajo->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Constanca de Trabajo</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Declaración de Impuestos</strong></td>
														<td class="text-left">
															@if(!is_null($declaracion_impuestos))
																<a target="_blank" href="{{ asset($declaracion_impuestos->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Declaración de Impuestos</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Recibo de pago</strong></td>
														<td class="text-left">
															@if(!is_null($recibo_pago))
																<a target="_blank" href="{{ asset($recibo_pago->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Recibo de pago</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Referencia del Profesor 1</strong></td>
														<td class="text-left">
															@if(!is_null($referencia_profesor1))
																<a target="_blank" href="{{ asset($referencia_profesor1->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Referencia del Profesor 1</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Referencia del Profesor 2</strong></td>
														<td class="text-left">
															@if(!is_null($referencia_profesor2))
																<a target="_blank" href="{{ asset($referencia_profesor2->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Referencia del Profesor 2</strong></span>
															@endif
														</td>
														</tr>
														<tr>
														<td class="text-left"><strong>Ensayo</strong></td>
														<td class="text-left">
															@if(!is_null($ensayo))
																<a target="_blank" href="{{ asset($ensayo->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
															@else
																<span class="label label-danger"><strong>Sin Ensayo</strong></span>
															@endif
														</td>
														</tr>
													</tbody>
												</table>
											</div>
								</div>
                            </div>
                        </div>
                    </div>

@endsection