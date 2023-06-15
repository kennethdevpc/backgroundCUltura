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
                <b>NOMBRE USUARIO</b>
            </th>
            <th>
                <b>FECHA ACTIVIDAD</b>
            </th>
            <th>
                <b>HORA INICIO</b>
            </th>
            <th>
                <b>HORA FINAL</b>
            </th>
            <th>
                <b>LUGAR</b>
            </th>
            <th>
                <b>OBJETIVOS ESTRATÉGICOS DEL ÁREA</b>
            </th>
            <th>
                <b>PROPÓSITO DE LA VISITA</b>
            </th>
            <th>
                <b>TEMÁTICAS ABORDADAS</b>
            </th>
            <th>
                <b>PERCEPCIÓN DE LOS PARTICIPANTES FRENTE A LAS ACTIVIDADES</b>
            </th>
            <th>
                <b>DIFICULTADES O PROBLEMÁTICAS IDENTIFICADAS</b>
            </th>
            <th>
                <b>RECOMENDACIONES Y ACCIONES DE MEJORA</b>
            </th>
            <th>
                <b>PERCEPCIONES/COMENTARIOS/ANÁLISIS FRENTE AL AVANCE DEL PROCESO</b>
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
        @foreach ($data as $strengtheningOfMonitoring)
            <tr>
                <td>{{ $strengtheningOfMonitoring->id }}</td>
                <td>{{ $strengtheningOfMonitoring->consecutive }}</td>
                <td>{{ $strengtheningOfMonitoring->created_user->name ?? '' }}</td>
                <td>{{ $strengtheningOfMonitoring->created_user->profile->document_number ?? '' }}</td>
                <td>{{ $strengtheningOfMonitoring->created_user->profile->role->name ?? '' }}</td>
                <td>{{ $strengtheningOfMonitoring->created_user->profile->nac->name ?? '' }}</td>
                <td>{{ $strengtheningOfMonitoring->nac->name ?? '' }}</td>
                <td>{{ $strengtheningOfMonitoring->roles[0]->name ?? '' }}</td>
                <td>{{ $strengtheningOfMonitoring->user->profile->contractor_full_name }}</td>
                <td>{{ $strengtheningOfMonitoring->activity_date }}</td>
                <td>{{ $strengtheningOfMonitoring->start_time }}</td>
                <td>{{ $strengtheningOfMonitoring->final_hour }}</td>
                <td>{{ $strengtheningOfMonitoring->place }}</td>
                <td>{{ $strengtheningOfMonitoring->strategic_objectives_area }}</td>
                <td>{{ $strengtheningOfMonitoring->purpose_visit }}</td>
                <td>{{ $strengtheningOfMonitoring->topics_covered }}</td>
                <td>{{ $strengtheningOfMonitoring->participants_perception }}</td>
                <td>{{ $strengtheningOfMonitoring->problems_identified }}</td>
                <td>{{ $strengtheningOfMonitoring->recommendations_actions }}</td>
                <td>{{ $strengtheningOfMonitoring->comments_analysis }}</td>
                <td>{{ date('Y-m-d G:i:s', strtotime($strengtheningOfMonitoring->created_at ?? '')) }}</td>
                <td>{{ $trait->dataExport($strengtheningOfMonitoring->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
