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
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->consecutive }}</td>
                {{-- Usuario --}}
                <td>{{ $value->name_user }}</td>
                <td>{{ $value->doc_number_user }}</td>
                <td>{{ $value->name_role }}</td>
                <td>{{ $value->name_nac }}</td>
                {{-- Resto de data --}}
                <td>{{ $value->activity_name }}</td>
                <td>{{ $value->activity_date }}</td>
                <td>{{ $value->start_time }}</td>
                <td>{{ $value->final_hour }}</td>
                <td>{{ $value->place }}</td>
                {{-- Pec --}}
                <td>{{ $value->data_pec }}</td>
                {{-- Pedagogical --}}
                <td>{{ $value->data_pedagogical }}</td>
                {{-- Resto de data relaciones --}}
                <td>{{ $value->name_nac_binnacle }}</td>
                <td>{{ $value->name_expertise }}</td>
                <td>{{ $value->name_cultural }}</td>
                <td>{{ $value->experiential_objective }}</td>
                <td>{{ $trait->dataExport($value->lineament_id, 'lineaments') }}</td>
                <td>{{ $value->name_orientation }}</td>
                <td>{{ $value->goals_met }}</td>
                <td>{{ $value->explain_goals_met }}</td>
                <td>{{ $value->start_activity }}</td>
                {{-- <td>{{ sprintf("%s", $binnacle->activity_development ?? ''); }}</td> <!-- CAMPO DE ERROR DIA 31 --> --}}
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $value->activity_development ?? '') }}</td> <!-- CAMPO DE PARA ARREGLAR ERROR DIA 31 -->
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $value->end_of_activity ?? '') }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $value->observations_activity ?? '') }}</td>
                {{-- Beneficiaries --}}
                <td>{{ $value->name_group }}</td>
                <td>{{ $value->beneficiary_count }}</td>
                <td>{{ $value->created_at }}</td>
                <td>{{ $trait->dataExport($value->status, 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
