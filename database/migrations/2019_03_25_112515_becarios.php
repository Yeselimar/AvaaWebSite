<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Becarios extends Migration
{
    public function up()
    {
        Schema::create('becarios', function (Blueprint $table)
        {
            $table->primary('user_id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            //se coloca el mismo id porque la relacion uno a uno no pueden existir dos perfiles con el mismo user
            $table->boolean('acepto_terminos')->default(false);
            $table->enum('status',['prepostulante','postulante','no_entrevista','entrevista','entrevistado','rechazado','activo','probatorio1','probatorio2','egresado','inactivo','desincorporado'])->default('prepostulante');
            $table->double('retroactivo',20,2)->default(0);
            //Datos Personales
            $table->text('direccion_permanente')->nullable();
            $table->text('direccion_temporal')->nullable();
            $table->string('celular')->nullable();
            $table->string('telefono_habitacion')->nullable();
            $table->string('telefono_pariente')->nullable();
            $table->string('lugar_nacimiento')->nullable();
            $table->double('ingreso_familiar',20,2)->nullable();
            $table->boolean('trabaja')->nullable();
            $table->string('lugar_trabajo')->nullable();
            $table->string('cargo_trabajo')->nullable();
            $table->unsignedInteger('horas_trabajo')->nullable();
            $table->boolean('contribuye_ingreso_familiar')->nullable();
            $table->float('porcentaje_contribuye_ingreso')->nullable();
            $table->enum('vives_con',['padres','otros'])->default('otros');
            $table->string('vives_otros')->nullable();
            $table->enum('tipo_vivienda',['propia','alquilada','hipotecada'])->default('propia');
            $table->unsignedInteger('composicion_familiar')->nullable();
            //Ocupacion Padre
            $table->string('ocupacion_padre')->nullable();
            $table->string('nombre_empresa_padre')->nullable();
            $table->unsignedInteger('experiencias_padre')->nullable();
            //Ocupacion Madre
            $table->string('ocupacion_madre')->nullable();
            $table->string('nombre_empresa_madre')->nullable();
            $table->unsignedInteger('experiencias_madre')->nullable();
            //Estudios Secundarios
            $table->string('nombre_institucion')->nullable();
            $table->text('direccion_institucion')->nullable();
            $table->string('director_institucion')->nullable();
            $table->string('bachiller_en')->nullable();
            $table->float('promedio_bachillerato')->nullable();
            $table->string('actividades_extracurriculares')->nullable();
            $table->string('lugar_labor_social')->nullable();
            $table->text('direccion_labor_social')->nullable();
            $table->string('supervisor_labor_social')->nullable();
            $table->text('aprendio_labor_social')->nullable();
            $table->boolean('habla_otro_idioma')->nullable();
            $table->string('habla_idioma')->nullable();
            $table->enum('nivel_idioma',['basico','medio','avanzando'])->default('basico');
            //Estudios Universitarios
            $table->dateTime('inicio_universidad')->nullable();
            $table->string('nombre_universidad')->nullable();
            $table->string('carrera_universidad')->nullable();
            $table->double('costo_matricula',20,2)->nullable();
            $table->float('promedio_universidad')->nullable();
            $table->string('periodo_academico')->nullable();
            //Informacion Adicional
            $table->enum('medio_proexcelencia',['amigo/pariente','internet','medios_comunicacion','otros'])->default('amigo/pariente');
            $table->string('otro_medio_proexcelencia')->nullable();
            $table->text('motivo_beca')->nullable();
            $table->datetime('fecha_egresado')->nullable();
            $table->datetime('fecha_inactivo')->nullable();
            $table->datetime('fecha_desincorporado')->nullable();
            $table->text('observacion_inactivo')->nullable();
            $table->text('motivo_probatorio1')->nullable();
            $table->text('motivo_probatorio2')->nullable();
            $table->text('observacion_egresado')->nullable();
            $table->text('observacion_desincorporado')->nullable();
            $table->text('cuenta_bancaria')->nullable();
            //campos para verificacion de documento
            $table->boolean('datos_personales')->default(false);
            $table->boolean('estudios_secundarios')->default(false);
            $table->boolean('estudios_universitarios')->default(false);
            $table->boolean('informacion_adicional')->default(false);
            $table->boolean('documentos')->default(false);
            $table->text('link_video')->nullable();

            //
            $table->enum('tipo',['nuevo','viejo'])->default('nuevo');
            $table->enum('regimen',['anual','semestral','trimestral'])->default('anual');
            $table->boolean('estatus_actividad');
            $table->boolean('estatus_curso');
            $table->boolean('estatus_voluntariado');



            /*$table->unsignedInteger('coordinador_id');
            $table->foreign('coordinador_id')
                ->references('user_id')->on('coordinadores')
                ->onDelete('cascade');*/

            $table->unsignedInteger('mentor_id')->nullable();
            $table->foreign('mentor_id')
                ->references('user_id')->on('mentores')
                ->onDelete('cascade');

            $table->text('observacion');//entrevistadores pone este ca|

            //campos de la entrevista
            $table->datetime('fecha_entrevista')->nullable();
            $table->time('hora_entrevista')->nullable();
            $table->text('lugar_entrevista')->nullable();
            $table->boolean('notificando_entrevista')->default(0);
            $table->datetime('fecha_notificacion_entrevista')->nullable();
            $table->text('documento_final_entrevista')->nullable();

            //campos para la nómina
            $table->datetime('fecha_ingreso')->nullable();//debe ser igual al created_at
            $table->datetime('fecha_aprobado')->nullable();//modificar cuando pasa de postulante becario a becario
            $table->datetime('fecha_egreso')->nullable();

            //campos para la bienvenida como becario
            $table->datetime('fecha_bienvenida')->nullable();
            $table->time('hora_bienvenida')->nullable();
            $table->text('lugar_bienvenida')->nullable();
            $table->text('observacion_privada')->nullable();//no esta siendo usada

            //campo para control de nómina
            $table->datetime('final_carga_academica')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('becarios');
    }
}
