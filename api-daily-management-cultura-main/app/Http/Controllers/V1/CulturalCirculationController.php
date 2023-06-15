<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CulturalCirculationRepository;
use Illuminate\Support\Facades\Gate;

class CulturalCirculationController extends Controller
{
    private $culturalCirculationRepository;

    function __construct(CulturalCirculationRepository $culturalCirculationRepository)
    {
        $this->culturalCirculationRepository = $culturalCirculationRepository;
    }

    /**
     * Muestra todos los datos de las Circulaciones Culturales
     *
     * Este método retorna todos los datos del modelo CultutalCirculation
     *
     * @access public
     * @return CultutalCirculation
     * @author Edgar Rojas
     * @version V1
     */
    public function index(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->culturalCirculationRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            //\Log::error($ex);
            return  $this->createErrorResponse([], 'Algo salio mal al listar circulacion culturals ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Registro de una Circulacion Cultural
     *
     * Este método inserta y crea datos que proviene del modelo
     * CulturalCirculation, actualmente no requiere de una autorización,
     * En su primera versión solo creamos la data, sin necesidad de autorización
     *
     * @access public
     * @author Edgar Rojas
     * @version V1
     */
    public function store(Request $request)
    {
        $params = $request->all();
        try {
            // TODO: Validaciones
            // $validator = $this->culturalCirculationRepository->getValidate($request->all(), $request->created_from, 'create');

            // if ($validator->fails()) {
                //return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            // }

            $result = $this->culturalCirculationRepository->create($request);
            return $this->createResponse($result, 'Circulacion Cultural creada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar circulacion cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Consultar circulacion cultural por id
     *
     * Este método muestra una circulacion cultural, filtrando por su id en el modelo
     * Binnacle, actualmente no requiere de una autorización,
     * En su primera versión solo visualizamos la data, sin necesidad de autorización
     *
     * @access public
     * @param number $id of the binnacle / url param
     * @return Binnacle
     * @author Gabriel Murillo
     * @version V1
     */
    public function show($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->culturalCirculationRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró circulacion cultural', 202);
            }
            return $this->createResponse($result, 'Circulacion Cultural encontrada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver circulacion cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
     * Actualizar circulacion cultural
     *
     * Este método actualiza la circulacion cultural, filtrando por su id en el modelo
     * Binnacle, actualmente no requiere de una autorización,
     * En su primera versión solo actualizamos la data, sin necesidad de autorización
     *
     * @access public
     * @param number $id of the binnalce / url param
     * @return Binnacle
     * @author Gabriel Murillo
     * @version V1
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('haveaccess');
        $arr = $request->all();
        try {
            $validator = $this->culturalCirculationRepository->getValidate($request->all(), $request->updated_from, 'update');

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->culturalCirculationRepository->update($request, $arr, $id);

            return $this->createResponse($result, 'Circulacion Cultural editada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar circulacion cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Eliminar circulacion cultural
     *
     * Este método elimina la circulacion cultural, filtrando por su id en el modelo
     * Binnacle, actualmente no requiere de una autorización,
     * En su primera versión solo eliminamos la data de manera lógica, sin necesidad de autorización
     *
     * @access public
     * @param number $id of the binnacle / url param
     * @author Jean Pool R
     * @version V1
     */
    public function destroy($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->culturalCirculationRepository->delete($id);

            return $this->createResponse($result, 'Circulacion Cultural eliminada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar circulacion cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
}
