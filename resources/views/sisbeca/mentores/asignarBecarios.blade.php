@extends('sisbeca.layouts.main')
@section('title','Relacion Becario-Mentor')
@section('subtitle','Sin Mentorias')
@section('content')

    <div class="row">
        <div class="col-12">
            <div id="relacionBecarioMentorID">
                <relacion-becario-mentor relacionbm="{{route('getRelacionBecarioMentor')}}" asignarrbm="{{route('asignarMentorBecario')}}" getb="{{route('getBecarios')}}" getm="{{route('getMentores')}}" ></relacion-becario-mentor>
            </div>
        </div>
    </div>
 
@endsection

@section('personaljs')


<script src="{{asset('js/relacionBecarioMentor.js')}}"></script>


@endsection
