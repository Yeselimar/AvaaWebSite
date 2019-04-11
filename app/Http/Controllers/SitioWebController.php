<?php

namespace avaa\Http\Controllers;

use avaa\Banner;
use avaa\Charla;
use avaa\Costo;
use avaa\Noticia;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;

class SitioWebController extends Controller
{
    public function index()
    {
        $noticias = Noticia::query()->where('tipo', '=', "noticia")->where('al_carrousel','=','1')->orderBy('updated_at','desc')->limit(5)->get();
        $organizaciones=  Banner::where('tipo','=','organizaciones')->get();
        $instituciones=  Banner::where('tipo','=','instituciones')->get();
        $empresas=  Banner::where('tipo', '=', "empresas")->get();
        $banners = Banner::where('tipo', '=', "banner")->get();
        return view('index')->with('route',"home")->with(compact('noticias','cantidad','organizaciones','instituciones','empresas','banners'));
    }

    public function noticias()
    {
        $noticias = Noticia::where('tipo','=','noticia')->orderBy('created_at','desc')->get();
        return view('web_site.noticias')->with('route','noticias')->with(compact('noticias'));
    }

    public function noticiaApi(){
        $noticias = Noticia::where('tipo','=','noticia')->orderBy('created_at','desc')->get();
        $todas = collect();
        foreach ($noticias as $n)
        {
        	$todas->push(array(
        		'id' => $n->id,
                'titulo' => $n->titulo,
                'informacion_contacto' => $n->informacion_contacto,
                'fecha_actualizacion' => $n->fechaActualizacion(),
                'slug' => $n->slug,
                'url_image' => $n->url_imagen,
            ));
        }
        return response()->json(['noticias'=>$todas]);
    }


    public function showNoticia($slug)
    {
        $noticia = Noticia::where('slug','=',$slug)->first();
        if(is_null($noticia))
        {
            abort('404','Archivo no encontrado');
        }
        return  view('web_site.page_notices.page-noticia')->with('route','noticias')->with('noticia',$noticia);
    }


    public function programas()
    {
        $costos = Costo::query()->first();
        if(is_null($costos))
        {
            $costos = new Costo();
        }
        $charla = Charla::first();
        return view('web_site.programas')->with('route','programas')->with(compact('costos','charla'));
    }

    public function donaciones()
    {
        $costos = Costo::query()->first();


        if(is_null($costos))
        {
            $costos = new Costo();
        }
        return view('web_site.membresias')->with('route','donaciones')->with('costos',$costos);
    }


}
