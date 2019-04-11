<?php

namespace avaa\Http\Controllers\Auth;

use avaa\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use avaa\Actividad;
use avaa\Becario;
use avaa\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/seb';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    
    public function login()
    {
        if (Auth::check())
        {
            return redirect()->route('dalepaso');
        }
        else
        {
            return view('auth.login');
        }
    }

    public function postlogin(Request $request)
    {
        $request->validate([
            'email' 			 	=> 'required|email',
            'password'				 	=> 'required',
        ]);
        $user = User::where('email','=',$request->email)->first();
        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'estatus'=> 1]))
        {
            return redirect()->route('dalepaso');
        }
        else
        {
            if($user){
                if($user->estatus == 0){
                    $msg = "Disculpe, el usuario está inactivo.";
                } else{
                    $msg = "Disculpe, correo y/o contraseña incorrecta.";
                }
            } else {
                $msg = "Disculpe, el usuario no existe.";
            }
            return response()->json(['res' => 1,'msg'=>$msg]);
            
            /*
            if($user)
            {
                if($user->estatus==0)
                {
                    flash("Disculpe, el usuario está inactivo.","danger");
                }
                else
                {
                    flash("Disculpe, correo y/o contraseña incorrecta.","danger");
                }
            }
            else
            {
                flash("Disculpe, el usuario no existe.","danger");
            }
            return redirect()->back();   
            */
        }

    }

    public function logout()
    {
        Auth::logout();
        flash("La sesión fue cerrada exitosamente.","success");
        return redirect()->route('login'); 
    }

    public function dalepaso()
    {
        return "dale paso";
        $usuario =  Auth::user();
        $becario = Becario::where('user_id','=',$usuario->id)->first();
        $actividades = Actividad::conEstatus('disponible')->ordenadaPorFecha('asc')->where('fecha','>=',date('Y-m-d 00:00:00'))->take(10)->get();
        return view('sisbeca.index')->with(compact('becario','usuario','actividades'));
    }
    
}
