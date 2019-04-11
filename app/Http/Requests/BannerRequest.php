<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public static function rulesCreate()
    {
        return [
            'titulo'    => 'required|min:10,max:255',
            'url'       => 'required|url',
            'imagen'    => 'required|mimes:jpeg,jpg,png|max:10000',
        ];
    }

    public static function rulesUpdate()
    {
        return [
            'titulo'    => 'required|min:10,max:255',
            'url'       => 'required|url',
            'imagen'    => 'mimes:jpeg,jpg,png|max:10000',
        ];
    }
}
