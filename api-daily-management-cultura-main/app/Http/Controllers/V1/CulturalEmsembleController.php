<?php

namespace App\Http\Controllers\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Repositories\CulturalEmsembleRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CulturalEmsembleController extends Controller
{

    private $culturalEmsembleRepository;

    function __construct(CulturalEmsembleRepository $culturalEmsembleRepository)
    {
        $this->culturalEmsembleRepository = $culturalEmsembleRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     **/

    /**
     * Muestra todos los datos de emsable cultural de la base de datos (cultural_rights)
     *
     * Este método retorna todos los datos del modelo emsable cultural
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
            $results = $this->culturalEmsembleRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar emsable cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Insertar emsable cultural
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
            $results = $this->culturalEmsembleRepository->create($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar emsable cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Consultar emsable cultural por id
     *
     * Este método muestra un emsable cultural, filtrando por su id en el modelo
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
            $result = $this->culturalEmsembleRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró el emsable cultural', 404);
            }
            return $this->createResponse($result, 'El emsable cultural fue encontrado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver el emsable cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
     * Actualizar emsable cultural
     *
     * Este método actualiza un emsable cultural, filtrando por su id en el modelo
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
            $results = $this->culturalEmsembleRepository->update($request, $data, $id);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar el emsable cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
     * Eliminar emsable cultural
     *
     * Este método elimina un emsable cultural, filtrando por su id en el modelo
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
            $results = $this->culturalEmsembleRepository->delete($id);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar el emsable cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
        return $results;
    }
}
