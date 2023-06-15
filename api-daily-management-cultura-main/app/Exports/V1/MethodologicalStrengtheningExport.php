<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Resources\V1\DialogueTableCollection;
use App\Models\BinnacleTerritorie;
use App\Models\MethodologicalAccompaniment;
use App\Models\MethodologicalStrengthening;
use App\Models\MonitoringReports;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MethodologicalStrengtheningExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $monitoringReport;
    public function __construct($data)
    {
        $this->data = $data;
        $this->monitoringReport =  new MethodologicalStrengthening();
    }

    public function map($monitoringReport): array
    {
        return [
            $monitoringReport->id ?? '',
            $monitoringReport->consecutive ?? '',
            $monitoringReport->user->name ?? '',
            $monitoringReport->user->profile->document_number ?? '',
            $monitoringReport->user->profile->nac->name ?? '',
            $monitoringReport->user->roles[0]->name ?? '',
            $monitoringReport->nac->name ?? '',
            $monitoringReport->date,
            $monitoringReport->cultural_right->name,
            $this->data($monitoringReport->lineament_id, 'lineaments'),
            $monitoringReport->orientation->name,
            $this->data($monitoringReport->value, 'values'),
            $monitoringReport->comments,
            $monitoringReport->metho_coordinator->name ?? '',
            $monitoringReport->assistants->count() ?? '0',
            /* $monitoringReport->date ?? '',
            $this->data($monitoringReport->aspects, 'aspects'),
            $monitoringReport->aspects_comments ?? '',
            $monitoringReport->others ?? '',
            $monitoringReport->objective_visit ?? '',
            $monitoringReport->aspects_comments ?? '',
            $monitoringReport->comments ?? '',
            $monitoringReport->assistants->count(),*/
            $monitoringReport->created_at?->format('Y-m-d G:i:s'),
            $this->data($monitoringReport->status, 'status'),
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
            'FECHA',
            'DERECHO CULTURAL',
            'LINEAMIENTO',
            'ORIENTACION',
            'VALOR',
            'OBSERVACIONES',
            'COORDINADOR METODOLOGICO',
            'NUMERO DE ASISTENTES AGREGADOS',
            /*  'FECHA',
            'ASPECTO',
            'ASPECTOS PREVIOS A TENER EN CUENTA',
            'OTROS: ¿CUAL?',
            'OBJETIVO DE LA VISITA',
            'ASPECTOS PREVIOS A TENER EN CUENTA',
            'OBSERVACIONES',
            'NUMERO DE ASISTENTES AGREGADOS',*/
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

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query =  $this->monitoringReport->query();
        $ambassadors = $this->monitoringReport->get();

        if ($this->data->status) {
            $ambassadors =   $query->where('status', $this->data->status)->get();
        }
        if ($this->data->user_id) {
            $ambassadors =   $query->where('created_by', $this->data->user_id)->get();
        }
        if ($this->data->nac_id) {
            $ambassadors =  $query->whereHas('benefiary', function ($beneficiary) {
                $beneficiary->where('nac_id', $this->data->nac_id);
            })->get();
        }
        if ($this->data->nac_id && $this->data->status && $this->data->user_id) {
            $ambassadors =  $query->whereHas('benefiary', function ($beneficiary) {
                $beneficiary->where('nac_id', $this->data->nac_id);
            })->where('status', $this->data->status)
                ->where('created_by', $this->data->user_id)
                ->get();
        }
        return new DialogueTableCollection($ambassadors);
    }
}
