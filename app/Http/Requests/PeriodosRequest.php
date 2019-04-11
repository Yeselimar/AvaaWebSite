<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeriodosRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static function rulesCreate()
    {
        return [
            'anho_lectivo'     => 'required',
            'fecha_inicio'     => 'required',
            'fecha_fin'        => 'required',
            'constancia'       => 'required|mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }

    public static function rulesUpdate()
    {
        return [
            'anho_lectivo'     => 'required',
            'fecha_inicio'     => 'required',
            'fecha_fin'        => 'required',
            'constancia'       => 'mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }

    public static function rulesCarga()
    {
        return [
            'anho_lectivo'     => 'required',
            'fecha_inicio'     => 'required',
            'fecha_fin'        => 'required',
            'constancia'       => 'mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }
}
