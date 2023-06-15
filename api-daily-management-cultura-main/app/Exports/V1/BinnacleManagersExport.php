<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\BinnacleCollection;
use App\Models\Binnacle;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
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


class BinnacleManagersExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;

    protected  $binnacle;

    public function __construct()
    {
        $this->binnacle = new Binnacle();
    }

    public function map($binnacle): array
    {
        return [
            $binnacle->id ?? '',
            $binnacle->consecutive ?? '',
            $binnacle->user->name ?? '',
            $binnacle->user->profile->document_number ?? '',
            $binnacle->user->roles[0]->name ?? '',
            $binnacle->user->profile->nac->name ?? '',
            $binnacle->activity_name,
            $binnacle->activity_date,
            date('G:i', strtotime($binnacle->start_time)),
            date('G:i', strtotime($binnacle->final_hour)),
            $binnacle->place,
            //  $binnacle->pec->consecutive ?? '',
            // $binnacle->pedagogical->consecutive ?? '' ,
            $binnacle->nac->name ?? '',
            $binnacle->expertise->name ?? '',
            $binnacle->cultural_right->name ?? '',
            $binnacle->experiential_objective ?? '',
            $this->data($binnacle->lineament_id, 'lineaments'),
            $binnacle->orientation->name ?? '',
            $binnacle->goals_met ?? '',
            $binnacle->explain_goals_met,
            $binnacle->start_activity,
            $binnacle->activity_development,
            $binnacle->end_of_activity,
            $binnacle->observations_activity,
            // $binnacle->beneficiaries[0]['group_id'] ?? '',
            $binnacle->beneficiaries_capacity ?? '',
            $binnacle->created_at->format('Y-m-d G:i:s'),
            $this->data($binnacle->status, 'status'),

        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            "USUARIO",
            "CEDULA USUARIO",
            "ROL USUARIO",
            "NAC USUARIO",
            'NOMBRE ACTIVIDAD',
            'FECHA DE ACTIVIDAD',
            'HORA DE INICIO',
            'HORA FINAL',
            'LUGAR',
            // 'PEC',
            //  'FICHA PEDAGÓGICA',
            'NAC',
            'EXPERTICIA',
            'DERECHO CULTURAL',
            'OBJETIVO VIVENCIAL',
            'LINEAMIENTOS',
            'ORIENTACIONES',
            '¿SE CUMPLIÓ EL OBJETIVO VIVENCIAL?',
            'EXPLIQUE EL ¿Por qué?',
            'INICIO',
            'DESARROLLO',
            'FINAL',
            'OBSERVACIONES',
            // 'GRUPO',
            'NÚMERO DE ASISTENTES',
            'FECHA DE CARGUE',
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
            'L' => 37,
            'M' => 37,
            'N' => 37,
            'O' => 37,
            'P' => 37,
            'Q' => 37,
            'R' => 37,
            'S' => 37,
            'T' => 38,
            'U' => 25,
            'V' => 37,
            'W' => 37,
            'X' => 37,
            'Y' => 37,
            'Z' => 22,
            'AA' => 37,
            'AB' => 37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:AB1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:AB')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:AB');
            },
        ];
    }

    public function collection()
    {
        $query =  $this->binnacle->query();
        // SE ANADE CONDICION PARA EVITAR MOSTRAR REPORTES CREADOS POR ROOT Y ADMIN
        $binnacles = $this->binnacle->whereNotIn('created_by', [1, 2])->where('type', 'manager')->get();

        return  new BinnacleCollection($binnacles);
    }
}
