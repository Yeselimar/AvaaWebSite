<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntrevistadorRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static function cargarDocumento()
    {
        return [
            'documento'  => 'required|mimes:pdf,jpeg,jpg,png,JPG,JPEG,PNG,PDF|max:10000',
        ];
    }

    public static function actualizarDocumento()
    {
        return [
            'documento'  => 'mimes:pdf,jpeg,jpg,png,JPG,JPEG,PNG,PDF|max:10000',
        ];
    }

    public static function cargarDocumentoConjunto()
    {
        return [
            'documento'  => 'required|mimes:pdf,jpeg,jpg,png,JPG,JPEG,PNG,PDF|max:10000',
        ];
    }

    public static function actualizarDocumentoConjunto()
    {
        return [
            'documento'  => 'mimes:pdf,jpeg,jpg,png,JPG,JPEG,PNG,PDF|max:10000',
        ];
    }
}
