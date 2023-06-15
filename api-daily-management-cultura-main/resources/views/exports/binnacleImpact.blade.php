<table>
    <thead>
        <tr>
            <th style="width: 30px;text-align:center">
                <b>#</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>CÉDULA DE USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>ROL DE USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NAC DE USUARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>NOMBRE DE BENEFICIARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>DOCUMENTO DE BENEFICIARIO</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>BITÁCORAS</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>EXPERTICIA</b>
            </th>
            <th style="width: 30px;text-align:center">
                <b>FECHA ACTIVIDAD BITÁCORA</b>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($binnaclesImpacts as $key => $binnacleImpact)
            <tr>
                <td>{{ $key+1 }}</td>
                {{-- Usuario creación. --}}
                <td>{{ $binnacleImpact->binnacle->user->name ?? '' }}</td>
                <td>{{ $binnacleImpact->binnacle->user->profile->document_number ?? '' }}</td>
                <td>{{ $binnacleImpact->binnacle->user->profile->role->name ?? '' }}</td>
                <td>{{ $binnacleImpact->binnacle->user->profile->nac->name ?? '' }}</td>
                {{-- Beneficiario. --}}
                <td>{{ $binnacleImpact->beneficiary->full_name ?? '' }}</td>
                <td>{{ $binnacleImpact->beneficiary->document_number ?? '' }}</td>
                <td>{{ $binnacleImpact->binnacle->consecutive ?? '' }}</td>
                <td>{{ $binnacleImpact->binnacle->expertise->name ?? '' }}</td>
                <td>{{ date('Y-m-d G:i:s', strtotime($binnacleImpact->binnacle->activity_date ?? '')) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
