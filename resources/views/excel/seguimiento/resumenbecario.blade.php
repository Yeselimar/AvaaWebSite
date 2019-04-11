 <table>
    <thead>
        <tr>
            <th colspan="5" style="background-color: #eeeeee">Datos Personales</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">Nombre y Apellido</td>
            <td colspan="3">{{ $becario->user->nombreyapellido()}}</td>
        </tr>
        <tr>
            <td colspan="2">Fecha de Nacimiento</td>
            <td colspan="3">{{ $becario->user->getFechaNacimiento()}}</td>
        </tr>
        <tr>
            <td colspan="2">Cédula</td>
            <td colspan="3">{{$becario->user->cedula}}</td>
        </tr>
        <tr>
            <td colspan="2">Edad</td>
            <td colspan="3">{{$becario->user->getEdad()}}</td>
        </tr>
        <tr>
            <td colspan="2">Correo</td>
            <td colspan="3">{{$becario->user->email}}</td>
        </tr>
        <tr>
            <td colspan="2">Teléfono</td>
            <td colspan="3">{{$becario->celular}}</td>
        </tr>

        <tr colspan="4"></tr>

        <tr>
            <th colspan="5" style="background-color: #eeeeee">VOLUNTARIADO</th>
        </tr>
    
        <tr>
            <td>Año</td>
            <td>Tipo Voluntariado</td>
            <td>Total</td>
            <td>Horas Voluntariado</td>
        </tr>
        @php ($horas = 0)
        @if($cursos->count()!=0)
            @foreach($voluntariados as $voluntariado)
            @php ($horas = $horas + $voluntariado->horas_voluntariado)
            <tr>
                <td>{{date("m", strtotime($voluntariado->fecha))}}-{{date("Y", strtotime($voluntariado->fecha))}}</td>
                <td>{{ucwords($voluntariado->tipo_voluntariado)}}</td>
                <td>{{$voluntariado->total_voluntariado}}</td>
                <td>{{$voluntariado->horas_voluntariado}}</td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan="4">No hay voluntariado</td>
        </tr>
        @endif

        @if($actividades_facilitadas->id!=null)
        <tr>
            @php ($horas = $horas + $actividades_facilitadas->horas_voluntariado)
            <td>{{date("m", strtotime($actividades_facilitadas->fecha))}}-{{date("Y", strtotime($actividades_facilitadas->fecha))}}</td>
            <td>Facilitador en {{ucwords($actividades_facilitadas->tipo)}}</td>
            <td>{{$actividades_facilitadas->total_actividades }}</td>
            <td>{{$actividades_facilitadas->horas_voluntariado}}</td>
        </tr>
        @else
        <tr>
            <td colspan="4">No ha sido facilitador de ningún Chat Club</td>
        </tr>
        @endif
        <tr>
            <td colspan="2"></td>
            <td>Total Horas</td>
            <td>{{ $horas }}</td>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>

        <tr>
            <th colspan="5" style="background-color: #eeeeee">CVA</th>
        </tr>

        <tr>
            <td>Año</td>
            <td>Nivel/Módulo</td>
            <td>Total por Nivel</td>
            <td>Promedio</td>
        </tr>
        @if($cursos->count()!=0)
            @php ($promedio = 0)
            @php ($total_curso = 0)
            @foreach($cursos as $index=>$curso)
            @php ($promedio = $promedio + $curso->promedio_modulo)
            @php ($total_curso = $total_curso + $curso->total_modulo)
            <tr>
                <td>{{date("m", strtotime($curso->fecha_inicio))}}-{{date("Y", strtotime($curso->fecha_inicio))}}</td>
                <td>
                    @if($curso->nivel=='basico')
                        Básico
                    @endif
                    @if($curso->nivel=='intermedio')
                        Intermedio
                    @endif
                    @if($curso->nivel=='avanzado')
                        Avanzado
                    @endif
                    @if($curso->total_modulo==1)
                        {{$curso->modulo}}
                    @endif
                </td>
                <td>{{$curso->total_modulo}}</td>
                <td>{{number_format($curso->promedio_modulo, 2, '.', ',')}}</td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan="4">No hay CVA</td>
        </tr>
        @endif
        <tr>    
            <td colspan="2"></td>
            <td>Promedio General</td>
            <td>
                @if($cursos->count()==0)
                    {{number_format(0, 2, '.', ',')}}
                @else
                    {{number_format($promedio/($index+1), 2, '.', ',')}}
                @endif
            </td>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>

        <tr>
            <th colspan="5" style="background-color: #eeeeee">NOTAS ACADÉMICAS</th>
        </tr>
        <tr>
            <td>Año</td>
            <td>Número Periodo</td>
            <td>Año Lectivo</td>
            <td>Total Materias</td>
            <td>Promedio</td>
        </tr>
        @php ($promedio_periodo = 0)
        @php ($j = 0)
        @if($periodos->count()!=0)

            @foreach($periodos as $periodo)
            @if($periodo->aval->estatus=='aceptada')
            @php ($j++)
            @php ($promedio_periodo = $promedio_periodo + $periodo->getPromedio())
            <tr>
                <td>{{date("m", strtotime($periodo->fecha_inicio))}}-{{date("Y", strtotime($periodo->fecha_inicio))}}</td>
                <td>
                    {{$periodo->getNumeroPeriodo()}} 
                </td>
                <td>{{$periodo->anho_lectivo}}</td>
                <td>{{$periodo->getTotalMaterias()}}</td>
                <td>{{$periodo->getPromedio()}}</td>
            </tr>
            @endif
            @endforeach
        @else
        <tr>
            <td colspan="5">No hay periodos</td>
        </tr>
        @endif
        <tr>    
            <td colspan="3"></td>
            <td>Promedio General</td>
            <td>
                
                @if($promedio_periodo!=0)
                {{ number_format($promedio_periodo/($j), 2, '.', ',')}}
                @else
                {{ number_format(0, 2, '.', ',')}}
                @endif
                
            </td>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>

        <tr>
            <th colspan="5" style="background-color: #eeeeee">TALLER ({{$mes_completo}}-{{$anho}})</th>
        </tr>
        <tr>
            <td>Modalidad</td>
            <td>Asistencias</td>
            <td>Inasistencias</td>
        </tr>
        <tr>
            <td>Presenciales</td>
            <td>{{$asistio['a_t_p']}}</td>
            <td>{{$noasistio['na_t_p']}}</td>
            
        </tr>
        <tr>
            <td>Virtuales</td>
            <td>{{$asistio['a_t_v']}}</td>
            <td>{{$noasistio['na_t_v']}}</td>
        </tr>
        <tr style="background-color: #eee">
            <td>Totales de talleres</td>
            <td>{{$asistio['a_t_p']+$asistio['a_t_v']}}</td>
            <td>{{$noasistio['na_t_p']+$noasistio['na_t_v']}}</td>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>

        <tr>
            <th colspan="5" style="background-color: #eeeeee">CHAT CLUB ({{$mes_completo}}-{{$anho}})</th>
        </tr>
        <tr>
            <td>Modalidad</td>
            <td>Asistencias</td>
            <td>Inasistencias</td>
        </tr>
        <tr>
            <td>Presenciales</td>
            <td>{{$asistio['a_c_p']}}</td>
            <td>{{$noasistio['na_c_p']}}</td>
        </tr>
        <tr>
            <td>Virtuales</td>
            <td>{{$asistio['a_c_v']}}</td>
            <td>{{$noasistio['na_c_v']}}</td>
        </tr>
        <tr style="background-color: #eee">
            <td>Totales de talleres</td>
            <td>{{$asistio['a_c_p']+$asistio['a_c_v']}}</td>
            <td>{{$noasistio['na_c_p']+$noasistio['na_c_v']}}</td>
        </tr>

        <tr>
            <td colspan="5"></td>
        </tr>

        <tr>
            <td colspan="5">Generado el {{date("d/m/Y h:i a", strtotime(date('Y-m-d H:i:s')))}}</td>
        </tr>
    </tbody>
</table>