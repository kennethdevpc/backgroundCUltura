<?php

namespace App\Exports\V1;

use App\Models\Pedagogical;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class PedagogicalsExport implements FromView
{
    use Exportable, FunctionGeneralTrait;

    protected  $model;

    public function __construct()
    {
        $this->model = new Pedagogical();
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $pedagogicals = $this->model->query()->with('user', 'nac', 'cultural_right', 'expertise', 'orientation')
            ->whereNotIn('created_by', [1,2])
            ->whereHas('user', function($user){
                $user->where('deleted_at', null);
            })->get();

        return view('exports.pedagogicals', [
            'pedagogicals' => $pedagogicals,
            'trait' => new PedagogicalsExport(null),
        ]);
    }
}
