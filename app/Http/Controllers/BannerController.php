<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Banner;
use Validator;
use avaa\Http\Requests\BannerRequest;
use File;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::where('tipo','=', 'banner')->get();
        return view('sisbeca.banner.index')->with(compact('banners'));
    }

    public function create()
    {
        $model="crear";
        return view('sisbeca.banner.model')->with(compact('model'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), BannerRequest::rulesCreate());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }
        if($request->file('imagen'))
        {
            $archivo= $request->file('imagen');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Banner::carpeta();
            $archivo->move($ruta, $nombre);
        }
        $banner = new Banner;
        $banner->titulo = $request->get('titulo');
        $banner->url = $request->get('url');
        $banner->imagen = Banner::carpeta().$nombre;
        $banner->save();
        flash("El banner fue creado exitosamente.",'success');
        return redirect()->route('banner.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        $model = "editar";
        return view('sisbeca.banner.model')->with(compact('banner','model'));
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), BannerRequest::rulesUpdate());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }
        $banner = Banner::find($id);
        if($request->file('imagen'))
        {
            File::delete($banner->imagen);

            $archivo= $request->file('imagen');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Banner::carpeta();
            $archivo->move($ruta, $nombre);

            $banner->imagen = Banner::carpeta().$nombre;
        }
        $banner->titulo = $request->get('titulo');
        $banner->url = $request->get('url');
        $banner->save();

        flash("El banner fue actualizado exitosamente.",'success');
        return redirect()->route('banner.index');
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);
        File::delete($banner->imagen);
        $banner->delete();
        flash("El banner fue eliminado exitosamente.",'success');
        return redirect()->route('banner.index');
    }

    //Banner de Aliados
    public function indexaliados()
    {
        $aliados = Banner::where('tipo', '=', 'empresas')->orwhere('tipo', '=', 'instituciones')->orwhere('tipo', '=', 'organizaciones')->get();

        return view('sisbeca.banner.indexAliados')->with(compact('aliados'));
    }
    public function createaliado()
    {
        $model="crear";
        return view('sisbeca.banner.modelAliado')->with(compact('model'));
    }
    public function storealiado(Request $request)
    {
        $validation = Validator::make($request->all(), BannerRequest::rulesCreate());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }
        if($request->file('imagen'))
        {
            $archivo= $request->file('imagen');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Banner::carpetaAliados();
            $archivo->move($ruta, $nombre);
        }
        $banner = new Banner;
        $banner->titulo = $request->get('titulo');
        $banner->url = $request->get('url');
        $banner->tipo = $request->get('tipo');
        $banner->imagen = Banner::carpetaAliados().$nombre;
        $banner->save();
        flash("El Aliado fue creado exitosamente.",'success');
        return redirect()->route('aliados.index');
    }

    public function editaliado($id)
    {
        $banner = Banner::find($id);
        $model = "editar";
        return view('sisbeca.banner.modelAliado')->with(compact('banner','model'));
    }

    public function updatealiado(Request $request, $id)
    {
        $validation = Validator::make($request->all(), BannerRequest::rulesUpdate());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }
        $banner = Banner::find($id);
        if($request->file('imagen'))
        {
            File::delete($banner->imagen);

            $archivo= $request->file('imagen');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Banner::carpetaAliados();
            $archivo->move($ruta, $nombre);

            $banner->imagen = Banner::carpetaAliados().$nombre;
        }
        $banner->titulo = $request->get('titulo');
        $banner->url = $request->get('url');
        $banner->tipo = $request->get('tipo');
        $banner->save();

        flash("El Aliado fue actualizado exitosamente.",'success');
        return redirect()->route('aliados.index');
    }
    public function destroyaliado($id)
    {
        $banner = Banner::find($id);
        File::delete($banner->imagen);
        $banner->delete();
        flash("El Aliado fue eliminado exitosamente.",'success');
        return redirect()->route('aliados.index');
    }
}
