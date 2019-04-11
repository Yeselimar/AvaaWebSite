<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use Validator;
use avaa\Http\Requests\CursoRequest;
use avaa\Http\Requests\VoluntariadoRequest;
use avaa\Http\Requests\PeriodosRequest;
use avaa\Aval;
use avaa\Becario;
use avaa\TipoCurso;
use avaa\Curso;
use avaa\Periodo;
use avaa\Voluntariado;
use DateTime;
use File;

class ActividadesBecariasController extends Controller
{
	public function becarioslistar()
	{
		$becarios = Becario::activos()->inactivos()->terminosaceptados()->probatorio1()->probatorio2()->get();
		return view('sisbeca.actividadesbecarias.listar')->with(compact('becarios'));
	}

    public function crearvoluntariado($id)
    {
    	$model =  'crear';
        $becario = Becario::find($id);
    	return view('sisbeca.actividadesbecarias.cargarvoluntariado')->with(compact('model','becario'));
    }

    public function guardarvoluntariado(Request $request, $id)
    {
    	$validation = Validator::make($request->all(), VoluntariadoRequest::rulesCarga());
		if ( $validation->fails() )
		{
			flash("Por favor, verifique el formulario.",'danger');
			return back()->withErrors($validation)->withInput();
		}
        if($request->file('comprobante'))
        {
    		$archivo= $request->file('comprobante');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Aval::carpetaComprobante();
            $archivo->move($ruta, $nombre);
        }
		$aval = new Aval;
		if($request->file('comprobante'))
        {
        	$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg" or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
        	$aval->url = Aval::carpetaComprobante().$nombre;
			
        }
        else
        {
        	$aval->extension = 'imagen';
        	$aval->url = 'null';
        }
		$aval->estatus = "aceptada";
		$aval->tipo = "comprobante";
		$aval->becario_id = $id;
		$aval->created_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha'))->format('Y-m-d');
		$aval->updated_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha'))->format('Y-m-d');
		$aval->save();

		$voluntariado = new Voluntariado;
		$voluntariado->nombre = $request->get('nombre');
		$voluntariado->institucion = $request->get('institucion');
        $voluntariado->responsable = $request->get('responsable');
        $voluntariado->observacion = $request->get('observacion');
		$voluntariado->fecha = DateTime::createFromFormat('d/m/Y', $request->get('fecha'))->format('Y-m-d');
		$voluntariado->tipo = $request->get('tipo');
		$voluntariado->lugar = $request->get('lugar');
		$voluntariado->horas = $request->get('horas');
		$voluntariado->becario_id = $id;
		$voluntariado->aval_id = $aval->id;
		$voluntariado->created_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha'))->format('Y-m-d');
		$voluntariado->updated_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha'))->format('Y-m-d');
		$voluntariado->save();
		return "bien";
    }

    public function crearperiodo($id)
    {
    	$model = 'crear';
    	$becario = Becario::find($id);
	    return view('sisbeca.actividadesbecarias.cargarperiodo')->with(compact('model','becario'));
    }

    public function guardarperiodo(Request $request, $id)
    {
    	$becario = Becario::find($id);
    	$validation = Validator::make($request->all(), PeriodosRequest::rulesCarga());
		if ( $validation->fails() )
		{
			flash("Por favor, verifique el formulario.",'danger');
			return back()->withErrors($validation)->withInput();
		}
        
        if($request->file('constancia'))
        {
    		$archivo= $request->file('constancia');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Aval::carpetaConstancia();
            $archivo->move($ruta, $nombre);
        }
        
		$aval = new Aval;
		if($request->file('constancia'))
        {
			$aval->url = Aval::carpetaConstancia().$nombre;
			$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg"or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
		}
		else
		{
			$aval->url = 'null';
			$aval->extension = 'imagen';
		}
		$aval->estatus = "aceptada";
		$aval->tipo = "constancia";
		$aval->becario_id = $id;
		$aval->created_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$aval->updated_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$aval->save();

		$periodo = new Periodo;
		$periodo->numero_periodo = $request->get('numero_periodo');
		$periodo->anho_lectivo = $request->get('anho_lectivo');
		$periodo->regimen_periodo = $becario->regimen;
		$periodo->fecha_inicio = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$periodo->fecha_fin = DateTime::createFromFormat('d/m/Y', $request->get('fecha_fin'))->format('Y-m-d');
		$periodo->becario_id = $id;
		$periodo->aval_id = $aval->id;
		$periodo->created_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$periodo->updated_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$periodo->save();
		return "todo bien";
    }

    public function crearcurso($id)
    {
    	$model = 'crear';
        $tipocurso = TipoCurso::pluck('nombre', 'id');
        $becario = Becario::find($id);
        return view('sisbeca.actividadesbecarias.cargarcurso')->with(compact('model','becario','tipocurso'));
    }

    public function guardarcurso(Request $request, $id)
    {
    	$validation = Validator::make($request->all(), CursoRequest::rulesCarga());
		if ( $validation->fails() )
		{
			flash("Por favor, verifique el formulario.",'danger');

			return back()->withErrors($validation)->withInput();
		}
        
        if($request->file('constancia_nota'))
        {
    		$archivo= $request->file('constancia_nota');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Aval::carpetaNota();
            $archivo->move($ruta, $nombre);
        }
        
		$aval = new Aval;
		if($request->file('constancia_nota'))
        {
			$aval->url = Aval::carpetaNota().$nombre;
			$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg" or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
		}
		else
		{
			$aval->url = 'null';
			$aval->extension = 'imagen';
		}
		$aval->estatus = "aceptada";
		$aval->tipo = "nota";
		$aval->becario_id = $id;
		$aval->created_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$aval->updated_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$aval->save();

		$curso = new Curso;
		$curso->modo = $request->get('modo');
        $curso->nivel = $request->get('nivel');
        $curso->modulo = $request->get('modulo');
        $curso->nota = $request->get('nota');
		$curso->tipocurso_id = $request->get('tipocurso_id');
		$curso->fecha_inicio = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$curso->fecha_fin = DateTime::createFromFormat('d/m/Y', $request->get('fecha_fin'))->format('Y-m-d');
		$curso->becario_id = $id;
		$curso->aval_id = $aval->id;
		$curso->created_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$curso->updated_at = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$curso->save();
		return "Bien";
    }
}
