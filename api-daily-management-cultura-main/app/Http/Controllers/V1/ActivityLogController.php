<?php

namespace App\Http\Controllers\V1;

use App\Repositories\ActivityLogRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{

    private $activityLogRepository;

    function __construct(ActivityLogRepository $activityLog)
    {
        $this->activityLogRepository = $activityLog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->activityLogRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar Actividad de la plataforma' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

}
