<?php

namespace App\Repositories;

use App\Http\Resources\V1\BinnacleShowCulturalCollection;
use App\Http\Resources\V1\BinnacleTerritorieCollection;
use App\Models\BinnacleCulturalShow;
use App\Models\BinnacleTerritorie;
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

class BinnacleCulturalShowRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new BinnacleCulturalShow();
    }

    public function getAll()
    {
        $results = $this->model->orderBy('id', 'DESC');
        $paginate = config('global.paginate');
        $rol = $this->getIdRolUserAuth();

        $id_auth = $this->getIdUserAuth();

        switch ($rol) {
            case config('roles.embajador'):
                $results->where('created_by', '=', $id_auth)
                    ->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC");
                break;
            case config('roles.lider_embajador'):
                $results->where('user_review_ambassador_leader_id', $id_auth)
                    ->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC");
                break;
            case config('roles.apoyo_al_seguimiento_monitoreo'):
                $results->where('user_review_support_follow_id', $id_auth)
                    ->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC");
                break;
            default:
                $results->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC");
                break;
        }

        // Aplicar filtros adicionales desde la URL
        $results = $this->model->scopeFilterByUrl($results);

        session()->forget('count_page_binnacles_show_cultural');
        session()->put('count_page_binnacles_show_cultural', ceil($results->count() / 2));

        return new BinnacleShowCulturalCollection($results->simplePaginate($paginate));

    }

    public function getAllByUserLogged()
    {
        $binnacles = $this->model->where('created_by', '=', Auth::id())->orderBy('id', 'DESC')->get();
        return new $binnacles;
    }

    public function create(Request $request)
    {
        $showCultural = $this->model;
        $showCultural->consecutive = $request->consecutive;
        $showCultural->date_range = $request->date_range;
        $showCultural->activity = $request->activity;
        $showCultural->expertise = $request->expertise;
        $showCultural->created_by = Auth::id();
        $showCultural->artistic_participation = $request->artistic_participation;
        $showCultural->reached_target = $request->reached_target;
        $showCultural->sustein = $request->sustein;
        $showCultural->number_attendees = $request->number_attendees;
        $showCultural->user_review_support_follow_id = $this->getIdUserReview()->support_tracing_monitoring_id;
        $showCultural->user_review_ambassador_leader_id = $this->getIdUserReview()->ambassador_leader_id;

        $save = $showCultural->save();

        if ($save) {
            $handle_1 = $this->send_file($request, 'development_activity_image', 'binnacle_territories', $showCultural->id);
            $showCultural->update(['development_activity_image' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];

            $handle_2 = $this->send_file($request, 'evidence_participation_image', 'binnacle_territories', $showCultural->id);
            $showCultural->update(['evidence_participation_image' => $handle_2['response']['payload']]);
            $save &= $handle_2['response']['success'];

            $handle_3 = $this->send_file($request, 'aforo_pdf', 'binnacle_territories', $showCultural->id);
            $showCultural->update(['aforo_pdf' => $handle_3['response']['payload']]);
            $save &= $handle_3['response']['success'];
        }

        // Guardamos en DataModel
        $this->control_data($showCultural, 'store');

        DB::update(DB::RAW("UPDATE binnacle_cultural_show SET consecutive = CONCAT('BCS', id) WHERE id=$showCultural->id"));
        return $showCultural;
    }

    public function update(Request $request, $id)
    {

        $showCultural = $this->model->find($id);

        $showCultural->date_range = $request->date_range;
        $showCultural->activity = $request->activity;
        $showCultural->expertise = $request->expertise;
        $showCultural->artistic_participation = $request->artistic_participation;
        $showCultural->reached_target = $request->reached_target;
        $showCultural->sustein = $request->sustein;
        $showCultural->number_attendees = $request->number_attendees;
        $showCultural->user_review_support_follow_id = $this->getIdUserReview()->support_tracing_monitoring_id;
        $showCultural->user_review_ambassador_leader_id = $this->getIdUserReview()->ambassador_leader_id;

        if ($request->hasFile('development_activity_image')) {
            $handle_1 = $this->update_file($request, 'development_activity_image', 'binnacle_territories', $showCultural->id, $showCultural->development_activity_image);

            $showCultural->update(['development_activity_image' => $handle_1['response']['payload']]);

            // $save &= $handle_1['response']['success'];
        }
        if ($request->hasFile('evidence_participation_image')) {
            $handle_2 = $this->update_file($request, 'evidence_participation_image', 'binnacle_territories', $showCultural->id, $showCultural->evidence_participation_image);

            $showCultural->update(['evidence_participation_image' => $handle_2['response']['payload']]);

            // $save &= $handle_2['response']['success'];
        }
        if ($request->status == 'REC') {
            if (isset($showCultural->last_status)) {
                $showCultural->status = $showCultural->last_status;
            } else {
                $showCultural->status = 'ENREV';
            }
        }

        $showCultural->save();

        // Guardamos en DataModel
        $this->control_data($showCultural, 'update');

        return $showCultural;
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
            'date_range' => 'bail|required',
            'activity' => 'bail|required',
            'expertise' => 'bail|required',
            'artistic_participation' => 'bail|required',
            'reached_target' => 'bail|required',
            'sustein' => 'bail|required',
            'aforo_pdf' => 'bail|required',
            'number_attendees' => 'bail|required',
            'development_activity_image' => $method != 'update' ? 'bail|required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
            'evidence_participation_image' => $method != 'update' ? 'bail|required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un asistente con este :attribute.',
        ];

        return $this->validator($data, $validate, $messages, []);
    }

    public function roles($id)
    {
        if ($id == 0)
            return null;
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
        if ($id == 0)
            return null;
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
