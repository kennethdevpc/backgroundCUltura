<table>
    <thead>
        <tr>
            <th style="width: 30px;text-align:center">
                <b>#</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>CONSECUTIVO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>CEDULA USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ROL USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NAC USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NOMBRE ACTIVIDAD</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FECHA DE ACTIVIDAD</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>HORA DE INICIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>HORA FINAL</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>LUGAR</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>PEC</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FICHA PEDAGÓGICA</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NAC</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>EXPERTICIA</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>DERECHO CULTURAL</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>OBJETIVO VIVENCIAL</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>LINEAMIENTOS</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ORIENTACIONES</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>¿SE CUMPLIÓ EL OBJETIVO VIVENCIAL?</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>EXPLIQUE EL ¿Por qué?</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>INICIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>DESARROLLO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FINAL</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>OBSERVACIONES</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>GRUPO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NÚMERO DE ASISTENTES</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FECHA DE CARGUE</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ESTADO</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($binnacles as $binnacle)
            <tr>
                <td>{{ $binnacle->id }}</td>
                <td>{{ $binnacle->consecutive }}</td>
                {{-- Usuario validacion --}}
                @if ($binnacle->user != null)
                    <td>{{ $binnacle->user->name }}</td>
                    <td>{{ $binnacle->user->profile->document_number }}</td>
                    <td>{{ $binnacle->user->roles[0]->name }}</td>
                    <td>{{ $binnacle->user->profile->nac->name }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                <td>{{ $binnacle->activity_name }}</td>
                <td>{{ $binnacle->activity_date }}</td>
                <td>{{ date('G:i', strtotime($binnacle->start_time)) }}</td>
                <td>{{ date('G:i', strtotime($binnacle->final_hour)) }}</td>
                <td>{{ $binnacle->place ?? '' }}</td>
                {{-- Pec validacion --}}
                @if ($binnacle->pec != null)
                    <td>{{ $binnacle->pec->consecutive . ' - ' .
                        $binnacle->pec->activity_date . ' - ' .
                        date('G:i', strtotime($binnacle->pec->start_time)) . ' - ' .
                        date('G:i', strtotime($binnacle->pec->final_hour)) }}</td>
                @else
                    <td></td>
                @endif
                {{-- Pedagogical validacion --}}
                @if ($binnacle->pedagogical != null)
                    <td>{{ $binnacle->pedagogical->consecutive . ' - ' .
                        $binnacle->pedagogical->activity_date . ' - ' .
                        date('G:i', strtotime($binnacle->pedagogical->start_time)) . ' - ' .
                        date('G:i', strtotime($binnacle->pedagogical->final_hour)) }}</td>
                @else
                    <td></td>
                @endif
                <td>{{ $binnacle->nac->name ?? '' }}</td>
                <td>{{ $binnacle->expertise->name ?? '' }}</td>
                <td>{{ $binnacle->cultural_right->name ?? '' }}</td>
                <td>{{ $binnacle->experiential_objective ?? '' }}</td>
                <td>{{ $trait->dataExport($binnacle->lineament_id ?? '', 'lineaments') }}</td>
                <td>{{ $binnacle->orientation->name ?? '' }}</td>
                <td>{{ $binnacle->goals_met }}</td>
                <td>{{ $binnacle->explain_goals_met }}</td>
                <td>{{ $binnacle->start_activity }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $binnacle->activity_development ?? '') }}</td> <!-- CAMPO DE PARA ARREGLAR ERROR DIA 31 -->
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $binnacle->end_of_activity ?? '') }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $binnacle->observations_activity ?? '') }}</td>
                {{-- Beneficiaries validacion --}}
                <td>{{ $binnacle->beneficiaries[0]->group->name  ?? '' }}</td>
                <td>{{ $binnacle->beneficiaries->count() ?? '' }}</td>
                <td>{{ date('Y-m-d G:i:s', strtotime($binnacle->created_at)) }}</td>
                <td>{{ $trait->dataExport($binnacle->status ?? '', 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
