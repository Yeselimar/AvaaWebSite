@extends('sisbeca.layouts.main')
@section('title','Perfil de '.$postulante->user->nombreyapellido())
@section('content')

<div class="container-fluid">
		<div class="text-right">
				<a href="{{  URL::previous() }}" class=" btn btn-sm sisbeca-btn-primary">Atrás</a>
			</div>
	<div class="card card-body bg-light border border-info p-2">
		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12">
		<p class="text-left"><strong>Perfil de {{$postulante->user->name.' '.$postulante->user->last_name}}</strong></p>
				<div class="row">
					<div class="col xs-6 col-md-8 col-lg-4 offset-md-5  offset-lg-0 p-t-20 text-center">
                            @if($img_perfil->count()>0)
                                <img src="{{asset($img_perfil[0]->url)}}" class="img-rounded img-responsive w-50">
                            @else
                                @if($postulante->user->sexo==='femenino')
                                    <img src="{{asset('images/perfil/femenino.png')}}" class="img-rounded img-responsive w-50">
                                @else
                                    <img src="{{asset('images/perfil/masculino.png')}}" class="img-rounded img-responsive w-50">
                                @endif
                            @endif
							<br>
						<span class="label label-inverse">
						    Postulante a Becario
						</span>
					<br/>
					</div>
					<div class="offset-md-5 offset-lg-0 col-md-12 col-lg-8 pr-4 pl-4 pb-2">
						<strong>Datos Básicos:</strong>
						<br/>
						<h4> {{$postulante->user->name}} {{$postulante->user->last_name }}</h4>
						<p>
							<i class="fa fa-envelope"> &nbsp;</i><strong>Email:</strong> {{$postulante->user->email}}
							<br />
							<i class="fa fa-user"> &nbsp;</i><strong>Cedula: </strong>{{$postulante->user->cedula}}
							<br/>
							<i class="fa fa-venus-mars">&nbsp; </i><strong>Sexo:</strong> {{ucwords($postulante->user->sexo)}}
							<br/>
							<i class="fa fa-birthday-cake">&nbsp; </i><strong>Fecha de nacimiento: </strong>{{ date("d/m/Y", strtotime($postulante->user->fecha_nacimiento)) }}
                            <br/>
                            <i class="fa fa-phone">&nbsp; </i> <strong>Teléfono:</strong> {{$postulante->celular}}
                            <br/>
							<strong>Status:</strong>
                            @if($postulante->status==='postulante')
                            <span class="label label-default">Sin Revisar</span>
                            @else
                                @if($postulante->status==='entrevista')
                                    <span class="label label-warning">Entrevista</span>
                                @elseif($postulante->status==='entrevistado')
                                    <span class="label label-inverse">E.Aprobada</span>
                                @elseif($postulante->status==='no_entrevista')
                                <span class="label label-danger">Rechazado</span>
                                @elseif($postulante->status==='rechazado')
                                <span class="label label-danger">Rechazado</span>
                                @elseif($postulante->status=='activo')
                                    @if($postulante->acepto_terminos=='false')
                                    <span class="label label-success">Aprobado a ProExcelencia</span>
                                    @else
                                    <span class="label label-success">Becario</span>
                                    @endif
                                @elseif($postulante->status==='probatorio1')
                                <span class="label label-warning">Probatorio1</span>
                                @elseif($postulante->status==='probatorio2')
                                <span class="label label-danger">Probatorio2</span>
                                @elseif($postulante->status==='inactivo')
                                <span class="label label-danger">Inactivo</span>
                                @elseif($postulante->status==='desincorporado')
                                <span class="label label-danger">Desincorporado</span>
                                @else
                                    <span class="label label-default">Egresado</span>
                                @endif
                             @endif
						</p>
					</div>

				</div>
                @if(Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
                    @if($postulante->status==='postulante')
                        <hr/>
                        <div align="center">
                        <h3>Rechazar / Aprobar a {{$postulante->user->nombreyapellido()}} para la <b>Entrevista</b>
                            <button type='button' title="Rechazar" class="btn btn-sm sisbeca-btn-default" data-toggle='modal' data-target='#modal-default' > <i class="fa fa-times" data-target="modal-asignar"></i></button>
                            <button type='button' title="Aprobar" class="btn btn-sm sisbeca-btn-success" data-toggle='modal' data-target='#modal' ><i class="fa fa-check" data-target="modal-asignar"></i></button>&nbsp;&nbsp;</h3>
                        </div>
                    @endif
                @endif
		</div>
	</div>

</div>


                    <!-- Column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="justify-content-between nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#datospersonales" role="tab">Datos Personales</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#estudiossecundarios" role="tab">Estudios Secundarios</a> </li>
								<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#estudiosuniversitarios" role="tab">Estudios Universitarios</a> </li>
								<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#informacionadicional" role="tab">Información Adicional</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#documentos" role="tab">Documentos</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#documentosentrevista" role="tab">Documentos Entrevista</a> </li>



                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane" id="datospersonales" role="tabpanel">
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
                                                        <td class="text-left">{{ $postulante->lugar_nacimiento }}</td>
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
                                                        <td class="text-left">{{ $postulante->direccion_permanente }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Dirección Temporal</strong></td>
                                                        <td class="text-left">{{ $postulante->direccion_temporal }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Teléfono Celular</strong></td>
                                                        <td class="text-left">{{ $postulante->celular }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Teléfono Habitación</strong></td>
                                                        <td class="text-left">{{ $postulante->telefono_habitacion }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Teléfono Pariente</strong></td>
                                                        <td class="text-left">{{ $postulante->telefono_pariente }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Promedio Ingreso Familiar</strong></td>
                                                        <td class="text-left">{{ $postulante->ingreso_familiar }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Trabaja</strong></td>
                                                        <td class="text-left">{{ $postulante->getTrabaja() }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Lugar de Trabajo</strong></td>
                                                        <td class="text-left">{{ $postulante->lugar_trabajo }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Cargo que desempeña en el trabajo</strong></td>
                                                        <td class="text-left">{{ $postulante->cargo_trabajo }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Horas mensuales de trabajo</strong></td>
                                                        <td class="text-left">{{ $postulante->horas_trabajo }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Contribuye con el ingreso familiar</strong></td>
                                                        <td class="text-left">{{ $postulante->getContribuyeIngreso() }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Contribuye con el ingreso familiar (en porcentaje) </strong></td>
                                                        <td class="text-left">{{ $postulante->getContribuyePorcentaje() }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Vive con </strong></td>
                                                        <td class="text-left">{{ $postulante->vives_con }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Vive con otros (en específico) </strong></td>
                                                        <td class="text-left">{{ $postulante->vives_otros }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Tipo Vivienda </strong></td>
                                                        <td class="text-left">{{ $postulante->tipo_vivienda }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Composición Familiar</strong></td>
                                                        <td class="text-left">{{ $postulante->composicion_familiar }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Ocupación del Padre</strong></td>
                                                        <td class="text-left">{{ $postulante->ocupacion_padre }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Nombre de la empresa del Padre</strong></td>
                                                        <td class="text-left">{{ $postulante->nombre_empresa_padre }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Experiencia Laboral del Padre</strong></td>
                                                        <td class="text-left">{{ $postulante->getExperienciaPadre() }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Ocupación del Madre</strong></td>
                                                        <td class="text-left">{{ $postulante->ocupacion_madre }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Nombre de la empresa del Madre</strong></td>
                                                        <td class="text-left">{{ $postulante->nombre_empresa_madre }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Experiencia Laboral del Madre</strong></td>
                                                        <td class="text-left">{{ $postulante->getExperienciaMadre() }}</td>
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
                                                        <td class="text-left">{{ $postulante->nombre_institucion }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Dirección de la institución</strong></td>
                                                        <td class="text-left">{{ $postulante->direccion_institucion }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Director de la institución</strong></td>
                                                        <td class="text-left">{{ $postulante->director_institucion }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Bachiller en</strong></td>
                                                        <td class="text-left">{{ $postulante->bachiller_en }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Promedio en Bachillerato</strong></td>
                                                        <td class="text-left">{{ $postulante->promedio_bachillerato }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Actividades Extracurriculares</strong></td>
                                                        <td class="text-left">{{ $postulante->actividades_extracurriculares }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Lugar de la Labor Social</strong></td>
                                                        <td class="text-left">{{ $postulante->lugar_labor_social }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Dirección de la Labor Social</strong></td>
                                                        <td class="text-left">{{ $postulante->direccion_labor_social }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Supervisor de la Labor Social</strong></td>
                                                        <td class="text-left">{{ $postulante->supervisor_labor_social }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Habla otro idioma</strong></td>
                                                        <td class="text-left">{{ $postulante->habla_otro_idioma }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Idioma que habla</strong></td>
                                                        <td class="text-left">{{ $postulante->habla_idioma }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Nivel de conocimiento del idioma</strong></td>
                                                        <td class="text-left">{{ $postulante->nivel_idioma }}</td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
									</div>
                                </div>
                                <div class="tab-pane" id="estudiosuniversitarios" role="tabpanel">
                                        <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                    <td class="text-left"><strong>Fecha Inicio de la Universidad</strong></td>
                                                    <td class="text-left">{{ $postulante->getInicioUniversidad() }}</td>
                                                    </tr>
                                                    <tr>
                                                    <td class="text-left"><strong>Universidad</strong></td>
                                                    <td class="text-left">{{ $postulante->nombre_universidad }}</td>
                                                    </tr>
                                                    <tr>
                                                    <td class="text-left"><strong>Carrera</strong></td>
                                                    <td class="text-left">{{ $postulante->carrera_universidad }}</td>
                                                    </tr>
                                                    <tr>
                                                    <td class="text-left"><strong>Costo de la matrícula académica</strong></td>
                                                    <td class="text-left">{{ $postulante->costo_matricula }}</td>
                                                    </tr>
                                                    <tr>
                                                    <td class="text-left"><strong>Promedio Universidad</strong></td>
                                                    <td class="text-left">{{ $postulante->promedio_universidad }}</td>
                                                    </tr>
                                                    <tr>
                                                    <td class="text-left"><strong>Periódo Académico</strong></td>
                                                    <td class="text-left">{{ $postulante->periodo_academico }}</td>
                                                    </tr>

                                                </tbody>
                                                </table>
									</div>
								<div class="tab-pane" id="informacionadicional" role="tabpanel">
									<div class="table-responsive">
											<table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                        <td class="text-left"><strong>Medio de que como enteró del Programa Proexcelencia</strong></td>
                                                        <td class="text-left">{{ $postulante->medio_proexcelencia }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Otro medio de como se enteró del Programa Proexcelencia</strong></td>
                                                        <td class="text-left">{{ $postulante->otro_medio_proexcelencia }}</td>
                                                        </tr>
                                                        <tr>
                                                        <td class="text-left"><strong>Motivo de la beca</strong></td>
                                                        <td class="text-left">{{ $postulante->motivo_beca }}</td>
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
                                                                    <span class="label label-default"><strong>Sin Fotografia</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Copia Cédula</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($cedula))
                                                                    <a target="_blank" href="{{ asset($cedula->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Imagen</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Cedula</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Constancia CNU</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($constancia_cnu))
                                                                    <a target="_blank" href="{{ asset($constancia_cnu->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Constancia CNU</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Calificaciones de Bachillerato</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($calificaciones_bachillerato))
                                                                    <a target="_blank" href="{{ asset($calificaciones_bachillerato->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Calificaciones de Bachillerato</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Constancia de Aceptación</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($constancia_aceptacion))
                                                                    <a target="_blank" href="{{ asset($constancia_aceptacion->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Constancia de Aceptación</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Constancia de Estudio</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($constancia_estudios))
                                                                    <a target="_blank" href="{{ asset($constancia_estudios->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Constancia de Estudio</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Calificaciones de la Universidad</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($calificaciones_universidad))
                                                                    <a target="_blank" href="{{ asset($calificaciones_universidad->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Calificaciones de la Universidad</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Constanca de Trabajo</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($constancia_trabajo))
                                                                    <a target="_blank" href="{{ asset($constancia_trabajo->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Constanca de Trabajo</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Declaración de Impuestos</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($declaracion_impuestos))
                                                                    <a target="_blank" href="{{ asset($declaracion_impuestos->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Declaración de Impuestos</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Recibo de pago</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($recibo_pago))
                                                                    <a target="_blank" href="{{ asset($recibo_pago->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Recibo de pago</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Referencia del Profesor 1</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($referencia_profesor1))
                                                                    <a target="_blank" href="{{ asset($referencia_profesor1->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Referencia del Profesor 1</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Referencia del Profesor 2</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($referencia_profesor2))
                                                                    <a target="_blank" href="{{ asset($referencia_profesor2->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Referencia del Profesor 2</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left"><strong>Ensayo</strong></td>
                                                            <td class="text-left">
                                                                @if(!is_null($ensayo))
                                                                    <a target="_blank" href="{{ asset($ensayo->url) }}" class="btn btn-xs sisbeca-btn-primary">Ver Documento</a>
                                                                @else
                                                                    <span class="label label-default"><strong>Sin Ensayo</strong></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
											</div>
                                        </div>
                                        @if((Auth::user()->rol=='coordinador')||(Auth::user()->rol=='directivo')||(Auth::user()->rol=='entrevistador')||(Auth::user()->rol=='mentor'))

                                                <div class="tab-pane" id="documentosentrevista" role="tabpanel">
                                                    <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                    <tbody>

                                                                    @foreach($postulante->entrevistadores as $entrevistador)
                                                                    <tr>
                                                                            <td class="text-left"><strong> {{$entrevistador->nombreyapellido()}}</strong></td>
                                                                            <td class="text-left"> <a class="btn btn-xs sisbeca-btn-primary" href="{{asset($entrevistador->documento)}}">Ver Informe</a></td>
                                                                            <!-- <td class="text-left"> {{$entrevistador->documento}}</td> -->
                                                                    </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                            <td class="text-left"><strong> Informe Final</strong></td>
                                                                            <td class="text-left">
                                                                                @if(!is_null($postulante->documento_final_entrevista))
                                                                                <a target="_blank" href="{{asset($postulante->documento_final_entrevista)}}" class="btn btn-xs sisbeca-btn-primary"> Ver Informe</a>
                                                                                @else
                                                                                <span class="label label-default"><strong>No ha cargado Documento</strong></span>
                                                                                @endif
                                                                            </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                    </div>
                                                </div>
                                            @endif
                            </div>

                        </div>
                    </div>


                    <form method="POST" action="{{route('agregarObservacion',$postulante->user_id)}}" accept-charset="UTF-8">
                            {{csrf_field()}}

                    <div class="form-group">
                        <div class="col-lg-12">
                            <label for="exampleFormControlTextarea1">Observaciones: </label>
                            <textarea  class="sisbeca-input" id="observaciones" for="observaciones" name="observaciones" style="height: 100px;" >{{$postulante->observacion}}</textarea>


                            <button type='submit' title="Guardar" class='btn sisbeca-btn-primary pull-right'>Guardar</button>
                        </div>
                    </div>
            </form>

            <!-- Modal para aprobar -->
            <div class="modal fade" id="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"> &times;</span>
                        </button>

                        <h5 class="modal-title pull-left"><strong>Confirmación</strong></h5>

                    </div>

                    <form method="POST" action={{route('aprobarParaEntrevista',$postulante->user_id)}} accept-charset="UTF-8">
                        {{csrf_field()}}
                        {{method_field('PUT')}}
                        <input type="hidden" id='valor' name="valor"  value="1">
                        <div class="modal-body text-center">
                        <div class="panel panel-default">
                            <div class="panel-heading"> {{$postulante->user->name}} {{$postulante->user->last_name}}</div>
                            <div class="row panel-body">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    @if(!is_null($fotografia))
                                    <img class="img-responsive w-50 m-b-20" src="{{asset($fotografia->url)}}">
                                    @else
                                        <span class="label label-default"><strong>Sin Fotografia</strong></span>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                        <div><b>Edad:</b>{{$postulante->user->edad}}</div>
                                        <div><b>Promedio Universitario:</b>{{$postulante->promedio_universidad}}</div>
                                        <div><b>Observación:</b>{{$postulante->observacion}}</div>

                                </div>
                            </div>
                        </div>

                            <br>
                            ¿Está seguro que desea <strong class="letras-verdes">Seleccionar</strong> a {{$postulante->user->name}} {{$postulante->user->last_name}} <strong> para ir a entrevista?</strong>
                        </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Sí</button>

                    </div>

                    </form>
                    </div>
                </div>
            </div>
            <!-- Modal para aprobar -->

            <!-- Modal para rechazar -->
            <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                        <h4 class="modal-title pull-left">Confirmación</h4>
                        <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body text-center">
                        <div class="panel panel-default">
                                <div class="panel-heading"> {{$postulante->user->name}} {{$postulante->user->last_name}}</div>
                                <div class="row panel-body">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            @if(!is_null($fotografia))
                                            <img class="img-responsive w-50 m-b-20" src="{{asset($fotografia->url)}}">
                                            @else
                                                <span class="label label-default"><strong>Sin Fotografia</strong></span>
                                            @endif
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                            <div><b>Edad:</b>{{$postulante->user->edad}}</div>
                                            <div><b>Promedio Universitario:</b>{{$postulante->promedio_universidad}}</div>
                                            <div><b>Observación:</b>{{$postulante->observacion}}</div>

                                    </div>
                                </div>
                            </div>
                    <br>
                    ¿Está seguro que desea <strong class="letras-rojas">Descartar</strong> a {{$postulante->user->name}} {{ $postulante->user->last_name}} <strong>para ir a entrevista?</strong>
                </div>
                <form method="POST" action={{route('aprobarParaEntrevista', $postulante->user_id)}} accept-charset="UTF-8">

                    {{csrf_field()}}
                    {{method_field('PUT')}}

                    <input type="hidden" id='valor' name="valor"  value="0">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right" >Si</button>
                    </div>

                </form>
                </div>

            </div>

            </div>

        @endsection


        @section('personaljs')
            <script type="text/javascript">
            function aprobar(){
                var observaciones = $('#observaciones').val();
                alert("Debe Aceptar los Terminos y Condiciones");
            }
            </script>

        @endsection
