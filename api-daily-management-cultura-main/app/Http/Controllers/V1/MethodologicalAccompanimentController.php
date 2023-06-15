<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\MethodologicalAccompanimentRepository;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
class MethodologicalAccompanimentController extends Controller
{
    use FunctionGeneralTrait;
    private $methodologicalAccompanimentRepository;
    function __construct(MethodologicalAccompanimentRepository $methodologicalAccompanimentRepository)
    {
        $this->methodologicalAccompanimentRepository = $methodologicalAccompanimentRepository;
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
            $results = $this->methodologicalAccompanimentRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar el acompañamiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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

            $validator = $this->methodologicalAccompanimentRepository->getValidate($request->all(), 'create');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }
            return $this->methodologicalAccompanimentRepository->create($request);

        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar acompañamiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
            $result = $this->methodologicalAccompanimentRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró la acompañamiento metodológico', 202);
            }
            return $this->createResponse($result, 'Acompañamiento metodológico fue encontrada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver acompañamiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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

            $validator = $this->methodologicalAccompanimentRepository->getValidate($request->all(), 'update');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

           return $this->methodologicalAccompanimentRepository->update($request, $id);

        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar acompañamiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
            $result = $this->methodologicalAccompanimentRepository->delete($id);

            return $this->createResponse($result, 'Usuario eliminado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar acompañamiento metodológico ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

}
