<table>
    <thead>
    <tr>
        <th style="background-color: #eeeeee">Nombres</th>
        <th style="background-color: #eeeeee">Apellidos</th>
        <th style="background-color: #eeeeee">Correo Electrónico</th>
    </tr>
    </thead>
    <tbody>
    @foreach($usuarios as $usuario)
        <tr>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->last_name }}</td>
            <td>{{ $usuario->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
