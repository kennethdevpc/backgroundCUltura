<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use App\Models\Binnacle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BinnaclePactMonitorsExport implements FromView
{

    use FunctionGeneralTrait, Exportable;

    protected  $binnacle;

    public function __construct()
    {
        $this->binnacle = new Binnacle();
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '12000M');

        // $data = DB::table('get_binnaclePactMonitor')->get();

        $binnacles = $this->binnacle->query()->with('user', 'pec', 'pedagogical', 'nac', 'expertise', 'cultural_right', 'orientation', 'beneficiaries')
        ->whereNotIn('created_by', [1,2])
        ->where('binnacle_id', 'JP')
        ->get();

        return view('exports.binnaclePactMonitors', [
            'binnacles' => $binnacles,
            'trait' => new BinnaclePactMonitorsExport(null),
        ]);
    }

}
