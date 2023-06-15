<?php

namespace App\Http\Controllers\V1;

use App\Repositories\StrengtheningSupervisionManagerRepository;
use App\Http\Controllers\Controller;
use App\Utilities\Resources;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class StrengtheningSupervisionManagerController extends Controller
{

    private $strengtheningSupervisionManagerRepository;

    function __construct(StrengtheningSupervisionManagerRepository $strengtheningSupervisionManager)
    {
        $this->strengtheningSupervisionManagerRepository = $strengtheningSupervisionManager;
    }

    public function index(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->strengtheningSupervisionManagerRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar Visita Supervisión Gestores' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function store(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $validator = $this->strengtheningSupervisionManagerRepository->getValidate($request->all(), 'create');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->strengtheningSupervisionManagerRepository->create($request);

            return $this->createResponse($result, 'Visita Supervisión Gestores creada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar Visita Supervisión Gestores ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function show($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->strengtheningSupervisionManagerRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró la Visita Supervisión Gestores', 202);
            }
            return $this->createResponse($result, 'Visita Supervisión Gestores encontrada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver Visita Supervisión Gestores ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('haveaccess');
        $arr = $request->all();
        try {
            $validator = $this->strengtheningSupervisionManagerRepository->getValidate($request->all(), 'update');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->strengtheningSupervisionManagerRepository->update($request, $arr, $id);

            return $this->createResponse($result, 'Visita Supervisión Gestores editada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar Visita Supervisión Gestores ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function destroy($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->strengtheningSupervisionManagerRepository->delete($id);

            return $this->createResponse($result, 'Visita Supervisión Gestores eliminada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar Visita Supervisión Gestores ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    // Traemos los roles una vez seleccionado el Nac
    public function getUsers($id)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->strengtheningSupervisionManagerRepository->users($id);
            return $this->createResponse($results, 'Usuarios encontrados.');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal buscar los usuarios ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    // Traemos los roles una vez seleccionado el Nac
    public function methodologicalInstructionGestor($id)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->strengtheningSupervisionManagerRepository->methodologicalInstructionGestor($id);
            return $this->createResponse($results, 'Usuarios encontrados.');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal buscar los usuarios ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
}
