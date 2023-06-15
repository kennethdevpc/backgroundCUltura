<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class BinnacleBeneficiaryMultipleSheetsExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected  $binnacle;

    use Exportable;

    public function sheets(): array
    {
        set_time_limit(0);
        ini_set('memory_limit', '20000M');

        $sheets = [
            'binnacleImpacts' => new BinnacleImpactExport(),
            'culturalSeedbedBeneficiary' => new CulturalSeedbedBeneficiaryExport(),
            'beneficiaryNotAssociates' => new BeneficiaryNotAssociateExport(),
        ];

        return $sheets;
    }

}
