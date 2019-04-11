@extends('sisbeca.layouts.main')
@section('title',$model=='crear' ? 'Crear Taller o Chat Club' : 'Editar '.$actividad->tipo.': '.$actividad->nombre)
@section('content')
<div class="col-lg-12" id="app">
    <div class="text-right">
        @if($model=="editar")
            <a href="{{route('actividad.detalles',$actividad->id)}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
        @else
             <a href="{{route('actividad.listar')}}" class="btn btn-sm sisbeca-btn-primary">Atrás</a>
        @endif
    </div>
	<br>
	<div class="col sisbeca-container-formulario">

        @if($model=="crear")
		    <form method="POST" @submit.prevent="guardaractividad()" class="form-horizontal">
            {{ csrf_field() }}
        @else
             <form method="POST" @submit.prevent="actualizaractividad()" class="form-horizontal">
            {{ csrf_field() }}
        @endif
		
		<div class="form-group">
			<div class="row">
                
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label class="control-label">*Tipo</label>
                    {{ Form::select('tipo', array('taller'=>'Taller','chat club'=>'Chat Club'),null,['class' =>'sisbeca-input','v-model'=>'tipo','@change'=>'actualizarfacilitadores(tipo)']) }}
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <label class="control-label">*Nombre</label>
                    {{ Form::text('nombre', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: The Last Game','v-model'=>'nombre','autocomplete'=>'off'])}}
                    <span v-if="errores.nombre" :class="['label label-danger']">@{{ errores.nombre[0] }}</span>
                </div>


                <template v-for="(input, index) in inputs">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <label class="control-label">*Tipo Facilitador @{{index+1}}</label>
                        <select v-model="input.becario" class="sisbeca-input" name="">
                            <option v-for="opcion in tipofacilitadores" v-bind:value="opcion.value">
                                @{{ opcion.text }}
                            </option>
                        </select>
                        <!-- @{{ input.becario }} -->
                    </div>

                    <template v-if="input.becario==='no'">
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            <label class="control-label">*Nombre y Apellido @{{index+1}}</label>
                            <div class="input-group">
                                <input type="text" v-model="input.nombre" class="sisbeca-input form-control" placeholder="John Doe" autocomplete="off">
                                <div class="input-group-append">
                                    <template v-if="inputs.length!=1">
                                        <button @click="eliminar(index)" class="btn sisbeca-btn-default-especial" type="button"><i class="fa fa-trash"></i></button>
                                    </template>
                                    <template v-else>
                                        <button @click="eliminar(index)" class="btn sisbeca-btn-default-especial" type="button" disabled="disabled"><i class="fa fa-trash"></i></button>
                                    </template>
                                </div>
                            </div>
                            <!-- @{{ input.nombre }}-->
                        </div>
                    </template>
                    <template v-else>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                           
                            <label class="control-label">*Seleccione becario @{{index+1}}</label>

                            <select v-model="input.id" class="custom-select sisbeca-select-especial sisbeca-input">
                                <option v-for="becario in becarios" :value="becario.user.id">
                                    @{{ becario.user.name }} @{{ becario.user.last_name }}
                                </option>
                            </select>
                            <!-- @{{ input.id}} -->
                            <!-- @{{ input.nombre }}-->
                            
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <label class="control-label">*Horas Facilitador @{{index+1}}</label>
                            <div class="input-group">
                                <input type="text" class="sisbeca-input form-control" placeholder="1" v-model="input.horas" autocomplete="off">
                                <div class="input-group-append">
                                    <template v-if="inputs.length!=1">
                                        <button @click="eliminar(index)" class="btn sisbeca-btn-default-especial" type="button"><i class="fa fa-trash"></i></button>
                                    </template>
                                    <template v-else>
                                        <button @click="eliminar(index)" class="btn sisbeca-btn-default-especial" type="button" disabled="disabled"><i class="fa fa-trash"></i></button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>
                

                <template v-if="inputs.length==0" >
                    <div class="col-lg-12" style="padding-top: 15px!important;">
                        <div class="alert alert-danger">El @{{actividad.tipo}} no tiene facilitador(es).</div>
                    </div><br>
                </template>

                <template v-if="inputs.length<3">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="text-right">
                            <button @click="anadir" type="button" class="btn sisbeca-btn-primary-especial">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </template>
                

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Modalidad</label>
                    {{ Form::select('modalidad', array('presencial'=>'Presencial','virtual'=>'Virtual'),null,['class' =>'sisbeca-input','v-model'=>'modalidad']) }}
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Nivel</label>
                    {{ Form::select('nivel', array('basico'=>'Básico','intermedio'=>'Intermedio','avanzado'=>'Avanzado','cualquier nivel'=>'Cualquier nivel'),null,['class' =>'sisbeca-input','v-model'=>'nivel']) }}
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">Año académico</label>
                    {{ Form::text('anho_academico', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 3er Año','v-model'=>'anho_academico','autocomplete'=>'off'])}}
                    <span v-if="errores.anho_academico" :class="['label label-danger']">@{{ errores.anho_academico[0] }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Fecha</label>
                    <date-picker class="sisbeca-input" name="fecha" v-model="fecha" placeholder="DD/MM/AAAA" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
                    <span v-if="errores.fecha" :class="['label label-danger']">@{{ errores.fecha[0] }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Hora inicio</label>
                    <date-picker class="sisbeca-input" v-model="hora_inicio" placeholder="HH:MM AA" placeholder="HH:MM AA" :config="{ enableTime: true, enableSeconds: false, noCalendar: true,  dateFormat: 'h:i K'}"></date-picker>
                    <span v-if="errores.hora_inicio" :class="['label label-danger']">@{{ errores.hora_inicio[0] }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Hora fin</label>
                    <date-picker class="sisbeca-input" v-model="hora_fin" placeholder="HH:MM AA" placeholder="HH:MM AA" :config="{ enableTime: true, enableSeconds: false, noCalendar: true,  dateFormat: 'h:i K'}"></date-picker>
                    <span v-if="errores.hora_fin" :class="['label label-danger']">@{{ errores.hora_fin[0] }}</span>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label class="control-label">*Límite participantes</label>
                    {{ Form::text('limite_participantes', null, ['class' => 'sisbeca-input', 'placeholder'=>'EJ: 2','v-model'=>'limite_participantes','autocomplete'=>'off'])}}
                    <span v-if="errores.limite" :class="['label label-danger']">@{{ errores.limite[0] }}</span>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label class="control-label">*Descripción</label>
                    {{ Form::text('descripcion', ($model=='crear') ? null : $actividad->descripcion , ['class' => 'sisbeca-input', 'placeholder'=>'Ingrese descripción','v-model'=>'descripcion','autocomplete'=>'off'])}}
                    <span v-if="errores.descripcion" :class="['label label-danger']">@{{ errores.descripcion[0] }}</span>
                </div>
                
			</div>
		</div>

		<hr>	

		<div class="form-group">
			<div class="row">
				<div class="col-lg-12 text-right" >
                    @if($model=="editar")
					   <a href="{{route('actividad.detalles',$actividad->id)}}" class="btn sisbeca-btn-default">Cancelar</a>
					@else
                        <a href="{{route('actividad.listar')}}" class="btn sisbeca-btn-default">Cancelar</a>
                    @endif

                    <input class="btn sisbeca-btn-primary" type="submit" value="Guardar">
				</div>
			</div>
		</div>		

		{{ Form::close() }}
	</div>

    <!-- Cargando.. -->
    <section class="loading" id="preloader">
        <div>
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
        </div>
    </section>
    <!-- Cargando.. -->
</div>
@endsection

@section('personaljs')

<script>
const app = new Vue({

    components:{DatePicker},
    el: '#app',
    data:
    {
        model:'',
        actividad:[],
        id_actividad:'',
        nombre:'',
        tipo:'chat club',
        modalidad:'presencial',
        nivel:'basico',
        anho_academico:'',
        limite_participantes:'',
        horas_voluntariados:'',
        fecha:'',
        hora_inicio:'',
        hora_fin:'',
        descripcion:'',
        becarios:[],
        inputs: [{
            "becario": 'no',
            "nombre": '',
            "id": '',
            "horas":''}
        ],
        errores:[],
        tipofacilitadores: 
        [
            { text: 'Si es Becario', value: 'si' },
            { text: 'No es Becario', value: 'no' }
        ]
    },
    created: function()
    {
        this.obtenerbecarios();
    },
    mounted()
    {
    },
    methods: 
    {
        actualizarfacilitadores(tipo)
        {
            if(tipo=='taller')
            {
                this.tipofacilitadores = 
                    [{ text: 'No es Becario', value: 'no' }];
            }
            else
            {
                this.tipofacilitadores =
                    [{ text: 'Si es Becario', value: 'si' },
                    { text: 'No es Becario', value: 'no' }];
            }
            this.inputs = 
                [{ "becario": 'no',
                "nombre": '',
                "id": '',
                "horas":''}];
        },
        obtenerbecarios: function()
        {
            $("#preloader").show();
            this.model = '{{$model}}';
            if(this.model==='editar')
            {
                var id = '{{ (empty($actividad->id)) ? 'null': $actividad->id}}';
                var url = '{{route('actividad.obtener',':id')}}';
                url = url.replace(':id', id);
                axios.get(url).then(response => 
                {
                    this.actividad = response.data.actividad;
                    this.inputs = [];
                    var esbecario = '';
                    var id_becario;
                    var nombre;
                    var horas;
                    for (var i = 0; i < this.actividad.facilitadores.length; i++)
                    {
                        if(this.actividad.facilitadores[i].becario_id!=null)
                        {
                            esbecario = "si";
                            id_becario = this.actividad.facilitadores[i].becario_id;
                            nombre = '';
                            horas = this.actividad.facilitadores[i].horas;
                        }
                        else
                        {
                            esbecario = "no";
                            id_becario = '';
                            nombre = this.actividad.facilitadores[i].nombreyapellido;
                            horas = 0;
                        }
                        this.inputs.push({
                            "becario": esbecario,
                            "nombre": nombre,
                            "id": id_becario,
                            "horas": horas
                        });
                    }
                    this.nombre = this.actividad.nombre;
                    this.tipo = this.actividad.tipo;
                    this.modalidad =  this.actividad.modalidad;
                    this.nivel = this.actividad.nivel;
                    this.anho_academico = this.actividad.anho_academico;
                    this.limite_participantes = this.actividad.limite_participantes;
                    this.horas_voluntariados = this.actividad.horas_voluntariado;
                    this.descripcion = this.actividad.descripcion;
                    var dia = new Date (this.actividad.fecha);
                    this.fecha = moment(dia).format('DD/MM/YYYY');
                    var dia = new Date ("2030-11-11 "+this.actividad.hora_inicio);
                    this.hora_inicio = moment(dia).format('hh:mm A');
                    var dia = new Date ("2030-11-11 "+this.actividad.hora_fin);
                    this.hora_fin = moment(dia).format('hh:mm A');
                   
                });
            }
            var url = '{{route('actividad.obtenerbecarios')}}';
            axios.get(url).then(response => 
            {
                $("#preloader").hide();
                this.becarios = response.data.becarios;
            });
        },
        anadir() 
        {
            if(this.inputs.length<3)
            {
                this.inputs.push({
                    "becario": 'no',
                    "nombre": '',
                    "id": '',
                    "horas":''
                })
            }
        },
        eliminar(index)
        {
            this.inputs.splice(index,1)
        },
        guardaractividad()
        {
            $("#preloader").show();
            //------------------Guardar actividad
            var dia = new Date ("2030-11-11 "+this.hora_inicio);
            this.hora_inicio = moment(dia).format('hh:mm A');
            var dia = new Date ("2030-11-11 "+this.hora_fin);
            this.hora_fin = moment(dia).format('hh:mm A');
            var url = '{{route('actividad.guardar')}}';
            let data = JSON.stringify({
                facilitadores: this.inputs,
                nombre: this.nombre,
                tipo: this.tipo,
                modalidad:this.modalidad,
                nivel:this.nivel,
                anho_academico:this.anho_academico,
                limite:this.limite_participantes,
                horas:this.horas_voluntariados,
                fecha:this.fecha,
                hora_inicio:this.hora_inicio,
                hora_fin:this.hora_fin,
                descripcion:this.descripcion
            });
            axios.post(url,data,{
            headers:
            {
                'Content-Type': 'application/json',
            } 
            }).then(response => 
            {
                this.errores=[];this.nombre='';this.tipo='taller'; this.modalidad='presencial';
                this.nivel='inicio';this.anho_academico='';this.limite_participantes='';
                this.horas_voluntariados='';this.fecha='';this.hora_inicio='';this.hora_fin='';
                this.descripcion='';this.inputs=[];
                this.inputs.push({
                    "becario": 'no',
                    "nombre": '',
                    "id": '',
                    "horas":''
                });
                $("#preloader").hide();
                toastr.success(response.data.success);
            }).catch( error =>
            {
                $("#preloader").hide();
                toastr.error("Disculpe, verifique el formulario");
                this.errores = error.response.data.errors;
            });
            //------------------Guardar actividad
        },
        actualizaractividad: function()
        {
            //------------------Actualizar actividad
            var id = '{{ (empty($actividad->id)) ? 'null': $actividad->id}}';
            var url = "{{ route('actividad.actualizar',':id' ) }}";
            url = url.replace(':id', id);
            var dia = new Date ("2030-11-11 "+this.hora_inicio);
            this.hora_inicio = moment(dia).format('hh:mm A');
            var dia = new Date ("2030-11-11 "+this.hora_fin);
            this.hora_fin = moment(dia).format('hh:mm A');
            let data = JSON.stringify({
                facilitadores: this.inputs,
                nombre: this.nombre,
                tipo: this.tipo,
                modalidad:this.modalidad,
                nivel:this.nivel,
                anho_academico:this.anho_academico,
                limite:this.limite_participantes,
                horas:this.horas_voluntariados,
                fecha:this.fecha,
                hora_inicio:this.hora_inicio,
                hora_fin:this.hora_fin,
                descripcion:this.descripcion
            });
            this.errores=[];
            axios.post(url,data,{
            headers:
            {
                'Content-Type': 'application/json',
            } 
            }).then(response => 
            {
                this.errores=[];
                toastr.success(response.data.success);
                this.obtenerbecarios();
            }).catch( error =>
            {
                toastr.error("Disculpe, verifique el formulario");
                this.errores = error.response.data.errors;
            });
            //------------------Actualizar actividad
        }
    }
});    
</script>

@endsection
