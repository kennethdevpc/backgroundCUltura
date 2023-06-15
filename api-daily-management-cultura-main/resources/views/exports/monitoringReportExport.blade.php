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
                <b>NAC USUARIO</b>
            </th>
            <th>
                <b>ROL USUARIO</b>
            </th>
            <th>
                <b>USUARIO DE SEGUIMIENTO</b>
            </th>
            <th>
                <b>FECHA</b>
            </th>
            <th>
                <b>DESCRIPCION</b>
            </th>
            <th>
                <b>FECHA CREACIÓN</b>
            </th>
            <th>
                <b>ESTADO</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $monitoringReport)
            <tr>
                <td>{{ $monitoringReport->id ?? '' }}</td>
                <td>{{ $monitoringReport->consecutive ?? '' }}</td>
                <td>{{ $monitoringReport->user->name ?? '' }}</td>
                <td>{{ $monitoringReport->user->profile->document_number ?? '' }}</td>
                <td>{{ $monitoringReport->user->profile->nac->name ?? '' }}</td>
                <td>{{ $monitoringReport->user->roles[0]->name ?? '' }}</td>
                <td>{{ $monitoringReport->monitoringUser->name ?? '' }}</td>
                <td>{{ $monitoringReport->date ?? '' }}</td>
                <td>{{ $monitoringReport->description ?? '' }}</td>
                <td>{{ date('Y-m-d G:i:s', strtotime($monitoringReport->created_at ?? '')) }}</td>
                <td>{{ $trait->dataExport($monitoringReport->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
