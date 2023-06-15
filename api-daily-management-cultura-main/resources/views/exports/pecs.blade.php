<table>
    <thead>
        <tr>
            <th>
                <b>#</b>
            </th>
            <th>
                <b>CONSECUTIVO</b>
            </th>
            <th>
                <b>USUARIO</b>
            </th>
            <th>
                <b>CEDULA USUARIO</b>
            </th>
            <th>
                <b>ROL USUARIO</b>
            </th>
            <th>
                <b>NAC USUARIO</b>
            </th>
            <th>
                <b>NAC PEC</b>
            </th>
            <th>
                <b>BARRIO</b>
            </th>
            <th>
                <b>LUGAR</b>
            </th>
            <th>
                <b>DIRECCIÓN DEL LUGAR</b>
            </th>
            <th>
                <b>FECHA DE LA ACTIVIDAD</b>
            </th>
            <th>
                <b>HORA INICIO</b>
            </th>
            <th>
                <b>HORA FINAL</b>
            </th>
            <th>
                <b>TIPO DE LUGAR</b>
            </th>
            <th>
                <b>BREVE RESEÑA DEL LUGAR</b>
            </th>
            <th>
                <b>GRUPO</b>
            </th>
            <th>
                <b>CANTIDAD DE ASISTENTES</b>
            </th>
            <th>
                <b>FECHA DE CREACION</b>
            </th>
            <th>
                <b>ESTADO</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $pec)
            <tr>
                <td>{{ $pec->id }}</td>
                <td>{{ $pec->consecutive }}</td>
                <td>{{ $pec->user->name ?? '' }}</td>
                <td>{{ $pec->user->profile->document_number ?? '' }}</td>
                <td>{{ $pec->user->roles[0]->name ?? '' }}</td>
                <td>{{ $pec->user->profile->nac->name ?? '' }}</td>
                <td>{{ $pec->nac->name ?? '' }}</td>
                <td>{{ $pec->neighborhood->name ?? '' }}</td>
                <td>{{ $pec->place }}</td>
                <td>{{ $pec->place_address }}</td>
                <td>{{ $pec->activity_date }}</td>
                <td>{{ date('g:i A', strtotime($pec->start_time)) }}</td>
                <td>{{ date('g:i A', strtotime($pec->final_time)) }}</td>
                <td>{{ $trait->dataExport($pec->place_type, 'place_types') }}</td>
                <td>{{ $pec->place_description }}</td>
                <td>{{ $pec->pecsbeneficiariesExport[0]->group->name ?? '' }}</td>
                <td>{{ $pec->pecsbeneficiariesExport->count() ?? '0' }}</td>
                <td>{{ date('Y-m-d G:i:s', strtotime($pec->created_at ?? '')) }}</td>
                <td>{{ $trait->dataExport($pec->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
