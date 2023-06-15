<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\ManagerMonitoringCollection;
use App\Models\ManagerMonitoring;
use Maatwebsite\Excel\Concerns\FromCollection;
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

class ManagerMonitoringsExport  implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $managerMonitoring;
    public function __construct($data)
    {
        $this->data = $data;
        $this->managerMonitoring  = new ManagerMonitoring();
    }

    public function map($managerMonitoring): array
    {
        return [
            $managerMonitoring->id,
            $managerMonitoring->consecutive,
            $managerMonitoring->user->name ?? '',
            $managerMonitoring->user->profile->document_number ?? '',
            $managerMonitoring->user->roles[0]->name ?? '',
            $managerMonitoring->user->profile->nac->name ?? '',
            $managerMonitoring->nac->name ?? '',
            $managerMonitoring->monitor->name ?? '',
            $managerMonitoring->activity_date ?? '',
            date('g:i A', strtotime($managerMonitoring->start_time)),
            date('g:i A', strtotime($managerMonitoring->final_time)),
            $managerMonitoring->target_tracking,
            $managerMonitoring->cultural_process,
            $managerMonitoring->cultural_guidelines,
            $managerMonitoring->cultural_communication,
            $managerMonitoring->difficulty_cultural_process,
            $managerMonitoring->proposal_improvement,
            $managerMonitoring->created_at?->format('Y-m-d G:i:s'),
            $this->data($managerMonitoring->status, 'status')
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
            "ROL",
            "NAC USUARIO",
            'NAC',
            'MONITOR',
            'FECHA DE ACTIVIDAD',
            'HORA INICIO',
            'HORA FINAL',
            'OBJECTIVO DE SEGUIMIENTO',
            'PROCESO CULTURAL',
            'PAUTAS CULTURALES',
            'COMUNICACIÓN CULTURAL ',
            'DIFICULTAD PROCESO CULTURAL',
            'MEJORA DE LA PROPUESTA',
            'FECHA DE CREACIÓN',
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
            'P' => 100,
            'Q' => 100,
            'R' => 37,
            'S' => 37,
            'T' => 37,
            'U' => 37,
            'V' => 37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:V1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:V')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:V');
            },
        ];
    }


    public function collection()
    {
        $query =    $this->managerMonitoring->query();
        $managerMonitorings =  $this->managerMonitoring->get();
        if ($this->data->status) {
            $managerMonitorings = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $managerMonitorings = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $managerMonitorings =  $query->where('activity_date',  $this->data->date_start)->get();
        }
        if ($this->data->user_id) {
            $managerMonitorings = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $managerMonitorings = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $managerMonitorings = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $managerMonitorings = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $managerMonitorings = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $managerMonitorings = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $managerMonitorings = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        return new ManagerMonitoringCollection($managerMonitorings);
    }
}
