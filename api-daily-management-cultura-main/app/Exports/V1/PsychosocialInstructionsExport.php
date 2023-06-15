<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\PsychosocialInstructionCollection;
use App\Models\PsychosocialInstructions\PsychosocialInstruction;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PsychosocialInstructionsExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $psychosocialInstruction;
    public function __construct($data)
    {
        $this->data = $data;
        $this->psychosocialInstruction  = new PsychosocialInstruction();
    }

    public function map($psychosocialInstruction): array
    {
        return [
            $psychosocialInstruction->id,
            $psychosocialInstruction->consecutive,
            $psychosocialInstruction->user->name ?? '',
            $psychosocialInstruction->user->profile->document_number,
            $psychosocialInstruction->user->profile->nac->name,
            $psychosocialInstruction->user->roles[0]->name,
            $psychosocialInstruction->nac->name ?? '',
            $psychosocialInstruction->activity_date ?? '',
            date('G:i', strtotime($psychosocialInstruction->start_time)),
            date('G:i', strtotime($psychosocialInstruction->final_hour)),
            $psychosocialInstruction->objective_day,
            $psychosocialInstruction->themes_day,
            $psychosocialInstruction->development_themes,
            $psychosocialInstruction->conclusions_reflections_commitments,
            $psychosocialInstruction->report_followup_alerts == 1 ? 'SI' : 'NO',
            $psychosocialInstruction->created_at?->format('Y-m-d G:i:s'),
            $this->data($psychosocialInstruction->status, 'status'),

        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            'PSICOSOCIAL',
            'CEDULA PSICOSOCIAL',
            'NAC PSICOSOCIAL',
            'ROL PSICOSOCIAL',
            'NAC ACTIVIDAD',
            'FECHA DE ACTIVIDAD',
            'HORA INICIO',
            'HORA FINAL',
            'OBJECTIVO DE LA JORNADA',
            'TEMAS A TRATAR',
            'DESARROLLO DE LOS TEMAS',
            'CONCLUSIONES, REFLEXIONES Y COMPROMISOS DE LA JORNADA',
            'REPORTE ALERTAS PARA HACER SEGUIMIENTO',
            'FECHA DE CARGUE',
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
            'L' =>  37,
            'M' =>  37,
            'N' =>  37,
            'O' =>  37,
            'P' =>  37,
            'Q' =>  37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:Q1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:Q')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:Q');
            },
        ];
    }

    public function collection()
    {
        $query =    $this->psychosocialInstruction->query();
        $psychosocialInstructions =  $this->psychosocialInstruction->get();
        $query =  PsychosocialInstruction::query();
        $psychosocialInstructions = PsychosocialInstruction::whereNotIn('user_id', [1, 2])->get();
        if ($this->data->status) {
            $psychosocialInstructions = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $psychosocialInstructions = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $psychosocialInstructions =  $query->where('activity_date',  $this->data->date_start)->get();
        }
        if ($this->data->user_id) {
            $psychosocialInstructions = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $psychosocialInstructions = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $psychosocialInstructions = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $psychosocialInstructions = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $psychosocialInstructions = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $psychosocialInstructions = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $psychosocialInstructions = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        return new PsychosocialInstructionCollection($psychosocialInstructions);
    }
}
