<?php

namespace App\Repositories;

use App\Http\Resources\V1\MethodologicalSheetsOneCollection;
use App\Models\MethodologicalSheetsOne;
use App\Traits\FunctionGeneralTrait;
use App\Traits\UserDataTrait;
use App\Traits\UserTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MethodologicalSheetsOneRepository
{
    use FunctionGeneralTrait, UserTrait, UserDataTrait;

    private $model;
    private $safeRolesID = [1, 2];
    function __construct()
    {
        $this->model = new MethodologicalSheetsOne();
    }

    function getAll()
    {
        $query = $this->model->query();
        $paginate = config('global.paginate');

        $response = [];
        $rol_id = $this->getIdRolUserAuth();

        $response =  $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC");

        if ($rol_id == config('roles.apoyo_al_seguimiento_monitoreo')) {
            $response = $query  = $this->model->orderBy('id', 'desc');
        }

        if($rol_id == config('roles.lider_instructor')){
            $response = $query  = $this->model->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC");
        }

        if ($rol_id == config('roles.instructor')) {
            $response =  $query->where("created_by", $this->getIdUserAuth())
                                ->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC");
        }

        $response = $this->model->scopeFilterByUrl($query);

        session()->forget('count_page_methodologicalSheetsOne');
        session()->put('count_page_methodologicalSheetsOne', ceil($response->count() / config('global.paginate')));

        return new MethodologicalSheetsOneCollection($response->simplePaginate($paginate));
    }

    function getAllCreatedBy()
    {
        $data = $this->model->where('created_by', '=', Auth::id());
        return  new MethodologicalSheetsOneCollection($data);
    }

    public function create($data)
    {
        $datasheet = $this->generateDataSheet($data['date_ini']);
        $data['created_by'] = Auth::id();
        $data['datasheet'] = $datasheet;
        $mso = $this->model->create($data);
        DB::update(DB::RAW("UPDATE methodological_sheets_one SET consecutive = CONCAT('FMP', id) WHERE id=$mso->id"));
        // Guardamos en DataModel
        $this->control_data($mso, 'store');
        return $mso;
    }

    public function show($id)
    {
        return $this->model->find($id);
    }

    public function update($data, $id)
    {
        $mso = $this->model->findOrFail($id);
        if ($data['status'] == 'REC') {
            $data['status'] = 'ENREV';
        }

        $mso->update($data);
        // Guardamos en ModelData
        $this->control_data($mso, 'update');
        return $mso;
    }

    public function delete($id)
    {
        $isSafe = in_array($id, $this->safeRolesID);
        if (!$isSafe) {
            return $this->model->where('id', $id)->delete();
        }
        return [];
    }

    public function getCountLimit()
    {
        $count = $this->model->where('created_by', '=', Auth::id())->count();

        $limit = config('global.limitSheet');

        $continue = ($limit - ($count + 1)) >= 0;

        return ([
            'count' => $count + 1,
            'continue' => $continue,
            'limit' => $limit
        ]);
    }
}
