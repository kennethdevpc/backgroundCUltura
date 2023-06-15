<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\ParentSchoolCollection;
use App\Models\ParentSchools\ParentSchool;
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

class ParentSchoolsExport  implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $parentSchool;
    public function __construct($data)
    {
        $this->data = $data;
        $this->parentSchool  = new ParentSchool();
    }

    public function map($parentSchool): array
    {
        return [
            $parentSchool->id,
            $parentSchool->consecutive,

            $parentSchool->user->name ?? '',
            $parentSchool->user->profile->document_number ?? '',
            $parentSchool->user->roles[0]->name ?? '',
            $parentSchool->user->profile->nac->name ?? '',

            $parentSchool->date?->format('Y-m-d'),
            date('g:i A', strtotime($parentSchool->start_time)),
            date('g:i A', strtotime($parentSchool->final_time)),
            $parentSchool->monitor->name ?? '',
            $parentSchool->place_attention,
            $parentSchool->contact,
            $parentSchool->objective,
            $parentSchool->development,
            $parentSchool->conclusions,
            $parentSchool->created_at?->format('Y-m-d G:i:s'),
            $this->data($parentSchool->status, 'status'),
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
            'FECHA',
            'HORA INICIO',
            'HORA FINAL',
            'NOMBRE MONITOR',
            'LUGAR DE ATENCIÓN',
            'CONTACTO',
            'OBJETIVO',
            'DESARROLLO ',
            'CONCLUSIONES',
            'FECHA CARGA',
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
            'R' => 37
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:R1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11); // Letra primera fila
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:R')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:R');
            },
        ];
    }
    public function collection()
    {
        $query =    $this->parentSchool->query();
        $parentSchools =  $this->parentSchool->get();
        if ($this->data->status) {
            $parentSchools = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $parentSchools = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $parentSchools =  $query->where('date',  $this->data->date_start)->get();
        }
        if ($this->data->user_id) {
            $parentSchools = $query->where('created_by',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $parentSchools = $query->where('date', '>=',  $this->data->date_start)->where('date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $parentSchools = $query->where('nac_id',  $this->data->nac_id)->where('date', '>=',  $this->data->date_start)->where('date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $parentSchools = $query->where('date', '>=',  $this->data->date_start)->where('date', '<=',  $this->data->date_end)->where('created_by',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $parentSchools = $query->where('nac_id',  $this->data->nac_id)->where('created_by',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $parentSchools = $query->where('nac_id',  $this->data->nac_id)->where('date', '>=',  $this->data->date_start)->where('date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $parentSchools = $query->where('nac_id',  $this->data->nac_id)->where('date', '>=',  $this->data->date_start)->where('date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        return new ParentSchoolCollection($parentSchools);
    }
}
