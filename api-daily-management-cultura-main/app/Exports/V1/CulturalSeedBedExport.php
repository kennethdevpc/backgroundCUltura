<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\CulturalSeedbedCollection;
use App\Models\CulturalSeedbed;
use App\Traits\FunctionGeneralTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class CulturalSeedBedExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;

    protected  $culturalSeedbeds;
    public function __construct()
    {
        $this->culturalSeedbeds =  new CulturalSeedbed();
    }

    public function map($culturalSeedbed): array
    {
        return [
            $culturalSeedbed->id ?? '',
            $culturalSeedbed->consecutive ?? '',
            $culturalSeedbed->datasheet ?? '',
            $culturalSeedbed->user->name ?? '',
            $culturalSeedbed->user->profile->document_number ?? '',
            $culturalSeedbed->user->profile->nac->name ?? '',
            $culturalSeedbed->user->roles[0]->name ?? '',
            date('Y-m-d', strtotime($culturalSeedbed->date)) ?? '',
            $culturalSeedbed->pec->consecutive ?? '' . ' - ' . $culturalSeedbed->pec->activity_date ?? '',
            $culturalSeedbed->datasheetPlanning->datasheet ?? '',
            $this->data($culturalSeedbed->filter_level ?? '', 'filter_level'),
            $culturalSeedbed->level_domain_description ?? '',
            $culturalSeedbed->objective_process ?? '',
            $culturalSeedbed->quantity_members ?? '',
            $culturalSeedbed->cultural_right->name ?? '',
            $this->data($culturalSeedbed->lineament_id ?? '', 'lineaments'),
            $culturalSeedbed->orientation->name ?? '',
            $this->data($culturalSeedbed->value, 'values'),
            $culturalSeedbed->artistic_expertise ?? '',
            $culturalSeedbed->observations ?? '',
            $culturalSeedbed->group->name ?? '',
            $culturalSeedbed->beneficiary->count() ?? '0',
            $culturalSeedbed->created_at?->format('Y-m-d G:i:s'),
            $this->data($culturalSeedbed->status ?? '', 'status'),
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            'SEMILLERO',
            'USUARIO',
            'CEDULA USUARIO',
            'NAC USUARIO',
            'ROL USUARIO',
            'FECHA',
            'PEC',
            'FICHAS DE PLANEACION',
            'DOMINIO DEL SEMILLERO',
            'DESCRIPCION',
            'OBJETIVO VIVENCIAL',
            'INTEGRANTES DEL SEMILLERO',
            'DERECHO CULTURAL',
            'LINEAMIENTO',
            'ORIENTACIONES',
            'VALOR',
            'EXPERTICIA ARTÍSTICA A TRABAJAR',
            'OBSERVACIONES DE LA REALIZACIÓN',
            'GRUPO',
            'CANTIDAD ASISTENTES',
            'FECHA CREACION',
            'ESTADO',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 37,
            'C' => 37,
            'D' => 37,
            'E' => 37,
            'F' => 37,
            'G' => 37,
            'H' => 37,
            'I' => 37,
            'J' => 37,
            'K' => 30,
            'L' => 35,
            'M' => 35,
            'N' => 37,
            'O' => 37,
            'P' => 37,
            'Q' => 37,
            'R' => 32,
            'S' => 32,
            'T' => 37,
            'U' => 37,
            'V' => 37,
            'W' => 37,
            'X' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:X1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:X')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:X');
            },
        ];
    }
    public function collection()
    {

        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $culturalSeedbeds = $this->culturalSeedbeds->whereNotIn('created_by', [1, 2])->get();

        return new CulturalSeedbedCollection($culturalSeedbeds);
    }
}
