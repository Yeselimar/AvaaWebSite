<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static function rulesCreate()
    {
        return [
            'asunto'       => 'required|min:5,max:255',
            'url'          => 'url',
            'descripcion'  => 'required||min:5,max:20000',
            'imagen'       => 'mimes:pdf,jpeg,jpg,png|max:50000',
        ];
    }

    public static function rulesUpdate()
    {
        return [
            'asunto'       => 'required|min:5,max:255',
            'url'          => 'url',
            'descripcion'  => 'required||min:5,max:20000',
            'imagen'       => 'mimes:pdf,jpeg,jpg,png|max:50000',
        ];
    }
}
