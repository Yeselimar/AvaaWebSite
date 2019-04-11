<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoluntariadoRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static function rulesCreate()
    {
        return [
            'institucion'    => 'required',
            'responsable'  => 'required',
            'observacion'  => 'required',
            'fecha'        => 'required',
            'lugar'        => 'required',
            'horas'        => 'required',
            'comprobante'  => 'required|mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }

    public static function rulesUpdate()
    {
        return [
            'institucion'    => 'required',
            'responsable'  => 'required',
            'observacion'  => 'required',
            'lugar'        => 'required',
            'horas'        => 'required',
            'comprobante'  => 'mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }

    public static function rulesCarga()
    {
        return [
            'institucion'    => 'required',
            'responsable'  => 'required',
            'observacion'  => 'required',
            'lugar'        => 'required',
            'horas'        => 'required',
            'comprobante'  => 'mimes:pdf,jpeg,jpg,png|max:10000',
        ];
    }
}
