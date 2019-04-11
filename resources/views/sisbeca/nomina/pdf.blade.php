<!DOCTYPE html>
<html>
<head>
    <title>PDF ...</title>
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
      margin: 5px;
      padding: 5px;
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


    </style>
</head>
<body>
    <header>

        <h1 class="text-center"><strong>Nómina de: {{ date($mes).'-'.$anho }}</strong></h1>

    </header>

    <footer class="page derecha"></footer>
    <table class="table-ads">
        <thead style="background-color: lightgrey !important; ">
            <tr>
                <th class="text-left">Nombre y Apellido</th>
                <th class="text-right">Retroactivo</th>
                <th class="text-right">Monto Libros</th>
                <th class="text-right">Estipendio Base</th>
                <th class="text-right" style="background-color: rgba(113,113,113,0.1);">Total</th>
                <th class="text-center">Fecha Pago</th>
                <th class="text-center">Fecha Generada</th>
            </tr>                         
        </thead>
        <tbody>
            @foreach($nominas as $nomina)
            <tr>
                <td class="text-left"> {{  $nomina->datos_nombres.' '.$nomina->datos_apellidos}} </td>
                <td class="text-right">{{ number_format($nomina->retroactivo, 2, ',', '.')}} </td>
                <td class="text-right">{{ number_format($nomina->monto_libros, 2, ',', '.')}}</td>
                <td class="text-right">{{ number_format($nomina->sueldo_base, 2, ',', '.') }}</td>
                <td class="text-right"style="background-color: rgba(113,113,113,0.1);">{{ number_format($nomina->total, 2, ',', '.') }}</td>
                <td class="text-center">{{ $nomina->getFechaPago() }}</td>
                <td class="text-center">{{ $nomina->getFechaGenerada()}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
   
