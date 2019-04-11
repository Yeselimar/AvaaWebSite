<?php

namespace avaa\Http\Controllers;
use avaa\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use avaa\Http\Requests\CursoRequest;
use avaa\Aval;
use avaa\Becario;
use avaa\TipoCurso;
use DateTime;
use File;

class CursoController extends Controller
{
    public function obtenertodos()
    {
        $cursos = Curso::with("becario")->with("usuario")->with("aval")->orderby('created_at','desc')->get();
        return response()->json(['cursos'=>$cursos]);
    }
    
    public function obtenertodosapi()
    {
        $todos = collect();
        $cursos = Curso::with("becario")->with("usuario")->with("aval")->orderby('created_at','desc')->get();
        foreach ($cursos as $c)
        {
            $todos->push(array(
                'id' => $c->id,
                'modulo' => $c->modulo,
                'modo' => $c->modo,
                'nivel' => $c->nivel,
                'fecha_inicio' => $c->fecha_inicio,
                'nota' => $c->nota,
                "aval" => array('id' => $c->aval->id,
                   'url' => $c->aval->url,
                   'estatus' => $c->aval->estatus,
                   'extension' => $c->aval->extension),
                "becario" => $c->usuario->name.' '.$c->usuario->last_name
            ));
        }
        return response()->json(['cursos'=>$todos]);
    }

    public function todoscursos()
    {
        return view('sisbeca.cursos.todos');
    }

    public function index()
    {
        $becario = Becario::find(Auth::user()->id);
    	$cursos = Curso::where('becario_id','=',Auth::user()->id)->get();
    	return view('sisbeca.cursos.index')->with(compact('cursos','becario'));
    }

    public function cursosbecario($id)
    {
        $becario = Becario::find($id);
        $cursos = Curso::where('becario_id','=',$id)->get();
        return view('sisbeca.cursos.index')->with(compact('cursos','becario'));
    }

    public function crear($id)
    {
    	$model = 'crear';
        $tipocurso = TipoCurso::pluck('nombre', 'id');
        $becario = Becario::find($id);
        if( (Auth::user()->esBecario() and $id==Auth::user()->id) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
        {
            return view('sisbeca.cursos.model')->with(compact('model','becario','tipocurso'));
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function guardar(Request $request,$id)
    {
    	$validation = Validator::make($request->all(), CursoRequest::rulesCreate());
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
		$aval->url = Aval::carpetaNota().$nombre;
		$aval->estatus = "pendiente";
		$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg" or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
		$aval->tipo = "nota";
		$aval->becario_id = $id;
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
		$curso->save();
		
		flash("El CVA fue creado exitosamente.",'success');
        if(Auth::user()->esDirectivo() || Auth::user()->esCoordinador())
        {
            return redirect()->route('cursos.todos');
        }
        else
        {
            return redirect()->route('cursos.index');
        }
    }

    public function editar($id)
    {
    	$curso = Curso::find($id);
    	$model = "editar";
        $becario = $curso->becario;
        $tipocurso = TipoCurso::pluck('nombre', 'id');
        if( (Auth::user()->esBecario() and $curso->becario_id==Auth::user()->id) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador())
        {
            return view('sisbeca.cursos.model')->with(compact('curso','model','becario','tipocurso'));
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function actualizar(Request $request,$id)
    {
    	$validation = Validator::make($request->all(), CursoRequest::rulesUpdate());
		if ( $validation->fails() )
		{
			flash("Por favor, verifique el formulario.",'danger');
			return back()->withErrors($validation)->withInput();
		}
        $curso = Curso::find($id);
        if($request->file('constancia_nota'))
        {	
        	File::delete($curso->aval->url);
        	
     		$archivo= $request->file('constancia_nota');
            $nombre = str_random(100).'.'.$archivo->getClientOriginalExtension();
            $ruta = public_path().'/'.Aval::carpetaNota();
            $archivo->move($ruta, $nombre);
            
        	$aval = $curso->aval;
            $aval->url = Aval::carpetaNota().$nombre;
			$aval->tipo = "nota";
			$aval->extension = ($archivo->getClientOriginalExtension()=="jpg" or $archivo->getClientOriginalExtension()=="jpeg" or $archivo->getClientOriginalExtension()=="png") ? "imagen":"pdf";
			$aval->save();
        }
        $curso->modo = $request->get('modo');
        $curso->nivel = $request->get('nivel');
        $curso->modulo = $request->get('modulo');
		$curso->nota = $request->get('nota');
		$curso->tipocurso_id = $request->get('tipocurso_id');
		$curso->fecha_inicio = DateTime::createFromFormat('d/m/Y', $request->get('fecha_inicio'))->format('Y-m-d');
		$curso->fecha_fin = DateTime::createFromFormat('d/m/Y', $request->get('fecha_fin'))->format('Y-m-d');
		$curso->save();
		
		flash("El CVA fue actualizado exitosamente.",'success');
    	if(Auth::user()->esBecario())
        {
            return redirect()->route('cursos.index');
        }
        else
        {
            return redirect()->route('cursos.todos');
        }
    }

    public function eliminar($id)
    {
        $curso = Curso::find($id);
        if( (Auth::user()->esBecario() and $curso->becario_id==Auth::user()->id) or Auth::user()->esCoordinador() or Auth::user()->esDirectivo())
        {
            $aval = $curso->aval;
            $curso->delete();
            File::delete($aval->url);
            $aval->delete();
            
            flash("El CVA fue eliminado exitosamente.",'success');
            if(Auth::user()->esBecario())
            {
                return redirect()->route('cursos.index');
            }
            else
            {
                return redirect()->route('cursos.todos');
            }
        }
        else
        {
            return view('sisbeca.error.404');
        }
    }

    public function eliminarservicio($id)
    {
        $curso = Curso::find($id);
        $aval = $curso->aval;
        $curso->delete();
        File::delete($aval->url);
        $aval->delete();
        return response()->json(['success'=>'El CVA fue eliminado exitosamente.']);
    }

    public function detalles($id)
    {
        $curso = Curso::find($id);
        $becario = $curso->becario;
        $tipocurso = TipoCurso::pluck('nombre', 'id');
        return view('sisbeca.cursos.detalles')->with(compact('curso','becario','tipocurso'));
    }

    public function detallesservicio($id)
    {
        $cva = Curso::where('id','=',$id)->with('aval')->first();
        return response()->json(['cva'=>$cva]);
    }

    public function actualizarcva(Request $request,$id)
    {
        $cva = Curso::find($id);
        $cva->aval->estatus = $request->estatus;
        $cva->aval->observacion = $request->observacion;
        $cva->aval->save();
        return response()->json(['success'=>'El CVA fue actualizado exitosamente.']);
    }
}
