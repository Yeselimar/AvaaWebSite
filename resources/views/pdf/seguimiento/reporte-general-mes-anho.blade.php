<!DOCTYPE html>
<html>
<head>
    <title>Reporte General - {{$mes_completo}}-{{$anho}}</title>
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
    <h1 class="text-center">
        <strong>
        AVAA - Asociación Venezolano Americana de Amistad
        </strong>
    </h1>
</header>

<footer class="page derecha"></footer>
    
    <table class="table-sisbeca">
        <thead>
            <tr>
                <th class="text-center" style="background-color: #eee">
                    REPORTE GENERAL: {{$mes_completo}}-{{$anho}}
                </th>
            </tr>
        </thead>
    </table>

    <table class="table-sisbeca">
        <thead>
            <tr>
                <td class="text-center">#</td>
                <td class="text-left"><strong>Becario</strong></td>
                <td class="text-center"><strong>Año/Sem./Trim.</strong></td>
                <td class="text-center"><strong>H. Vol.</strong></td>
                <td class="text-center"><strong># Taller</strong></td>
                <td class="text-center"><strong># Chat</strong></td>
                <td class="text-center"><strong>CVA</strong></td>
                <td class="text-center"><strong>AVG CVA</strong></td>
                <td class="text-center"><strong>AVG Acad.</strong></td>
            </tr>
        </thead>
        <tbody>
            @foreach($todos as $index => $item)
            <tr>
                <td class="text-center" style="width: 25px">
                    {{$index+1}}
                </td>
                <td class="text-left">
                    {{$item['becario']['nombreyapellido']}}
                </td>
                <td class="text-center" style="width: 100px">
                    {{$item['nivel_carrera']}}
                </td>
                <td class="text-center">
                    {{$item['horas_voluntariados']}}
                </td>
                <td class="text-center">
                    {{$item['asistio_t']}}
                </td>
                <td class="text-center">
                    {{$item['asistio_cc']}}
                </td>
                <td class="text-center">
                    {{$item['nivel_cva']}}
                </td>
                <td class="text-center">
                    {{$item['avg_cva']}}
                </td>
                <td class="text-center">
                    {{$item['avg_academico']}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

<div class="linea-sisbeca">
    </div>

    <p class="text-right">Generado el {{date("d/m/Y h:i a", strtotime(date('Y-m-d H:i:s')))}}</p>
</body>
</html>
