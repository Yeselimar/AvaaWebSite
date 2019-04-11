<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CursoRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static  function rulesCreate()
    {
        return [
            'nota'             => 'required',
            'fecha_inicio'     => 'required',
            'fecha_fin'        => 'required',
            'constancia_nota'  => 'required|mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }

    public static  function rulesUpdate()
    {
        return [
            'nota'             => 'required',
            'fecha_inicio'     => 'required',
            'fecha_fin'        => 'required',
            'constancia_nota'  => 'mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }

    public static  function rulesCarga()
    {
        return [
            'nota'             => 'required',
            'fecha_inicio'     => 'required',
            'fecha_fin'        => 'required',
            'constancia_nota'  => 'mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }
}
