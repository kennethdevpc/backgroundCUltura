<table>
    <thead>
        <tr>
            <th style="width: 30px;text-align:center">
                <b>#</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NOMBRE</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>DESCRIPCION</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>CREACION</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->description }}</td>
                <td>{{ $value->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
