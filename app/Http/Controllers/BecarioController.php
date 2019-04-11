<?php

namespace avaa\Http\Controllers;

use avaa\Alerta;
use avaa\FactLibro;
use avaa\Imagen;
use avaa\Mentor;
use avaa\User;
use Illuminate\Http\Request;
use avaa\Http\Controllers\Controller;
use avaa\Becario;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class BecarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('becario');
    }

    public function bancariapdf()
    {
        switch (date('m')) {
            case 1:
                $mes= 'Enero';
                break;
            case 2:
                $mes=  'Febrero';
                break;
            case 3:
                $mes= 'Marzo';
                break;
            case 4:
                $mes=  'Abril';
                break;
            case 5:
                $mes=  'Mayo';
                break;
            case 6:
                $mes=  'Junio';
                break;
            case 7:
                $mes=  'Julio';
                break;
            case 8:
                $mes=  'Agosto';
                break;
            case 9:
                $mes=  'Septiembre';
                break;
            case 10:
                $mes=  'Octubre';
                break;
            case 11:
                $mes=  'Noviembre';
                break;
            case 12:
                $mes=  'Diciembre';
                break;
        }

        $becario= Becario::find(Auth::user()->id);
        $pdf = PDF::loadView('sisbeca.cartas.bancaria', compact('mes','becario'));

        return $pdf->stream('bancaria.pdf', 'PDF xasa');
    }

    public function aceptoTerminosCondiciones()
    {

        if (Auth::user()->becario->acepto_termino == 1) {
            return back();
        }
        return view('sisbeca.becarios.terminosCondiciones');
    }

    public function verCuentaBancaria()
    {
        $becario = User::find(Auth::user()->id)->becario;
        if (is_null($becario->cuenta_bancaria)) {
            flash('Disculpe, por favor cargue su cuenta bancaria')->error()->important();
        }
        return view('sisbeca.becarios.cuentaBancaria')->with('becario', $becario);
    }

    public function cuentaBancariaUpdated(Request $request, $id)
    {
        if(Auth::user()->id==$id)
        {
            $becario= Becario::find($id);

            $becario->cuenta_bancaria= $request->get('cuenta_bancaria');

            if($becario->save())
            {
                flash('Su número de duenta fue actualizado exitosamente.','success')->important();
            }

        }
        else
        {
            flash('Error al acceder a la pagina solicitada', 'danger')->important();
            return back();
        }
        return redirect()->route('verCuentaBancaria');
    }

    public function verMentorAsignado()
    {
        $mentor= Mentor::query()->where('user_id','=',Auth::user()->becario->mentor_id)->first();

        $img_perfil=Imagen::query()->where('user_id','=',Auth::user()->becario->mentor_id)->where('titulo','=','img_perfil')->get();
        $documento = null;

        Alerta::where('status', '=', 'generada')->where('solicitud','=',null)->where('user_id', '=', Auth::user()->id)->update(array('leido' => true));

        return view('sisbeca.mentores.perfilMentor')->with('mentor',$mentor)->with('img_perfil',$img_perfil)->with('documento',$documento);
    }

    

}