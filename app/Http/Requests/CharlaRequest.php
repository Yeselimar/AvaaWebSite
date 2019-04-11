<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharlaRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static function rulesCreate()
    {
        return [
            'anho'    => 'required',
            'imagen'  => 'required|mimes:jpeg,jpg,png|max:10000',
        ];
    }

    public static function rulesUpdate()
    {
        return [
            'anho'    => 'required',
            'imagen'  => 'mimes:jpeg,jpg,png|max:10000',
        ];
    }
}
