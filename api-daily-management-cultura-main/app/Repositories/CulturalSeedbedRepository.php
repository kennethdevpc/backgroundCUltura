<?php

namespace App\Repositories;

use App\Models\CulturalSeedbed;
use App\Http\Resources\V1\CulturalSeedbedCollection;
use App\Http\Resources\V1\CulturalSeedbedResource;
use App\Traits\FunctionGeneralTrait;
use App\Traits\UserDataTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;

class CulturalSeedbedRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new CulturalSeedbed();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id =  $this->getIdUserAuth();
        $results = [];

        $paginate = config('global.paginate');

        $query = $this->model->query();

        // Solo aparezca lo relacionado a cada rol
        if ($rol_id == config('roles.apoyo_al_seguimiento_monitoreo')) {
            $query->whereHas('user.profile', function ($query) use ($user_id) {
                    $query->where('profiles.support_tracing_monitoring_id',  $user_id);
                })->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC");
        }
        // Solo revisa los usuarios que le pertenezcan
        if ($rol_id == config('roles.lider_instructor')) {
            $query->whereHas('user.profile', function ($query) use ($user_id) {
                    $query->where('profiles.instructor_leader_id',  $user_id);
                })->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','ENREV','REC','APRO') ASC");
        }
        // Solo aparece lo creado por el
        if ($rol_id == config('roles.instructor')) {
            $query->where('created_by', '=', $user_id)->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC");
        }
        // Aparece toda la data
        if ($rol_id == config('roles.super_root') || $rol_id == config('roles.root')) {
            $query->orderByRaw("FIELD(status,'REC','REV','ENREV','APRO') ASC");
        }

        // Aparece toda la data excepto lo de los admins
        if ($rol_id == config('roles.secretaria_cultural')) {
            $query->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','REV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_culturalSeedBed');
        session()->put('count_page_culturalSeedBed', ceil($query->get()->count()/$paginate));

        return new CulturalSeedbedCollection($query->simplePaginate($paginate));
    }

    public function create($request)
    {
        $culturalSeedBeed = $this->model;
        $dataClean = $request->all();
        $dataClean['created_by'] = Auth::id();

        $culturalSeedBeed->fill($dataClean);
        $culturalSeedBeed->consecutive = $request->consecutive;
        // $culturalSeedBeed->user_review_support_follow_id = $this->getIdUserReview()->support_tracing_monitoring_id;
        // $culturalSeedBeed->user_review_manager_cultural_id = $this->getIdUserReview()->gestor_id;
        // $culturalSeedBeed->user_review_instructor_leader_id  = $this->getIdUserReview()->instructor_leader_id;
        $save = $culturalSeedBeed->save();

        if ($save) {
            // $beneficiaries = json_decode($request->beneficiary_id, true);
            // $culturalSeedBeed->beneficiaries()->attach($beneficiaries);

            $base_query = CulturalSeedbed::query();

            $update = $base_query->find($culturalSeedBeed->id);

            $save &= $update->save();

            $handle_1 = $this->send_file($request, 'development_activity_image', 'culturalseedbeds', $culturalSeedBeed->id);
            $culturalSeedBeed->update(['development_activity_image' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];

            $handle_2 = $this->send_file($request, 'evidence_participation_image', 'culturalseedbeds', $culturalSeedBeed->id);
            $culturalSeedBeed->update(['evidence_participation_image' => $handle_2['response']['payload']]);
            $save &= $handle_2['response']['success'];

            $aggregates = json_decode($request->aggregates, true);
            $culturalSeedBeed->beneficiary()->sync($aggregates);
        }

        // Guardamos en DataModel
        $this->control_data($culturalSeedBeed, 'store');

        DB::update(DB::RAW("UPDATE cultural_seedbeds SET consecutive = CONCAT('BCC', id) WHERE id=$culturalSeedBeed->id"));
        return true;
    }

    public function findById($id)
    {
        $CulturalSeedbed = CulturalSeedbed::findOrFail($id);
        $result = new CulturalSeedbedResource($CulturalSeedbed);
        return $result;
    }

    public function update(Request $request, $data, $id)
    {
        $culturalSeedbed = CulturalSeedbed::findOrFail($id);
        $culturalSeedbed->fill($data);

        if (isset($culturalSeedbed->last_status)) {
            $culturalSeedbed->status = $culturalSeedbed->last_status;
        } else {
            $culturalSeedbed->status = 'ENREV';
        }

        if ($request->hasFile('development_activity_image')) {
            $handle_1 = $this->update_file($request, 'development_activity_image', 'culturalcirculations', $culturalSeedbed->id, $culturalSeedbed->development_activity_image);
            $culturalSeedbed->update(['development_activity_image' => $handle_1['response']['payload']]);
        }

        if ($request->hasFile('evidence_participation_image')) {
            $handle_2 = $this->update_file($request, 'evidence_participation_image', 'culturalcirculations', $culturalSeedbed->id, $culturalSeedbed->evidence_participation_image);
            $culturalSeedbed->update(['evidence_participation_image' => $handle_2['response']['payload']]);
        }

        $aggregates = json_decode($request->aggregates, true);
        $culturalSeedbed->beneficiary()->sync($aggregates);

        $culturalSeedbed->save();


        $this->control_data($culturalSeedbed, 'update');
        $result = new CulturalSeedbedResource($culturalSeedbed);
        return $result;
    }

    public function delete($id)
    {
        $CulturalSeedbed = CulturalSeedbed::findOrFail($id);
        $CulturalSeedbed->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }

    function getValidate($data, $method)
    {
        $validate = [
            'level_domain_description' =>  'max:3500',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
        ];

        $attrs = [
            'level_domain_description' => 'Nivel de dominio',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }
}
