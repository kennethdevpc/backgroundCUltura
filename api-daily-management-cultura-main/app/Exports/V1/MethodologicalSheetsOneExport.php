<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\MethodologicalSheetsOneCollection;
use App\Models\MethodologicalSheetsOne;
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


class MethodologicalSheetsOneExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $MethodologicalSheetsOnes;
    public function __construct($data)
    {
        $this->data = $data;
        $this->MethodologicalSheetsOnes =  new MethodologicalSheetsOne();
    }

    public function map($MethodologicalSheetsOne): array
    {
        return [
            $MethodologicalSheetsOne->id,
            $MethodologicalSheetsOne->consecutive,
            $MethodologicalSheetsOne->createdBy->name,
            $MethodologicalSheetsOne->createdBy->profile->document_number,
            $MethodologicalSheetsOne->createdBy->profile->nac->name,
            $MethodologicalSheetsOne->createdBy->roles[0]->name,
            $MethodologicalSheetsOne->semillero_name,
            date('Y-m-d', strtotime($MethodologicalSheetsOne->date_ini)),
            date('Y-m-d', strtotime($MethodologicalSheetsOne->date_fin)),
            $MethodologicalSheetsOne->group->name,
            $this->data($MethodologicalSheetsOne->filter_level, 'filter_level'),
            $MethodologicalSheetsOne->cultural_right->name,
            $this->data($MethodologicalSheetsOne->lineament_id, 'lineaments'),
            $MethodologicalSheetsOne->orientation->name,
            $this->data($MethodologicalSheetsOne->value, 'values'),
            $MethodologicalSheetsOne->worked_expertise,
            $MethodologicalSheetsOne->characteristics_process,
            $MethodologicalSheetsOne->objective_process,
            $MethodologicalSheetsOne->comments,
            $MethodologicalSheetsOne->created_at?->format('Y-m-d G:i:s'),
            $this->data($MethodologicalSheetsOne->status, 'status'),
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
            'SEMILLERO CULTURAL',
            'FECHA INICIAL',
            'FECHA FINAL',
            'GRUPO',
            'FILTRO',
            'DERECHO CULTURAL',
            'LINEAMIENTOS',
            'ORIENTACIONES',
            'VALOR',
            'EXPERTICIA ARTÍSTICA A TRABAJAR',
            'CARACTERÍSTICAS DEL PROCESO A TRABAJAR',
            'OBJETIVO DEL PROCESO',
            'OBSERVACIONES',
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
            'K' => 37,
            'L' => 37,
            'M' => 37,
            'N' => 37,
            'O' => 37,
            'P' => 42,
            'Q' =>  52,
            'R' =>  32,
            'S' =>  37,
            'T' =>  37,
            'U' =>  37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:U1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11); // Letra primera fila
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:U')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:U');
            },
        ];
    }

    public function collection()
    {

        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $fecha_start = Carbon::parse($this->data['date_start'])->timezone('America/Lima');
        $fecha_end = Carbon::parse($this->data['date_end'])->timezone('America/Lima');

        $query =  $this->MethodologicalSheetsOnes->query();
        $MethodologicalSheetsOnes = $this->MethodologicalSheetsOnes->get();
        if ($this->data->status) {
            $MethodologicalSheetsOnes = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $MethodologicalSheetsOnes = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $MethodologicalSheetsOnes =  $query->where('activity_date',  $fecha_start)->get();
        }
        if ($this->data->user_id) {
            $MethodologicalSheetsOnes = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $MethodologicalSheetsOnes = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $MethodologicalSheetsOnes = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $MethodologicalSheetsOnes = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $MethodologicalSheetsOnes = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $MethodologicalSheetsOnes = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $MethodologicalSheetsOnes = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        return new MethodologicalSheetsOneCollection($MethodologicalSheetsOnes);
    }
}
