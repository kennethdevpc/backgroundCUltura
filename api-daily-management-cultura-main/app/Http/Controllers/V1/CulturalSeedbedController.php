<?php

namespace App\Http\Controllers\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Repositories\CulturalSeedbedRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CulturalSeedbedController extends Controller
{

    private $repository;

    function __construct(CulturalSeedbedRepository $CulturalSeedbedRepository)
    {
        $this->repository = $CulturalSeedbedRepository;
    }

    /**
     * Muestra todos los datos de semillero cultural de la base de datos (cultural_rights)
     *
     * Este método retorna todos los datos del modelo semillero cultural
     *
     * @access public
     * @param NoRequiereParametros
     * @return culturalRight
     * @author Jean Pool R
     * @version V1
     */
    public function index(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->repository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar derechos culturales ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Insertar semillero cultural
     *
     * Este método inserta y crea datos que proviene del modelo
     * CulturalRight, actualmente no requiere de una autorización,
     * En su primera versión solo creamos la data, sin necesidad de autorización
     *
     * @access public
     * @param string $name name of the cultural right
     * @param number $user_id id of the creator user
     * @return culturalRightInsert
     * @author Jean Pool R
     * @version V1
     */
    public function store(Request $request)
    {
        try {
            // TODO: Validaciones
            $validator = $this->repository->getValidate($request->all(), $request->created_from, 'create');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->repository->create($request);
            return $this->createResponse($result, 'Semillero Cultural creada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar semilleros culturales ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Consultar semillero cultural por id
     *
     * Este método muestra un semillero cultural, filtrando por su id en el modelo
     * CulturalRight, actualmente no requiere de una autorización,
     * En su primera versión solo visualizamos la data, sin necesidad de autorización
     *
     * @access public
     * @param number $id of the cultural right / url param
     * @return culturalRightShowById
     * @author Jean Pool R
     * @version V1
     */
    public function show($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->repository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró el semillero cultural', 404);
            }
            return $this->createResponse($result, 'El semillero cultural fue encontrado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver el semillero cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Actualizar semillero cultural
     *
     * Este método actualiza un semillero cultural, filtrando por su id en el modelo
     * CulturalRight, actualmente no requiere de una autorización,
     * En su primera versión solo actualizamos la data, sin necesidad de autorización
     *
     * @access public
     * @param string $name name of the cultural right
     * @param number $user_id id of the creator user
     * @author Jean Pool R
     * @version V1
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('haveaccess');
        try {
            $data = $request->all();
            $results = $this->repository->update($request, $data, $id);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar el semillero cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
        return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Eliminar semillero cultural
     *
     * Este método elimina un semillero cultural, filtrando por su id en el modelo
     * CulturalRight, actualmente no requiere de una autorización,
     * En su primera versión solo eliminamos la data de manera lógica, sin necesidad de autorización
     *
     * @access public
     * @param number $id of the cultural right / url param
     * @return culturalRightDeleteById
     * @author Jean Pool R
     * @version V1
     */
    public function destroy($id)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->repository->delete($id);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar el semillero cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
        return $results;
    }
}
