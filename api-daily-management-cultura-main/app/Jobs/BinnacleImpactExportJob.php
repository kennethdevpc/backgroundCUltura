<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Exports\V1\BinnacleImpactExport;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class BinnacleImpactExportJob implements ShouldQueue
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
            (new BinnacleImpactExport())->store($base_path . "binnacleImpacts.xlsx");
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }
}
