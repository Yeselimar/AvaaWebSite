@extends('sisbeca.layouts.main')
@section('title',$model=='crear' ? 'Crear Ticket ' : 'Editar Ticket: '.$usuario->nombreyapellido())
@section('content')
	<div class="col-lg-12">
        <div class="text-right">
            <a href="{{ route('ticket.index',Auth::user()->id) }}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
        </div>
		<br>
        <div class="alert alert-primary" role="alert">
            Pasos para <strong>crear un ticket</strong> de Soporte o Ayuda:
            <br>
            <strong>1.</strong> Identifique de que tipo es el ticket a generar. Soporte: Si se generó un error o Ayuda: si solicita información detallada de una accion a realizar en el sistema.
            <br>
            <strong>2.</strong> Seleccione la prioridad que considera del error reportado o de la ayuda solicitada.
            <br>
            <strong>3.</strong> Ingrese el asunto  del soporte o ayuda solicitada.
            <br>
            <strong>4.</strong> En la descripción especifique de manera detallada su error.
            <br>
            <strong>5.</strong> Ingrese el link donde se genero el error.
            <br>
            <strong>6.</strong> Si es posible tome foto o pantallazo al error producido.
            <br>
            <strong>7.</strong> Guarde el ticket y espere respuesta de nuestro personal de soporte.
            <br>
            <strong>8.</strong> Los campos con asterisco son obligatorios.
        </div>

		<div class="col sisbeca-container-formulario">

			@if($model=='crear')
				{{ Form::open(['route' => ['ticket.guardar'], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}

			@else
				{{ Form::model($curso,['route' => ['ticket.actualizar',$ticket->id], 'method' => 'post', 'class'=>'form-horizontal', 'novalidate' => 'novalidate', 'files'=> true]) }}
			@endif


			<div class="form-group">
				<div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">Usuario</label>
                        <!-- Si es editar ticket el usuario cambia, se muestra quien genero ticket --><!-- Si es crear ticket el usuarioa a mostrar es el que está logueado -->
                        {{ Form::text('usuario', $usuario->nombreyapellido(), ['class' => 'sisbeca-input sisbeca-disabled', 'disabled'=>'disabled'])}}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Tipo</label>
                        {{ Form::select('tipo', array('soporte'=>'Soporte','ayuda'=>'Ayuda'),($model=='crear') ? 'soporte' : $ticket->tipo ,['class' =>'sisbeca-input']) }}
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Prioridad</label>
                        {{ Form::select('prioridad', array('baja'=>'Baja','media'=>'Media','alta'=>'Alta'),($model=='crear') ? 'baja' : $ticket->prioridad,['class' =>'sisbeca-input']) }}
                        <span class="errors" style="color:#red">{{ $errors->first('prioridad') }}</span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Asunto</label>
                        {{ Form::text('asunto', ($model=='crear') ? null : $ticket->asunto, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: Error al iniciar sesión'])}}
                        <span class="errors" style="color:#red">{{ $errors->first('asunto') }}</span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">URL (Link o enlace)</label>
                        {{ Form::text('url', ($model=='crear') ? null : $ticket->url, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: https://www.avaa.org/sisbeca'])}}
                        <span class="errors" style="color:#red">{{ $errors->first('url') }}</span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="imagen" class="control-label">
                            {{ $model=='crear' ? 'Imagen' : 'Actualizar Imagen' }}
                        </label>
                        {{ Form::file('imagen',['class' => 'sisbeca-input ', 'accept'=>'image/jpeg,image/jpg/image/png' ] ) }}
                        <span class="errors">{{ $errors->first('imagen') }}</span>
                    </div>

                    @if($model=='editar')
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label for="imagen" class="control-label">Imagen Actual</label>
                        <a href="{{url($ticket->imagen)}}" target="_blank" class="btn sisbeca-btn-primary btn-block">
                            <i class="fa fa-photo"></i>
                        </a>
                    </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="control-label">*Descripción</label>
                        {{ Form::textarea('descripcion', ($model=='crear') ? null : $ticket->descripcion , ['class' => 'sisbeca-input sisbeca-textarea', 'placeholder'=>'EJ: El día de hoy presente...'])}}
                        <span class="errors">{{ $errors->first('descripcion') }}</span>
                    </div>
                </div>
				
			</div>

			<hr>

			<div class="form-group">
				<div class="row">
					<div class="col-lg-12 text-right" >
                        <button class="btn sisbeca-btn-primary" type="submit" >Guardar</button>
					</div>
				</div>
			</div>		

			{{ Form::close() }}
		</div>
	</div>
@endsection
