 <table >
    <thead>
        <tr>
            <td colspan="7">NÓMINA GENERADA: {{$mes_completo}}-{{$anho}}</td>
        </tr>
        <tr>
            <td style="background-color: #eeeeee">#</td>
            <td style="background-color: #eeeeee">Nombres</td>
            <td style="background-color: #eeeeee">Apellidos</td>
            <td style="background-color: #eeeeee">Cédula</td>
            <td style="background-color: #eeeeee">N° Cuenta</td>
            <th style="background-color: #eeeeee">CVA</th>
            <th style="background-color: #eeeeee">Facturas Libros</th>
            <th style="background-color: #eeeeee">Retroactivo</th>
            <th style="background-color: #eeeeee">Estipendio</th>
            <th style="background-color: #eeeeee">Total a Pagar</th>
            <th style="background-color: #eeeeee">Beca Aprobada</th>
            <th style="background-color: #eeeeee">Ingreso</th>
            <th style="background-color: #eeeeee">Egreso</th>
        </tr>                         
    </thead>
    <tbody>
        @for($i=0;$i<$nominas->count();$i++)
        <tr>
            <td>
                {{ $i+1}}
            </td>
            <td> {{ $nominas[$i]->datos_nombres}} </td>
            <td> {{ $nominas[$i]->datos_apellidos }} </td>
            <td> {{ $nominas[$i]->datos_cedula }} </td>
            @if( $nominas[$i]->datos_cuenta != null)
                <td> {{ $nominas[$i]->datos_cuenta }} </td>
            @else
                <td>SIN NÚMERO DE CUENTA</td>
            @endif
            <td>{{number_format($nominas[$i]->cva, 2, ',', '')}}</td>
            <td>{{number_format($nominas[$i]->monto_libros, 2, ',', '')}}</td>
            <td>{{number_format($nominas[$i]->retroactivo, 2, ',', '')}}</td>
            <td>{{number_format($nominas[$i]->sueldo_base, 2, ',', '')}}</td>
            <td>{{number_format($nominas[$i]->total, 2, ',', '')}}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        @endfor

    </tbody>
</table>