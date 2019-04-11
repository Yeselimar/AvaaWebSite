<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rol_rule='in:coordinador,editor,directivo,entrevistador';
        $email_rule = 'required|max:30|email';
        $cedula_rule= 'nullable|min:2|max:15';
        if (!($this->method() == 'PUT'))
        {
            // Create operation. There is no id yet.
            $email_rule .= '|unique:users,email';
            if(!is_null($request->get('cedula')))
                 $cedula_rule .= '|unique:users,cedula';


        }

        return [
            'name' => 'required',
            'email' => $email_rule,
            'password' => 'min:6|required|max:30',
            'cedula' => $cedula_rule,
            'rol' => $rol_rule

        ];
    }

    public static function reglasContrasena()
    {
        return [
            'contrasena_nueva' => 'between:6,16|required',
        ];
    }

}
