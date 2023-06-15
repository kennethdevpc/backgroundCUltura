<table>
    <thead>
        <tr>
            <th style="width: 30px;text-align:center">
                <b>#</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ROL USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>EN REVISION</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>RECHAZADAS</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>REVISADOS</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>APROBADAS</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->rol }}</td>
                <td>{{ $value->en_revision }}</td>
                <td>{{ $value->rechazadas }}</td>
                <td>{{ $value->revisadas }}</td>
                <td>{{ $value->aprobadas }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
