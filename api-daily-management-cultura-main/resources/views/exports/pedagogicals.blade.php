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
                <b>NAC USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ROL USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FECHA DE ACTIVIDAD</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NOMBRE DE LA ACTIVIDAD</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>EXPERTICIAS</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NAC</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>DERECHOS CULTURALES</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>LINEAMIENTO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ORIENTACIONES</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>OBJETIVO VIVENCIAL</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>MANIFESTACIÓN CULTURAL</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>PROCESO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>PRODUCTO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>RECURSOS NECESARIOS</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FECHA CREACIÓN</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ESTADO</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pedagogicals as $key => $pedagogical)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $pedagogical->consecutive }}</td>
                <td>{{ $pedagogical->user->name ?? '' }}</td>
                <td>{{ $pedagogical->user->profile->document_number ?? '', }}</td>
                <td>{{ $pedagogical->user->profile->nac->name ?? '', }}</td>
                <td>{{ $pedagogical->user->roles[0]->name ?? '' }}</td>
                <td>{{ $pedagogical->activity_date }}</td>
                <td>{{ $pedagogical->activity_name }}</td>
                <td>{{ $pedagogical->expertise->name ?? '' }}</td>
                <td>{{ $pedagogical->nac->name ?? '' }}</td>
                <td>{{ $pedagogical->cultural_right->name ?? '' }}</td>
                <td>{{ $trait->dataExport($pedagogical->lineament_id ?? '', 'lineaments') }}</td>
                <td>{{ $pedagogical->orientation->name ?? '' }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $pedagogical->experiential_objective ?? '') }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $pedagogical->manifestation ?? '') }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $pedagogical->process ?? '') }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $pedagogical->product ?? '') }}</td>
                <td>{{ preg_replace('/[^A-Za-z0-9\ ]/', '', $pedagogical->resources ?? '') }}</td>
                <td>{{ $pedagogical->created_at }}</td>
                <td>{{ $trait->dataExport($pedagogical->status ?? '', 'status') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
