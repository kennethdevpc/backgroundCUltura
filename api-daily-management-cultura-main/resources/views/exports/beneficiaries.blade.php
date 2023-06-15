<table>
    <thead>
    <tr>
        <th style="width: 30px;text-align:center"><strong>
            #
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            CONSECUTIVO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            USUARIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            CEDULA USUARIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ROL USUARIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            NAC USUARIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            TERMINOS Y CONDICIONES
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿CÓMO SE VINCULO USTED AL PROYECTO?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿CUÁL REFERIDO?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            CARACTERIZADO/NOCARACTERIZADO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            GRUPO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            NOMBRE ACUDIENTE
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            DOCUMENTO ACUDIENTE
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            PARENTESCO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            NOMBRE BENEFICIARIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            TIPO DE DOCUMENTO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            DOCUMENTO BENEFICIARIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            NAC
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            BARRIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ZONA
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ESTRATO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            TELÉFONO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            EMAIL
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            GÉNERO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            EDAD
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿ESTUDIA ACTUALMENTE?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿NIVEL EDUCATIVO ALCANZADO?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿PRESENTA ALGUNA DISCAPACIDAD?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            DISCAPACIDAD QUE PRESENTA
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            OTRO DISCAPACIDAD
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿CON QUE TIPO DE ETNIA SE REPRESENTA?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿CONDICIÓN?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ¿A QUE REGIMEN DE SALUD COLOMBIANO PERTENECE?
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            EPS
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            OTRO EPS
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ESTADO DE SALUD DEL BENEFICIARIO
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            FECHA DE CARGUE
        </strong></th>
        <th style="width: 30px;text-align:center"><strong>
            ESTADO
        </strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($beneficiaries as $key => $beneficiary)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $beneficiary->inscription->consecutive ?? '' }}</td>
            <td>{{ $beneficiary->user->name ?? '' }}</td>
            <td>{{ $beneficiary->user->profile->document_number ?? '' }}</td>
            <td>{{ $beneficiary->user->roles[0]->name ?? '' }}</td>
            <td>{{ $beneficiary->user->profile->nac->name ?? '' }}</td>
            <td>{{ $beneficiary->accept == 1 ? 'SI' : 'NO' }}</td>
            <td>{{ $trait->dataExport($beneficiary->linkage_project ?? '', 'linkage_projects') }}</td>
            <td>{{ $beneficiary->referrer_name ?? '' }}</td>
            <td>{{ $trait->dataExport($beneficiary->participant_type ?? '', 'participant_types') }}</td>
            <td>{{ $beneficiary->group->name ?? '' }}</td>
            <td>{{ $beneficiary->attendant->full_name ?? '' }}</td>
            <td>{{ $beneficiary->attendant->document_number ?? '' }}</td>
            <td>{{ $trait->dataExport($beneficiary->attendant->relationship ?? '', 'relationships') }}</td>
            <td>{{ $beneficiary->full_name }}</td>
            <td>{{ $trait->dataExport($beneficiary->type_document ?? '', 'type_documents') }}</td>
            <td>{{ $beneficiary->document_number ?? '' }}</td>
            <td>{{ $beneficiary->nac->name ?? '' }}</td>
            <td>{{ $beneficiary->neighborhood->name ?? '' }}</td>
            <td>{{ $trait->dataExport($beneficiary->zone ?? '', 'zones') }}</td>
            <td>{{ $beneficiary->stratum ?? '' }}</td>
            <td>{{ $beneficiary->phone ?? '' }}</td>
            <td>{{ $beneficiary->email ?? '' }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->gender ?? '', 'genders') }}</td>
            <td>{{ $beneficiary->socio_demo->age ?? '' }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->decision_study ?? '', 'decisions') }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->educational_level ?? '', 'educational_levels') }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->decision_disability ?? '', 'decisions') }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->disability_type ?? '', 'disability_types') }}</td>
            <td>{{ $beneficiary->health_data->other_disability ?? '' }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->ethnicity ?? '', 'ethnicities') }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->condition ?? '', 'conditions') }}</td>
            <td>{{ $trait->dataExport($beneficiary->socio_demo->medical_service ?? '', 'medical_services') }}</td>
            <td>{{ $beneficiary->health_data->entity_name->name ?? '' }}</td>
            <td>{{ $beneficiary->health_data->other_entity_name ?? '' }}</td>
            <td>{{ $trait->dataExport($beneficiary->health_data->health_condition ?? '', 'health_conditions') }}</td>
            <td>{{ $beneficiary->created_at?->format('Y-m-d G:i:s') }}</td>
            <td>{{ $trait->dataExport($beneficiary->status ?? '', 'status') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
