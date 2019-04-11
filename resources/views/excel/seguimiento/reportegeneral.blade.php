<table>
    <thead>
        <tr>
            <th colspan="10">REPORTE GENERAL {{$mes_completo}} - {{$anho}}</th>
        </tr>
        <tr>
            <td style="background-color: #eeeeee">#</td>
            <td style="background-color: #eeeeee">Nombres y Apellidos</td>
            <td style="background-color: #eeeeee">Cédula</td>
            <td style="background-color: #eeeeee">Año/Sem./Trim.</td>
            <td style="background-color: #eeeeee">H. Vol.</td>
            <td style="background-color: #eeeeee"># Taller</td>
            <td style="background-color: #eeeeee"># Chat</td>
            <td style="background-color: #eeeeee">CVA</td>
            <td style="background-color: #eeeeee">AVG CVA</td>
            <td style="background-color: #eeeeee">AVG Acad.</td>
        </tr>
    </thead>
    <tbody>
        @foreach($todos as $index => $item)
            <tr>
                <td>
                    {{$index+1}}
                </td>
                <td>
                    {{$item['becario']['nombreyapellido']}}
                </td>
                 <td>
                    {{$item['becario']['cedula']}}
                </td>
                <td>
                    {{$item['nivel_carrera']}}
                </td>
                <td>
                    {{$item['horas_voluntariados']}}
                </td>
                <td>
                    {{$item['asistio_t']}}
                </td>
                <td>
                    {{$item['asistio_cc']}}
                </td>
                <td>
                    {{$item['nivel_cva']}}
                </td>
                <td>
                    {{$item['avg_cva']}}
                </td>
                <td>
                    {{$item['avg_academico']}}
                </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="10">Generado el  {{date("d/m/Y h:i a", strtotime(date('Y-m-d H:i:s')))}}</td>
        </tr>
    </tbody>
</table>
