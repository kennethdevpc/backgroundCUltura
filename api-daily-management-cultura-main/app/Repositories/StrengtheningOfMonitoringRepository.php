<?php

namespace App\Repositories;

use App\Http\Resources\V1\StrengtheningOfMonitoringCollection;
use App\Models\StrengtheningOfMonitoring;
use App\Models\Nac;
use App\Models\Profile;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StrengtheningOfMonitoringRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new StrengtheningOfMonitoring();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query();

        if (config('roles.coordinador_seguimiento') == $rol_id) {
            $query->where('monitoring_coordinator_id', $user_id);
        }

        if (config('roles.apoyo_al_seguimiento_monitoreo') == $rol_id) {
            $query->where('created_by', $user_id);
        }

        if ($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
            $query->whereHas('created_user', function ($query) {
                $query->whereHas('profile', function ($profile) {
                    $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                });
            });
        }


        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_strengtheningOfMonitoring');
        session()->put('count_page_strengtheningOfMonitoring', ceil($query->count() / $paginate));

        // Realizar paginación y retornar resultados como una StrengtheningOfMonitoringCollection
        return new StrengtheningOfMonitoringCollection($query->simplePaginate($paginate));

    }

    public function getAllByUserLogged()
    {
        $strengthening_of_monitorings = $this->model->where('created_by', '=', Auth::id())->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
            ->orderBy('id', 'DESC')->get();

        return new StrengtheningOfMonitoringCollection($strengthening_of_monitorings);
    }

    public function create(Request $request)
    {
        $strengtheningOfMonitoring = $this->model;
        $strengtheningOfMonitoring->consecutive = $request->consecutive;
        $strengtheningOfMonitoring->nac_id = $request->nac_id;
        $strengtheningOfMonitoring->role_id = $request->role_id;
        $strengtheningOfMonitoring->user_id = $request->user_id;
        $strengtheningOfMonitoring->created_by = Auth::id();
        $strengtheningOfMonitoring->activity_date = $request->activity_date;
        $strengtheningOfMonitoring->start_time = $request->start_time;
        $strengtheningOfMonitoring->start_time = $request->start_time;
        $strengtheningOfMonitoring->final_hour = $request->final_hour;
        $strengtheningOfMonitoring->final_hour = $request->final_hour;
        $strengtheningOfMonitoring->place = $request->place;
        $strengtheningOfMonitoring->monitoring_coordinator_id = config('user_default.monitoring_coordinator');
        $strengtheningOfMonitoring->strategic_objectives_area = $request->strategic_objectives_area;
        $strengtheningOfMonitoring->purpose_visit = $request->purpose_visit;
        $strengtheningOfMonitoring->topics_covered = $request->topics_covered;
        $strengtheningOfMonitoring->participants_perception = $request->participants_perception;
        $strengtheningOfMonitoring->problems_identified = $request->problems_identified;
        $strengtheningOfMonitoring->recommendations_actions = $request->recommendations_actions;
        $strengtheningOfMonitoring->comments_analysis = $request->comments_analysis;
        $save = $strengtheningOfMonitoring->save();
        DB::update(DB::RAW("UPDATE strengthening_of_monitorings SET consecutive = CONCAT('FAS', id) WHERE id=$strengtheningOfMonitoring->id"));

        if ($save) {
            $handle_1 = $this->send_file($request, 'development_activity_image', 'binnacle_territories', $strengtheningOfMonitoring->id);
            $strengtheningOfMonitoring->update(['development_activity_image' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];

            $handle_2 = $this->send_file($request, 'evidence_participation_image', 'binnacle_territories', $strengtheningOfMonitoring->id);
            $strengtheningOfMonitoring->update(['evidence_participation_image' => $handle_2['response']['payload']]);
            $save &= $handle_2['response']['success'];
        }

        // Guardamos en DataModel
        $this->control_data($strengtheningOfMonitoring, 'store');

        return $strengtheningOfMonitoring;
    }

    public function update(Request $request, $data, $id)
    {

        $strengtheningOfMonitoring = $this->model->find($id);

        $strengtheningOfMonitoring->consecutive = $request->consecutive;
        $strengtheningOfMonitoring->nac_id = $request->nac_id;
        $strengtheningOfMonitoring->role_id = $request->role_id;
        $strengtheningOfMonitoring->user_id = $request->user_id;
        $strengtheningOfMonitoring->activity_date = $request->activity_date;
        $strengtheningOfMonitoring->start_time = $request->start_time;
        $strengtheningOfMonitoring->start_time = $request->start_time;
        $strengtheningOfMonitoring->final_hour = $request->final_hour;
        $strengtheningOfMonitoring->final_hour = $request->final_hour;
        $strengtheningOfMonitoring->place = $request->place;
        $strengtheningOfMonitoring->strategic_objectives_area = $request->strategic_objectives_area;
        $strengtheningOfMonitoring->purpose_visit = $request->purpose_visit;
        $strengtheningOfMonitoring->topics_covered = $request->topics_covered;
        $strengtheningOfMonitoring->participants_perception = $request->participants_perception;
        $strengtheningOfMonitoring->problems_identified = $request->problems_identified;
        $strengtheningOfMonitoring->recommendations_actions = $request->recommendations_actions;
        $strengtheningOfMonitoring->comments_analysis = $request->comments_analysis;

        if ($request->hasFile('development_activity_image')) {
            $handle_1 = $this->update_file($request, 'development_activity_image', 'binnacle_territories', $strengtheningOfMonitoring->id, $strengtheningOfMonitoring->development_activity_image);

            $strengtheningOfMonitoring->update(['development_activity_image' => $handle_1['response']['payload']]);

            // $save &= $handle_1['response']['success'];
        }
        if ($request->hasFile('evidence_participation_image')) {
            $handle_2 = $this->update_file($request, 'evidence_participation_image', 'binnacle_territories', $strengtheningOfMonitoring->id, $strengtheningOfMonitoring->evidence_participation_image);

            $strengtheningOfMonitoring->update(['evidence_participation_image' => $handle_2['response']['payload']]);

            // $save &= $handle_2['response']['success'];
        }
        if ($request->status == 'REC') {
            $strengtheningOfMonitoring->status = 'ENREV';
        }

        $strengtheningOfMonitoring->save();

        // Guardamos en DataModel
        $this->control_data($strengtheningOfMonitoring, 'update');

        return $strengtheningOfMonitoring;
    }

    public function findById($id)
    {
        $find = $this->model->find($id);
        return $find;
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }


    function getValidate($data, $method)
    {

        $validate = [
            'consecutive' => 'bail|required',
            'nac_id' => 'bail|required',
            'role_id' => 'bail|required',
            'user_id' => 'bail|required',
            'activity_date' => 'bail|required',
            'start_time' => 'bail|required',
            'final_hour' => 'bail|required',
            'place' => 'bail|required',
            'strategic_objectives_area' => 'bail|required',
            'purpose_visit' => 'bail|required',
            'topics_covered' => 'bail|required',
            'participants_perception' => 'bail|required',
            'problems_identified' => 'bail|required',
            'recommendations_actions' => 'bail|required',
            'comments_analysis' => 'bail|required',
            'development_activity_image' => $method != 'update' ? 'bail|required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
            'evidence_participation_image' => $method != 'update' ? 'bail|required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un asistente con este :attribute.',
        ];

        $attrs = [
            'consecutive' => 'Consecutivo',
            'nac_id' => 'Nac',
            'role_id' => 'Rol',
            'user_id' => 'Usuario',
            'activity_date' => 'Fecha de actividad',
            'start_time' => 'Hora inicio',
            'final_hour' => 'Hora final',
            'place' => 'Lugar',
            'strategic_objectives_area' => 'Objetivos estratégicos del área',
            'purpose_visit' => 'Objetivos estratégicos del área',
            'topics_covered' => 'Temáticas abordadas',
            'participants_perception' => 'Percepción de los participantes frente a las actividades desarrolladas por el área',
            'problems_identified' => 'Dificultades o problemáticas identificadas',
            'recommendations_actions' => 'Recomendaciones y acciones de mejora propuestas por los participantes',
            'comments_analysis' => 'Percepciones/Comentarios/Análisis frente al avance del proceso',
            'development_activity_image' => 'Desarrollo de la visita territorial',
            'evidence_participation_image' => 'Evidencia de participación',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }

    public function roles($id)
    {
        if ($id == 0) return null;
        $nac = Nac::find($id);
        $profile = Profile::where('nac_id', $nac->id)->get();

        $rolesId = [];
        foreach ($profile as $key => $value) {
            array_push($rolesId, $value->id);
        }

        $roles = [];
        foreach ($rolesId as $key => $value) {
            $roles_query = Role::where('id', '=', $value)
                ->whereIn('slug', ['monitor_cultural', 'gestores_culturales', 'embajador', 'instructor', 'psicosocial'])
                ->select(['id as value', 'name as label'])->orderBy('id', 'DESC')->get();
            foreach ($roles_query as $role_key => $role) {
                array_push($roles, $role);
            }
        }

        return $roles;
    }

    public function usuarios($id)
    {
        if ($id == 0) return null;
        $rol = Role::find($id);
        $profile = Profile::where('role_id', $rol->id)->get();

        $usuariosId = [];
        foreach ($profile as $key => $value) {
            array_push($usuariosId, $value->user_id);
        }

        $usuarios = [];
        foreach ($usuariosId as $key => $user_id) {
            $users_query = Profile::where('user_id', $user_id)->select(['user_id as value', 'contractor_full_name as label'])->orderBy('id', 'DESC')->get();

            foreach ($users_query as $user_key => $user) {
                array_push($usuarios, $user);
            }
        }

        return $usuarios;
    }
}
