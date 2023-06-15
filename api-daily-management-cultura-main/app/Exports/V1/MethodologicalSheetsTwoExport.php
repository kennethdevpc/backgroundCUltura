<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\MethodologicalSheetsOneCollection;
use App\Http\Resources\V1\MethodologicalSheetsTwoCollection;
use App\Models\MethodologicalSheetsOne;
use App\Models\MethodologicalSheetsTwo;
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


class MethodologicalSheetsTwoExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $MethodologicalSheetsTwos;
    public function __construct($data)
    {
        $this->data = $data;
        $this->MethodologicalSheetsTwos =  new MethodologicalSheetsTwo();
    }

    public function map($MethodologicalSheetsTwo): array
    {
        return [
            $MethodologicalSheetsTwo->id,
            $MethodologicalSheetsTwo->consecutive,
            $MethodologicalSheetsTwo->datasheet,
            $MethodologicalSheetsTwo->createdBy->name,
            $MethodologicalSheetsTwo->createdBy->profile->document_number,
            $MethodologicalSheetsTwo->createdBy->profile->nac->name,
            $MethodologicalSheetsTwo->createdBy->roles[0]->name,
            $this->data($MethodologicalSheetsTwo->activity_type, 'activity_type'),
            date('Y-m-d', strtotime($MethodologicalSheetsTwo->date_ini)),
            date('Y-m-d', strtotime($MethodologicalSheetsTwo->date_fin)),
            $MethodologicalSheetsTwo->keyactors_participating_community,
            $MethodologicalSheetsTwo->objective_process,
            $MethodologicalSheetsTwo->reached_target == 1 ? 'SI' : 'NO',
            $MethodologicalSheetsTwo->sustein,
            $MethodologicalSheetsTwo->number_attendees,
            $MethodologicalSheetsTwo->beneficiary->count(),
            $MethodologicalSheetsTwo->created_at?->format('Y-m-d G:i:s'),
            $this->data($MethodologicalSheetsTwo->status, 'status'),
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            'FICHA',
            'USUARIO',
            'CEDULA USUARIO',
            'NAC USUARIO',
            'ROL USUARIO',
            'TIPO ACTIVIDAD',
            'FECHA INICIAL',
            'FECHA FINAL',
            'ACTORES CLAVES',
            'OBJETIVO DEL PROCESO',
            '¿SE ALCANZO EL OBJETIVO?',
            'SUSTENTACIÓN',
            'CANTIDAD DE ASISTENTES',
            'NUMERO DE AFORO',
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
            'R' => 37,
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
                $event->sheet->getStyle('E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:R');
            },
        ];
    }
    public function collection()
    {

        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $fecha_start = Carbon::parse($this->data['date_start'])->timezone('America/Lima');
        $fecha_end = Carbon::parse($this->data['date_end'])->timezone('America/Lima');

        $query =  $this->MethodologicalSheetsTwos->query();
        $MethodologicalSheetsTwos = $this->MethodologicalSheetsTwos->get();
        if ($this->data->status) {
            $MethodologicalSheetsTwos = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $MethodologicalSheetsTwos = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $MethodologicalSheetsTwos =  $query->where('activity_date',  $fecha_start)->get();
        }
        if ($this->data->user_id) {
            $MethodologicalSheetsTwos = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $MethodologicalSheetsTwos = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $MethodologicalSheetsTwos = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $MethodologicalSheetsTwos = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $MethodologicalSheetsTwos = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $MethodologicalSheetsTwos = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $MethodologicalSheetsTwos = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        return new MethodologicalSheetsTwoCollection($MethodologicalSheetsTwos);
    }
}
