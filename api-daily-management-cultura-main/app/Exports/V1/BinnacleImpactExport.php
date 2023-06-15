<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use App\Models\BinnacleBeneficiary;
use Illuminate\Contracts\View\View;

class BinnacleImpactExport implements FromView, WithTitle
{
    use FunctionGeneralTrait, Exportable;

    protected $binnacle;
    private $sheetName;

    public function __construct()
    {
        $this->sheetName = $this->title();
        $this->binnacle = new BinnacleBeneficiary();
    }

    public function title(): string
    {
        return 'Bitácora Pacto';
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '28000M');

        $binnacles = $this->binnacle->query()->with('binnacle', 'beneficiary')
            ->whereHas('binnacle.user', function($user){
                $user->where('deleted_at', null);
            })->get();

        return view('exports.binnacleImpact', [
            'binnaclesImpacts' => $binnacles,
            'trait' => new BinnacleImpactExport(null, null),
        ]);
    }
}
