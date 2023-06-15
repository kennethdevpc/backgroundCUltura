<table>
    <thead>
        <tr>
            <th>
                <b>#</b>
            </th>
            <th>
                <b>USUARIO CREACIÓN</b>
            </th>
            <th>
                <b>CÉDULA DE USUARIO CREACIÓN</b>
            </th>
            <th>
                <b>ROL DE USUARIO CREACIÓN</b>
            </th>
            <th>
                <b>NAC DE USUARIO CREACIÓN</b>
            </th>
            <th>
                <b>NOMBRE DE BENEFICIARIO</b>
            </th>
            <th>
                <b>DOCUMENTO DE BENEFICIARIO</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($beneficiaries as $key => $beneficiary)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $beneficiary->user->name ?? '' }}</td>
                <td>{{ $beneficiary->user->profile->document_number ?? '' }}</td>
                <td>{{ $beneficiary->user->profile->role->name ?? '' }}</td>
                <td>{{ $beneficiary->user->profile->nac->name ?? '' }}</td>
                <td>{{ $beneficiary->full_name ?? '' }}</td>
                <td>{{ $beneficiary->document_number ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
