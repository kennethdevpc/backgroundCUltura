<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\UserCollection;
use App\Models\User;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;

class AssistantExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $assintant;

    public function __construct($data)
    {
        $this->data = $data;
        $this->assintant = new User();
    }

    public function map($assintant): array
    {
        return [
            $assintant->id,
            $assintant->assistant_name,
            $assintant->assistant_document_number,
            $assintant->assistant_position,
            $assintant->nac_id,
            $assintant->assistant_phone,
            $assintant->assistant_email
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            'CONSECUTIVO',
            "USUARIO",
            "ROL USUARIO",
            "NAC USUARIO",
            "CEDULA USUARIO",
            'NOMBRES Y APELLIDOS',
            'NUIP',
            'CARGO',
            'BARRIO',
            'TELÉFONO',
            'EMAIL'
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 50,
            'C' => 20,
            'D' => 30,
            'E' => 10,
            'F' => 30,
            'G' => 30,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:L1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:L')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('D')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:G');
            },
        ];
    }
    public function collection()
    {
        $assintants = $this->assintant->get();
        return  $assintants;
    }
}
