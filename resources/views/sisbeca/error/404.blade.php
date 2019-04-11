@extends('sisbeca.layouts.main')
@section('title','Error 404')
@section('content')
<div class="col-lg-12" id="app">
	<div class="col-lg-12">
		<p class="h1 text-center"><strong>:( Error 404</strong></p>
		<hr>
		<p class="h5 text-center" style="color:#424242">Disculpe, la página solicitada ya expiró o no puede ser encontrada. Por favor, verifique la URL e intente nuevamente.</p>
		<hr>
		<div class="text-center">
			<a class="btn btn-md sisbeca-btn-primary" href="{{ route('seb') }}">Ir al inicio</a>
		</div>
	</div>
</div>


@endsection