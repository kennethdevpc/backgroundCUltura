<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use App\Models\BinnacleTerritorie;
use Illuminate\Contracts\View\View;

class BinnacleTerritorieExport implements FromView
{
    use Exportable, FunctionGeneralTrait;
    protected  $data;
    protected  $model;
    public function __construct($data)
    {
        $this->data = $data;
        $this->model =  new BinnacleTerritorie();
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '6000M');

        $query = $this->model->query();

        $data = [];

        if ($this->data['date_start'] == null && $this->data['date_end'] == null) {
            $data = $query->with('created_user', 'nac', 'user')
                ->whereNotIn('created_by', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        if ($this->data['date_start'] != null && $this->data['date_end'] == null) {
            $data = $query->with('created_user', 'nac', 'user')
            ->where('created_at', $this->data['date_start'])
            ->whereNotIn('created_by', [1,2])
            ->whereHas('user', function($user){
                $user->where('deleted_at', null);
            })->get();
        }

        if ($this->data['date_start'] == null && $this->data['date_end'] != null) {
            $data = $query->with('created_user', 'nac', 'user')
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('created_by', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        if ($this->data['date_start'] != null && $this->data['date_end'] != null) {
            $data = $query->with('created_user', 'nac', 'user')
                ->where('created_at', '>=', $this->data['date_start'])
                ->where('created_at', '<=', $this->data['date_end'])
                ->whereNotIn('created_by', [1,2])
                ->whereHas('user', function($user){
                    $user->where('deleted_at', null);
                })->get();
        }

        return view('exports.binnacleTerritorie', [
            'data' => $data,
            'trait' => new StrengtheningSuperMonInsReport(null),
        ]);
    }

}
