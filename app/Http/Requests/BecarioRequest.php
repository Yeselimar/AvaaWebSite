<?php

namespace avaa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BecarioRequest extends FormRequest
{
  
    public function authorize()
    {
        return true;
    }
    
    public static function rulesDatosPersonales()
    {
        return [
            'direccion_permanente'        => 'required|min:2,max:255',
            'direccion_temporal'          => 'required|min:2,max:255',
            'celular'                     => 'required|min:11,max:11',
            'telefono_habitacion'         => 'min:11,max:11',
            'telefono_pariente'           => 'min:11,max:11',
            'lugar_nacimiento'            => 'required|min:2,max:255',
            'ingreso_familiar'            => 'numeric|between:0,999999999.99',
            'lugar_trabajo'               => 'min:0,max:255',
            'cargo_trabajo'               => 'min:0,max:255',
            'horas_trabajo'               => 'between:0,1000',
            'contribuye_ingreso_familiar' => 'between:0,100.00',
            'vives_otros'                 => 'min:0,max:255',
            'composicion_familiar'        => 'between:0,100',
            'ocupacion_padre'             => 'required|min:2,max:255',
            'nombre_empresa_padre'        => 'required|min:2,max:255',
            'experiencias_padre'          => 'required|between:0,100',
            'ocupacion_madre'             => 'required|min:2,max:255',
            'nombre_empresa_madre'        => 'required|min:2,max:255',
            'experiencias_madre'          => 'required|between:0,100',
        ];
    }

    public static function rulesEstudiosSecundarios()
    {
        return [
            'nombre_institucion'            => 'required|min:2,max:255',
            'direccion_institucion'         => 'min:0,max:255',
            'director_institucion'          => 'min:0,max:255',
            'bachiller_en'                  => 'required|min:2,max:255',
            'promedio_bachillerato'         => 'required|numeric|between:0,20.00',
            'actividades_extracurriculares' => 'min:0,max:255',
            'lugar_labor_social'            => 'required|min:0,max:255',
            'direccion_labor_social'        => 'required|min:0,max:1000',
            'supervisor_labor_social'       => 'min:0,max:1000',
            'aprendio_labor_social'         => 'required|min:0,max:1000',
            'habla_idioma'                  => 'min:0,max:255',
        ];
    }

    public static function rulesEstudiosUniversitarios()
    {
        return [
            'inicio_universidad'            => 'required',
            'nombre_universidad'            => 'required|min:2,max:255',
            'carrera_universidad'           => 'required|min:2,max:255',
            'costo_matricula'               => 'between:0,999999999.99',
            'promedio_universidad'          => 'required|numeric|between:0,20.00',
            'periodo_academico'             => 'required|min:2,max:255',

        ];
    }

    public static function rulesInformacionAdicional()
    {
        return [
            'otro_medio_proexcelencia'      => 'required|min:2,max:255',
            'motivo_beca'                   => 'required|min:2,max:1000',
            'link_video'                    => 'required|url',

        ];
    }

    public static function rulesDocumentos()
    {
        return [
            'fotografia'                    => 'required|mimes:jpeg,jpg,png',
            'cedula'                        => 'required|mimes:jpeg,jpg,png',
            'constancia_cnu'                => 'required|mimes:pdf',
            'calificaciones_bachillerato'   => 'required|mimes:pdf',
            'constancia_aceptacion'         => 'required|mimes:pdf',
            'constancia_estudios'           => 'required|mimes:pdf',
            'calificaciones_universidad'    => 'required|mimes:pdf',
            'constancia_trabajo'            => 'required|mimes:pdf',
            'declaracion_impuestos'         => 'required|mimes:pdf',
            'recibo_pago'                   => 'required|mimes:pdf',
            'referencia_profesor1'          => 'required|mimes:pdf',
            'referencia_profesor2'          => 'required|mimes:pdf',
            'ensayo'                        => 'required|mimes:pdf',
        ];
    }
}
