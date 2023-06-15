<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use App\Models\MonitoringReports;

class MonitoringReportExport implements FromView
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $model;
    public function __construct($data)
    {
        $this->data = $data;
        $this->model =  new MonitoringReports();
    }

    public function view(): View
    {
        set_time_limit(0);

        $query = $this->model->query();

        $ambassadors = $query
                ->whereNotIn('created_by', [1,2])
                ->get();

        if ($this->data['date_start'] != null && $this->data['date_end'] == null) {
            $ambassadors = $query
                ->where('created_at', $this->data['date_start'])
                ->whereNotIn('created_by', [1,2])
                ->get();
        }

        if ($this->data['date_start'] == null && $this->data['date_end'] != null) {
            $ambassadors = $query
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('created_by', [1,2])
                ->get();
        }

        if ($this->data['date_start'] != null && $this->data['date_end'] != null) {
            $ambassadors = $query
                ->where('created_at', '>=', $this->data['date_start'])
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('created_by', [1,2])
                ->get();
        }

        return view('exports.monitoringReportExport', [
            'data' => $ambassadors,
            'trait' => new MonitoringReportExport(null),
        ]);
    }

}
