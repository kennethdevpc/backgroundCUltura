<?php

namespace App\Repositories;

use App\Models\Neighborhood;
use App\Http\Resources\V1\NeighborhoodCollection;
use App\Http\Resources\V1\NeighborhoodResource;
use App\Traits\FunctionGeneralTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\FunctionGeneralTraitS;


class NeighborhoodRepository
{
    use FunctionGeneralTrait;

    private $model;

    function __construct()
    {
        $this->model = new Neighborhood();
    }

    public function getAll()
    {
        $neighborhoods = [];
        $paginate = config('global.paginate');

        $query = $this->model->query()->orderBy('id', 'DESC');

        $neighborhoods = $this->model->scopeFilterByUrl($query);

        session()->forget('count_page_neighborhoods');
        session()->put('count_page_neighborhoods', ceil($neighborhoods->count() / config('global.paginate')));

        return new NeighborhoodCollection($neighborhoods->simplePaginate($paginate));
    }
    public function create($request)
    {
        $neighborhood = Neighborhood::create($request);
        // Guardamos en dataModel
        $this->control_data($neighborhood, 'store');
        $results = new NeighborhoodResource($neighborhood);
        return $results;
    }

    public function findById($id)
    {
        $neighborhood = Neighborhood::findOrFail($id);
        $result = new NeighborhoodResource($neighborhood);
        return $result;
    }

    public function update($data, $id)
    {
        $neighborhood = Neighborhood::findOrFail($id);
        $neighborhood->update($data);
        // Guardamos en dataModel
        $this->control_data($neighborhood, 'update');
        $result = new NeighborhoodResource($neighborhood);
        return $result;
    }

    public function delete($id)
    {
        $neighborhood = Neighborhood::findOrFail($id);
        $neighborhood->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }

    public function getValidate($data){
        $validate = [
            'name' => 'bail|required',
            'nac_id' => 'bail|required',
            'user_id' => 'bail|required',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un asistente con este :attribute.',
        ];

        $attrs = [
            'name' => 'Nombre',
            'nac_id' => 'Nac',
            'user_id' => 'Usuario',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }

}
