<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\StrengtheningOfMonitoring;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
// added
use Maatwebsite\Excel\Concerns\FromView;

class StrengtheningOfMonitoringReport implements FromView
{
    use Exportable, FunctionGeneralTrait;

    protected  $data;
    protected  $model;

    public function __construct($data)
    {
        $this->data = $data;
        $this->model = new StrengtheningOfMonitoring();
    }

    public function view(): View
    {
        set_time_limit(0);

        $query = $this->model->query();

        $strengtheningOfMonitorings = $query->with('user', 'created_user', 'roles', 'nac')
                                        ->whereNotIn('created_by', [1,2])
                                        ->get();

        if ($this->data['date_start'] != null && $this->data['date_end'] == null) {
            $strengtheningOfMonitorings = $query
            ->with('user', 'created_user', 'roles', 'nac')
            ->whereNotIn('created_by', [1,2])
            ->where('created_at', $this->data['date_start'])
            ->get();
        }

        if ($this->data['date_start'] == null && $this->data['date_end'] != null) {
            $strengtheningOfMonitorings = $query
                ->with('user', 'created_user', 'roles', 'nac')
                ->whereNotIn('created_by', [1,2])
                ->where('created_at', '<=', $this->data['date_end'])
                ->get();
        }

        if ($this->data['date_start'] != null && $this->data['date_end'] != null) {
            $strengtheningOfMonitorings = $query
                ->with('user', 'created_user', 'roles', 'nac')
                ->whereNotIn('created_by', [1,2])
                ->where('created_at', '>=', $this->data['date_start'])
                ->where('created_at', '<=', $this->data['date_end'])
                ->get();
        }

        return view('exports.strengtheningOfMonitoringReport', [
            'data' => $strengtheningOfMonitorings,
            'trait' => new StrengtheningOfMonitoringReport(null),
        ]);
    }

}
