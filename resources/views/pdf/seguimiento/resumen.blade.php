<!DOCTYPE html>
<html>
<head>
    <title>Resumen Becario {{$becario->user->nombreyapellido()}}</title>
    <style type="text/css">
        *
        {
            margin: 0;
            padding: 0;
            font-family: sans-serif, Arial, sans-serif;
            font-size: 10px;
            vertical-align: top;
        }
        html, body
        {
            margin: 10px;
            padding: 5px;
        }
        body
        {
            padding: 10px 0 !important;
            padding-top: 25px !important;
            padding-bottom: 25px !important;
        }
        .wrapper
        {
            width: 100%;
            max-width: 780px;
            min-width: 780px;
            margin: 0 auto !important;
        }
        .col-xs-6
        {
            display: inline-block;
            width: 50%;
            float: left;
        }
        .text-uppercase
        {
            text-transform: uppercase;
        }
        .text-right
        {
            text-align: right;
        }
        .text-center
        {
            text-align: center;
        }
        .text-left
        {
            text-align: left;
        }
        .title
        {
            background: #ccc;
            padding: 5px;
            margin-bottom: 5px;
        }
        .clearfix
        {
            clear: both !important;
            float: none !important;
        }
        table
        {
            width: 100%;
            border-collapse: separate;
        }
        .border-bottom
        {
            border-bottom: 1px solid #ccc;
        }
        .border-top
        {
            border-top: 1px solid #ccc;
        }


        table.table-sisbeca
        {
            margin-top: 4px;
        }
        .table-sisbeca table,  .table-sisbeca th,  .table-sisbeca td {
            border: 1px solid #ccc;
            border-collapse: collapse;
            padding: 0;
            margin: 0;
        }
        .table-sisbeca td,
        .table-sisbeca th
        {
            padding: 5px;
            vertical-align: middle;
        }
        .table-resume
        {
            width: 100%;
        }
        .table-resume th,  .table-resume td {
            border-collapse: collapse;
            padding: 0;
            margin: 0;
        }
        .table-resume td
        {
            text-align: right;
            text-transform: none;
        }
        .table-resume th
        {
            text-align: right;
            text-transform: none;
        }

        body{
            font-family: sans-serif;
        }
        @page {
            margin: 160px 50px;
        }
        header { position: fixed;
            left: 0px;
            right: 0px;
            height: 15px;
            top:10px;
            text-align: center;
        }

        footer {
            position: fixed;
            left: 0px;
            bottom: -5px;
            right: 15px;
            height: 40px;
            border-bottom: 2px solid #ddd;
        }
        .page:after {
            content: 'Página ' counter(page);
        }
        footer table {
            width: 100%;
        }
        footer p {
            text-align: right;
        }
        footer.derecha {
            text-align: right;
        }  
        .cabecera-sisbeca
        {
            background-color: lightgrey !important;
        }
        .linea-sisbeca
        {
            border-bottom:1px solid #eee;
            height: 5px;
            margin-bottom: 5px;
        }
        .ancho-sisbeca
        {
            width: 100px;
        }
    </style>
</head>
<body>

<header>
    <h1 class="text-center"><strong>Resumen Becario: {{ $becario->user->nombreyapellido()}}</strong></h1>
</header>

