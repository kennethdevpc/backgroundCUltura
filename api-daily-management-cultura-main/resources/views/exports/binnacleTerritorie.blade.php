<table>
    <thead>
        <tr>
            <th><b>#</b></th>
            <th><b>CONSECUTIVO</b></th>
            <th><b>USUARIO</b></th>
            <th><b>CEDULA USUARIO</b></th>
            <th><b>NAC USUARIO</b></th>
            <th><b>ROL USUARIO</b></th>
            <th><b>NAC</b></th>
            <th><b>USUARIO VISITA</b></th>
            <th><b>ROL</b></th>
            <th><b>FECHA DE ACTIVIDAD</b></th>
            <th><b>HORA DE INICIO</b></th>
            <th><b>HORA FINAL</b></th>
            <th><b>LUGAR</b></th>
            <th><b>OBJETIVOS ESTRATÉGICOS DEL ÁREA</b></th>
            <th><b>PROPÓSITO DE LA VISITA</b></th>
            <th><b>TEMÁTICAS ABORDADAS</b></th>
            <th><b>PERCEPCIÓN DE LOS PARTICIPANTES FRENTE A LAS ACTIVIDADES DESARROLLADAS POR EL ÁREA</b></th>
            <th><b>DIFICULTADES O PROBLEMÁTICAS IDENTIFICADAS</b></th>
            <th><b>RECOMENDACIONES Y ACCIONES DE MEJORA PROPUESTAS POR LOS PARTICIPANTES</b></th>
            <th><b>PERCEPCIONES/COMENTARIOS/ANÁLISIS FRENTE AL AVANCE DEL PROCESO</b></th>
            <th><b>FECHA CREACIÓN</b></th>
            <th><b>ESTADO</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $binnacleTerritorie)
            <tr>
                <td>{{ $binnacleTerritorie->id }}</td>
                <td>{{ $binnacleTerritorie->consecutive }}</td>
                <td>{{ $binnacleTerritorie->created_user->name ?? '' }}</td>
                <td>{{ $binnacleTerritorie->created_user->profile->document_number ?? '' }}</td>
                <td>{{ $binnacleTerritorie->created_user->profile->nac->name ?? '' }}</td>
                <td>{{ $binnacleTerritorie->created_user->roles[0]->name ?? '' }}</td>
                <td>{{ $binnacleTerritorie->nac->name ?? '' }}</td>
                <td>{{ $binnacleTerritorie->user->name ?? '' }}</td>
                <td>{{ $binnacleTerritorie->user->roles[0]->name ?? '' }}</td>
                <td>{{ $binnacleTerritorie->activity_date }}</td>
                <td>{{ date('g:i A', strtotime($binnacleTerritorie->start_time)) ?? '' }}</td>
                <td>{{ date('g:i A', strtotime($binnacleTerritorie->final_hour)) ?? '' }}</td>
                <td>{{ $binnacleTerritorie->place }}</td>
                <td>{{ $binnacleTerritorie->strategic_objectives_area }}</td>
                <td>{{ $binnacleTerritorie->purpose_visit }}</td>
                <td>{{ $binnacleTerritorie->topics_covered }}</td>
                <td>{{ $binnacleTerritorie->participants_perception }}</td>
                <td>{{ $binnacleTerritorie->problems_identified }}</td>
                <td>{{ $binnacleTerritorie->recommendations_actions }}</td>
                <td>{{ $binnacleTerritorie->comments_analysis }}</td>
                <td>{{ $binnacleTerritorie->created_at }}</td>
                <td>{{ $trait->dataExport($binnacleTerritorie->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
