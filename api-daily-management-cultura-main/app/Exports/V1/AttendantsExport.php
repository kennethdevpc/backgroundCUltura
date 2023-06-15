<?php

namespace App\Exports\V1;

use App\Models\Inscriptions\Attendant;
use App\Repositories\ReportRepository;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Resources\V1\DialogueTableCollection;
use App\Traits\FunctionGeneralTrait;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class AttendantsExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $attendant;
    public function __construct($data)
    {
        $this->data = $data;
        $this->attendant =  new Attendant();
    }


    public function map($attendant): array
    {
        return [
            $attendant->id,

            $attendant->beneficiary->user->name ?? '',
            $attendant->beneficiary->user->profile->document_number ?? '',
            $attendant->beneficiary->user->roles[0]->name ?? '',
            $attendant->beneficiary->user->profile->nac->name ?? '',

            //USUARIO
            $attendant->full_name ?? '',
            $attendant->document_number,
            $attendant->phone,
            $attendant->email,
            $attendant->beneficiary->full_name,
            $this->data($attendant->beneficiary->socio_demo->gender ?? '', 'genders'),
            $attendant->beneficiary->socio_demo->age ?? '',
            $this->data($attendant->beneficiary->socio_demo->decision_study ?? '', 'decisions'),
            $this->data($attendant->beneficiary->socio_demo->educational_level ?? '', 'educational_levels'),
            $this->data($attendant->beneficiary->socio_demo->disability_type ?? '', 'disability_types'),
            $this->data($attendant->beneficiary->socio_demo->decision_disability ?? '', 'decisions'),
            $this->data($attendant->beneficiary->socio_demo->condition ?? '', 'conditions'),
            $this->data($attendant->beneficiary->socio_demo->ethnicity ?? '', 'ethnicities'),
            $this->data($attendant->beneficiary->attendant->relationship ?? '', 'relationships'),
            $this->data($attendant->beneficiary->health_data->medical_service ?? '', 'medical_services'),
            $attendant->beneficiary->health_data->entity_name->name ?? '',
            $this->data($attendant->beneficiary->health_data->health_condition ?? '', 'health_conditions'),
            // $attendant->beneficiary->health_data->entity_name->created_at ?? '',
            $attendant->created_at?->format('Y-m-d G:i:s'),
            $this->data($attendant->beneficiary->status, 'status'),
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            "USUARIO",
            "CEDULA DEL USUARIO",
            "ROL USUARIO",
            "NAC USUARIO",
            'NOMBRE DEL ACUDIENTE',
            'DOCUMENTO DEL ACUDIENTE',
            'TELEFONO DEL ACUDIENTE',
            'CORREO DEL ACUDIENTE',
            'NOMBRE DEL BENEFICIARIO',
            'GENERO DEL BENEFICIARIO',
            'EDAD DEL BENEFICIARIO',
            'ESTUDIO',
            'NIVEL ESTUDIO',
            'DISCAPACIDAD',
            'TIPO DISCAPACIDAD',
            'CONDICION',
            'ETNIA',
            'PARENTESCO',
            'SERVICIO MEDICO',
            'NOMBRE ENTIDAD',
            'ESTADO SALUD',
            // 'FECHA DE CREACION DEL BENEFICIARIO',
            'FECHA CARGA',
            'ESTADO'
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
            'G' => 37,
            'H' => 37,
            'I' => 37,
            'J' => 37,
            'K' => 37,
            'L' =>  37,
            'M' =>  37,
            'N' =>  37,
            'O' =>  37,
            'P' =>  37,
            'Q' =>  37,
            'R' =>  37,
            'S' =>  37,
            'T' =>  37,
            'U' =>  37,
            'V' =>  37,
            'W' =>  37,
            'X' =>  37,
            'Y' =>  37,
            'Z' =>  37,
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
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:AK1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:AK')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('C')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:AK');
            },
        ];
    }


    public function collection()
    {
        $query =  $this->attendant->query();
        $ambassadors = $this->attendant->get();

        if ($this->data->status) {
            $ambassadors =   $query->where('status', $this->data->status)->get();
        }
        if ($this->data->user_id) {
            $ambassadors =   $query->where('created_by', $this->data->user_id)->get();
        }
        if ($this->data->nac_id) {
            $ambassadors =  $query->whereHas('benefiary', function ($beneficiary) {
                $beneficiary->where('nac_id', $this->data->nac_id);
            })->get();
        }
        if ($this->data->nac_id && $this->data->status && $this->data->user_id) {
            $ambassadors =  $query->whereHas('benefiary', function ($beneficiary) {
                $beneficiary->where('nac_id', $this->data->nac_id);
            })->where('status', $this->data->status)
                ->where('created_by', $this->data->user_id)
                ->get();
        }
        return new DialogueTableCollection($ambassadors);
    }
}
