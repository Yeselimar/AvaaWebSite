@extends('sisbeca.layouts.main')
@if(Auth::user()->rol==='directivo')
  @section('title','Postulante Mentor: '.$postulanteMentor->user->name.' '.$postulanteMentor->user->last_name)
@else
  @section('title','Perfil Mentor '.$postulanteMentor->name.' '.$postulanteMentor->last_name)
@endif

@section('content')

@if((Auth::user()->rol==='directivo' || Auth::user()->rol==='coordinador'))
<div class="text-right col-12" align="right" >
        @if(!is_null($documento))
        <a target="_blank" href="{{asset($documento->url)}}" class='btn btn-sm sisbeca-btn-primary'>Ver Hoja de Vida</a>
        @else
        <span class="label label-default"><strong>Sin Documento</strong></span>
        @endif
    <a href="{{  URL::previous() }}" class=" btn btn-sm sisbeca-btn-primary">Atrás</a>
</div>
@endif
<div class="container-fluid">
	<div class="card card-body bg-light border border-info p-2">
		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12" >
		    <p class="text-left"><strong>Perfil de {{$postulanteMentor->user->name.' '.$postulanteMentor->user->last_name}}</strong></p>
			<div class="row">
				<div class="col xs-6 col-md-8 col-lg-4 offset-md-5 offset-lg-0 p-t-20 text-center" >
                        @if($img_perfil_postulante->count()>0)
                        <img src="{{asset($img_perfil_postulante[0]->url)}}" class="img-rounded img-responsive w-50">
                        @else
                            @if($postulanteMentor->sexo==='femenino')
                            <img src="{{asset('images/perfil/femenino.png')}}" class="img-rounded img-responsive w-50">
                            @else
                            <img src="{{asset('images/perfil/masculino.png')}}" class="img-rounded img-responsive w-50">
    
                            @endif
                         @endif
						<br>
					<span class="label label-inverse">
					Postulante Mentor

					</span>
				<br/>
				</div>
				<div class="offset-md-5 offset-lg-0 col-md-12 col-lg-8 p-4">
					<strong>Datos Básicos:</strong>
					<br/>
					<h4> {{$postulanteMentor->user->name}} {{$postulanteMentor->user->last_name }}</h4>
					<p>
						<i class="fa fa-envelope"> &nbsp;</i><strong>Email:</strong> {{$postulanteMentor->user->email}}
						<br />
						<i class="fa fa-user"> &nbsp;</i><strong>Cedula: </strong>{{$postulanteMentor->user->cedula}}
						<br/>
						<i class="fa fa-venus-mars">&nbsp; </i><strong>Sexo:</strong> {{ucwords($postulanteMentor->user->sexo)}}
						<br/>
						<i class="fa fa-birthday-cake">&nbsp; </i><strong>Fecha de nacimiento: </strong>{{ date("d/m/Y", strtotime($postulanteMentor->user->fecha_nacimiento)) }}
						<br/>
					</p>
				</div>

			</div>

        </div>
    @if(($postulanteMentor->status==='postulante')&&($postulanteMentor->user->rol==='postulante_mentor'))
    <hr/>
    <div align="center">
    <h3>¿Que acción desea tomar para la postulación de <b>{{$postulanteMentor->user->name.' '.$postulanteMentor->user->last_name}}</b>
        <button type='button' title="Rechazar" class="btn btn-sm sisbeca-btn-default" data-toggle='modal' data-target='#modal-default' > <i class="fa fa-times" data-target="modal-asignar"></i></button>
        <button type='button' title="Aprobar" class="btn btn-sm sisbeca-btn-success" data-toggle='modal' data-target='#modal' ><i class="fa fa-check" data-target="modal-asignar"></i></button>&nbsp;&nbsp;</h3>
    </div>
    @endif
	</div>

</div>



<!-- Column -->
<div class="col-lg-12">
    <div class="card">
        <!-- Nav tabs -->
        <ul class=" nav nav-tabs profile-tab" role="tablist">
            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#datospersonales" role="tab">Datos Personales</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#informacionadicional" role="tab">Información Adicional</a> </li>

        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="datospersonales" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="text-left"><strong>Nombres</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Apellidos</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->last_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Fecha Nacimiento</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->getFechaNacimiento() }}</td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Lugar de Nacimiento</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->lugar_nacimiento }}</td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Edad</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->getEdad() }}</td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Sexo</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->sexo }}</td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Cédula</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->cedula }}</td>
                            </tr>
                            <tr>
                                <td class="text-left"><strong>Correo Electrónico</strong></td>
                                <td class="text-left">{{ $postulanteMentor->user->email }}</td>
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
                                    <td class="text-left">{{ $postulanteMentor->profesion }}</td>
                                </tr>
                                <tr>
                                    <td class="text-left"><strong>Empresa</strong></td>
                                    <td class="text-left">{{ $postulanteMentor->empresa}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left"><strong>Cargo Actual</strong></td>
                                    <td class="text-left">{{ $postulanteMentor->cargo_actual}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left"><strong>Area de Interes</strong></td>
                                    <td class="text-left">{{ $postulanteMentor->areas_de_interes}}</td>
                                </tr>
                                <tr>
                                    <td class="text-left"><strong>Fecha Ingreso Empresa</strong></td>
                                    <td class="text-left">{{ date("d/m/Y", strtotime($postulanteMentor->fecha_ingreso_empresa)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

            </div>

        </div>
    </div>
</div>
@if(Auth::user()->rol==='directivo')

<!-- Modal para aprobar  -->
<div class="modal fade" id="modal">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title pull-left">Confirmación</h4>
            <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
        </div>
        <div class="modal-body">
        <br>
        ¿Está seguro que desea <strong class="letras-verdes">Aprobar</strong> <strong> a {{$postulanteMentor->user->name}} {{$postulanteMentor->user->last_name}}</strong> como Mentor de ProExcelencia?
        </div>
        <form method="POST" action={{route('postulanteMentor.update',$postulanteMentor->user->id)}} accept-charset="UTF-8">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <input type="hidden" id='valor' name="valor" value="1">
        <div class="modal-footer">
            <button type="button" class="btn sisbeca-btn-default pull-left" data-dismiss="modal">No</button>
            <button type="submit" class="btn sisbeca-btn-primary pull-left">Sí</button>
        </div>

        </form>

    </div>

    </div>

</div>
<!-- Modal para aprobar  -->


<!-- Modal para rechazar -->
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title pull-left">Confirmación</h4>
            <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
        </div>

        <div class="modal-body">
            <br>
            ¿Está seguro que desea <strong class="letras-rojas">Rechazar</strong> a <strong>{{$postulanteMentor->user->name}} {{$postulanteMentor->user->last_name}}</strong> como mentor?
        </div>

        <form method="POST" action={{route('postulanteMentor.update',$postulanteMentor->user->id)}} accept-charset="UTF-8">

            {{csrf_field()}}
            {{method_field('PUT')}}
            <input type="hidden" id='valor' name="valor"  value="0">

            <div class="modal-footer">
                <button type="button" class="btn sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
                <button type="submit" class="btn sisbeca-btn-primary pull-right" >Sí</button>
            </div>

        </form>


    </div>
    </div>
</div>
<!-- Modal para rechazar -->

@endif


@endsection

@if(Auth::user()->rol==='directivo')
    @section('personaljs')
        <script type="text/javascript">

            function Regresar() {

                var route= "{{route('listarPostulantesMentores')}}";


                location.assign(route);

            }
        </script>
    @endsection
@endif
                  