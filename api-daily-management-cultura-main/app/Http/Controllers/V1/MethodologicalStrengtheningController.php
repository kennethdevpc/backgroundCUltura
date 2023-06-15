<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\MethodologicalStrengtheningRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class MethodologicalStrengtheningController extends Controller
{

    private $methodologicalStrengtheningRepository;
    function __construct(MethodologicalStrengtheningRepository $methodologicalStrengtheningRepository)
    {
        $this->methodologicalStrengtheningRepository = $methodologicalStrengtheningRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->methodologicalStrengtheningRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar el fortalecimiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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


        $request['created_by'] = Auth::id();
        try {

            $validator = $this->methodologicalStrengtheningRepository->getValidate($request->all(), 'create');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }
            return $this->methodologicalStrengtheningRepository->create($request);

        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar fortalecimiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->methodologicalStrengtheningRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró la fortalecimiento metodológico', 202);
            }
            return $this->createResponse($result, 'Fortalecimiento metodológico fue encontrada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver fortalecimiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        Gate::authorize('haveaccess');
        try {

            $validator = $this->methodologicalStrengtheningRepository->getValidate($request->all(), 'update');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

           return $this->methodologicalStrengtheningRepository->update($request, $id);

        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar fortalecimiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->methodologicalStrengtheningRepository->delete($id);

            return $this->createResponse($result, 'Usuario eliminado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar fortalecimiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
}
