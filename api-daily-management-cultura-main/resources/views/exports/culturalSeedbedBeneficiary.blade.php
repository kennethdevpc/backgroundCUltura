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
                <b>CÉDULA DE USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ROL DE USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NAC DE USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NOMBRE DE BENEFICIARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>DOCUMENTO DE BENEFICIARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>BITÁCORAS</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FECHA ACTIVIDAD BITÁCORA</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->name_user }}</td>
                <td>{{ $value->user_doc_number }}</td>
                <td>{{ $value->role }}</td>
                <td>{{ $value->nac_nac }}</td>
                <td>{{ $value->full_name }}</td>
                <td>{{ $value->bene_doc_number }}</td>
                <td>{{ $value->consecutive }}</td>
                <td>{{ $value->date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
