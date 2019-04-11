<!DOCTYPE html>
<html>
<head>
    <title>Lista de Asistentes</title>
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
    <h1 class="text-center"><strong>AVAA</strong></h1>
</header>

<footer class="page derecha"></footer>
    
    <table class="table-sisbeca">
        <thead class="cabecera-sisbeca">
            <tr>
                <th class="text-left" colspan="2">
                    Información General
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="text-left ancho-sisbeca">
                    {{ $actividad->getTipo()}}
                </th>
                <td class="text-left">
                    {{ $actividad->nombre }}
                </td>
            </tr>
            <tr>
                <th class="text-left ancho-sisbeca">Fecha</th>
                <td class="text-left">
                    {{ $actividad->getDiaFecha()}}, {{ $actividad->getFecha() }}
                </td>
            </tr>
            <tr>
                <th class="text-left ancho-sisbeca">Hora</th>
                <td class="text-left">
                     {{ $actividad->getHoraInicio() }} a 
                     {{ $actividad->getHoraFin() }}
                </td>
            </tr> 
            
            <tr>
                <th class="text-left ancho-sisbeca">Modalidad</th>
                <td class="text-left">
                    {{ $actividad->getModalidad() }}
                </td>
            </tr>
            <tr>
                <th class="text-left ancho-sisbeca">Nivel</th>
                <td class="text-left">
                    {{ $actividad->getNivel() }}
                </td>
            </tr> 
            <tr>
                <th class="text-left ancho-sisbeca">Año Académico</th>
                <td class="text-left">
                     {{ $actividad->anho_academico }}
                </td>
            </tr>
            <tr>
                <th class="text-left ancho-sisbeca">
                    Estatus {{ $actividad->getTipo() }}
                </th>
                <td class="text-left">
                    {{ $actividad->getEstatus() }}
                </td>
            </tr>
            <tr>
                <th class="text-left ancho-sisbeca">Límite Participantes</th>
                <td class="text-left">
                     {{ $actividad->limite_participantes }}
                </td>
            </tr>
            <tr>
                <th class="text-left ancho-sisbeca">Descripción</th>
                <td class="text-left">
                     {{ $actividad->descripcion }}
                </td>
            </tr>
            
        </tbody>
    </table>
    <br>
    <table class="table-sisbeca">
        <thead class="cabecera-sisbeca">
            <tr>
                <th class="text-left" colspan="3">
                    Facilitador(es)
                </th>
            </tr>                  
        </thead>
        <tbody>
            <tr>
                <th class="text-center">#</th>
                <th class="text-left">Tipo Facilitador</th>
                <th class="text-left">Nombre y Apellido</th>
            </tr>
            @foreach($facilitadores as $index => $facilitador)
            <tr>
                <td class="text-center" style="width: 25px;">
                    {{$index+1}}
                </td>
                <td class="text-left">
                    @if($facilitador->becario_id==null)
                        Es Becario
                    @else
                        No es Becario
                    @endif
                </td>
                <td class="text-left">
                    @if($facilitador->becario_id==null)
                        {{ $facilitador->nombreyapellido}}
                    @else
                        {{ $facilitador->user->nombreyapellido()}}  
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="linea-sisbeca">
    </div>
    <p class="text-right"> {{ $facilitadores->count() }} facilitador(es)</p>
    <br>
    <table class="table-sisbeca">
        <thead class="cabecera-sisbeca">
            <tr>
                <th class="text-left" colspan="5">
                    Becarios Inscritos
                </th>
            </tr>                  
        </thead>
        <tbody>
            <tr>
                <th class="text-center">#</th>
                <th class="text-left">Nombres y Apellidos</th>
                <th class="text-center">Cédula</th>
                <th class="text-center">Estatus</th>
                <th class="text-center">Firma</th>
            </tr>
            @foreach($becarios as $index => $becario)
            <tr>
                <td class="text-center" style="width: 25px;">
                    {{$index+1}}
                </td>
                <td class="text-left">
                    {{ $becario->user->nombreyapellido()}}
                </td>
                <td class="text-center" style="width: 50px;">
                    {{ $becario->user->cedula}}
                </td>
                <td class="text-center">
                    @if($becario->estatus=='asistira')
                        ASISTIRÁ
                    @else
                        @if($becario->estatus=='asistio')
                            ASISTIÓ
                        @else
                            @if($becario->estatus=='no asistio')
                                NO ASISTIÓ
                            @else
                                @if($becario->estatus=='lista de espera')
                                    LISTA DE ESPERA
                                @else
                                    JUSTIFICACIÓN CARGADA
                                @endif
                            @endif
                        @endif
                    @endif
                </td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="linea-sisbeca">
    </div>
    <p class="text-right"> {{ $becarios->count() }} becario(s)</p>
</body>
</html>
   
