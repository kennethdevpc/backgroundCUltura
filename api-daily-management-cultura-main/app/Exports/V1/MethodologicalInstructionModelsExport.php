<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\MethodologicalInstructionCollection;
use App\Models\MethodologicalInstructionModel;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MethodologicalInstructionModelsExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $methodologicalInstructionModel;
    public function __construct($data)
    {
        $this->data = $data;
        $this->methodologicalInstructionModel  = new MethodologicalInstructionModel();
    }

    public function map($methodologicalInstructionModel): array
    {
        return [
            $methodologicalInstructionModel->id,
            $methodologicalInstructionModel->consecutive,
            $methodologicalInstructionModel->user->name ?? '',
            $methodologicalInstructionModel->user->profile->document_number ?? '',
            $methodologicalInstructionModel->user->roles[0]->name ?? '',
            $methodologicalInstructionModel->user->profile->nac->name ?? '',
            $methodologicalInstructionModel->nac->name ?? '',
            $methodologicalInstructionModel->place ?? '',
            $methodologicalInstructionModel->expertise->name ?? '',
            $methodologicalInstructionModel->activity_date ?? '',
            date('g:i A', strtotime($methodologicalInstructionModel->start_time)),
            date('g:i A', strtotime($methodologicalInstructionModel->final_hour)),
            $methodologicalInstructionModel->goals_met,
            $methodologicalInstructionModel->explanation,
            $methodologicalInstructionModel->pedagogical_comments,
            $methodologicalInstructionModel->technical_practical_comments,
            $methodologicalInstructionModel->methodological_comments,
            $methodologicalInstructionModel->others_observations,
            $methodologicalInstructionModel->assistants->count() ?? '0',
            $methodologicalInstructionModel->created_at?->format('Y-m-d G:i:s'),
            $this->data($methodologicalInstructionModel->status, 'status'),
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
            'NAC',
            'LUGAR',
            'EXPERTICIA',
            'FECHA DE ACTIVIDAD',
            'HORA INICIO',
            'HORA FINAL',
            'SE CUMPLIÓ EL OBJETIVO',
            'EXPLICACIÓN',
            'PEDAGÓGICO',
            'TÉCNICO PRACTICO',
            'METODOLÓGICO *',
            'OTROS Y OBSERVACIONES',
            'NÚMERO DE ASISTENTES',
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
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:U1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:U')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:W');
            },
        ];
    }

    public function collection()
    {
        $query =  $this->methodologicalInstructionModel->query();
        $methodologicalInstructionModels =  $this->methodologicalInstructionModel->whereNotIn('created_by', [1, 2])->get();
        if ($this->data->status) {
            $methodologicalInstructionModels = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $methodologicalInstructionModels = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $methodologicalInstructionModels =  $query->where('activity_date',  $this->data->date_start)->get();
        }
        if ($this->data->user_id) {
            $methodologicalInstructionModels = $query->where('created_by',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $methodologicalInstructionModels = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $methodologicalInstructionModels = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $methodologicalInstructionModels = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('created_by',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $methodologicalInstructionModels = $query->where('nac_id',  $this->data->nac_id)->where('created_by',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $methodologicalInstructionModels = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $methodologicalInstructionModels = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        return new MethodologicalInstructionCollection($methodologicalInstructionModels);
    }
}
