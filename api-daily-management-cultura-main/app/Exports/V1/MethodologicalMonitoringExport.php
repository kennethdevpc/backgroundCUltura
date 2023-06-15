<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\MethodologicalMonitoringCollection;
use App\Models\MethodologicalMonitoring;
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

class MethodologicalMonitoringExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;

    protected  $data;
    protected  $methodologicalMonitoring;

    public function __construct($data)
    {
        $this->data = $data;
        $this->methodologicalMonitoring =  new MethodologicalMonitoring();
    }

    public function map($methodologicalMonitoring): array
    {
        return [
            $methodologicalMonitoring->id,
            $methodologicalMonitoring->consecutive,
            $methodologicalMonitoring->user->name ?? '',
            $methodologicalMonitoring->user->profile->document_number ?? '',
            $methodologicalMonitoring->user->profile->role->name ?? '',
            $methodologicalMonitoring->user->profile->nac->name ?? '',
            $methodologicalMonitoring->nac->name ?? '',
            $this->rolesMonitorings($methodologicalMonitoring->id) ?? '',
            // '', // Provicional mientras hacen el cambio de form
            $methodologicalMonitoring->date_realization,
            $methodologicalMonitoring->datasheet ?? '',
            $methodologicalMonitoring->date_planning_ini . ' - ' . $methodologicalMonitoring->date_planning_fin ?? '',
            $methodologicalMonitoring->cultural_right->name ?? '',
            $this->data($methodologicalMonitoring->lineament_id, 'lineaments'),
            $methodologicalMonitoring->orientation->name ?? '',
            $this->data($methodologicalMonitoring->value, 'values'),
            $methodologicalMonitoring->objective_process,
            $methodologicalMonitoring->comments,
            $this->data($methodologicalMonitoring->strengthening_type, 'strengthening_types'),
            $methodologicalMonitoring->strengthening_comments,
            $methodologicalMonitoring->topics_to_strengthened,
            $methodologicalMonitoring->aggregates->count() ?? '',
            $methodologicalMonitoring->created_at?->format('Y-m-d G:i:s'),
            $this->data($methodologicalMonitoring->status, 'status'),
        ];
    }

    public function rolesMonitorings($id){
        $temps = DB::table('methodological_monitorings_roles')->where('monitoring_id', '=', $id)->get();
        $results = "";
        $i = 1;
        foreach ($temps as $rol_monitoring) {
            $role = DB::table('roles')->where('id', '=', $rol_monitoring->role_id)->get();
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
            'ROL',
            'FECHA',
            'FICHA NO.',
            'FECHA RANGO PLANEACIÓN',
            'DERECHO CULTURAL',
            'LINEAMIENTO',
            'ORIENTACIÓN',
            'VALOR',
            'OBJETIVO DEL PROCESO',
            'OBSERVACIONES',
            'TIPO DE FORTALECIMIENTO',
            'COMENTARIOS DEL FORTALECIMIENTO',
            'TEMÁTICAS A FORTALECER SEGÚN ROL',
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
            'R' => 32,
            'S' => 32,
            'T' => 37,
            'U' => 37,
            'V' => 37,
            'W' => 37,
            'X' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:X1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:X')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:X');
            },
        ];
    }
    public function collection()
    {

        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $fecha_start = Carbon::parse($this->data['date_start'])->timezone('America/Lima');
        $fecha_end = Carbon::parse($this->data['date_end'])->timezone('America/Lima');

        $query =  $this->methodologicalMonitoring->query();
        $methodologicalMonitorings = $this->methodologicalMonitoring->whereNotIn('created_by', [1,2])->get();
        if ($this->data->status) {
            $methodologicalMonitorings = $query->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id) {
            $methodologicalMonitorings = $query->where('nac_id',  $this->data->nac_id)->get();
        }
        if ($this->data->date_start) {
            $methodologicalMonitorings =  $query->where('activity_date',  $fecha_start)->get();
        }
        if ($this->data->user_id) {
            $methodologicalMonitorings = $query->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->date_start &&  $this->data->date_end) {
            $methodologicalMonitorings = $query->where('activity_date', '>=', $fecha_start)->where('activity_date', '<=', $fecha_end)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start && $this->data->date_end) {
            $methodologicalMonitorings = $query->where('nac_id',  $this->data->nac_id)->where('activity_date', '>=',  $fecha_start)->where('activity_date', '<=', $fecha_end)->get();
        }
        if ($this->data->date_start &&   $this->data->date_end &&  $this->data->user_id) {
            $methodologicalMonitorings = $query->where('activity_date', '>=', $fecha_start)->where('activity_date', '<=', $fecha_end)->where('user_id',  $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->user_id) {
            $methodologicalMonitorings = $query->where('nac_id', $this->data->nac_id)->where('user_id', $this->data->user_id)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start &&  $this->data->date_end &&  $this->data->status) {
            $methodologicalMonitorings = $query->where('nac_id', $this->data->nac_id)->where('activity_date', '>=', $fecha_start)->where('activity_date', '<=', $fecha_end)->where('status',  $this->data->status)->get();
        }
        if ($this->data->nac_id &&  $this->data->date_start && $this->data->date_end &&  $this->data->status &&  $this->data->user_id) {
            $methodologicalMonitorings = $query->where('nac_id', $this->data->nac_id)->where('activity_date', '>=', $fecha_start)->where('activity_date', '<=', $fecha_end)->where('status',  $this->data->status)->get();
        }
        return new MethodologicalMonitoringCollection($methodologicalMonitorings);
    }
}
