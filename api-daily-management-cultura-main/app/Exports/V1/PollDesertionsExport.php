<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\PollDesertionCollection;
use App\Models\PollDesertion;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PollDesertionsExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $pollDesertion;

    public function __construct($data)
    {
        $this->data = $data;
        $this->pollDesertion = new PollDesertion();
    }

    public function map($pollDesertion): array
    {
        return [
            $pollDesertion->id,
            $pollDesertion->consecutive,
            $pollDesertion->user->name ?? '',
            $pollDesertion->user->profile->document_number ?? '',
            $pollDesertion->user->profile->nac->name ?? '',
            $pollDesertion->user->roles[0]->name ?? '',
            $pollDesertion->beneficiary->full_name ?? '',
            $pollDesertion->nac->name ?? '',
            $pollDesertion->date,
            $this->data($pollDesertion->beneficiary_attrition_factors, 'beneficiary_attrition_factors'),
            $pollDesertion->beneficiary_attrition_factor_other,
            $pollDesertion->disinterest_apathy == 1 ? 'SI' : 'NO',
            $pollDesertion->disinterest_apathy_explanation,
            $pollDesertion->reintegration == 1 ? 'SI' : 'NO',
            $pollDesertion->reintegration_explanation,
            $pollDesertion->created_at->format('Y-m-d G:i:s'),
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
            'BENEFICIARIO',
            'NAC',
            'FECHA DESERCIÓN',
            'FACTOR DE DESERCIÓN',
            'OTRO FACTOR',
            '¿CREE USTED QUE HUBO DESINTERÉS Y APATÍA?',
            'EXPLICACIÓN',
            'REINTEGRACIÓN',
            'EXPLICACIÓN DE REINTEGRACIÓN',
            'FECHA CREACIÓN',
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
            'J' => 28,
            'K' => 37,
            'L' => 56,
            'M' => 37,
            'N' => 37,
            'O' => 40,
            'P' => 37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:P1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->getStyle('A:P')->getAlignment()->setHorizontal('center');
            },
        ];
    }
    public function collection()
    {
        $query =  $this->pollDesertion->query();
        $pollDesertions = $this->pollDesertion->get();
        if ($this->data->status) {
            $pollDesertions = $query->where('status', $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $pollDesertions = $query->where('nac_id', $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $pollDesertions =  $query->where('date', $this->data->date_start)->get();
        }
        if ($this->data->user_id) {
            $pollDesertions = $query->where('user_id', $this->data->user_id)->get();
        }
        if ($this->data->date_start && $this->data->date_end) {
            $pollDesertions = $query->where('date', '>=', $this->data->date_start)->where('date', '<=', $this->data->date_end)->get();
        }
        if ($this->data->nac_id && $this->data->date_start && $this->data->date_end) {
            $pollDesertions = $query->where('nac_id', $this->data->nac_id)->where('date', '>=', $this->data->date_start)->where('date', '<=', $this->data->date_end)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end && $this->data->user_id) {
            $pollDesertions = $query->where('date', '>=', $this->data->date_start)->where('date', '<=', $this->data->date_end)->where('user_id', $this->data->user_id)->get();
        }
        if ($this->data->nac_id && $this->data->user_id) {
            $pollDesertions = $query->where('nac_id', $this->data->nac_id)->where('user_id', $this->data->user_id)->get();
        }
        if ($this->data->nac_id && $this->data->date_start && $this->data->date_end && $this->data->status) {
            $pollDesertions = $query->where('nac_id', $this->data->nac_id)->where('date', '>=', $this->data->date_start)->where('date', '<=', $this->data->date_end)->where('status', $this->data->status)->get();
        }
        if ($this->data->nac_id && $this->data->date_start && $this->data->date_end && $this->data->status && $this->data->user_id) {
            $pollDesertions = $query->where('nac_id', $this->data->nac_id)->where('date', '>=', $this->data->date_start)->where('date', '<=', $this->data->date_end)->where('status', $this->data->status)->get();
        }
        return  new PollDesertionCollection($pollDesertions);
    }
}
