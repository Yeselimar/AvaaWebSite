<table>
	<thead>
		<tr>
            <th colspan="7">REPORTE TIEMPO</th>
        </tr>
		<tr>
			<td style="background-color: #eeeeee">#</td>
			<td style="background-color: #eeeeee">Ponderación</td>
			<td style="background-color: #eeeeee">Nombres y Apellidos</td>
			<td style="background-color: #eeeeee">Cédula</td>
			<td style="background-color: #eeeeee">Talleres/ChatClub</td>
			<td style="background-color: #eeeeee">CVA</td>
			<td style="background-color: #eeeeee">Voluntariado</td>
			<td style="background-color: #eeeeee">Nota Académica</td>
		</tr>
	</thead>
	<tbody>
		@foreach($todos as $index=>$item)
		<tr>
			<td>{{$index+1}}</td>
			<td
					@if($item["puntos"]==0)
						@if($item["tiempo_actividades"]=='Nunca' and $item["tiempo_cva"]=='Nunca' and $item["tiempo_voluntariado"]=='Nunca' and $item["tiempo_periodos"]=='Nunca')
							style="background-color: #EF5350;"
								
						@else
							style="background-color: #66BB6A;"
							
						@endif
					@endif

					@if($item["puntos"]>0 and $item["puntos"]<=4)
						style="background-color: #66BB6A;"
					@endif
					@if($item["puntos"]>4 && $item["puntos"]<=8)
						style="background-color: #66BB6A;"
					@endif
					@if($item["puntos"]>8 && $item["puntos"]<=20)
						style="background-color: #FFEE58;"
					@endif
					@if($item["puntos"]>20 && $item["puntos"]<=40)
						style="background-color: #EF5350;"
					@endif
					@if($item["puntos"]>40)
						style="background-color: #EF5350;"
					@endif
			>{{$item["puntos"]}}</td>
			<td>{{$item["becario"]["nombreyapellido"]}}</td>
			<td>{{$item["becario"]["cedula"]}}</td>
			<td>{{$item["tiempo_actividades"]}}</td>
			<td>{{$item["tiempo_cva"]}}</td>
			<td>{{$item["tiempo_voluntariado"]}}</td>
			<td>{{$item["tiempo_periodos"]}}</td>
		</tr>
		@endforeach
		<tr>
            <td colspan="7">Generado el  {{date("d/m/Y h:i a", strtotime(date('Y-m-d H:i:s')))}}</td>
        </tr>
	</tbody>
</table>
					
		

					
		

					
					
					
					
