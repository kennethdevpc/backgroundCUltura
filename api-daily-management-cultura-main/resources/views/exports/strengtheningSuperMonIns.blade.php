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
                <b>ROL</b>
            </th>
            <th>
                <b>FECHA REVISIÓN</b>
            </th>
            <th>
                <b>USUARIO SUPERVISADO</b>
            </th>
            <th>
                <b>FECHA DE REGISTRO EN PLATAFORMA</b>
            </th>
            <th>
                <b>DIRECCIÓN</b>
            </th>
            <th>
                <b>¿CUMPLIO?</b>
            </th>
            <th>
                <b>FICHA PEDAGOGICA REGISTRADA EN PLATAFORMA</b>
            </th>
            <th>
                <b>LISTADO DE ASISTENCIA</b>
            </th>
            <th>
                <b>EL MONITOR INICIO LA JORNADA A LA HORA REGISTRADA EN EL PEC?</b>
            </th>
            <th>
                <b>DESCRIPCIÓN DE LA JORNADA</b>
            </th>
            <th>
                <b>OBSERVACIONES</b>
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
        @foreach ($data as $strengtheningSupervisionMonitorsInstructor)
            <tr>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->id }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->consecutive }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->createdBy->name ?? '' }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->createdBy->profile->document_number ?? '' }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->createdBy->profile->role->name ?? '' }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->createdBy->profile->nac->name ?? '' }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->nac->name ?? '' }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->role->name ?? '' }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->revision_date }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->user->name ?? '' }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->platform_registration_date }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->address }}</td>
                <td>{{ $trait->dataExport($strengtheningSupervisionMonitorsInstructor->pec_reached_target, 'decisions') }}</td>
                <td>{{ $trait->dataExport($strengtheningSupervisionMonitorsInstructor->pedagogicals_reached_target, 'decisions') }}</td>
                <td>{{ $trait->dataExport($strengtheningSupervisionMonitorsInstructor->attendance_list, 'decisions') }}</td>
                <td>{{ $trait->dataExport($strengtheningSupervisionMonitorsInstructor->validated_pec_time, 'decisions') }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->description }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->comments }}</td>
                <td>{{ $strengtheningSupervisionMonitorsInstructor->created_at }}</td>
                <td>{{ $trait->dataExport($strengtheningSupervisionMonitorsInstructor->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
