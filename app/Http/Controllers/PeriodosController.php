<?php

namespace avaa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DateTime;
use avaa\Periodo;
use avaa\Aval;
use avaa\Becario;
use avaa\Http\Requests\PeriodosRequest;
use Validator;
use Illuminate\Support\Facades\Storage;
use File;
use Intervention\Image\ImageManagerStatic as Image;


class PeriodosController extends Controller
{
	public function getEstatusAval()
	{
		$estatusaval = ['0'=>'pendiente','1'=>'aceptada','2'=>'negada'];
		return response()->json(['estatusaval'=>$estatusaval]);
	}

	public function obtenertodos()
	{
		$periodos = Periodo::with("becario")->with("usuario")->with("aval")->with("materias")->orderby('created_at','desc')->get();
		return response()->json(['periodos'=>$periodos]);
	}

	public function obtenertodosapi()
	{
		$todos = collect();
        $periodos = Periodo::with("becario")->with("usuario")->with("aval")->with("materias")->orderby('created_at','desc')->get();
       	foreach ($periodos as $p)
        {
        	$todos->push(array(
        		'id' => $p->id,
                'numero_periodo' => $p->getNumeroPeriodo(),
                'anho_lectivo' => $p->anho_lectivo,
                'numero_materias' => $p->getTotalMaterias(),
                'promedio_periodo' => $p->getPromedio(),
                'promedio_periodo' => $p->getPromedio(),
                "aval" => array('id' => $p->aval->id,
                   'url' => $p->aval->url,
                   'estatus' => $p->aval->estatus,
                   'extension' => $p->aval->extension),
                "becario" => $p->usuario->name.' '.$p->usuario->last_name
            ));
        }
        return response()->json(['periodos'=>$todos]);
	}

	public function todosperiodos()
	{
		return view('sisbeca.periodos.todos');
	}

	public function verconstancia($id)
	{
		//return response()->file(Periodo::find(7)->aval->url);
		//return  response(asset(Periodo::find($id)->aval->url), 200)->header('Content-Type', 'image/jpg');
	}

    public function index()
    {
    	$becario = Becario::find(Auth::user()->id);
    	$periodos = Periodo::where('becario_id','=',Auth::user()->id)->get();
    	return view('sisbeca.periodos.index')->with(compact('periodos','becario'));
    }

    public function periodosbecario($id)
	{
		$becario = Becario::find($id);
    	$periodos = Periodo::where('becario_id','=',$id)->get();
    	return view('sisbeca.periodos.index')->with(compact('periodos','becario'));
	}
	
