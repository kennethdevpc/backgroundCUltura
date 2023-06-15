<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\CulturalCirculationCollection;
use App\Models\CulturalCirculation;
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

class CulturalCirculationExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $culturalCirculations;
    public function __construct($data)
    {
        $this->data = $data;
        $this->culturalCirculations =  new CulturalCirculation();
    }

    public function map($culturalCirculation): array
    {
        return [
            $culturalCirculation->id ?? '',
            $culturalCirculation->consecutive ?? '',
            $culturalCirculation->datasheet ?? '',
            $culturalCirculation->user->name ?? '',
            $culturalCirculation->user->profile->document_number ?? '',
            $culturalCirculation->user->profile->nac->name ?? '',
            $culturalCirculation->user->roles[0]->name ?? '',
            date('Y-m-d', strtotime($culturalCirculation->date)) ?? '',
            $culturalCirculation->keyactors_circulation_alliance ?? '',
            $culturalCirculation->pec->consecutive  ?? '' . ' - ' . $culturalCirculation->pec->created_at?->format('Y-m-d G:i:s'),
            $culturalCirculation->datasheet_planning->datasheet ?? '',
            $culturalCirculation->event_name ?? '',
            $this->data($culturalCirculation->filter_level ?? '', 'filter_level'),
            $culturalCirculation->description ?? '',
            $culturalCirculation->nac->name ?? '',
            $culturalCirculation->other_nac ?? '',
            $culturalCirculation->quantity_members ?? '',
            $culturalCirculation->public_characteristics ?? '',
            $culturalCirculation->cultural_right->name ?? '',
            $this->data($culturalCirculation->lineament_id ?? '', 'lineaments'),
            $culturalCirculation->orientation->name ?? '',
            $this->data($culturalCirculation->value ?? '' , 'values'),
            $culturalCirculation->artistic_expertise ?? '' ,
            $culturalCirculation->participation_observations ?? '' ,
            $culturalCirculation->number_attendees ?? '' ,
            $culturalCirculation->created_at?->format('Y-m-d G:i:s'),
            $this->data($culturalCirculation->status ?? '', 'status'),
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            'CIRCULACION',
            'USUARIO',
            'CEDULA USUARIO',
            'NAC USUARIO',
            'ROL USUARIO',
            'FECHA ACTIVIDAD',
            'ACTORES CLAVES PARA LA CIRCULACIÓN',
            'PEC',
            'FICHAS METODOLOGICA PLANEACION',
            'NOMBRE DEL EVENTO',
            'NIVEL DE DOMINIO PARTICIPANTE',
            'DESCRIPCION',
            'TERRITORIO DE CIRCULACIÓN',
            'OTRO TERRITORIO ¿CUÁL?',
            'CANTIDAD DE INTEGRANTES DEL SEMILLERO',
            'CARACTERISTICAS PÚBLICO ASISTENTE',
            'DERECHO CULTURAL',
            'LINEAMIENTOS',
            'ORIENTACIONES',
            'VALOR',
            'EXPERTICIA ARTÍSTICA A TRABAJAR',
            'OBSERVACIONES DE TU PARTICIPACIÓN',
            'NUMERO DE ASISTENTES',
            'FECHA CREACION',
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
            'R' => 37,
            'S' => 37,
            'T' => 37,
            'U' => 37,
            'V' => 37,
            'W' => 37,
            'X' => 37,
            'Y' => 37,
            'Z' => 37,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:Z1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:Z')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:Z');
            },
        ];
    }
    public function collection()
    {

        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $fecha_start = Carbon::parse($this->data['date_start'])->timezone('America/Lima');
        $fecha_end = Carbon::parse($this->data['date_end'])->timezone('America/Lima');

        $query =  $this->culturalCirculations->query();
        $culturalCirculations = $this->culturalCirculations->whereNotIn('created_by', [1, 2])->get();
        if ($this->data->status) {
            $culturalCirculations = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $culturalCirculations = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $culturalCirculations =  $query->where('activity_date',  $fecha_start)->get();
        }
        if ($this->data->user_id) {
            $culturalCirculations = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $culturalCirculations = $query->where('activity_date', '>=', $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $culturalCirculations = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $culturalCirculations = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $culturalCirculations = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $culturalCirculations = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $culturalCirculations = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        return new CulturalCirculationCollection($culturalCirculations);
    }
}
