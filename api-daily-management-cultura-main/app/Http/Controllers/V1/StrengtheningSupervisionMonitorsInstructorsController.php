<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\StrengtheningSupervisionMonitorsInstructorsRepository;
use Illuminate\Http\Request;

class StrengtheningSupervisionMonitorsInstructorsController extends Controller
{
    private $repository;
    function __construct(StrengtheningSupervisionMonitorsInstructorsRepository $repository)
    {
        $this->repository = $repository;
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
            return  $this->createErrorResponse([], 'Algo salio mal al listar las Fortalecimiento a la supervisión monitores e instructores '.$ex->getMessage() .' linea '. $ex->getCode());
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
        try {
            $result = $this->repository->create($request);
            return $this->createResponse($result, 'FORTALECIMIENTO creado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse(['ex' => $ex->getMessage()], 'Algo salio mal al guardar Fortalecimiento a la supervisión monitores e instructores'.$ex->getMessage() .' linea '. $ex->getCode());
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
        try {
            $result = $this->repository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró Fortalecimiento a la supervisión monitores e instructores', 202);
            }
            return $this->createResponse($result, 'Fortalecimiento a la supervisión monitores e instructores encontrado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver la Fortalecimiento a la supervisión monitores e instructores '.$ex->getMessage() .' linea '. $ex->getCode());
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
        try {
            $data = $request->all();

            $result = $this->repository->update($request, $data, $id);

            return $this->createResponse($result, 'Fortalecimiento a la supervisión monitores e instructores editado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar la Fortalecimiento a la supervisión monitores e instructores '.$ex->getMessage() .' linea '. $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = $this->repository->delete($id);

            return $this->createResponse($result, 'Fortalecimiento a la supervisión monitores e instructores eliminada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar Fortalecimiento a la supervisión monitores e instructores '.$ex->getMessage() .' linea '. $ex->getCode());
        }
    }
}
