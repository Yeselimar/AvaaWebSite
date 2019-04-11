<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use avaa\Concurso;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Timestamp;


class MantenimientoConcursoController extends Controller
{
    public function __construct()
    {
        $this->middleware('editor');
    }
    
    public function index()
    {
        $concursoTemp= Concurso::query()->where('status','=','abierto')->orWhere('status','=','cerrado')->first();
        if(!is_null($concursoTemp))
        {
            $fecha_actual = strtotime(date("Y-m-d",time()));
            $fecha_entrada = strtotime($concursoTemp->fecha_final);
            $fecha_entrada2 = strtotime($concursoTemp->fecha_inicio);
            if($fecha_actual<$fecha_entrada2 || $fecha_actual>$fecha_entrada)
            {
                $concursoTemp->status= 'cerrado';
            }
            else
            {
                $concursoTemp->status='abierto';
            }
            $concursoTemp->save();
        }
        $concursos = Concurso::query()->get();
        return view('sisbeca.concursos.crudAperturarConcursos')->with('concursos',$concursos);
    }

    
    public function create()
    {
        return view('sisbeca.concursos.concursoCreate');
    }

   
    public function store(Request $request)
    {
        $concurso= new Concurso();
        $concurso->fecha_inicio = DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_inicio').' 00:00:00');
        $concurso->fecha_final = DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_final').' 00:00:00');
        $fecha_actual = strtotime(date("Y-m-d",time()));
        $fecha_entrada = strtotime($concurso->fecha_final->format('Y-m-d'));
        $fecha_entrada2 = strtotime($concurso->fecha_inicio->format('Y-m-d'));
        if($fecha_actual<$fecha_entrada2 || $fecha_actual>$fecha_entrada)
        {
            $concurso->status= 'cerrado';
            $concurso->tipo=$request->tipo;
        }
        else
        {
            $concurso->status='abierto';
            $concurso->tipo=$request->tipo;
        }
        $concurso->save();
        flash('Se ha creado un nuevo concurso exitosamente!','success')->important();
        return redirect()->route('mantenimientoConcurso.index');

    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $concurso= Concurso::find($id);
        if(is_null($concurso) || $concurso->status==='finalizado')
        {
            flash('El archivo solicitado no existe.','danger')->important();
            return back();
        }

        return view('sisbeca.concursos.concursoEdit')->with('concurso',$concurso);
    }

    public function update(Request $request, $id)
    {
        $concurso=  Concurso::find($id);
        $concurso->fecha_inicio = DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_inicio').' 00:00:00');
        $concurso->fecha_final = DateTime::createFromFormat('d/m/Y H:i:s', $request->get('fecha_final').' 00:00:00');
        $fecha_actual = strtotime(date("Y-m-d",time()));
        $fecha_entrada = strtotime($concurso->fecha_final->format('Y-m-d'));
        $fecha_entrada2 = strtotime($concurso->fecha_inicio->format('Y-m-d'));

        if($fecha_actual<$fecha_entrada2 || $fecha_actual>$fecha_entrada)
        {
            $concurso->status= 'cerrado';
        }
        else
        {
            $concurso->status='abierto';
        }
        $concurso->save();
        flash('Se ha Actualizado el concurso exitosamente!','success')->important();
        return redirect()->route('mantenimientoConcurso.index');
    }
        
    public function destroy($id)
    {
        //
    }
}
