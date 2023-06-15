<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class PermissionExport implements FromView
{
    use Exportable, FunctionGeneralTrait;

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $data = DB::table('get_permissions')->get();

        return view('exports.permissions', [
            'data' => $data,
            'trait' => new PecsExport(null),
        ]);
    }

}
