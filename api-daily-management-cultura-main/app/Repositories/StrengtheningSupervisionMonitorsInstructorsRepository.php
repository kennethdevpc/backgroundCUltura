<?php

namespace App\Repositories;

use App\Http\Resources\V1\StrengtheningOfMonitoringCollection;
use App\Http\Resources\V1\StrengtheningSupervisionMonitorsInstructorsCollection;
use App\Models\StrengtheningOfMonitoring;
use App\Models\Nac;
use App\Models\Profile;
use App\Models\Role;
use App\Models\StrengtheningSupervisionMonitorsInstructors;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StrengtheningSupervisionMonitorsInstructorsRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new StrengtheningSupervisionMonitorsInstructors();
    }

    public function getAll()
    {
        $rol = $this->getIdUserAuth();
        $query = $this->model->query();

        $paginate = config('global.paginate');

        if ($rol === config('roles.apoyo_supervision')) {
            $query->where('created_by', '=', Auth::id())->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
                ->orderBy('id', 'DESC')->get();
        } else {
            $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
                ->orderBy('id', 'DESC')->get();
        }

        // Filtrar por cada uno de los campos
        $query = $this->model->scopeFilterByUrl($query);

        // Guardar numeros de paginas para la paginación
        session()->forget('count_page_strengtheningSupervisionMonitors');
        session()->put('count_page_strengtheningSupervisionMonitors', ceil($query->count() / $paginate));

        return new StrengtheningSupervisionMonitorsInstructorsCollection($query->simplePaginate($paginate));
        // return new StrengtheningSupervisionMonitorsInstructorsCollection($data);
    }

    public function create(Request $request)
    {
        $strengtheningOfMonitoring = $this->model;
        $strengtheningOfMonitoring->consecutive = $request->consecutive;
        $strengtheningOfMonitoring->revision_date = $request->revision_date;
        $strengtheningOfMonitoring->nac_id = $request->nac_id;
        $strengtheningOfMonitoring->role_id = $request->role_id;
        $strengtheningOfMonitoring->supervised_user_full_name = $request->supervised_user_full_name;
        $strengtheningOfMonitoring->platform_registration_date = $request->platform_registration_date;
        $strengtheningOfMonitoring->created_by = Auth::id();
        $strengtheningOfMonitoring->address = $request->address;
        $strengtheningOfMonitoring->pec_reached_target = $request->pec_reached_target;
        $strengtheningOfMonitoring->pedagogicals_reached_target = $request->pedagogicals_reached_target;
        $strengtheningOfMonitoring->attendance_list = $request->attendance_list;
        $strengtheningOfMonitoring->validated_pec_time = $request->validated_pec_time;
        $strengtheningOfMonitoring->description = $request->description;
        $strengtheningOfMonitoring->comments = $request->comments;
        $save = $strengtheningOfMonitoring->save();

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
        DB::update(DB::RAW("UPDATE strengthening_super_mons_insts SET consecutive = CONCAT('FSMI', id) WHERE id=$strengtheningOfMonitoring->id"));

        return $strengtheningOfMonitoring;
    }

    public function update(Request $request, $data, $id)
    {

        $strengtheningOfMonitoring = $this->model->find($id);

        $strengtheningOfMonitoring->consecutive = $request->consecutive;
        $strengtheningOfMonitoring->revision_date = $request->revision_date;
        $strengtheningOfMonitoring->nac_id = $request->nac_id;
        $strengtheningOfMonitoring->role_id = $request->role_id;
        $strengtheningOfMonitoring->supervised_user_full_name = $request->supervised_user_full_name;
        $strengtheningOfMonitoring->platform_registration_date = $request->platform_registration_date;
        $strengtheningOfMonitoring->address = $request->address;
        $strengtheningOfMonitoring->pec_reached_target = $request->pec_reached_target;
        $strengtheningOfMonitoring->pedagogicals_reached_target = $request->pedagogicals_reached_target;
        $strengtheningOfMonitoring->attendance_list = $request->attendance_list;
        $strengtheningOfMonitoring->validated_pec_time = $request->validated_pec_time;
        $strengtheningOfMonitoring->description = $request->description;
        $strengtheningOfMonitoring->comments = $request->comments;

        if ($request->hasFile('development_activity_image')) {
            $handle_1 = $this->update_file($request, 'development_activity_image', 'binnacle_territories', $strengtheningOfMonitoring->id, $strengtheningOfMonitoring->development_activity_image);
            $strengtheningOfMonitoring->update(['development_activity_image' => $handle_1['response']['payload']]);
        }

        if ($request->hasFile('evidence_participation_image')) {
            $handle_2 = $this->update_file($request, 'evidence_participation_image', 'binnacle_territories', $strengtheningOfMonitoring->id, $strengtheningOfMonitoring->evidence_participation_image);
            $strengtheningOfMonitoring->update(['evidence_participation_image' => $handle_2['response']['payload']]);
        }
        if ($strengtheningOfMonitoring->status == 'REC') {
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
            'supervised_user_full_name' => 'bail|required',
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
            'description' =>  'bail|required|max:3500',
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
            'supervised_user_full_name' => 'Usuario',
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
            'description' => 'Descripcion',
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
            array_push($usuariosId, $value->supervised_user_full_name);
        }

        $usuarios = [];
        foreach ($usuariosId as $key => $supervised_user_full_name) {
            $users_query = Profile::where('supervised_user_full_name', $supervised_user_full_name)->select(['supervised_user_full_name as value', 'contractor_full_name as label'])->orderBy('id', 'DESC')->get();

            foreach ($users_query as $user_key => $user) {
                array_push($usuarios, $user);
            }
        }

        return $usuarios;
    }
}
