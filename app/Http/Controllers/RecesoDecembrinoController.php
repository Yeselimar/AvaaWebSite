<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use avaa\RecesoDecembrino;
use DateTime;

class RecesoDecembrinoController extends Controller
{
    public function index()
    {
    	return view('sisbeca.recesodecembrino.index');
    }

    public function obtener()
    {
    	$receso = RecesoDecembrino::first();
    	return response()->json(['receso'=>$receso]);
    }

    public function guardar(Request $request)
    {
    	$receso = RecesoDecembrino::first();
    	$receso->fecha_inicio = DateTime::createFromFormat('d/m/Y', $request->fecha_inicio )->format('Y-m-d');
    	$receso->fecha_fin = DateTime::createFromFormat('d/m/Y', $request->fecha_fin )->format('Y-m-d');
    	$receso->save();
    	return response()->json(['success'=>'El receso fue actualizado exitosamente.']);
    }

    public function cambiar()
    {
    	$receso = RecesoDecembrino::first();
    	if($receso->activo==0)
    	{
    		$receso->activo = 1;
    	}
    	else
    	{
    		$receso->activo = 0;
    	}
    	$receso->save();
    	return response()->json(['success'=>'El receso fue actualizado exitosamente.']);
    }
}
