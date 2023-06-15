<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use App\Models\Inscriptions\Pec;
use Carbon\Carbon;

class PecsExport implements FromView
{
    use Exportable, FunctionGeneralTrait;

    protected  $model;

    public function __construct()
    {
        $this->model = new Pec();
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $pecs = $this->model->query()->with('user', 'nac', 'neighborhood', 'pecsbeneficiariesExport')
            ->whereNotIn('created_by', [1,2])
            ->whereHas('user', function($user){
                $user->where('deleted_at', null);
            })->get();

        return view('exports.pecs', [
            'data' => $pecs,
            'trait' => new PecsExport(null),
        ]);
    }

}
