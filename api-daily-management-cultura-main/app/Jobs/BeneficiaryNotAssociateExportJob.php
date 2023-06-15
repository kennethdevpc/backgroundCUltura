<?php

namespace App\Jobs;

use App\Exports\V1\BeneficiaryNotAssociateExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class BeneficiaryNotAssociateExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $base_path = "public/export/";
            (new BeneficiaryNotAssociateExport())->store($base_path . "beneficiaryNotAssociates.xlsx");
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
}
