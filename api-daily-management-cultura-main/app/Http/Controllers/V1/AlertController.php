<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\AlertRepository;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    private $repository;
    function __construct(AlertRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->repository->getAll();
            return $this->createResponse($results);
        } catch (\Exception $th) {
            return $this->createErrorResponse([], 'Algo salio mal al listar' . $th->getMessage() . ' linea ' . $th->getCode());
        }
    }

    public function getPaginate(Request $request)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->repository->getPaginate();
            return $results->toArray($request);
        } catch (\Exception $th) {
            return $this->createErrorResponse([], 'Algo salio mal al listar' . $th->getMessage() . ' linea ' . $th->getCode());
        }
    }

    public function create(Request $request)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->repository->create($request);
            return $this->createResponse($results);
        } catch (\Throwable $th) {
            return $this->createErrorResponse([], 'Algo salio mal al crear' . $th->getMessage() . 'linea' . $th->getCode());
        }
    }

    public function destroy($id)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->repository->delete($id);
            return $this->createResponse($results);
        } catch (\Throwable $th) {
            return $this->createErrorResponse([], 'Algo salio mal al borrar' . $th->getMessage() . 'linea' . $th->getCode());
        }
    }
}
