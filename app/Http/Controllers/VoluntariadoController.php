<?php

namespace avaa\Http\Controllers;
use Illuminate\Http\Request;
use avaa\Voluntariado;
use Illuminate\Support\Facades\Auth;
use DateTime;
use avaa\Http\Requests\VoluntariadoRequest;
use Validator;
use Illuminate\Support\Facades\Storage;
use File;
use avaa\Aval;
use avaa\Becario;
use Intervention\Image\ImageManagerStatic as Image;

class VoluntariadoController extends Controller
{
    public function obtenertodos()
    {
        $voluntariados = Voluntariado::with("becario")->with("usuario")->with("aval")->orderby('created_at','desc')->get();
        return response()->json(['voluntariados'=>$voluntariados]);
    }

    public function obtenertodosapi()
    {
        $todos = collect();
        $voluntariados = Voluntariado::with("becario")->with("usuario")->with("aval")->orderby('created_at','desc')->get();
        foreach ($voluntariados as $v)
        {
            $todos->push(array(
                'id' => $v->id,
                'nombre' => $v->nombre,
                'horas' => $v->horas,
                'responsable' => $v->responsable,
                "aval" => array('id' => $v->aval->id,
                   'url' => $v->aval->url,
                   'estatus' => $v->aval->estatus,
                    'extension' => $v->aval->extension),
                "becario" => $v->usuario->name.' '.$v->usuario->last_name
            ));
        }
        return response()->json(['voluntariados'=>$todos]);
    }

    public function todosvoluntariados()
    {
        return view('sisbeca.voluntariados.todos');
    }

    public function index()
    {
    	$voluntariados = Voluntariado::where('becario_id','=',Auth::user()->id)->get();
    	return view('sisbeca.voluntariados.index')->with(compact('voluntariados'));
    }

    public function voluntariadosbecario($id)
    {
        $voluntariados = Voluntariado::where('becario_id','=',$id)->get();
        $becario = Becario::find($id);
        return view('sisbeca.voluntariados.index')->with(compact('voluntariados','becario'));
    }

    public function crear($id)
    {
    	$model =  'crear';
        $becario = Becario::find($id);
        if( (Auth::user()->esBecario() and $id==Auth::user()->id) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
        {
    	   return view('sisbeca.voluntariados.model')->with(compact('model','becario'));
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function guardar(Request $request,$id)
    {
    	$validation = Validator::make($request->all(), VoluntariadoRequest::rulesCreate());
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
		$aval->url = Aval::carpetaComprobante().$nombre;
		$aval->estatus = "pendiente";
		$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg" or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
		$aval->tipo = "comprobante";
		$aval->becario_id = $id;
		$aval->save();

		$voluntariado = new Voluntariado;
		$voluntariado->institucion = $request->get('institucion');
        $voluntariado->responsable = $request->get('responsable');
        $voluntariado->observacion = $request->get('observacion');
		$voluntariado->fecha = DateTime::createFromFormat('d/m/Y', $request->get('fecha'))->format('Y-m-d');
		$voluntariado->tipo = $request->get('tipo');
		$voluntariado->lugar = $request->get('lugar');
		$voluntariado->horas = $request->get('horas');
		$voluntariado->becario_id = $id;
		$voluntariado->aval_id = $aval->id;
		$voluntariado->save();
		flash("El voluntariado fue creado exitosamente.",'success');
        if(Auth::user()->esDirectivo() || Auth::user()->esCoordinador())
        {
            return redirect()->route('voluntariados.todos');
        }
        else
        {
            return redirect()->route('voluntariados.index');
        }
    }

    public function editar($id)
    {
        $model = 'editar';
    	$voluntariado = Voluntariado::find($id);
        $becario = $voluntariado->becario;
        if( (Auth::user()->esBecario() and $voluntariado->becario_id==Auth::user()->id) or Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
        {
            return view('sisbeca.voluntariados.model')->with(compact('voluntariado','model','becario'));
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function actualizar(Request $request,$id)
    {
    	$validation = Validator::make($request->all(), VoluntariadoRequest::rulesUpdate());
        if ( $validation->fails() )
        {
            flash("Por favor, verifique el formulario.",'danger');
            return back()->withErrors($validation)->withInput();
        }
        $voluntariado = Voluntariado::find($id);
        if($request->file('comprobante'))
        {   
            File::delete($voluntariado->aval->url);
            
            $archivo= $request->file('comprobante');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Aval::carpetaComprobante();
            $archivo->move($ruta, $nombre);
            
            $aval = $voluntariado->aval;
            $aval->url = Aval::carpetaComprobante().$nombre;
            $aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg"or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
            $aval->save();
        }
        $voluntariado->institucion = $request->get('institucion');
        $voluntariado->responsable = $request->get('responsable');
        $voluntariado->observacion = $request->get('observacion');
        $voluntariado->fecha = DateTime::createFromFormat('d/m/Y', $request->get('fecha'))->format('Y-m-d');
        $voluntariado->tipo = $request->get('tipo');
        $voluntariado->lugar = $request->get('lugar');
        $voluntariado->horas = $request->get('horas');
        $voluntariado->save();
        flash("El voluntariado fue actualizado exitosamente.",'success');
        if(Auth::user()->esBecario())
        {
            return redirect()->route('voluntariados.index');
        }
        else
        {
            return redirect()->route('voluntariados.todos');
        }
    }

    public function eliminar($id)
    {
        $voluntariado = Voluntariado::find($id);
        if( (Auth::user()->esBecario() and $voluntariado->becario_id==Auth::user()->id) or Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
        {
            $aval = $voluntariado->aval;
            $voluntariado->delete();
            File::delete($aval->url);
            $aval->delete();
            
            flash("El voluntariado fue eliminado exitosamente.",'success');
            if(Auth::user()->esBecario())
            {
                return redirect()->route('voluntariados.index');
            }
            else
            {
                return redirect()->route('voluntariados.todos');
            }
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function eliminarservicio($id)
    {
        $voluntariado = Voluntariado::find($id);
        $aval = $voluntariado->aval;
        $voluntariado->delete();
        File::delete($aval->url);
        $aval->delete();
        return response()->json(['success'=>'El voluntariado fue eliminado exitosamente.']);
    }

    public function detalles($id)
    {
        $voluntariado = Voluntariado::find($id);
        $becario = $voluntariado->becario;
        return view('sisbeca.voluntariados.detalles')->with(compact('voluntariado','becario'));
    }

    public function detallesservicio($id)
    {
        $voluntariado = Voluntariado::where('id','=',$id)->with('aval')->first();
        return response()->json(['voluntariado'=>$voluntariado]);
    }

    public function actualizarvoluntariado(Request $request,$id)
    {
        $voluntariado = Voluntariado::find($id);
        $voluntariado->aval->estatus = $request->estatus;
        $voluntariado->aval->observacion = $request->observacion;
        $voluntariado->aval->save();
        return response()->json(['success'=>'El voluntariado fue actualizado exitosamente.']);
    }
}
