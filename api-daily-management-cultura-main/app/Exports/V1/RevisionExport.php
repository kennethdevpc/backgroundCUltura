<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Resources\V1\DialogueTableCollection;
use App\Models\User;
use App\Traits\FunctionGeneralTrait;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class RevisionExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $user;
    public function __construct($data)
    {
        $this->data = $data;
        $this->user =  User::take(50)->get();
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->profile->role->name ?? '',
            $user->control_data ?? '',
        ];
    }
    //
    public function headings(): array
    {
        return [
            '#',
            'NOMBRE',
            'ROL',
            'EN REVISION',
            'RECHAZADAS',
            'REVISADO',
            'APROBADAS',
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
            'G' => 35,
            'H' => 37,
            'I' => 37,
            'J' => 35,
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
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:Z')->getAlignment()->setHorizontal('center');
                $event->sheet->setAutoFilter('A:Z');
            },
        ];
    }

    public function collection()
    {
        $query =  $this->user->query();
        $users = $query->get();
        return new DialogueTableCollection($users);
    }
}
