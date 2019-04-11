@extends('sisbeca.layouts.main')
@section('title','Publicaciones')
@section('content')

    <div class="row">
        <div class="col-12">
            <div id="editorNotiID">
                <editor-noti  editnotice="{{route('edit.publicacion.api',':id_edit')}}"  deletenotice="{{route('delete.publicacion.api',':id_delete')}}" vernotice="{{route('showNoticia',':id_slug')}}"  createnotice="{{route('create.publicacion.api')}}" getnotices="{{route('get.publicaciones')}}" getimage="{{asset(':id_img')}}" routeinsertimg="{{route('insert.img.noticia')}}" storagedelete="{{route('delete.storage.api')}}" ></editor-noti>
            </div>
        </div>
    </div>
 
@endsection

@section('personaljs')


<script src="{{asset('js/editorNoti.js')}}"></script>


@endsection
