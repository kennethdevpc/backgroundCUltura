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
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersExport implements  FromCollection, WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $user;

    public function __construct($data)
    {
        $this->data = $data;
        $this->user = new User();
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->profile->contractor_full_name,
            $user->profile->document_number ?? '',
            $user->roles[0]->name ?? '',
            $user->profile->nac->name ?? '',
            $user->email,
            $user->profile->gestor->name ?? '',
            $user->profile->methodological_support->name ?? '',
            $user->profile->support_tracing_monitoring->name ?? '',
            $user->profile->psychosocial->name ?? '',
            $user->profile->instructor_leader->name ?? '',
            $user->profile->ambassador_leader->name ?? '',
            $user->created_at?->format('Y-m-d G:i:s'),
            $user->status == '1' ? 'Activo' : 'Inactivo',
        ];
    }
    //
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 37,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 30,
            'I' => 50,
            'J' => 20,
            'K' => 30,
            'L' => 30,
            'M' => 20,
            'N' => 20,
        ];
    }
    public function headings(): array
    {
        return [
            '#',
            "NOMBRE COMPLETO",
            "CEDULA USUARIO",
            "ROL USUARIO",
            "NAC USUARIO",
            "EMAIL",
            'GESTOR',
            'APOYO METODOLOGICO',
            'APOYO AL SEGUIMIENTO Y MONITOREO',
            'PSICOSOCIAL',
            'LIDER INSTRUCTOR',
            'LIDER EMBAJADOR',
            'FECHA DE CREACION',
            'ESTADO',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:N1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11); // Letra primera fila
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True); // Negrita primera fila
                $event->sheet->getStyle('A:N')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('C')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:N');
            },
        ];
    }

    public function collection()
    {
        $users = $this->user->whereNotIn('id', [1,2])->get();
        return  new UserCollection($users);
    }
}
