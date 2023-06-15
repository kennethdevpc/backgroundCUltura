<?php

namespace App\Exports\V1;

use App\Models\Inscriptions\Inscription;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Resources\V1\DialogueTableCollection;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
class InscriptionExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $incription;
    public function __construct($data)
    {
        $this->data = $data;
        $this->incription =  new Inscription();
    }

    public function map($incription): array
    {
        return [
            $incription->id,
            $incription->consecutive,

            //USUARIO
            $incription->user->name ?? '',
            $incription->user->profile->document_number ?? '',
            $incription->user->roles[0]->name ?? '',
            $incription->user->profile->nac->name ?? '',

            //BENEFICIARIO
            $incription->benefiary->full_name ?? '',
            $this->data($incription->benefiary->linkage_project ?? '', 'linkage_projects'),
            $incription->benefiary->referrer_name ?? '',
            $incription->benefiary->institution_entity_referred ?? '',

            //TIPO DE PARTICIPANTE Y GRUPO
            $this->data($incription->benefiary->participant_type ?? '', 'participant_types'),
            $incription->benefiary->group->name ?? '',

            $this->data($incription->benefiary->type_document ?? '', 'type_documents'),
            $incription->benefiary->document_number ?? '',
            $incription->benefiary->nac->name ?? '',
            $incription->benefiary->neighborhood->name ?? '',
            $incription->benefiary->neighborhood_new ?? '',
            $this->data($incription->benefiary->zone ?? '', 'zones'),
            $this->data($incription->benefiary->stratum ?? '', 'stratums'),
            $incription->benefiary->phone ?? '',
            $incription->benefiary->email ?? '',
            $this->data($incription->benefiary->socio_demo->gender ?? '', 'genders'),
            $incription->benefiary->socio_demo->age ?? '',
            $incription->benefiary->socio_demo->decision_study ?? '' == 1 ? 'SI' : 'NO',
            $this->data($incription->benefiary->socio_demo->educational_level ?? '', 'educational_levels'),
            $incription->benefiary->socio_demo->decision_disability ?? '' == 1 ? 'SI' : 'NO',
            $this->data($incription->benefiary->socio_demo->disability_type ?? '', 'disability_types'),
            $this->data($incription->benefiary->socio_demo->ethnicity ?? '', 'ethnicities'),
            $this->data($incription->benefiary->socio_demo->condition ?? '', 'conditions'),
            $this->data($incription->benefiary->health_data->medical_service ?? '', 'medical_services'),
            $incription->benefiary->health_data->entity_name->name ?? '',
            $incription->benefiary->health_data->other_entity_name ?? '',
            $this->data($incription->benefiary->health_data->health_condition ?? '', 'health_conditions'),

            //ACUDIENTE
            $incription->benefiary->attendant->full_name ?? '',
            $this->data($incription->benefiary->attendant->relationship ?? '', 'relationships'),
            $this->data($incription->benefiary->attendant->type_document ?? '', 'type_documents'),
            $incription->benefiary->attendant->document_number ?? '',
            $this->data($incription->benefiary->attendant->zone ?? '', 'zones'),
            $incription->benefiary->attendant->phone ?? '',
            $incription->benefiary->attendant->email ?? '',
            $this->data($incription->benefiary->attendant->socio_demo->gender ?? '', 'genders'),
            $incription->benefiary->attendant->socio_demo->age ?? '',
            $incription->benefiary->attendant->socio_demo->decision_study ?? '' == 1 ? 'SI' : 'NO',
            $this->data($incription->benefiary->attendant->socio_demo->educational_level ?? '', 'educational_levels'),
            $incription->benefiary->attendant->socio_demo->decision_disability ?? '' == 1 ? 'SI' : 'NO',
            $this->data($incription->benefiary->attendant->socio_demo->disability_type ?? '', 'disability_types'),
            $this->data($incription->benefiary->attendant->socio_demo->ethnicity ?? '', 'ethnicities'),
            $this->data($incription->benefiary->attendant->socio_demo->condition ?? '', 'conditions'),
            $this->data($incription->benefiary->attendant->health_data->medical_service ?? '', 'medical_services'),
            $incription->benefiary->attendant->health_data->entity_name->name ?? '',
            $incription->benefiary->attendant->health_data->other_entity_name ?? '',
            $this->data($incription->benefiary->attendant->health_data->health_condition ?? '', 'health_conditions'),

            $incription->created_at->format('Y-m-d G:i:s'),
            $this->data($incription->status, 'status'),
        ];
    }
    //
    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            "USUARIO",
            'CEDULA USUARIO',
            "ROL",
            "NAC USUARIO",
            "NOMBRE BENEFICIARIO",
            "VINCULACIÓN",
            "REFERIDO",
            "INSTITUCIÓN, ENTIDAD O REFERIDO",
            "TIPO PARTICIANTE",
            "GRUPO",
            "TIPO DE DOCUMENTO",
            "NÚMERO DE DOCUMENTO",
            "NAC",
            "BARRIO",
            "OTRO BARRIO",
            "ZONA",
            "ESTRATO",
            "TELÉFONO",
            "EMAIL",
            "GENERO",
            "EDAD",
            "ACTUALMENTE ESTUDIA",
            "NIVEL EDUCATIVO ALCANZADO",
            "PRESENTA DISCAPACIDAD",
            "TIPO DE DISCAPACIDAD",
            "TIPO DE ETNIA",
            "CONDICIÓN",
            "REGIMEN DE SALUD",
            "EPS",
            "OTRO EPS",
            "ESTADO DE SALUD",

            //ACUDIENTE
            'NOMBRE COMPLETO ACUDIENTE',
            "PARENTESCO",
            "TIPO DE DOCUMENTO",
            "NUMERO DE DOCUMENTO",
            "ZONA",
            "TELÉFONO",
            "EMAIL",
            "GENERO",
            "EDAD",
            "ACTUALMENTE ESTUDIA",
            "NIVEL EDUCATIVO ALCANZADO",
            "PRESENTA DISCAPACIDAD",
            "TIPO DE DISCAPACIDAD",
            "TIPO DE ETNIA",
            "CONDICIÓN",
            "REGIMEN DE SALUD",
            "EPS",
            "OTRO EPS",
            "ESTADO DE SALUD",
            'FECHA DE CREACION',
            'ESTADO',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 37,
            'B' => 37,
            'C' => 37,
            'D' => 37,
            'E' => 37,
            'F' => 37,
            'G' => 35,
            'H' => 37,
            'I' => 37,
            'J' => 35,
            'K' => 37,
            'L' => 37,
            'M' => 37,
            'N' => 37,
            'O' => 37,
            'P' => 37,
            'Q' => 37,
            'R' => 37,
            'S' => 37,
            'T' => 37,
            'U' => 37,
            'V' => 37,
            'W' => 37,
            'X' => 37,
            'Y' => 37,
            'Z' => 37,
            'AA' => 37,
            'AB' => 37,
            'AC' => 37,
            'AD' => 37,
            'AE' => 37,
            'AF' => 37,
            'AG' => 37,
            'AH' => 37,
            'AI' => 37,
            'AJ' => 37,
            'AK' => 37,
            'AL' => 37,
            'AM' => 37,
            'AN' => 37,
            'AO' => 37,
            'AP' => 37,
            'AQ' => 37,
            'AR' => 37,
            'AS' => 37,
            'AT' => 37,
            'AU' => 37,
            'AV' => 37,
            'AW' => 37,
            'AX' => 37,
            'AY' => 37,
            'AZ' => 37,
            'BA' => 37,
            'BB' => 37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:BB1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:BB')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:BB');
            },
        ];
    }

    public function collection()
    {
        $query =  $this->incription->query();
        $incriptions = $this->incription->get();

        if ($this->data->status) {
            $incriptions =   $query->where('status', $this->data->status)->get();
        }
        if ($this->data->user_id) {
            $incriptions =   $query->where('created_by', $this->data->user_id)->get();
        }
        if ($this->data->nac_id) {
            $incriptions =  $query->whereHas('benefiary', function ($beneficiary) {
                $beneficiary->where('nac_id', $this->data->nac_id);
            })->get();
        }
        if ($this->data->nac_id && $this->data->status && $this->data->user_id) {
            $incriptions =  $query->whereHas('benefiary', function ($beneficiary) {
                $beneficiary->where('nac_id', $this->data->nac_id);
            })->where('status', $this->data->status)
                ->where('created_by', $this->data->user_id)
                ->get();
        }
        return new DialogueTableCollection($incriptions);
    }
}
