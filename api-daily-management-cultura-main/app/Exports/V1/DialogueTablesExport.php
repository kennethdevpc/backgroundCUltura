<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\DialogueTableCollection;
use App\Models\DialogueTables\DialogueTable;
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


class DialogueTablesExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $dialogueTables;
    public function __construct($data)
    {
        $this->data = $data;
        $this->dialogueTables =  new DialogueTable();
    }

    public function map($dialogueTable): array
    {
        return [
            $dialogueTable->id ?? '',
            $dialogueTable->consecutive ?? '',
            $dialogueTable->user->name ?? '',
            $dialogueTable->user->profile->document_number ?? '',
            $dialogueTable->user->profile->nac->name ?? '',
            $dialogueTable->user->roles[0]->name ?? '',
            $dialogueTable->nac->name ?? '',
            $dialogueTable->assistant->count() ?? '',
            $dialogueTable->activity_date ?? '',
            date('g:i A', strtotime($dialogueTable->start_time)) ?? '',
            date('g:i A', strtotime($dialogueTable->final_hour)) ?? '',
            $dialogueTable->target_workday ?? '',
            $dialogueTable->theme ?? '',
            $dialogueTable->schedule_day ?? '',
            $dialogueTable->workday_description ?? '',
            $dialogueTable->achievements_difficulties ?? '',
            $dialogueTable->alerts ?? '',
            $dialogueTable->method_support->name ?? '',
            $dialogueTable->created_at?->format('Y-m-d G:i:s') ?? '',
            $this->data($dialogueTable->status, 'status'),
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            'USUARIO',
            'CEDULA USUARIO',
            'NAC USUARIO',
            'ROL USUARIO',
            'NAC',
            'NÚMERO DE ASISTENTES',
            'FECHA DE ACTIVIDAD',
            'HORA DE INICIO',
            'HORA FINAL',
            'OBJECTIVO DE JORNADA',
            'TEMA',
            'AGENDA DEL DIA',
            'DESCRIPCIÓN DE LA JORNADA',
            'LOGROS Y DIFICULTADES',
            'ALERTAS',
            'APOYO METODOLÓGICO',
            'FECHA CREACIÓN',
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
            'H' => 29,
            'I' => 30,
            'J' => 37,
            'K' => 37,
            'L' => 37,
            'M' => 37,
            'N' => 30,
            'O' => 37,
            'P' => 37,
            'Q' =>  35,
            'R' =>  30,
            'S' =>  37,
            'T' =>  37,
            'U' =>  37,
            'V' =>  29,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:V1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:V')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:V');
            },
        ];
    }
    public function collection()
    {
        $query =  $this->dialogueTables->query();
        $dialogueTables = $this->dialogueTables->whereNotIn('user_id', [1, 2])->get();
        if ($this->data->status) {
            $dialogueTables = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $dialogueTables = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $dialogueTables =  $query->where('activity_date',  $this->data->date_start)->get();
        }
        if ($this->data->user_id) {
            $dialogueTables = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $dialogueTables = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $dialogueTables = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $dialogueTables = $query->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $dialogueTables = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $dialogueTables = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $dialogueTables = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $this->data->date_start)->where('activity_date', '<=',  $this->data->date_end)->where('status',  $this->data->status)->get();
        }
        return new DialogueTableCollection($dialogueTables);
    }
}
