<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\StrengtheningOfMonitoringRepository;
use Illuminate\Http\Request;

class StrengtheningOfMonitoringController extends Controller
{

    private $strengtheningOfMonitoringRepository;

    function __construct(StrengtheningOfMonitoringRepository $strengtheningOfMonitoringRepository)
    {
        $this->strengtheningOfMonitoringRepository = $strengtheningOfMonitoringRepository;
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
            $results = $this->strengtheningOfMonitoringRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar ortalecimiento seguimiento' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
        // Gate::authorize('haveaccess');
        try {
            $validator = $this->strengtheningOfMonitoringRepository->getValidate($request->all(), 'create');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->strengtheningOfMonitoringRepository->create($request);

            return $this->createResponse($result, 'Fortalecimiento seguimiento creada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar fortalecimiento seguimiento ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BinnacleTerritorie  $binnacleTerritorie
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Gate::authorize('haveaccess');
        try {
            $result = $this->strengtheningOfMonitoringRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró la fortalecimiento seguimiento', 202);
            }
            return $this->createResponse($result, 'Fortalecimiento seguimiento encontrada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver fortalecimiento seguimiento ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BinnacleTerritorie  $binnacleTerritorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Gate::authorize('haveaccess');
        $arr = $request->all();
        try {
            $validator = $this->strengtheningOfMonitoringRepository->getValidate($request->all(), 'update');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->strengtheningOfMonitoringRepository->update($request, $arr, $id);

            return $this->createResponse($result, 'Bitácora editada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar bitácora ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BinnacleTerritorie  $binnacleTerritorie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->strengtheningOfMonitoringRepository->delete($id);

            return $this->createResponse($result, 'Fortalecimiento seguimiento eliminada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar fortalecimiento seguimiento ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    // Traemos los roles una vez seleccionado el Nac
    public function getRoles($id){
        // Gate::authorize('haveaccess');
        try {
            $results = $this->strengtheningOfMonitoringRepository->roles($id);
            return $this->createResponse($results, 'Roles encontrados.');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal buscar los roles ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    // Traemos los roles una vez seleccionado el Nac
    public function getUsuarios($id){
        // Gate::authorize('haveaccess');
        try {
            $results = $this->strengtheningOfMonitoringRepository->usuarios($id);
            return $this->createResponse($results, 'Usuarios encontrados.');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal buscar los usuarios ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    // Obtener todos las bitacoras de terrirotio por id de usuario logueado
    public function getAllByUserLogged(Request $request)
    {
        //Gate::authorize('haveaccess');
        try {
            $results = $this->strengtheningOfMonitoringRepository->getAllByUserLogged();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar ortalecimiento seguimiento' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

}