<footer class="page derecha"></footer>
    
    <table class="table-sisbeca">
        <thead>
            <tr>
                <th colspan="4" style="background-color: #eee">VOLUNTARIADO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left"><strong>Año</strong></td>
                <td class="text-left"><strong>Tipo Voluntariado</strong></td>
                <td class="text-left"><strong>Total</strong></td>
                <td class="text-right"><strong>Horas Voluntariado</strong></td>
            </tr>
            @php ($horas = 0)
            @if($cursos->count()!=0)
                @foreach($voluntariados as $voluntariado)
                @php ($horas = $horas + $voluntariado->horas_voluntariado)
                <tr>
                    <td class="text-left ancho-sisbeca">{{$anho}}</td>
                    <td class="text-left">{{ucwords($voluntariado->tipo_voluntariado)}}</td>
                    <td class="text-left">{{$voluntariado->total_voluntariado}}</td>
                    <td class="text-right">{{$voluntariado->horas_voluntariado}}</td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="4" class="text-center">No hay <strong>voluntariado</strong></td>
            </tr>
            @endif

            @if($actividades_facilitadas[0]->total_actividades!=0)
            <tr>
                @foreach($actividades_facilitadas as $af)
                @php ($horas = $horas + $af->horas_voluntariado)
                    <td class="text-left">{{$anho}}</td>
                    <td class="text-left">Facilitador en {{ucwords($af->tipo)}}</td>
                    <td class="text-left">{{$af->total_actividades }}</td>
                    <td class="text-right">{{$af->horas_voluntariado}}</td>
                @endforeach
            </tr>
            @else
            <tr>
                <td colspan="4" class="text-center">No ha sido facilitador de <strong>ningún chat club</strong></td>
            </tr>
            @endif
            <tr>
                <td colspan="2"></td>
                <td class="text-right"><strong>Total Horas</strong></td>
                <td class="text-right"><strong>{{ $horas }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="table-sisbeca">
        <thead>
            <tr>
                <th colspan="4" style="background-color: #eee">CVA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left"><strong>Año</strong></td>
                <td class="text-left"><strong>Módulo</strong></td>
                <td class="text-left"><strong>Total por Nivel</strong></td>
                <td class="text-right"><strong>Promedio</strong></td>
            </tr>
            @if($cursos->count()!=0)
                @php ($promedio = 0)
                @php ($total_curso = 0)
                @foreach($cursos as $index=>$curso)
                @php ($promedio = $promedio + $curso->promedio_modulo)
                @php ($total_curso = $total_curso + $curso->total_modulo)
                <tr>
                    <td class="text-left ancho-sisbeca">{{$anho}}</td>
                    <td class="text-left">{{$curso->modulo}} nivel</td>
                    <td class="text-left">{{$curso->total_modulo}}</td>
                    <td class="text-right">{{number_format($curso->promedio_modulo, 2, '.', ',')}}</td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="4" class="text-center">No hay <strong>CVA</strong></td>
            </tr>
            @endif
            <tr>    
                <td colspan="2"></td>
                <td class="text-right"><strong>Promedio General</strong></td>
                <td class="text-right">
                    <strong>
                    @if($cursos->count()==0)
                        {{number_format(0, 2, '.', ',')}}
                    @else
                        {{number_format($promedio/($index+1), 2, '.', ',')}}
                    @endif
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table-sisbeca">
        <thead>
            <tr>
                <th colspan="5" style="background-color: #eee">NOTAS ACADÉMICAS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left"><strong>Año</strong></td>
                <td class="text-left"><strong>Número Periodo</strong></td>
                <td class="text-left"><strong>Año Lectivo</strong></td>
                <td class="text-left"><strong>Total Materias</strong></td>
                <td class="text-right"><strong>Promedio</strong></td>
            </tr>
            @php ($promedio_periodo = 0)
            @php ($j = 0)
            @if($periodos->count()!=0)

                @foreach($periodos as $periodo)
                @if($periodo->aval->estatus=='aceptada')
                @php ($j++)
                @php ($promedio_periodo = $promedio_periodo + $periodo->getPromedio())
                <tr>
                    <td class="text-left ancho-sisbeca">{{$anho}}</td>
                    <td class="text-left">
                        {{$periodo->getNumeroPeriodo()}} 
                    </td>
                    <td class="text-left">{{$periodo->anho_lectivo}}</td>
                    <td class="text-left">{{$periodo->getTotalMaterias()}}</td>
                    <td class="text-right">{{$periodo->getPromedio()}}</td>
                </tr>
                @endif
                @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">No hay <strong>periodos</strong></td>
            </tr>
            @endif
            <tr>    
                <td colspan="3"></td>
                <td class="text-right"><strong>Promedio General</strong></td>
                <td class="text-right">
                    <strong>
                    @if($promedio_periodo!=0)
                    {{ number_format($promedio_periodo/($j), 2, '.', ',')}}
                    @else
                    {{ number_format(0, 2, '.', ',')}}
                    @endif
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table-sisbeca">
        <thead>
            <tr>
                <th colspan="4" style="background-color: #eee">TALLER Y CHAT CLUB</th>
            </tr>
        </thead>
        <tbody>
            <!-- ASISTIO -->
            @php ($total_taller=$a_t_p->total_actividades+$a_t_v->total_actividades)
            @php ($total_chat=$a_c_p->total_actividades+$a_c_v->total_actividades)
            @php ($total_presencial=$a_t_p->total_actividades+$a_c_p->total_actividades)
            @php ($total_virtual=$a_t_v->total_actividades+$a_c_v->total_actividades)
            <tr>
                <td class="ancho-sisbeca"><strong>ASISTIO</strong></td>
                <td><strong>Presencial</strong></td>
                <td><strong>Virtual</strong></td>
                <td><strong>Total</strong></td>
            </tr>
            <tr>
                <td class="ancho-sisbeca"><strong>Taller</strong></td>
                <td>{{$a_t_p->total_actividades}}</td>
                <td>{{$a_t_v->total_actividades}}</td>
                <td>{{$total_taller}}</td>
            </tr>
            <tr>
                <td class="ancho-sisbeca"><strong>Chat Club</strong></td>
                <td>{{$a_c_p->total_actividades}} </td>
                <td>{{$a_c_v->total_actividades}} </td>
                <td>{{$total_chat}}</td>
            </tr>
            <tr>
                <td class="ancho-sisbeca"><strong>Total</strong></td>
                <td>{{$total_presencial}}</td>
                <td>{{$total_virtual}}</td>
                <td>
                    <strong>
                    {{$a_t_p->total_actividades+$a_t_v->total_actividades
                    +$a_c_p->total_actividades+$a_c_v->total_actividades}}
                    </strong>
                </td>
            </tr>
            <!-- ASISTIO -->

            <!-- NO ASISTIO -->
            @php ($total_n_taller=$na_t_p->total_actividades+$na_t_v->total_actividades)
            @php ($total_n_chat=$na_c_p->total_actividades+$na_c_v->total_actividades)
            @php ($total_n_presencial=$na_t_p->total_actividades+$na_c_p->total_actividades)
            @php ($total_n_virtual=$na_t_v->total_actividades+$na_c_v->total_actividades)
            <tr>
                <td class="ancho-sisbeca"><strong>NO ASISTIO</strong></td>
                <td><strong>Presencial</strong></td>
                <td><strong>Virtual</strong></td>
                <td><strong>Total</strong></td>
            </tr>
            <tr>
                <td class="ancho-sisbeca"><strong>Taller</strong></td>
                <td>{{$na_t_p->total_actividades}}</td>
                <td>{{$na_t_v->total_actividades}}</td>
                <td>{{$total_n_taller}}</td>
            </tr>
            <tr>
                <td class="ancho-sisbeca"><strong>Chat Club</strong></td>
                <td>{{$na_c_p->total_actividades}} </td>
                <td>{{$na_c_v->total_actividades}} </td>
                <td>{{$total_n_chat}}</td>
            </tr>
            <tr>
                <td class="ancho-sisbeca"><strong>Total</strong></td>
                <td>{{$total_n_presencial}}</td>
                <td>{{$total_n_virtual}}</td>
                <td>
                    <strong>
                    {{$na_t_p->total_actividades+$na_t_v->total_actividades
                    +$na_c_p->total_actividades+$na_c_v->total_actividades}}
                    </strong>
                </td>
            </tr>
            <!-- NO ASISTIO -->
        </tbody>
    </table>

    <div class="linea-sisbeca">
    </div>

    <p class="text-right">Generado el {{date("d/m/Y h:i a", strtotime(date('Y-m-d H:i:s')))}}</p>
</body>
</html>
