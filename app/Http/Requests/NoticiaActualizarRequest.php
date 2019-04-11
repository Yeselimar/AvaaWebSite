<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticiaActualizarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $email_contacto_rule = '';
        $url_articulo_rule= '';
        if (!($this->method() == 'PUT')) {

            if($this->get('tipo')==='miembroins')
            {
                $url_articulo_rule .= 'required|url';
                $email_contacto_rule .='nullable|email|max:30|unique:noticias,email_contacto';
            }

        }

            return [
            'titulo'                   => 'required|min:2,max:100',
            'informacion_contacto'     => 'required',
            'contenido'                => 'required|min:60',
            'url_imagen'               => 'file|image|mimes:jpeg,bmp,png,jpg',
            'tipo'                     => 'in:noticia,miembroins',
            'url_articulo'             => $url_articulo_rule,
            'email_contacto'           => $email_contacto_rule
        ];
    }
}