    public function crear($id)
    {
    	$model = 'crear';
    	$becario = Becario::find($id);
    	if( (Auth::user()->esBecario() and $id==Auth::user()->id) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
    	{
	    	return view('sisbeca.periodos.model')->with(compact('model','becario'));
    	}
    	else
    	{
    		return view('sisbeca.error.404');
    	}
    }

    public function guardar(Request $request,$id)
    {
    	$becario = Becario::find($id);
    	$validation = Validator::make($request->all(), PeriodosRequest::rulesCreate());
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
		$aval->url = Aval::carpetaConstancia().$nombre;
		$aval->estatus = "pendiente";
		$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg"or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
		$aval->tipo = "constancia";
		$aval->becario_id = $id;
		$aval->save();

		$periodo = new Periodo;
		$periodo->numero_periodo = $request->get('numero_periodo');
		$periodo->anho_lectivo = $request->get('anho_lectivo');
		$periodo->regimen_periodo = $becario->regimen;
		$periodo->fecha_inicio = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$periodo->fecha_fin = DateTime::createFromFormat('d/m/Y', $request->get('fecha_fin'))->format('Y-m-d');
		$periodo->becario_id = $id;
		$periodo->aval_id = $aval->id;
		$periodo->save();
		
		flash("El periodo fue creado exitosamente.",'success');
		if(Auth::user()->esDirectivo() || Auth::user()->esCoordinador())
    	{
    		return redirect()->route('periodos.todos');
    	}
    	else
    	{
    		return redirect()->route('periodos.index');
    	}
	    	
	}

	public function editar($id)
	{
		$model = 'editar';
		$periodo = Periodo::find($id);
		$becario = $periodo->becario;
		if( (Auth::user()->esBecario() and $periodo->becario_id==Auth::user()->id) or Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
    	{
				return view("sisbeca.periodos.model")->with(compact('model','periodo','becario'));
		}
		else
		{
			return view('sisbeca.error.404');
		}
	}

	public function actualizar(Request $request,$id)
	{
		$validation = Validator::make($request->all(), PeriodosRequest::rulesUpdate());
		if ( $validation->fails() )
		{
			flash("Por favor, verifique el formulario.",'danger');
			return back()->withErrors($validation)->withInput();
		}
                
        $periodo = Periodo::find($id);
        $becario = Becario::find($periodo->user_id);
        if($request->file('constancia'))
        {	
        	File::delete($periodo->aval->url);
        	
     		$archivo= $request->file('constancia');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Aval::carpetaConstancia();
            $archivo->move($ruta, $nombre);
            
        	$aval = $periodo->aval;
            $aval->url = Aval::carpetaConstancia().$nombre;
			$aval->estatus = "pendiente";
			$aval->tipo = "constancia";
			$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg"or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
			$aval->save();
        }
		
		//$periodo->numero_periodo = $request->get('numero_periodo');
		//$periodo->regimen_periodo = $becario->regimen;
		$periodo->anho_lectivo = $request->get('anho_lectivo');
		$periodo->fecha_inicio = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$periodo->fecha_fin = DateTime::createFromFormat('d/m/Y', $request->get('fecha_fin'))->format('Y-m-d');
		$periodo->save();
		
		flash("El periodo fue actualizado exitosamente.",'success');
	    if(Auth::user()->esBecario())
    	{
    		return redirect()->route('periodos.index');
    	}
    	else
    	{
    		return redirect()->route('periodos.todos');
    	}
	}

	public function mostrarmaterias($id)
	{
		$periodo = Periodo::find($id);
		$becario = $periodo->becario;
		if((Auth::user()->esBecario() and $periodo->becario_id==Auth::user()->id) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
		{
			return view('sisbeca.materias.mostrar')->with(compact('periodo','becario'));
		}
		else
		{
			return view('sisbeca.error.404');
		}
	}
	
	public function obtenermaterias($id)
	{
		//Incorporar seguridad aunque no prioritario
		$materias = Periodo::find($id)->materias;
		return response()->json(['materias'=>$materias]);
	}
	
	public function eliminar($id)
	{
		$periodo = Periodo::find($id);
		if( (Auth::user()->esBecario() and $periodo->becario_id==Auth::user()->id) or Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
		{
			$aval = $periodo->aval;
			$periodo->delete();
			File::delete($aval->url);
			$aval->delete();
			
			flash("El periodo fue eliminado exitosamente.",'success');
			if(Auth::user()->esBecario())
	        {
	            return redirect()->route('periodos.index');
	        }
	        else
	        {
	            return redirect()->route('periodos.todos');
	        }
		}
		else
		{
			return view('sisbeca.error.404');
		}
	}

	public function eliminarservicio($id)
	{
		$periodo = Periodo::find($id);
		$aval = $periodo->aval;
		$periodo->delete();
		File::delete($aval->url);
		$aval->delete();
		return response()->json(['success'=>'El periodo fue eliminado exitosamente.']);
	}

	public function detalles($id)
	{
		$periodo = Periodo::find($id);
		$becario = $periodo->becario;
		return view('sisbeca.periodos.detalles')->with(compact('periodo','becario'));
	}

	public function detallesservicio($id)
	{
		$periodo = Periodo::where('id','=',$id)->with('aval')->first();
		return response()->json(['periodo'=>$periodo]);
	}

	public function actualizarperiodo(Request $request,$id)
	{
		$periodo = Periodo::find($id);
		$periodo->aval->estatus = $request->estatus;
		$periodo->aval->observacion = $request->observacion;
		$periodo->aval->save();
		return response()->json(['success'=>'El periodo fue actualizado exitosamente.']);
	}
}
