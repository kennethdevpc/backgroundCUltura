<?php

namespace App\Exports\V1;

use App\Models\Inscriptions\Beneficiary;
use App\Traits\FunctionGeneralTrait;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class BeneficiariesExport implements FromView
{

    use FunctionGeneralTrait, Exportable;

    protected $who;
    protected $beneficiarie;

    public function __construct($who)
    {
        $this->who =  $who;
        $this->beneficiarie = new Beneficiary();
    }

    public function view(): View
    {
        set_time_limit(0);
        ini_set('memory_limit', '16000M');

        $beneficiaries = $this->beneficiarie->query()->with('user', 'inscription', 'socio_demo', 'attendant', 'health_data')
                    ->whereHas('user.profile', function($queryBene){
                        $queryBene->where('profiles.role_id', $this->who);
                    })->get();

        /* $nrows = count($beneficiaries);
		for($i=0; $i<$nrows ;$i++){
			$beneficiaries[$i]->full_name = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiaries[$i]->full_name ?? '');
			// $beneficiaries[$i]->attendant[0]->full_name = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiaries[$i]->attendant[0]->full_name ?? '');
		} */

        return view('exports.beneficiaries', [
            'beneficiaries' => $beneficiaries,
            'trait' => new BeneficiariesExport(null, null),
        ]);
    }

}
