<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\AccessLoginCollection;
use App\Models\AccessLogin;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
// added
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LoginAccessExport implements FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $accessLogin;

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessLogin = new AccessLogin();
    }

    public function map($accessLogin): array
    {
        return [
            $accessLogin->id,
            $accessLogin->user->name ?? '',
            $accessLogin->date,
            $accessLogin->time,
            $accessLogin->active == 1 ? 'ACTIVO' : 'INACTIVO',
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'Usuario',
            'Fecha',
            'Hora',
            'Estado',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:E1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11); // Letra primera fila
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:E')->getAlignment()->setHorizontal('center');
                $event->sheet->setAutoFilter('A:E');
            },
        ];
    }

    public function collection()
    {

        $query =  $this->accessLogin->query();
        $accessLogins = $this->accessLogin->get();
        if ($this->data->date_start) {
            $accessLogins =  $query->where('date', $this->data->date_start)->get();
        }
        if ($this->data->date_start && $this->data->date_end) {
            $accessLogins =  $query->where('date', '>=', $this->data->date_start)->where('date', '<=', $this->data->date_end)->get();
        }
        return new AccessLoginCollection($accessLogins);
    }
}
