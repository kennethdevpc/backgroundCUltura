<?php

namespace App\Exports\V1;

use App\Http\Resources\V1\MethodologicalAccompanimentCollection;
use App\Http\Resources\V1\StrengtheningSupervisionMonitorsInstructorsCollection;
use App\Models\StrengtheningSupervisionMonitorsInstructors;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class StrengtheningSuperMonInsReport implements FromView
{
    use Exportable, FunctionGeneralTrait;

    protected  $data;
    protected  $model;

    public function __construct($data)
    {
        $this->data = $data;
        $this->model = new StrengtheningSupervisionMonitorsInstructors();
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $query = $this->model->query();

        $data = [];

        if ($this->data['date_start'] == null && $this->data['date_end'] == null) {
            $data = $query->with('user', 'nac', 'role', 'createdBy')
                ->whereNotIn('created_by', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        if ($this->data['date_start'] != null && $this->data['date_end'] == null) {
            $data = $query->with('user', 'nac', 'role', 'createdBy')
            ->where('created_at', $this->data['date_start'])
            ->whereNotIn('created_by', [1,2])
            ->whereHas('user', function($user){
                $user->where('deleted_at', null);
            })->get();
        }

        if ($this->data['date_start'] == null && $this->data['date_end'] != null) {
            $data = $query->with('user', 'nac', 'role', 'createdBy')
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('created_by', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        if ($this->data['date_start'] != null && $this->data['date_end'] != null) {
            $data = $query->with('user', 'nac', 'role', 'createdBy')
                ->where('created_at', '>=', $this->data['date_start'])
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('created_by', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        return view('exports.strengtheningSuperMonIns', [
            'data' => $data,
            'trait' => new StrengtheningSuperMonInsReport(null),
        ]);
    }

}
