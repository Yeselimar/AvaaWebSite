<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => 'El campo :attribute no es una URL válida.',
    'after'                => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo :attribute sólo puede contener letras.',
    'alpha_dash'           => 'El campo :attribute sólo puede contener letras, números y guiones (a-z, 0-9, -_).',
    'alpha_num'            => 'El campo :attribute sólo puede contener letras y números.',
    'array'                => 'El campo :attribute debe ser un array.',
    'before'               => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El campo :attribute debe ser un valor entre :min y :max.',
        'file'    => 'El archivo :attribute debe pesar entre :min y :max kilobytes.',
        'string'  => 'El campo :attribute debe contener entre :min y :max caracteres.',
        'array'   => 'El campo :attribute debe contener entre :min y :max elementos.',
    ],
    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'            => 'El campo confirmación de :attribute no coincide.',
    'country'              => 'El campo :attribute no es un país válido.',
    'date'                 => 'El campo :attribute no corresponde con una fecha válida.',
    'date_format'          => 'El campo :attribute no corresponde con el formato de fecha :format.',
    'different'            => 'Los campos :attribute y :other han de ser diferentes.',
    'digits'               => 'El campo :attribute debe ser un número de :digits dígitos.',
    'digits_between'       => 'El campo :attribute debe contener entre :min y :max dígitos.',
    'dimensions'           => 'El campo :attribute tiene dimensiones inválidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'email'                => 'El campo :attribute no corresponde con una dirección de e-mail válida.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute es obligatorio.',
    'exists'               => 'El campo :attribute no existe.',
    'image'                => 'El campo :attribute debe ser una imagen.',
    'in'                   => 'El campo :attribute debe ser igual a alguno de estos valores :values.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => 'El campo :attribute debe ser un número entero.',
    'ip'                   => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4'                 => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo :attribute debe ser una cadena de texto JSON válida.',
    'max'                  => [
        'numeric' => 'El campo :attribute debe ser :max como máximo.',
        'file'    => 'El archivo :attribute debe pesar :max kilobytes como máximo.',
        'string'  => 'El campo :attribute debe contener :max caracteres como máximo.',
        'array'   => 'El campo :attribute debe contener :max elementos como máximo.',
    ],
    'mimes'                => 'El campo :attribute debe ser un archivo de tipo :values.',
    'mimetypes'            => 'El campo :attribute debe ser un archivo de tipo :values.',
    'min'                  => [
        'numeric' => 'El campo :attribute debe tener al menos :min.',
        'file'    => 'El archivo :attribute debe pesar al menos :min kilobytes.',
        'string'  => 'El campo :attribute debe contener al menos :min caracteres.',
        'array'   => 'El campo :attribute no debe contener más de :min elementos.',
    ],
    'not_in'               => 'El campo :attribute seleccionado es inválido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El formato del campo :attribute es inválido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_if'          => 'El campo :attribute es obligatorio cuando el campo :other es :value.',
    'required_unless'      => 'El campo :attribute es requerido a menos que :other se encuentre en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los campos :values está presente.',
    'same'                 => 'Los campos :attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file'    => 'El archivo :attribute debe pesar :size kilobytes.',
        'string'  => 'El campo :attribute debe contener :size caracteres.',
        'array'   => 'El campo :attribute debe contener :size elementos.',
    ],
    'state'                => 'El estado no es válido para el país seleccionado.',
    'string'               => 'El campo :attribute debe contener sólo caracteres.',
    'timezone'             => 'El campo :attribute debe contener una zona válida.',
    'unique'               => 'El elemento :attribute ya está en uso.',
    'uploaded'             => 'El elemento :attribute fallo al subir.',
    'url'                  => 'El formato de :attribute no corresponde con el de una URL válida.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
    /* Datos Personales */
    'titulo'                        => 'título',
    'url_imagen'                    => 'imagen',
    'informacion_contacto'          => 'publicador',
    'direccion_permanente'          => 'dirección permanente',
    'direccion_temporal'            => 'dirección temporal',
    'celular'                       => 'teléfono celular',
    'telefono_habitacion'           => 'teléfono habitación',
    'telefono_pariente'             => 'teléfono pariente',
    'lugar_nacimiento'              => 'lugar de nacimiento',
    'ingreso_familiar'              => 'promedio ingreso familiar',
    'lugar_trabajo'                 => 'lugar de trabajo',
    'cargo_trabajo'                 => 'cargo que desempeña en el trabajo',
    'horas_trabajo'                 => 'horas mensuales de trabajo',
    'contribuye_ingreso_familiar'   => 'contribuye con el ingreso familiar',
    'porcentaje_contribuye_ingreso' => 'contribuye al ingreso familiar',
    'vives_con'                     => 'con quien vives',
    'vives_otros'                   => 'vives con otros',
    'tipo_vivienda'                 => 'tipo de vivienda',
    'composicion_familiar'          => 'composición del núcleo familiar',
    'ocupacion_padre'               => 'ocupación del padre',
    'nombre_empresa_padre'          => 'nombre de la empresa del padre',
    'experiencias_padre'            => 'experiencia laboral del padre',
    'ocupacion_madre'               => 'ocupación de la madre',
    'nombre_empresa_madre'          => 'nombre de la empresa de la madre',
    'experiencias_madre'            => 'experiencia laboral de la madre',
 
    /* Estudios secundarios  */
    'nombre_institucion'            => 'nombre de la institución',
    'direccion_institucion'         => 'dirección de la institución',
    'director_institucion'          => 'nombre del director de la institución',
    'lugar_labor_social'            => 'lugar dónde realizó labor social',
    'supervisor_labor_social'       => 'supervisor de la labor social',
    'aprendio_labor_social'         => 'qué aprendió en la labor social',
    'habla_idioma'                  => 'cual idioma habla',
    'nivel_idioma'                  => 'nivel de conocimiento del idioma',

    /* Estudios Universitarios  */
    'inicio_universidad'            => 'fecha de inicio de la universidad',
    'nombre_universidad'            => 'nombre de la universidad',
    'carrera_universidad'           => 'carrera',
    'costo_matricula'               => 'costo de la matrícula',
    'periodo_academico'             => 'periódo académico',
    
    /* Información Adicional  */
    'otro_medio_proexcelencia'      => 'especifique el otro medio',
    'motivo_beca'                   => 'por qué solicita la beca',

    /* Documentos */ 
    'fotografia'                   => 'fotografía',
    'cedula'                        => 'copia de cédula',
    'constancia_cnu'                    => 'constancia CNU',
    'calificaciones_bachillerato'       => 'calificaciones de bachillerato',
    'constancia_aceptacion'         => 'constancia de aceptación',
    'constancia_estudios'       => 'constancia de estudios',
    'calificaciones_universidad'    => 'calificaciones del primer año de la universidad',
    'constancia_trabajo'    => 'constancia de trabajo',
    'declaracion_impuestos' => 'declaración de impuestos',
    'recibo_pago'           => 'recibo de pago de un servicio',
    'referencia_profesor1' => 'carta de referencia del profesor 1',
    'referencia_profesor2' => 'carta de referencia del profesor 2',
    'descripcion' => 'descripción',
    'titulo' =>  'título',
    'observacion' => 'observación',
    ],
];