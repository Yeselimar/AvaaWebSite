<?php

namespace avaa\Http\Controllers\Auth;

use avaa\Concurso;
use avaa\User;
use avaa\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegisterMentors;
use DateTime;
use Timestamp;

class RegisterMentorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegisterMentors;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'cedula' => 'string|max:15|unique:users', //validacion agregada para la tabla user
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \avaa\User
     */
    protected function create(array $data)
    {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->last_name = $data['last_name'];
        $user->fecha_nacimiento = DateTime::createFromFormat('d/m/Y H:i:s', $data['fecha_nacimiento'].' 00:00:00');
        $user->password = bcrypt($data['password']);
        $user->cedula = $data['cedula'];
        $user->sexo = $data['sexo'];
        $user->rol= 'postulante_mentor';
        $user->edad = $data['edad'];
        $user->save();
     

        return ($user);

    }
}
