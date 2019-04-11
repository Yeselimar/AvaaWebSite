<?php

namespace avaa\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use avaa\Becario;
use avaa\User;
use DateTime;
use avaa\Exports\UsersExport;
use avaa\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use avaa\Imagen;
use File;

class UserController extends Controller
{
    public function obtenerdatos($id)
    {
        $becario = Becario::find($id);
        $usuario = User::find($id);
        $foto = Imagen::where('titulo','=','img_perfil')->where('user_id','=',$id)->first();
        $img_perfil = null;
        if($foto)
        {
            $img_perfil = $foto;
        }
        return response()->json(['becario'=>$becario,'usuario'=>$usuario,'img_perfil'=>$img_perfil]);
    }

    public function editardatos($id)
    {
    	$becario = Becario::find($id);
    	$usuario = User::find($id);
        if((Auth::user()->id == $id and Auth::user()->esBecario()) or Auth::user()->esDirectivo() or Auth::user()->esCoordinador() )
        {
            if($becario)
            {
                return view('sisbeca.becarios.editar-datos')->with(compact("becario","usuario"));
            }
        }
        return view('sisbeca.error.404');
    }

    public function actualizardatos(Request $request,$id)
    {
        $request->validate([
            'sexo' 			   => 'required',
            'fecha_nacimiento' => 'required',
            'direccion_permanente' => 'required|min:10,max:1000',
            'direccion_temporal' => 'min:0,max:1000',
            'celular' => 'required|min:11,max:11',
            'telefono_habitacion' => 'min:11,max:11',
            'telefono_pariente' => 'min:11,max:11',
            'lugar_trabajo' => 'min:0,max:255',
            'cargo_trabajo' => 'min:0,max:255',
            'lugar_trabajo' => 'min:0,max:255'
        ]);
        $becario = Becario::find($id);
        $usuario = User::find($id);

        $usuario->fecha_nacimiento = DateTime::createFromFormat('d/m/Y', $request->fecha_nacimiento )->format('Y-m-d');
        $usuario->sexo = $request->sexo;
        $usuario->estatus = $request->estatus;
        $usuario->save();
        $becario->direccion_permanente = $request->direccion_permanente;
        $becario->direccion_temporal = $request->direccion_temporal;
        $becario->celular = $request->celular;
        $becario->telefono_habitacion = $request->telefono_habitacion;
        $becario->telefono_pariente = $request->telefono_pariente;
        $becario->trabaja = $request->trabaja;
        if($becario->trabaja=='0')
        {
            $becario->lugar_trabajo = null;
            $becario->cargo_trabajo = null;
            $becario->horas_trabajo = null;
        }
        else
        {
            $becario->lugar_trabajo = $request->lugar_trabajo;
            $becario->cargo_trabajo = $request->cargo_trabajo;
            $becario->horas_trabajo = $request->horas_trabajo;
        }
        $becario->save();

        return response()->json(['success'=>'Los datos personales del becario fueron actualizados.']);
    }

    public function actualizaruniversidad(Request $request, $id)
    {
        $request->validate([
            'inicio_universidad'   => 'required',
            'nombre_universidad'   => 'required|min:2,max:1000',
            'carrera_universidad'  => 'required|min:2,max:255',
            'promedio_universidad' => 'required',
        ]);
        $becario = Becario::find($id);
        $usuario = User::find($id);
        $becario->inicio_universidad = DateTime::createFromFormat('d/m/Y', $request->inicio_universidad )->format('Y-m-d');
        $becario->nombre_universidad = $request->nombre_universidad;
        $becario->carrera_universidad = $request->carrera_universidad;
        $becario->promedio_universidad = $request->promedio_universidad;
        $becario->regimen = $request->regimen;
        $becario->save();

        return response()->json(['success'=>'Los estudios universitarios del becario fueron actualizados.']);
    }

    public function actualizarestatusbecario(Request $request, $id)
    {
        $becario = Becario::find($id);
        $usuario = User::find($id);

        $usuario->estatus = $request->usuario_estatus;
        $usuario->save();

        $becario->status = $request->becario_estatus;
        if($request->becario_estatus=='inactivo')
        {
            //Guardo fecha desde el cual entra en inactivo
            $becario->fecha_inactivo = DateTime::createFromFormat('d/m/Y H:i:s', date('d/m/Y H:i:s') )->format('Y-m-d H:i:s');
            $becario->observacion_inactivo = "El ".Auth::user()->getRol()." ".Auth::user()->nombreyapellido()." paso al becario a inactivo.";
        }
        else
        {
            if($request->becario_estatus=='activo')
            {
                $becario->fecha_inactivo = null;
                $becario->observacion_inactivo = null;
                //Coloco en nulo la fecha de inactivo
            }
        }
        $becario->save();

        return response()->json(['success'=>'El estatus del becario fue actualizado.']);
    }

    public function actualizarcontrasena(Request $request, $id)
    {
        if($request->contrasena_nueva==$request->contrasena_repite)
        {
            return response()->json(['errors'=>'success','error'=>'La contraseña fue actualizada exitosamente.']);
        }
        return response()->json(['tipo'=>'success','success'=>'La contraseña fue actualizada exitosamente.']);
    }

    public function actualizarfoto(Request $request,$id)
    {
        $user = User::find($id);
        if($request->file('foto'))
        {
            $file = $request->file('foto');
            $name = 'img-user_' . $user->cedula . time() . '.' . $file->getClientOriginalExtension();
            $path = public_path() . '/images/perfil/';
            $file->move($path, $name);
            //Borramos la foto anterioor
            $foto = Imagen::where('titulo','=','img_perfil')->where('user_id','=',$id)->first();
            if($foto)
            {
                File::delete($foto->url);
                $foto->delete();
            }
            

            $img_perfil = new Imagen();
            $img_perfil->titulo = 'img_perfil';
            $img_perfil->url = 'images/perfil/' . $name;
            $img_perfil->verificado = true;
            $img_perfil->user_id = $user->id;
            $img_perfil->save();
        }
        return response()->json(['success'=>'La imagen perfil fue actualizada.','foto'=>$request->foto]);
    }

    public function export() //
    {
        return Excel::download(new UsersExport, 'usuarios.xlsx');
    }
    
    public function import() 
    {
        return Excel::import(new UsersImport, 'users.xlsx');
    }
}   
