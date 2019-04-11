<?php

namespace avaa\Http\Controllers;

use avaa\Alerta;
use avaa\Documento;
use avaa\Imagen;
use avaa\Mentor;
use avaa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorController extends Controller
{
    public function __construct()
    {
        $this->middleware('mentor');
    }

    public function listarBecariosAsignados()
    {
        $mentor= Mentor::find(Auth::user()->id);
        $becarios=$mentor->becarios;
        if($becarios->count()==0)
        {
            flash('No tiene becarios asignados.','warning');
        }
        else
        {
            Alerta::where('status', '=', 'generada')->where('solicitud','=',null)->where('user_id', '=', Auth::user()->id)->update(array('leido' => true));
        }
        return view('sisbeca.becarios.listar')->with('becarios',$becarios);
    }

    public function verMiPerfil()
    {
        $mentor= Mentor::find(Auth::user()->id);
        $img_perfil=Imagen::query()->where('user_id','=',$mentor->user_id)->where('titulo','=','img_perfil')->get();
        $documento = Documento::query()->where('user_id', '=', $mentor->user_id)->where('titulo', '=', 'hoja_vida')->first();
        return view('sisbeca.mentores.perfilMentor')->with('mentor',$mentor)->with('img_perfil',$img_perfil)->with('documento',$documento);
    }
}
