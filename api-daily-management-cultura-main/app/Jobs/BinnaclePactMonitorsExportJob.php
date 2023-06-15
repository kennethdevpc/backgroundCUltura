<?php

namespace App\Jobs;

use App\Exports\V1\BinnaclePactMonitorsExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Exports\V1\CulturalSeedBedExport;
use Illuminate\Queue\InteractsWithQueue;
use App\Exports\V1\BeneficiariesExport;
use Illuminate\Queue\SerializesModels;
use App\Exports\V1\PecsExport;
use Illuminate\Bus\Queueable;

class BinnaclePactMonitorsExportJob implements ShouldQueue
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
            (new BinnaclePactMonitorsExport())->store($base_path . "binnacles_monitor.xlsx");
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
}
