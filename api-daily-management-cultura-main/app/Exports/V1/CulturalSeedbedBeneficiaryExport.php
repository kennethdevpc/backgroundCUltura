<?php

namespace App\Exports\V1;

use App\Models\BinnacleCulturalSeedbedBeneficiary;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class CulturalSeedbedBeneficiaryExport implements FromView, WithTitle
{
    use FunctionGeneralTrait, Exportable;

    protected $binnacle;
    private $sheetName;

    public function __construct()
    {
        $this->sheetName = $this->title();
        $this->binnacle = new BinnacleCulturalSeedbedBeneficiary();
    }

    public function title(): string
    {
        return 'Bitácora Semillero Cultural';
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $data = DB::table('get_binnacleCulturalSeedbedBeneficiary')->get();

        return view('exports.culturalSeedbedBeneficiary', [
            'data' => $data,
            'trait' => new BeneficiariesExport(null, null),
        ]);
    }

}
