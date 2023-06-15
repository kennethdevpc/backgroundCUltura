<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\MethodologicalSheetsOneCollection;
use App\Http\Resources\V1\CulturalCirculationCollection;
use App\Http\Resources\V1\CulturalEnsembleCollection;
use App\Models\CulturalEnsemble;
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


class CulturalEnsembleExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $culturalEnsembles;
    public function __construct($data)
    {
        $this->data = $data;
        $this->culturalEnsembles =  new CulturalEnsemble();
    }

    public function map($culturalEnsemble): array
    {
        return [
            $culturalEnsemble->id,
            $culturalEnsemble->consecutive,
            $culturalEnsemble->datasheet ?? '',
            $culturalEnsemble->user->name ?? '',
            $culturalEnsemble->user->profile->document_number ?? '',
            $culturalEnsemble->user->profile->nac->name ?? '',
            $culturalEnsemble->user->roles[0]->name ?? '',
            date('Y-m-d', strtotime($culturalEnsemble->date)),
            $culturalEnsemble->pec->consecutive . ' - ' . $culturalEnsemble->pec->created_at?->format('Y-m-d G:i:s'),
            $culturalEnsemble->datasheetPlanning->datasheet ?? '',
            $this->data($culturalEnsemble->filter_level, 'filter_level'),
            $culturalEnsemble->assembly_characteristics,
            $culturalEnsemble->description,
            $culturalEnsemble->objective_process,
            $culturalEnsemble->public_characteristics,
            $culturalEnsemble->culturalRight->name ?? '',
            $this->data($culturalEnsemble->lineament_id, 'lineaments'),
            $culturalEnsemble->orientation->name ?? '',
            $this->data($culturalEnsemble->value, 'values'),
            $culturalEnsemble->artistic_expertise,
            $this->split($culturalEnsemble->evaluate_aspects, 'evaluate_aspects'),
            $culturalEnsemble->evaluate_aspects_comments,
            $culturalEnsemble->number_attendees,
            $culturalEnsemble->created_at?->format('Y-m-d G:i:s'),
            $this->data($culturalEnsemble->status, 'status'),
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            'ENSAMBLE CULTURAL',
            'USUARIO',
            'CEDULA USUARIO',
            'NAC USUARIO',
            'ROL USUARIO',
            'FECHA',
            'PEC',
            'FICHAS METODOLOGICA PLANEACION',
            'NIVEL DE DOMINIO DEL SEMILLERO',
            'CARACTERÍSTICAS DEL ENSAMBLE',
            'DESCRIPCION ',
            'OBJETIVO DEL PROCESO',
            'CARACTERISTICAS PÚBLICO ASISTENTE',
            'DERECHO CULTURAL',
            'LINEAMIENTOS',
            'ORIENTACIONES',
            'VALOR',
            'EXPERTICIA ARTÍSTICA A TRABAJAR',
            'ASPECTOS A EVALUAR',
            'COMENTARIOS ',
            'NUMERO DE ASISTENTES',
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
            'I' => 46,
            'J' => 42,
            'K' => 37,
            'L' => 40,
            'M' => 30,
            'N' => 48,
            'O' => 37,
            'P' => 37,
            'Q' => 37,
            'R' => 37,
            'S' => 46,
            'T' => 30,
            'U' => 37,
            'V' => 30,
            'W' => 37,
            'X' => 37,
            'Y' => 37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:Y1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:Y')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:Y');
            },
        ];
    }
    public function collection()
    {

        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $fecha_start = Carbon::parse($this->data['date_start'])->timezone('America/Lima');
        $fecha_end = Carbon::parse($this->data['date_end'])->timezone('America/Lima');

        $query =  $this->culturalEnsembles->query();
        // SE ANADE CONDICION PARA EVITAR MOSTRAR REPORTES CREADOS POR ROOT Y ADMIN
        $culturalEnsembles = $this->culturalEnsembles->whereNotIn('created_by', [1, 2])->get();
        if ($this->data->status) {
            $culturalEnsembles = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $culturalEnsembles = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $culturalEnsembles =  $query->where('activity_date',  $fecha_start)->get();
        }
        if ($this->data->user_id) {
            $culturalEnsembles = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $culturalEnsembles = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $culturalEnsembles = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $culturalEnsembles = $query->where('activity_date', '>=', $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $culturalEnsembles = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $culturalEnsembles = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $culturalEnsembles = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        return new CulturalEnsembleCollection($culturalEnsembles);
    }
}
