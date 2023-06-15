<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\PollCollection;
use App\Models\Poll;
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
class PollsExport implements FromCollection,  WithMapping, WithHeadings, WithColumnWidths, WithEvents, ShouldAutoSize
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $poll;

    public function __construct($data)
    {
        $this->data = $data;
        $this->poll = new Poll();
    }

    public function map($poll): array
    {
        return [
            $poll->id,
            $poll->user->name ?? '',
            $poll->user->profile->document_number ?? '',
            $poll->user->roles[0]->name ?? '',
            $poll->user->profile->nac->name ?? '',
            $poll->neighborhood->name ?? '',
            $poll->other_neighborhoods,
            $this->data($poll->gender, 'genders'),
            $poll->age,
            date('Y-m-d', strtotime( $poll->birth_date)),
            $this->data($poll->marital_state, 'marital_status'),
            $poll->stratum,
            $poll->phone,
            $poll->email,
            $poll->number_children,
            $poll->dependents,
            $this->data($poll->relationship_head_household, 'relationship_households'),
            $this->data($poll->ethnicity, 'ethnicities'),
            $poll->victim_armed_conflict == 1 ? 'SI' : 'NO',
            $this->data($poll->single_registry_victims, 'single_registry_victims'),
            $poll->study  == 1 ? 'SI' : 'NO',
            $this->data($poll->educational_level, 'educational_levels'),
            $this->data($poll->medical_service, 'medical_services'),
            $poll->entity_name->name,
            $poll->other_entity_name,
            $this->data($poll->health_condition, 'health_conditions'),
            $poll->suffers_disease  == 1 ? 'SI' : 'NO',
            $this->data($poll->type_disease, 'type_diseases'),
            $poll->other_disease_type,
            $poll->takes_medication == 1 ? 'SI' : 'NO',
            $poll->disability == 1 ? 'SI' : 'NO',
            $this->data($poll->disability_type, 'disability_types'),
            $poll->other_disability_type,
            $poll->assessed_disability == 1 ? 'SI' : 'NO',
            $poll->receives_therapy == 1 ? 'SI' : 'NO',
            $poll->expertise->name ?? '',
            $poll->artistic_experience,
            $poll->artistic_group == 1 ? 'SI' : 'NO',
            $poll->artistic_group_name,
            $poll->role_artistic_group,
            $poll->created_at?->format('Y-m-d G:i:s'),
            $this->data($poll->status, 'status'),
        ];
    }
    //

    public function headings(): array
    {
        return [
            '#',
            "USUARIO",
            "CEDULA USUARIO",
            "ROL USUARIO",
            "NAC USUARIO",
            'BARRIO',
            'OTROS BARRIOS',
            'GENERO',
            'EDAD',
            'FECHA DE CUMPLEAÑOS',
            'ESTADO CIVIL',
            'ESTRATO',
            'TELEFONO',
            'CORREO ELECTRONICO',
            'NUMERO DE HIJOS',
            'DEPENDIENTES',
            'RELACION CON JEFE DEL HOGAR',
            'ETNIA',
            'VICTIMA DEL CONFLICTO ARMADO',
            'REGISTRO UNICO DE VICTIMAS',
            'ESTUDIA',
            'NIVEL EDUCATIVO',
            'REGIMEN DE SALUD',
            'EPS',
            'OTRA EPS',
            'ESTADO DE SALUD',
            'SUFRE ALGUNA ENFERMEDAD',
            'TIPO ENFERMEDAD',
            'OTRA ENFERMEDAD',
            'TOMA MEDICACION',
            'DISCAPACIDAD',
            'TIPO DISCAPACIDAD',
            'OTRA DISCAPACIDAD',
            'DISCAPACIDAD HA SIDO VALORADA',
            'RECIBE TERAPIA',
            'ARTE EN EL QUE SE DESEMPEÑA',
            'EXPERIENCIA ARTISTICA',
            'PERTENECE ALGUN GRUPO ARTISTICO',
            'NOMBRE GRUPO DE ARTISTICO',
            'ROL EN EL GRUPO',
            'FECHA DE CREACION',
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
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' =>  20,
            'M' =>  20,
            'N' =>  20,
            'O' =>  20,
            'P' =>  20,
            'Q' =>  20,
            'R' =>  20,
            'S' =>  20,
            'T' =>  20,
            'U' =>  20,
            'V' =>  20,
            'W' =>  20,
            'X' =>  20,
            'Y' =>  20,
            'Z' =>  20,
            'AA' => 20,
            'AB' => 20,
            'AC' => 20,
            'AD' => 20,
            'AE' => 20,
            'AF' => 20,
            'AG' => 20,
            'AH' => 20,
            'AI' => 20,
            'AJ' => 20,
            'AK' => 20,
            'AL' =>  20,
            'AM' =>  20,
            'AN' =>  20,
            'AO' =>  20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:AO1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Arial Narrow')->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(True);
                $event->sheet->getStyle('A:AO')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('C')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);
                $event->sheet->setAutoFilter('A:AO');
            },
        ];
    }

    public function collection()
    {
        $query =  $this->poll->query();
        $polls = $this->poll
            ->whereHas('user', function($user){
                $user->where('deleted_at', null);
            })->get();
        if ($this->data->status) {
            $polls = $query->where('status', $this->data->status)
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }
        if ($this->data->date_start) {
            $polls =  $query->whereDate('created_at', $this->data->date_start)
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }
        if ($this->data->user_id) {
            $polls = $query->where('user_id', $this->data->user_id)
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }
        if ($this->data->date_start && $this->data->date_end) {
            $polls = $query->whereDate('created_at', '>=', $this->data->date_start)->whereDate('created_at', '<=', $this->data->date_end)
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }
        if ($this->data->date_start &&  $this->data->date_end && $this->data->user_id) {
            $polls = $query->whereDate('created_at', '>=', $this->data->date_start)->whereDate('created_at', '<=', $this->data->date_end)->where('user_id', $this->data->user_id)
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }
        if ($this->data->date_start && $this->data->date_end && $this->data->status) {
            $polls = $query->whereDate('created_at', '>=', $this->data->date_start)->whereDate('created_at', '<=', $this->data->date_end)->where('status', $this->data->status)
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }
        if ($this->data->date_start && $this->data->date_end && $this->data->status && $this->data->user_id) {
            $polls = $query->whereDate('created_at', '>=', $this->data->date_start)->whereDate('created_at', '<=', $this->data->date_end)->where('status', $this->data->status)
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }
        return  new PollCollection($polls);
    }
}
