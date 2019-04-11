<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Charla;
use Validator;
use avaa\Http\Requests\CharlaRequest;
use File;

class CharlaController extends Controller
{
   
    public function index()
    {
        $charlas = Charla::all();
        return view("sisbeca.charlas.index")->with(compact('charlas'));
    }

    public function create()
    {
        $model = "crear";
        return view('sisbeca.charlas.model')->with(compact('model'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), CharlaRequest::rulesCreate());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }
        if($request->file('imagen'))
        {
            $archivo= $request->file('imagen');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Charla::carpeta();
            $archivo->move($ruta, $nombre);
        }
        $charla = new Charla;
        $charla->anho = $request->get('anho');
        $charla->imagen = Charla::carpeta().$nombre;
        $charla->save();
        flash("La charla fue creada exitosamente.",'success');
        return redirect()->route('charla.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $charla = Charla::find($id);
        $model = "editar";
        return view('sisbeca.charlas.model')->with(compact('charla','model'));
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), CharlaRequest::rulesUpdate());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }
        $charla = Charla::find($id);
        if($request->file('imagen'))
        {
            File::delete($charla->imagen);

            $archivo= $request->file('imagen');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Charla::carpeta();
            $archivo->move($ruta, $nombre);

            $charla->imagen = Charla::carpeta().$nombre;
        }
        
        $charla->anho = $request->get('anho');
        $charla->save();

        flash("La charla fue actualizada exitosamente.",'success');
        return redirect()->route('charla.index');
    }

    public function destroy($id)
    {
        $charla = Charla::find($id);
        File::delete($charla->imagen);
        $charla->delete();
        flash("La charla fue eliminada exitosamente.",'success');
        return redirect()->route('charla.index');
    }
}
