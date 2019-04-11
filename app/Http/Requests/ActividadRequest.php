<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static function cargarJustificativo()
    {
        return [
            'justificativo'  => 'required|mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }

    public static function actualizarJustificativo()
    {
        return [
            'justificativo'  => 'mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }
}
