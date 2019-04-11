<!DOCTYPE html>
<html>
<head>
    <title>Carta al Banco...</title>
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
            margin: 25px;
            margin-bottom: 0px !important;
            padding: 25px;
        }
        body
        {
            padding: 10px 0 !important;
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
            border-collapse: collapse;
        }
        .border-bottom
        {
            border-bottom: 1px solid #ccc;
        }
        .border-top
        {
            border-top: 1px solid #ccc;
        }


        table.table-ads
        {
            margin-top: 4px;
        }
        .table-ads table,  .table-ads th,  .table-ads td {
            border: 1px solid #ccc;
            border-collapse: collapse;
            padding: 0;
            margin: 0;
        }
        .table-ads td,
        .table-ads th
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


        footer {
            position: fixed;
            left: 0px;
            bottom: -5px;
            right: 15px;
            height: 40px;
            padding-top: 1px;
            border-top: 2px solid #ddd;
        }
        .page:after {
            content: 'OBSERVACIONES: Esta Carta Bancaria deberá llevar la certificación por el comite del programa proexcelencia para fines externos.';
        }
        footer table {
            width: 100%;
        }
        footer p {
            text-align: right;

        }
        footer.justify {
            text-align: justify;
            font-weight: bold !important;
        }

    </style>
</head>
<body>
<div class="row " >
    <div class="content col-lg-12">
        <div class="col-lg-4" align="right" style="font-size: 14px!important;">
            <p>Caracas {{date('d')}} de {{$mes}} de {{date('Y')}}</p>


        </div>
<div class="col-lg-4" style="padding-top: 50px;padding-bottom: 50px;font-size: 14px!important;">
<p>Señores</p>
    <p>Banco Mercantil, CA Banco Universal</p>
    <p>Presente.-</p>

</div>

</div>

</div>
<p style="font-size: 16px;padding:8px;text-align: justify !important;margin-bottom: 20px">
    Tengo el agrado de dirigirme a ustedes, en mi carácter de responsable de la Administracón del Programa Excelencia
    de la Asociación Venezolano Americana de Amistad, J-00066994-5 empresa debidamente identificada ante el Banco Mercantil, en atención
    a sus instrucciones y en cumplimiento de las normas de banco previstas para cumplir con su Politica
    de "Conozca a su Cliente" Con la finalidad de proporcionarles los nombres y demás datos personales de los becarios
    de la organizaciónque represento, a los fines de que procedan a abrirle la respectiva cuenta de ahorra
    en el Banco Mercantil
</p>
<table class="table-ads">
    <thead>
    <tr>
        <th class="text-left">Apellidos y Nombres</th>
        <th class="text-center">Nacionalidad</th>
        <th class="text-center">N° de Cedula de Identidad</th>
        <th class="text-left">Dirección y Telefono</th>
        <th class="text-center">Fecha de Ingreso a la Empresa</th>
        <th class="text-center">Cargo</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-left">{{$becario->user->last_name.' '.$becario->user->name}}</td>
            <td class="text-center"> Venezolana</td>
            <td class="text-center"> {{$becario->user->cedula}}</td>
            <td class="text-left">{{$becario->direccion_permanente}}<br/>Tlf.: {{$becario->celular}}</td>
            <td class="text-center"></td>
            <td class="text-center">Becario</td>
        </tr>

    </tbody>
</table>

<p style="font-size: 16px;padding:8px;text-align: justify !important;margin-top: 20px">
De la misma forma y nuevamente, en mi caráter de responsable de la Administración del Programa Excelencia
    de la Asociación Venezolano Americana de Amista, J-0066994-5, certifica que los datos
    proporcionados por nuestros becarios y que hacemos llegar a ustedes por ésta via han sido verificados por nuestra organización

</p>
<br/>
<div class="text-center">
    <p style="font-size: 16px;padding:8px;text-align: center !important;font-weight: bold"> </p><br/>
    <p style="font-size: 16px;font-weight: bold">N° de Cuenta Matriz:0105-0034-66-1034255894</p>
</div>
<br/>
<br/>
<br/>
<br/>
<div class="text-center">
<p style="font-size: 16px;padding:8px;text-align: center !important">
    Agradeciendo de antemano la atención que sirva prestar a la presente
</p>
</div>
<br/>
<br/>

<div class="text-center">
<p style="font-size: 16px;padding:8px;text-align: center !important;margin-top: 80px !important; ">
______________________________
</p>
</div>
<div class="text-center">
    <p style="font-size: 16px;font-weight: bold;padding:0px;text-align: center !important;margin-top: 10px">
        Gerente de Programas Educativos
    </p>
    <p style="font-size: 14px;padding:0px !important;margin: 0px !important;text-align: center !important;">
        DIRECCION
    </p>
    <p style="font-size: 14px;padding:0px !important;margin: 0px !important;text-align: center !important;">
        CORREO / <a style="font-size: 14px !important;" href="http://www.avaa.com">www.avaa.com</a>
    </p>
</div>


<footer class="page justify"></footer>

</body>
</html>

