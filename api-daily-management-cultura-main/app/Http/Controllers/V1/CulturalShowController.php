<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\BinnacleCulturalShowRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CulturalShowController extends Controller
{
    private $repository;
    function __construct(BinnacleCulturalShowRepository $dialogueTableRepository)
    {
        $this->repository = $dialogueTableRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $results = $this->repository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal las bitácoras show cultural' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $request['user_id'] = Auth::id();
            $validator = $this->repository->getValidate($request->all(), 'create');
            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }
            $results = $this->repository->create($request);
            return  $this->createResponse($results, 'Se ha creado exitosamente');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar bitácora show cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->repository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró la bitacora show cultural', 404);
            }
            return $this->createResponse($result, 'bitacora show cultural fue encontrado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver bitacora show cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('haveaccess');
        try {
            $validator = $this->repository->getValidate($request->all(), 'update');
            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $results = $this->repository->update($request, $id);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar  bitacora show cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
        return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->repository->delete($id);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar  bitacora show cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
        return $results;
    }
}
