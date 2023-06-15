<?php

namespace App\Repositories;

use App\Models\MethodologicalMonitoring;
use App\Http\Resources\V1\MethodologicalMonitoringCollection;
use App\Http\Resources\V1\MethodologicalMonitoringResource;
use App\Models\MethodologicalMonitorings\Aggregates;
use App\Models\MethodologicalMonitorings\Roles;
use App\Traits\FunctionGeneralTrait;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MethodologicalMonitoringRepository
{
    use FunctionGeneralTrait, UserDataTrait, ImageTrait;

    private $model;

    function __construct()
    {
        $this->model = new MethodologicalMonitoring();
    }

    public function getAll()
    {

        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query();

        // Solo revisa los usuarios que le pertenezcan
        if ($rol_id == config('roles.coordinador_metodologico')) {
            $query->whereHas('user.profile', function ($query) use ($user_id) {
                    $query->where('profiles.monitoring_coordinator_id', $user_id);
                })->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','ENREV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Solo aparece lo creado por el
        if ($rol_id == config('roles.lider_metodologico')) {
            $query->where('created_by', '=', $user_id)->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Aparece toda la data excepto lo de los admins
        if ($rol_id == config('roles.secretaria_cultural')) {
            $query->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','REV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Aparece toda la data
        if ($rol_id == config('roles.super_root') || $rol_id == config('roles.root')) {
            $query->orderByRaw("FIELD(status,'REC','REV','ENREV','APRO') ASC");
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_methodologicalMonitoring');
        session()->put('count_page_methodologicalMonitoring', ceil($query->get()->count()/$paginate));

        return new MethodologicalMonitoringCollection($query->simplePaginate($paginate));

    }
    public function create($request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::id();
        $MethodologicalMonitoring = MethodologicalMonitoring::create($data);

        $saveOne = $this->send_file($request, 'development_activity_image', 'methodological_monitoring', $MethodologicalMonitoring->id);
        $saveTwo = $this->send_file($request, 'evidence_participation_image', 'methodological_monitoring', $MethodologicalMonitoring->id);

        $MethodologicalMonitoring->update([
            'development_activity_image' => $saveOne['response']['payload'],
            'evidence_participation_image' => $saveTwo['response']['payload'],
        ]);

        $Aggregates = explode(',', $request->aggregates);

        foreach ($Aggregates as $key => $value) {
            Aggregates::create([
                'monitoring_id' => $MethodologicalMonitoring->id,
                'aggregate_id' => $value
            ]);
        }

        $Roles = explode(',', $request->roles);

        foreach ($Roles as $key => $value) {
            Roles::create([
                'monitoring_id' => $MethodologicalMonitoring->id,
                'role_id' => $value
            ]);
        }

        $this->control_data($MethodologicalMonitoring, 'store');
        DB::update(DB::RAW("UPDATE methodological_monitorings SET consecutive = CONCAT('SML', id) WHERE id=$MethodologicalMonitoring->id"));
        $results = new MethodologicalMonitoringResource($MethodologicalMonitoring);
        return $results;
    }

    public function findById($id)
    {
        $MethodologicalMonitoring = MethodologicalMonitoring::findOrFail($id);
        $result = new MethodologicalMonitoringResource($MethodologicalMonitoring);
        return $result;
    }

    public function update($request, $id)
    {
        $MethodologicalMonitoring = MethodologicalMonitoring::findOrFail($id);

        $data = $request->all();
        $MethodologicalMonitoring->update($data);

        if ($request->hasFile('development_activity_image')) {
            $saveOne = $this->update_file(
                $request,
                'development_activity_image',
                'methodological_monitoring',
                $MethodologicalMonitoring->id,
                $MethodologicalMonitoring->development_activity_image
            );
            $MethodologicalMonitoring->update([
                'development_activity_image' => $saveOne['response']['payload'],
            ]);
        }
        if ($request->hasFile('evidence_participation_image')) {
            $saveTwo = $this->update_file(
                $request,
                'evidence_participation_image',
                'methodological_monitoring',
                $MethodologicalMonitoring->id,
                $MethodologicalMonitoring->evidence_participation_image
            );
            $MethodologicalMonitoring->update([
                'evidence_participation_image' => $saveTwo['response']['payload'],
            ]);
        }

        if ($MethodologicalMonitoring->status == 'REC') {
            $rol_id = $this->getIdUserAuth();
            if ($rol_id == config('roles.lider_metodologico')) {
                $MethodologicalMonitoring->update([
                    'status' => 'ENREV'
                ]);
            }
        }

        Aggregates::query()->where('monitoring_id', '=', $MethodologicalMonitoring->id)->delete();

        $Aggregates = explode(',', $request->aggregates);
        if (count($Aggregates) > 0) {
            foreach ($Aggregates as $key => $value) {
                Aggregates::create([
                    'monitoring_id' => $MethodologicalMonitoring->id,
                    'aggregate_id' => $value
                ]);
            }
        }

        Roles::query()->where('monitoring_id', '=', $MethodologicalMonitoring->id)->delete();

        $Roles = explode(',', $request->roles);
        if (count($Roles) > 0) {
            foreach ($Roles as $key => $value) {
                Roles::create([
                    'monitoring_id' => $MethodologicalMonitoring->id,
                    'role_id' => $value
                ]);
            }
        }

        $this->control_data($MethodologicalMonitoring, 'update');
        $result = new MethodologicalMonitoringResource($MethodologicalMonitoring);
        return $result;
    }

    public function delete($id)
    {
        $MethodologicalMonitoring = MethodologicalMonitoring::findOrFail($id);
        $MethodologicalMonitoring->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }
}
