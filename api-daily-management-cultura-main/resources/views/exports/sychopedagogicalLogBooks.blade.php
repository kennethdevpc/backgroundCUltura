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
                <b>NAC</b>
            </th>
            <th>
                <b>FECHA</b>
            </th>
            <th>
                <b>HORA DE INICIO</b>
            </th>
            <th>
                <b>HORA FINAL</b>
            </th>
            <th>
                <b>NOMBRE DE LA PERSONA ATENDIDA</b>
            </th>
            <th>
                <b>NOMBRE DEL MONITOR CULTURAL</b>
            </th>
            <th>
                <b>ROLES</b>
            </th>
            <th>
                <b>OBJETIVO</b>
            </th>
            <th>
                <b>DESARROLLO</b>
            </th>
            <th>
                <b>CONCLUCIONES, REFLEXIONES Y COMPROMISOS DE LA JORNADA</b>
            </th>
            <th>
                <b>REPORTE DE ALERTA PARA HACER SEGUIMIENTO</b>
            </th>
            <th>
                <b>CANTIDAD DE ASISTENTES</b>
            </th>
            <th>
                <b>CANTIDAD DE ASISTENTES MONITORES</b>
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
        @foreach ($data as $psychopedagogicalLogBook)
            <tr>
                <td>{{ $psychopedagogicalLogBook->id }}</td>
                <td>{{ $psychopedagogicalLogBook->consecutive }}</td>
                <td>{{ $psychopedagogicalLogBook->user->name ?? '' }}</td>
                <td>{{ $psychopedagogicalLogBook->user->profile->document_number ?? '' }}</td>
                <td>{{ $psychopedagogicalLogBook->user->roles[0]->name ?? '' }}</td>
                <td>{{ $psychopedagogicalLogBook->user->profile->nac->name ?? '' }}</td>
                <td>{{ $psychopedagogicalLogBook->nac->name }}</td>
                <td>{{ $psychopedagogicalLogBook->date }}</td>
                <td>{{ date('g:i A', strtotime($psychopedagogicalLogBook->start_time)) }}</td>
                <td>{{ date('g:i A', strtotime($psychopedagogicalLogBook->final_time)) }}</td>
                <td>{{ $psychopedagogicalLogBook->person_served_name }}</td>
                <td>{{ $psychopedagogicalLogBook->monitor->name ?? '' }}</td>
                <td>{{ $trait->printValueRelations($psychopedagogicalLogBook->user->roles) }}</td>
                <td>{{ $psychopedagogicalLogBook->objective }}</td>
                <td>{{ $psychopedagogicalLogBook->development }}</td>
                <td>{{ $psychopedagogicalLogBook->conclusions_reflections_commitments }}</td>
                <td>{{ $psychopedagogicalLogBook->alert_reporting_tracking }}</td>
                <td>{{ $psychopedagogicalLogBook->addedWizards->count() ?? '0' }}</td>
                <td>{{ $psychopedagogicalLogBook->assistanceMonitors->count() ?? '0' }}</td>
                <td>{{ date('Y-m-d G:i:s', strtotime($psychopedagogicalLogBook->created_at)) }}</td>
                <td>{{ $trait->data($psychopedagogicalLogBook->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
