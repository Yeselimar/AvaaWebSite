<?php

namespace avaa\Http\Controllers;

use avaa\Coordinador;
use avaa\Editor;
use avaa\User;
use Redirect;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use avaa\Http\Requests\UserRequest;
use Validator;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MantenimientoUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $usuarios = User::select(['id','cedula','name','last_name','email','rol'])->where('rol', '=', "coordinador")->orWhere('rol','editor')->orWhere('rol','directivo')->orWhere('rol','entrevistador')->get();
        return view('sisbeca.crudUser.mantenimientoUsuario')->with(compact('usuarios'));
    }

    public function getUsers()
    {
        $users = User::select(['id','cedula','name','email','rol'])->where('rol', '=', "coordinador")->orWhere('rol','editor')->orWhere('rol','directivo')->orWhere('rol','entrevistador')->get();
        return Datatables::of($users)
            ->make(true);
    }
    public function create()
    {
        return view('sisbeca.crudUser.crearUsuario');
    }

    public function store(UserRequest $request)
    {
        if($request->get('password') !== $request->get('password-repeat'))
        {
            flash('Disculpe, su contraseña no coincide.','danger')->important();
            return back();
        }
        //para guardar esos datos del formulario de creacion
        $rol=$request->rol;
        $user = new User($request->all());
        $user->remember_token = str_random(10);
        $user->password = bcrypt($request->password);
        $user->save();

        /*if ($user->rol === 'coordinador' || $user->rol === 'directivo')
        {

            //creacion de un registro en la tabla de coordinador
            $coordinador = new Coordinador();

            $coordinador->user_id = $user->id;

            if($coordinador->save()){
                flash('Usuario->'.strtoupper($user->rol).' Registrado Exitosamente!','success')->important();
            }else{
                flash('Ha ocurrido un error al registrar el Usuario->'.strtoupper($user->rol))->error()->important();
            }
        }
        else
        {
            if ($user->rol === 'editor') {

                //creacion de un registro en la tabla de Editor
                $editor = new Editor();

                $editor->user_id = $user->id;


                if($editor->save()){
                    flash('Usuario->'.strtoupper($user->rol).' Registrado Exitosamente!','success')->important();
                }
                else{
                    flash('Ha ocurrido un error al registrar el Usuario->'.strtoupper($user->rol))->error()->important();
                }

            }
        }*/

        $usuario = $user;
        //Enviar correo a la persona notificando que el usuario fue creado
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "TLS";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->Username = "delgadorafael2011@gmail.com";
        $mail->Password = "scxxuchujshrgpao";
        $mail->setFrom("no-responder@avaa.org", "Sisbeca");
        $mail->Subject = "Notificación";
        $body = view("emails.usuarios.mensaje-usuario-creado")->with(compact("usuario"));
        $mail->MsgHTML($body);
        $mail->addAddress($usuario->email);
        //$mail->send();

        flash('El usuario fue creado exitosamente.','success')->important();

        return redirect()->route('mantenimientoUser.index');
    }

    public function show($id)
    {
        // para mostrar un registro en especifico
    }

    public function edit($id)
    {
        //para cargar un formulario el cual es donde vamos a editar algun tipo usuario
        $user = User::find($id);
        if(is_null($user))
        {
            flash('Disculpe, el archivo no fue encontrado.','error')->important();
            return back();
        }

        return view('sisbeca.crudUser.editarUsuario')->with('user',$user);
    }

    public function update(UserRequest $request, $id)
    {
        if($request->get('password') !== $request->get('password-repeat'))
        {
            flash('Disculpe, su contraseña no coincide.','danger')->important();
            return back();
        }
        //encargado de recibir los datos que mandemos del usuario que indiquemos en edit y poder actualizarlo
        $user = User::find($id);
        $rolNuevo=$request->rol;
        $rolViejo=$user->rol;

        //los errores de validación  personales para el email y cedula
        Validator::make($request->all(), [
            'email' => [
                Rule::unique('users')->ignore($user->id),'required','max:30','email',
            ],
            'cedula' => [
                Rule::unique('users')->ignore($user->id),'nullable','min:2','max:15',
            ],
        ])->validate();

        //este condicional me gestionara si existen en la tabla usuarios con rol de editor o directivo, ya que coordinadores si puede existir varios
        /*if($rolNuevo===$user->rol || $rolNuevo==='coordinador') {

            //
        }
        else
        {
            //errores personales para el rol
            Validator::make($request->all(), [
                'rol' => [
                    Rule::unique('users')->ignore($user->id),'in:coordinador,editor,directivo',
                ],
            ])->validate();
        }*/

        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();
        flash('El usuario fue actualizado exitosamente.','success')->important();

        //Si al actualizar se quiere cambiar de rol  a uno que tenga una relacion con tablas distintas se manejan estas condiciones

        //ademas esto mas adelante tambien se debe modificar ya que si un coordinador tiene a cargo becarios o un editor tiene otras tablas ya asociadas entonces se puede perder las relaciones

        /*if(($rolViejo==='directivo'||$rolViejo==='coordinador')&&($rolNuevo=='editor'))
        {
            $coordinadorDelete= Coordinador::query()->where('user_id', '=', "$user->id")->delete();
            $edit= new Editor();
            $edit->user_id=$user->id;
            if($edit->save())
            {
                flash('Usuario con rol '.strtoupper($rolViejo).' Actualizado a rol '.strtoupper($rolNuevo).' Exitosamente','success')->important();
            }
            else
            {
                flash('Ha ocurrido un error al cambiar rol de usuario')->error()->important();
            }
        }
        else
        {
            if(($rolNuevo==='directivo'||$rolNuevo==='coordinador')&&($rolViejo=='editor'))
            {
                $editDelete= Editor::query()->where('user_id', '=', "$user->id")->delete();

                $coord= new Coordinador();
                $coord->user_id=$user->id;

               if( $coord->save()){
                   flash('Usuario con rol '.strtoupper($rolViejo).' Actualizado a rol '.strtoupper($rolNuevo).' Exitosamente','success')->important();
               }
               else{
                   flash('Ha ocurrido un error al cambiar rol de usuario')->error()->important();
               }
            }
        }*/
        return  redirect()->route('mantenimientoUser.index');
    }

    public function destroy($id)
    {
        // para eliminar un usuario preciso
        $user= User::find($id);
        //reconfirmar que rol que esta asociado al id
        if(is_null($user))
        {
            flash('Disculpe, el archivo solicitado no ha sido encontrado.')->error()->important();
            return back();
        }
        else
        {
            if($user->rol==='admin')
            {
                flash('Disculpe, el archivo solicitado no ha sido encontrado.')->error()->important();
                return back();
            }
        }

        if($user->delete())
        {
            flash('El usuario fue sido eliminado exitosamente.', 'success')->important();
        }
        else
        {
            flash('Disculpe, hubo un error al eliminar usuario.')->error()->important();
        }
        return  redirect()->route('mantenimientoUser.index');
    }
}
