<?php

namespace App\Repositories;

use App\Models\ManagerMonitoring;
use App\Http\Resources\V1\ManagerMonitoringCollection;
use App\Http\Resources\V1\ManagerMonitoringResource;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class ManagerMonitoringRepository
{
    use UserDataTrait, FunctionGeneralTrait;
    private $model;
    function __construct()
    {
        $this->model = new ManagerMonitoring();
    }
    public function getAll()
    {

        $rol_id =$this->getIdRolUserAuth();
        $user_id =$this->getIdUserAuth();
        $query = $this->model->query()->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
        ->orderBy('id', 'DESC');

        $managerMonitorings =[];
        $paginate = config('global.paginate');
        if($rol_id == config('roles.gestor')){

            $managerMonitorings =  $query->where('user_id',$user_id);
        }
        if($rol_id == config('roles.apoyo_metodologico')){

            $managerMonitorings =  $query->where('user_method_support_id',$user_id);
        }

        if($rol_id == config('roles.root')|| $rol_id == config('roles.super_root')){
            $managerMonitorings =  $query;
        }
        if($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')){
            $managerMonitorings = $query->whereHas('user', function ($query) {
                $query->whereHas('profile',function ($profile) {
                    $profile->whereNotIn('role_id',[config('roles.super_root'),config('roles.root')]);
                });
            });
        }

         // Aplicar filtros adicionales desde la URL
         $query = $this->model->scopeFilterByUrl($query);
        session()->forget('count_page_manager_monitorings');
        session()->put('count_page_manager_monitorings', ceil($query->count()/$paginate));

        return new ManagerMonitoringCollection($query->simplePaginate($paginate));
    }
    public function create($request)
    {
        $managerMonitoring = $this->model->create($request);
        // Guardamos en DataModel
        $this->control_data($managerMonitoring, 'store');
        DB::update(DB::RAW("UPDATE manager_monitorings SET consecutive = CONCAT('IMSG', id) WHERE id=$managerMonitoring->id"));
        $results = new ManagerMonitoringResource($managerMonitoring);
        return $results;
    }

    public function findById($id)
    {
        $managerMonitoring = $this->model->findOrFail($id);
        $result = new ManagerMonitoringResource($managerMonitoring);
        return $result;
    }

    public function update($data, $id)
    {
        $managerMonitoring = $this->model->findOrFail($id);
        $rol_id  =  $this->getIdRolUserAuth();
        if ($rol_id == config('roles.gestor')) {
            $data['status']= 'ENREV';
        }
        $managerMonitoring->update($data);
        // Guardamos en DataModel
        $this->control_data($managerMonitoring, 'update');
        $rol_id = $this->getIdRolUserAuth();
        if ($managerMonitoring->status == 'REC') {
            if ($rol_id == config('roles.gestor')) {
                $managerMonitoring->update([
                    'status' => 'ENREV'
                ]);
            }
        }
        $result = new ManagerMonitoringResource($managerMonitoring);
        return $result;
    }

    public function delete($id)
    {
        $managerMonitoring = $this->model->findOrFail($id);
        $managerMonitoring->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }

    public function getValidate($data) {

        $validate = [
            'user_id' => 'required',
            'monitor_id' => 'required',
            'activity_date' => 'required',
            'start_time' => 'required',
            'final_hour' => 'required|after:start_time',
            'target_tracking' => 'required',
            'nac_id' => 'required',
            'cultural_process' => 'required',
            'cultural_guidelines' => 'required',
            'cultural_communication' => 'required',
            'difficulty_cultural_process' => 'required',
            'proposal_improvement' => 'required',
            'consecutive' => 'required',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
        ];

        $attrs = [
            'user_id' => 'Usuario',
            'monitor_id' => 'Monitor',
            'activity_date' => 'Fecha actividad',
            'start_time' => 'Fecha inicio',
            'final_hour' => 'Fecha final',
            'target_tracking' => 'Seguimiento de objetivo',
            'nac_id' => 'Nac',
            'cultural_process' => 'Proceso cultural',
            'cultural_guidelines' => 'Directrices culturales',
            'cultural_communication' => 'Comunicación cultural',
            'difficulty_cultural_process' => 'Dificultad proceso cultural',
            'proposal_improvement' => 'Propuesta para mejorar',
            'consecutive' => 'Consecutivo',
        ];

        return $this->validator($data, $validate, $messages, $attrs);

    }

}
