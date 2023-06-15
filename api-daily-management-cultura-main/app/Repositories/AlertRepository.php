<?php

namespace App\Repositories;

use App\Http\Resources\V1\AlertCollection;
use App\Models\Alert;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Http\Request;

class AlertRepository
{
    use FunctionGeneralTrait;

    private $model;

    function __construct()
    {
        $this->model = new Alert();
    }

    public function getPaginate()
    {
        $query = $this->model->query();

        $paginate = config('global.paginate');

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_alerts');
        session()->put('count_page_alerts', ceil($query->get()->count()/$paginate));

        return new AlertCollection($query->simplePaginate($paginate));

        return $query;
    }

    public function getAll()
    {
        $alerts = Alert::all();
        return $alerts;
    }

    public function create($request)
    {
        $alert = Alert::query()->create($request->all());
        return $alert;
    }

    public function delete($id)
    {
        $alert = Alert::query()->where('id', $id)->delete();
        return $alert;
    }
}
