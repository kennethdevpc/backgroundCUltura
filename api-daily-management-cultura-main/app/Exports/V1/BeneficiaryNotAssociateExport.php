<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Inscriptions\Beneficiary;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class BeneficiaryNotAssociateExport implements FromView, WithTitle
{

    use FunctionGeneralTrait, Exportable;

    protected $beneficiarie;
    private $sheetName;

    public function __construct()
    {
        $this->sheetName = $this->title();
        $this->beneficiarie = new Beneficiary();
    }

    public function title(): string
    {
        return 'Beneficiarios no impactados';
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        // $data = DB::table('get_beneficiaryNotAssociates')->get();

        $beneficiaries = $this->beneficiarie->query()->with('user')
                    ->whereDoesntHave('binnacles')
                    ->whereDoesntHave('binnaclesCulturalSeedbed')
                    ->whereNotIn('created_by', [1,2])
                    ->whereHas('user', function($user){
                        $user->where('deleted_at', null);
                    })->get();

        return view('exports.beneficiaryNotAssociate', [
            'beneficiaries' => $beneficiaries,
        ]);
    }

}
