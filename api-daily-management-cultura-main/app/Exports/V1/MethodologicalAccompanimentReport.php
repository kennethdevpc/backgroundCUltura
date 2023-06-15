<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\MethodologicalAccompanimentCollection;
use App\Models\MethodologicalAccompaniment;
use App\Models\Role;
use App\Traits\FunctionGeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MethodologicalAccompanimentReport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;

    protected  $data;
    protected  $methodologicalAccompaniment;

    public function __construct($data)
    {
        $this->data = $data;
        $this->methodologicalAccompaniment =  new MethodologicalAccompaniment();
    }

    public function map($methodologicalAccompaniment): array
    {
        return [
            $methodologicalAccompaniment->id,
            $methodologicalAccompaniment->consecutive,
            $methodologicalAccompaniment->user->name ?? '',
            $methodologicalAccompaniment->user->profile->document_number ?? '',
            $methodologicalAccompaniment->user->profile->role->name ?? '',
            $methodologicalAccompaniment->user->profile->nac->name ?? '',
            $methodologicalAccompaniment->nac->name ?? '',
            /* MOSTRAR TODOS LOS ROLES */
            $this->rolesAccompaniment($methodologicalAccompaniment->roles),
            $methodologicalAccompaniment->date,
            $methodologicalAccompaniment->aspects,
            $methodologicalAccompaniment->others,
            $methodologicalAccompaniment->objective_visit,
            $methodologicalAccompaniment->aspects_comments,
            $methodologicalAccompaniment->comments,
            $methodologicalAccompaniment->assistants->count() ?? '',
            $methodologicalAccompaniment->created_at?->format('Y-m-d G:i:s'),
            $this->data($methodologicalAccompaniment->status, 'status'),
        ];
    }

    public function rolesAccompaniment($roles){
        $temps = explode(',', $roles);
        $results = "";
        $i = 1;
        foreach ($temps as $id) {
            $role = DB::table('roles')->where('id', '=', $id)->get();
            $results .= $i != count($temps) ? $role[0]->name . ', ' : $role[0]->name;
            $i++;
        }
        return $results;
    }

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            'USUARIO',
            'CEDULA USUARIO',
            'ROL USUARIO',
            'NAC USUARIO',
            'NAC',
            'ROLES',
            'FECHA ACOMPAÑAMIENTO',
            'ASPECTOS PREVIOS A TENER EN CUENTA',
            'OTROS: ¿CUAL?',
            'OBJETIVO DE LA VISITA',
            'ASPECTOS PREVIOS A TENER EN CUENTA',
            'OBSERVACIONES',
            'CANTIDAD ASISTENTES',
            'FECHA CREACIÓN',
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

        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $fecha_start = Carbon::parse($this->data['date_start'])->timezone('America/Lima');
        $fecha_end = Carbon::parse($this->data['date_end'])->timezone('America/Lima');

        $query =  $this->methodologicalAccompaniment->query();
        $methodologicalAccompaniments = $this->methodologicalAccompaniment->whereNotIn('created_by', [1,2])->get();
        if ($this->data->status) {
            $methodologicalAccompaniments = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $methodologicalAccompaniments = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $methodologicalAccompaniments =  $query->where('activity_date',  $fecha_start)->get();
        }
        if ($this->data->user_id) {
            $methodologicalAccompaniments = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $methodologicalAccompaniments = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end) {
            $methodologicalAccompaniments = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $methodologicalAccompaniments = $query->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $methodologicalAccompaniments = $query->where('nac_id',  $this->data->nac_id)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $methodologicalAccompaniments = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $methodologicalAccompaniments = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=',  $fecha_end)->where('status',  $this->data->status)->get();
        }
        return new MethodologicalAccompanimentCollection($methodologicalAccompaniments);
    }
}
