<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Resources\V1\DialogueTableCollection;
use App\Models\BinnacleCulturalShow;
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


class AmbassadorExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $ambassador;
    public function __construct($data)
    {
        $this->data = $data;
        $this->ambassador =  new BinnacleCulturalShow();
    }

    public function map($ambassador): array
    {
        return [
            $ambassador->id,
            $ambassador->consecutive,

            //USUARIO
            $ambassador->created_user->name ?? '',
            $ambassador->created_user->profile->document_number ?? '',
            $ambassador->created_user->roles[0]->name ?? '',
            $ambassador->created_user->profile->nac->name ?? '',

            $ambassador->date_range ?? '',
            $ambassador->activity ?? '',
            $ambassador->expertise ?? '',
            $ambassador->artistic_participation ?? '',
            $ambassador->reached_target ?? '' == 1 ? 'SI' : 'NO',

            $ambassador->sustein ?? '',
            $ambassador->number_attendees ?? '',

            $ambassador->created_at?->format('Y-m-d G:i:s'),
            $this->data($ambassador->status, 'status'),
        ];
    }
    //
    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            "USUARIO",
            "CEDULA DEL USUARIO",
            "ROL USUARIO",
            "NAC USUARIO",
            "FECHA DE LA ACTIVIDAD",
            "NOMBRE DE ACTIVIDAD",
            "EXPERTICIA ARTISTICA",
            "PARTICIPACION ARTISTICA",
            "FUE EXITOSA TU PARTICIPACIÓN",
            "SUSTENTACION",
            "NUMERO DE ASISTENTES",
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
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:O1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11); // Letra primera fila
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:O')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:O');
            },
        ];
    }

    public function collection()
    {
        $query =  $this->ambassador->query();
        $ambassadors = $this->ambassador->get();

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
