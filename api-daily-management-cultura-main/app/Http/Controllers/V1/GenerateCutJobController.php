<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GenerateCutJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function general(Request $request)
    {
        // Generamos el corte de todos los informes
        /*
            "El número corresponde al informe a generar\n
            1 => BinnaclePactMonitorsExpot\n
            2 => PecsExpot\n
            3 => CulturalSeedBedExpot\n
            4 => BeneficiariesInstruExpot\n
            5 => BeneficiariesMoniExpot\n
            6 => BeneficiaryNotAssociateExpot\n
            7 => CulturalSeedbedBeneficiaryExpot\n
            8 => BinnacleImpactExport\n"
        */
        try {

            switch ($request->type_excel) {
                case 'beneficiariesMoni':
                    Artisan::call('g:e 5');
                    break;
                case 'binnacleImpact':
                    Artisan::call('g:e 6');
                    Artisan::call('g:e 7');
                    Artisan::call('g:e 8');
                    break;
                default:
                    return 0;
                    break;
            }

            return response()->json('Informes generado con exito.');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar nacs ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

}
