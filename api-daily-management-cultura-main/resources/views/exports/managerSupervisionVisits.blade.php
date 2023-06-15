<table>
    <thead>
        <tr>
            <th><b>#</b></th>
            <th><b>CONSECUTIVO</b></th>
            <th><b>USUARIO</b></th>
            <th><b>CEDULA DEL USUARIO</b></th>
            <th><b>ROL USUARIO</b></th>
            <th><b>NAC USUARIO</b></th>
            <th><b>NAC</b></th>
            <th><b>FECHA REVISIÓN</b></th>
            <th><b>USUARIO (GESTOR CULTURAL)</b></th>
            <th><b>TRANSFERENCIA</b></th>
            <th><b>DIRECCIÓN</b></th>
            <th><b>¿CUMPLIO?</b></th>
            <th><b>FRECUENCIA</b></th>
            <th><b>BITACORAS REGISTRADAS EN PLATAFORMA</b></th>
            <th><b>DESCRIPCIÓN DE LA JORNADA</b></th>
            <th><b>HORA INICIO</b></th>
            <th><b>HORA FINAL</b></th>
            <th><b>OBSERVACIONES</b></th>
            {{-- <th><b>NUMERO ASISTENTES</b></th> --}}
            <th><b>FECHA DE CARGA</b></th>
            <th><b>ESTADO</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $managerSupervision)
            <tr>
                <td>{{ $managerSupervision->id }}</td>
                <td>{{ $managerSupervision->consecutive }}</td>
                {{-- USUARIO --}}
                <td>{{ $managerSupervision->user->name ?? '' }}</td>
                <td>{{ $managerSupervision->user->profile->document_number ?? '' }}</td>
                <td>{{ $managerSupervision->user->roles[0]->name ?? '' }}</td>
                <td>{{ $managerSupervision->user->profile->nac->name ?? '' }}</td>
                <td>{{ $managerSupervision->nac->name ?? '' }}</td>

                <td>{{ $managerSupervision->revision_date }}</td>
                <td>{{ $managerSupervision->user_manager->name ?? '' }}</td>
                <td>{{ $managerSupervision->methodological_instruction->name ?? '' }}</td>
                <td>{{ $managerSupervision->address }}</td>
                <td>{{ $trait->dataExport($managerSupervision->methodological_instruction_reached_target, 'decisions') }}</td>
                <td>{{ $managerSupervision->frequency }}</td>
                <td>{{ $managerSupervision->binnacle_registered_plataform }}</td>
                <td>{{ $managerSupervision->description }}</td>
                <td>{{ $managerSupervision->comments }}</td>
                {{-- <td>0</td> --}}
                <td>{{ date('g:i A', strtotime($managerSupervision->start_time)) }}</td>
                <td>{{ date('g:i A', strtotime($managerSupervision->final_time)) }}</td>
                <td>{{ date('Y-m-d G:i:s', strtotime($managerSupervision->created_at ?? '')) }}</td>
                <td>{{ $trait->dataExport($managerSupervision->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
