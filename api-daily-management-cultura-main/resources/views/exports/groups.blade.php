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
                <b>NOMBRE GRUPO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FECHA CREACION</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($groups as $key => $group)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $group->user->name ?? '' }}</td>
                <td>{{ $group->user->roles[0]->name ?? '' }}</td>
                <td>{{ $group->name ?? '' }}</td>
                <td>{{ $group->created_at ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
