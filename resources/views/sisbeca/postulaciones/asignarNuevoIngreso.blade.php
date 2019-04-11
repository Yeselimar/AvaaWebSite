@extends('sisbeca.layouts.main')
@section('title','Asignar los Nuevos Becarios')
@section('content')

<div class="col-12" id="app">
    <button v-b-popover.hover="'Asignar Fecha de Bienvenida a Todos por Igual'" class="btn btn-sm sisbeca-btn-primary pull-right mb-3" @click.prevent="mostrarModalBienvenidaTodos()">
        <i class="fa fa-calendar"></i> Asignar Fecha de Bienvenida
    </button>
    <br>
    <div v-if="showflash" class="mt-3 alert  alert-danger alert-important" role="alert">
        El postulante seleccionado ha sido rechazado exitosamente, sus datos ahora <b>sólo estan disponibles</b> en el menú lateral izquierdo en el apartado <b>Postulaciones> Becarios> Ver Rechazados.</b>
    </div>
    <h3 class="letras-verdes">Postulantes Candidatos a Becario:</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
             <!--    table table-bordered table-striped -->
            <thead>
                <tr>
                    <th class="text-center">Estatus</th>
                    <th class="text-center">Postulante</th>
                    <th class="text-center">Cédula</th>
                    <th class="text-center">Entrevistadores</th>
                    <th class="text-center">Fecha Entrevista</th>
                    <th class="text-center">Reunión Bienvenida</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="postulante in postulantes">
                <template v-if="postulante.status!='rechazado'">
                    <td class="text-center">
                        <template v-if="postulante.status=='activo'">
                            <span class="label label-success">Aprobado</span>
                        </template>
                        <template v-else-if="postulante.status=='entrevistado'">
                            <span class="label label-success">E.Aprobada</span>
                        </template>
                        <template v-else-if="postulante.status=='rechazado'">
                            <span class="label label-danger">Rechazado</span>
                        </template>
                    </td>
                    <td class="text-center">@{{postulante.user.name}} @{{postulante.user.last_name}} </td>
                    <td class="text-center">@{{postulante.user.cedula}}</td>
                    <td class="text-center">
                        <template v-if="postulante.entrevistadores.length!=0">
                            <div v-for="(entrevistador,index) in postulante.entrevistadores" :key="index">
                                @{{ entrevistador.name}} @{{ entrevistador.last_name}}<span v-show="index<postulante.entrevistadores.length-1">,</span>
                                <br>
                            </div>
                        </template>
                        <template v-else>
                            <span class="label label-default">Sin Entrevistadores</span>&nbsp;
                        </template>
                    </td>
                    <td class="text-center">
                        <template v-if="postulante.fecha_entrevista">
                            @{{fechaformatear(postulante.fecha_entrevista)}}
                        </template>
                        <template v-else>
                            <span class="label label-default">Sin fecha</span>
                        </template>
                    </td>
                    <td class="text-center">
                        <template v-if="postulante.fecha_bienvenida">
                            @{{fechaformatear(postulante.fecha_bienvenida)}}
                            <button v-b-popover.hover="'Editar Reunión de Bienvenida'" class="btn btn-xs sisbeca-btn-primary" @click.prevent="mostrarModalBienvenida(postulante)" data-target="modal-fecha-bienvenida" >
                                <i class="fa fa-edit"></i>
                            </button>
                        </template>
                        <template v-else>
                            <button v-b-popover.hover="'Asignar Fecha de Bienvenida'" class="btn btn-xs sisbeca-btn-primary" @click.prevent="mostrarModalBienvenida(postulante)" data-target="modal-fecha-bienvenida" >
                                <i class="fa fa-calendar"></i> Asignar Fecha
                            </button>
                        </template>
                    </td>
                    <td>
                    <template v-if="(postulante.status==='activo')">
                        <button class="btn btn-xs sisbeca-btn-success disabled">
                        <i class="fa fa-check" data-target="modal-asignar"></i>
                        </button>
                        <button class="btn btn-xs sisbeca-btn-default disabled">
                        <i class="fa fa-times" data-target="modal-asignar"></i>
                        </button>
                    </template>
                    <template v-else>
                        <button class="btn btn-xs sisbeca-btn-success" @click.prevent="mostrarModal(postulante,postulante.imagenes,1)">
                        <i class="fa fa-check" data-target="modal-asignar"></i>
                        </button>
                        <button class="btn btn-xs sisbeca-btn-default" @click.prevent="mostrarModal(postulante,postulante.imagenes,0)">
                        <i class="fa fa-times" data-target="modal-asignar"></i>
                        </button>
                    </template>
                    <template>
                        <a :href="getRutaVerPerfil(postulante.user.id)" class="btn btn-xs sisbeca-btn-primary"> <i class="fa fa-eye"></i></a>
                    </template>
                    <template v-if="postulante.status=='rechazado'">
                        <button  v-b-popover.hover="'Eliminar postulante'" class="btn btn-xs sisbeca-btn-default" @click="modalEliminarPostulante(postulante)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </template>
                    <template v-else>
                        <button  v-b-popover.hover="'Eliminar postulante'" class="btn btn-xs sisbeca-btn-default" disabled="disabled" @click="modalEliminarPostulante(postulante)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </template>
                    </td>
                </template>
                </tr>
                <tr v-if="postulantes.length==0">
                    <td colspan="7" class="text-center">
                        No hay <strong>postulantes con entrevista Aprobada</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="linea"></div>
    <br>

   <br>

    <!-- Modal para eliminar al postulante becario -->
    <div class="modal fade" id="eliminarpostulante">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><strong>Eliminar Postulante</strong></h5>
                    <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <br>
                        <p class="h6 text-center">
                            ¿Está seguro que desea <strong>eliminar</strong> al postulante becario <strong>@{{eliminar_postulante}}</strong> de manera permanente?
                        </p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-sm sisbeca-btn-primary pull-right" @click="eliminarPostulante(eliminar_id)">Sí</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para eliminar al postulante becario -->

    <!-- Modal para aprobar becario -->
    <form method="POST" @submit.prevent="veredictopostulantesbecarios(id)">
        {{ csrf_field() }}
        <div class="modal fade" id="modal-asignar">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pull-left"><strong>Decisión de la Postulación</strong></h5>
                        <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                    </div>
                    <div class="modal-body text-center">
                    <br>

                    <div class="panel panel-default">
                        <div class="panel-heading"> @{{nombreyapellido}}</div>
                        <div class="row panel-body">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <img class="img-responsive w-50 m-b-20" :src="imagen_postulante">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                    <div>Edad: @{{edad}}</div>
                                    <div><b>Promedio Universitario:</b> @{{promedio}}</div>
                                    <div><b>Observación:</b> @{{observacion}}</div>

                            </div>
                        </div>
                    </div>
                    <template v-if="funcion==='Aprobar'">
                    <div class="panel-footer"><h5>¿Está seguro que desea <strong class="letras-verdes">@{{funcion}}</strong> a <strong>@{{nombreyapellido}}</strong> como Becario de ProExcelencia?</h5>
                    </div>
                    </template>
                    <template v-else>
                        <h5>¿Está seguro que desea <strong class="letras-rojas">@{{funcion}}</strong> a @{{nombreyapellido}} Becario de ProExcelencia?</h5>
                    </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cancelar</button>
                        <button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal para aprobar becario -->

    <!-- Modal para fecha de Bienvenida -->
    <form method="POST" @submit.prevent="fechadebienvenida(id)">
        {{ csrf_field() }}
        <div class="modal fade" id="modal-fecha-bienvenida">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pull-left"><strong>Datos de la Reunión de Bienvenida</strong></h5>
                        <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
                                <label class="control-label " style="margin-bottom: 0px !important">Fecha</label>
                                <date-picker data-trigger="focus" class="sisbeca-input input-sm" name="fecha" v-model="fecha" placeholder="Seleccione la Fecha" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
                                <label class="control-label " for="lugar" style="margin-bottom: 0px !important">Lugar</label>
                                    <input type="text" name="lugar" v-model="lugar" class="sisbeca-input input-sm" placeholder="Los Ruices">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
                            <label class="control-label " style="margin-bottom: 0px !important">Hora</label>
                            <date-picker class="sisbeca-input input-sm" v-model="hora" placeholder="HH:MM AA" placeholder="Seleccione la Hora" :config="{ enableTime: true, enableSeconds: false, noCalendar: true,  dateFormat: 'h:i K'}"></date-picker>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px">
                            </div>
                        </div>
                        <div class="modal-footer" style="border">
                            <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cancelar</button>
                            <button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal para fecha de Bienvenida -->

    <!-- Modal para fecha de Bienvenida Todos -->
    <form method="POST" @submit.prevent="fechadebienvenidaparatodos()">
        {{ csrf_field() }}
        <div class="modal fade" id="modal-fecha-bienvenida-todos">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pull-left"><strong>Datos de la Reunión de Bienvenida</strong></h5>
                        <a class="pull-right mr-1" href="javascript(0)" data-dismiss="modal" ><i class="fa fa-remove"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="alert  alert-warning alert-important" role="alert">
                                    Atención: Estos cambios se aplicarán a todos los postulantes por igual.
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
                                <label class="control-label " style="margin-bottom: 0px !important">Fecha</label>
                                <date-picker data-trigger="focus" class="sisbeca-input input-sm" name="fecha" v-model="fecha" placeholder="Seleccione la Fecha" :config="{ enableTime: false , dateFormat: 'd/m/Y'}"></date-picker>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
                                <label class="control-label " for="lugar" style="margin-bottom: 0px !important">Lugar</label>
                                    <input type="text" name="lugar" v-model="lugar" class="sisbeca-input input-sm" placeholder="Los Ruices">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px !important">
                            <label class="control-label " style="margin-bottom: 0px !important">Hora</label>
                            <date-picker class="sisbeca-input input-sm" v-model="hora" placeholder="HH:MM AA" placeholder="Seleccione la Hora" :config="{ enableTime: true, enableSeconds: false, noCalendar: true,  dateFormat: 'h:i K'}"></date-picker>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-bottom: 0px">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm sisbeca-btn-default pull-right" data-dismiss="modal" >Cancelar</button>
                            <button type="submit" class="btn btn-sm sisbeca-btn-primary pull-right">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal para fecha de Bienvenida Todos -->


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
        created: function()
        {
            this.obtenerpostulantes();
        },
        data:
        {
            eliminar_postulante:'',
            eliminar_id:0,
            postulante:'',
            imagenes:[],
            imagen_postulante:'',
            id:'0',
            funcion:'',
            nombreyapellido:'',
            fecha:'',
            hora:'',
            lugar:'',
            edad:'',
            promedio:'',
            observacion:'',
            postulantes:[],
            rechazados:[],
            contador:0,
            showflash: false,
        },
        methods:
        {
            getRutaVerPerfil: function(id)
            {
                //var url = "{{route('entrevistador.documentoconjunto',array('id'=>':id'))}}";
                var url ="{{route('perfilPostulanteBecario', array('id'=>':id'))}}"
                url = url.replace(':id', id);
                return url;
            },
            getRutaVerPerfilRechazado: function(id)
            {
                var url ="{{route('perfilPostulanteRechazado', array('id'=>':id'))}}"
                url = url.replace(':id', id);
                return url;

            },
            obtenerpostulantes:function()
            {
                var url = '{{route('postulantes.entrevistados')}}';
                $("#preloader").show();
                axios.get(url).then(response =>
                {
                    this.postulantes = response.data.postulantes;
                    this.rechazados = response.data.rechazados;
                    $("#preloader").hide();
                });
            },
            veredictopostulantesbecarios:function(id)
            {
                var url = '{{route('veredicto.postulantes.becarios')}}';
                $('#modal-asignar').modal('hide');
                $("#preloader").show();
                axios.post(url,{
                    id:this.id,
                    funcion:this.funcion
                    }).then(response=>{
                    this.obtenerpostulantes();

                    $("#preloader").hide();
                    console.log(this.funcion)
                    if(this.funcion=='Rechazar'){
                    this.showflash=true
                    }
                    if(this.funcion=='Aprobar'){
                     this.showflash=false
                    }

                    toastr.success(response.data.success);
                });
            },
            mostrarModal:function(postulante,imagenes,funcion)
		    {
                if(funcion=='1'){
                    this.funcion='Aprobar';
                }else if(funcion=='0'){
                    this.funcion='Rechazar';
                }
                this.id = postulante.user_id;
                this.nombreyapellido = postulante.user.name+' '+postulante.user.last_name;
                this.edad = postulante.user.edad;
                this.observacion = postulante.observacion_privada;
                this.promedio = postulante.promedio_universidad;
                for (var i = 0; i < imagenes.length; i++)
                {
                    if(imagenes[i].titulo=='fotografia')
                    {
                        this.imagen_postulante=imagenes[i].url;
                        console.log(this.imagen_postulante);
                    }
                }
                $('#modal-asignar').modal('show');
		    },
            mostrarModalBienvenida:function(postulante)
            {
                this.id = postulante.user_id;
                // console.log('ID:' , postulante.user_id);
                $('#modal-fecha-bienvenida').modal('show');
            },
            fechadebienvenida: function(id)
            {
                var url ='{{route('asignar.fecha.bienvenida')}}';
                $('#modal-fecha-bienvenida').modal('hide');
                $("#preloader").show();
                axios.post(url,{
                id:this.id,
                fecha:this.fecha,
                lugar:this.lugar,
                hora:this.hora,
                }).then(response =>
                {
                    //$('#modal-fecha-bienvenida').modal('hide');
                    $("#preloader").hide();
                    this.obtenerpostulantes();
                    toastr.success(response.data.success);
                });
            },
            mostrarModalBienvenidaTodos:function(postulantes)
            {
                $('#modal-fecha-bienvenida-todos').modal('show');
            },
            fechadebienvenidaparatodos: function()
            {
                /* this.hora = $('#hora').val();
                this.fecha = $('#fecha').val();*/
                var url = '{{route('asignar.fecha.bienvenida.todos')}}';
                let data = JSON.stringify({
                    //postulantes: this.postulantes,
                    lugar: this.lugar,
                    fecha: this.fecha,
                    hora: this.hora,
                });
                axios.post(url,data,{
                    headers:
                    {
                        'Content-Type': 'application/json',
                    }
                }).then(response =>
                {
                    $('#modal-fecha-bienvenida-todos').modal('hide');
                    this.obtenerpostulantes();
                    toastr.success(response.data.success);
                });
            },
            modalEliminarPostulante(postulante)
            {
                this.eliminar_postulante=postulante.user.name+' '+postulante.user.last_name;
                this.eliminar_id = postulante.user.id;
                $('#eliminarpostulante').modal('show');
            },
            eliminarPostulante(eliminar_id)
            {
                var url = '{{route('postulante.eliminar',':id')}}';
                url = url.replace(':id', eliminar_id);
                $('#eliminarpostulante').modal('hide');
                console.log(url);
                $("#preloader").show();
                axios.get(url).then(response =>
                {
                    this.obtenerpostulantes()
                    $("#preloader").hide();
                    toastr.success(response.data.success);
                });
            },
            zfill: function(number, width)
            {
                var numberOutput = Math.abs(number); /* Valor absoluto del número */
                var length = number.toString().length; /* Largo del número */
                var zero = "0"; /* String de cero */

                if (width <= length)
                {
                    if (number < 0)
                    {
                        return ("-" + numberOutput.toString());
                    }
                    else
                    {
                        return numberOutput.toString();
                    }
                }
                else
                {
                    if (number < 0)
                    {
                        return ("-" + (zero.repeat(width - length)) + numberOutput.toString());
                    }
                    else
                    {
                        return ((zero.repeat(width - length)) + numberOutput.toString());
                    }
                }
            },
            fechaformatear: function (fecha)
            {
                var d = new Date(fecha);
                var dia = d.getDate();
                var mes = d.getMonth() + 1;
                var anho = d.getFullYear();
                var fecha = this.zfill(dia,2) + "/" + this.zfill(mes,2) + "/" + anho;
                return fecha;
            },
        }
    });
</script>


 @endsection



