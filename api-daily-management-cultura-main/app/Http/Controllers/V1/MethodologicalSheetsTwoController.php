<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\MethodologicalSheetsTwoRepository;
use Illuminate\Http\Request;

class MethodologicalSheetsTwoController extends Controller
{
    private $repository;
    function __construct(MethodologicalSheetsTwoRepository $repository)
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
            return  $this->createErrorResponse([], 'Algo salio mal al listar las fichas metodológicas de planeación '.$ex->getMessage() .' linea '. $ex->getCode());
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

            return $this->createResponse($result, 'Ficha metodológica de evaluación creada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar la ficha metodológica de planeación '.$ex->getMessage() .' linea '. $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $result = $this->repository->show($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró la ficha metodológica de planeación', 202);
            }
            return $this->createResponse($result, 'Ficha metodológica de planeación encontrado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver la ficha metodológica de planeación '.$ex->getMessage() .' linea '. $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        try {
            $result = $this->repository->update($request, $id);

            return $this->createResponse($result, 'Ficha metodológica de planeación editado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar la Ficha metodológica de planeación '.$ex->getMessage() .' linea '. $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = $this->repository->delete($id);

            return $this->createResponse($result, 'Ficha metodológica de planeación eliminada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar el perfil '.$ex->getMessage() .' linea '. $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function getCountLimit()
    {
        try {
            $result = $this->repository->getCountLimit();
            return $this->createResponse($result, 'Limite');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal ver el limite '.$ex->getMessage() .' linea '. $ex->getCode());
        }
    }
}
