<?php

namespace App\Repositories;

use App\Http\Resources\V1\BinnacleCollection;
use App\Http\Resources\V1\BinnacleResource;
use App\Models\Binnacle;
use App\Utilities\Resources;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BinnacleRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new Binnacle();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();
        $paginate = config('global.paginate');
        $query = $this->model->query()
            ->with(['nac', 'cultural_right', 'expertise', 'orientation'])
            ->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
            ->where('type', 'other')
            ->orderBy('id', 'DESC');

        switch ($rol_id) {
            case config('roles.monitor'):
            case config('roles.instructor'):
            case config('roles.embajador'):
                $query->where('created_by', $user_id);
                break;

            case config('roles.gestor'):
                $query->where('user_review_manager_cultural_id', $user_id);
                break;

            case config('roles.apoyo_al_seguimiento_monitoreo'):
                $data = $query = $this->model->query()
                    ->with(['nac', 'cultural_right', 'expertise', 'orientation'])
                    ->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC")
                    ->where('type', 'other')
                    ->orderBy('id', 'DESC');

                $data->where('user_review_support_follow_id', $user_id)->get();
                break;

            case config('roles.lider_instructor'):
                $query->where('user_review_instructor_leader_id', $user_id);
                break;

            case config('roles.apoyo_metodologico'):
                $query->where('user_method_support_id', $user_id);
                break;

            case config('roles.direccion'):
            case config('roles.secretaria_cultural'):
            case config('roles.coordinador_supervision'):
            case config('roles.apoyo_supervision'):
                $query->whereHas('user', function ($query) {
                    $query->whereHas('profile', function ($profile) {
                        $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                    }
                    );
                });
                break;
        }

        $query = $this->model->scopeFilterByUrl($query);

        session()->forget('count_page_binnacles');
        session()->put('count_page_binnacles', ceil($query->count() / $paginate));

        return new BinnacleCollection($query->simplePaginate($paginate));
    }

    public function create(Request $request)
    {
        $binnacle = $this->model;
        $dataClean = $this->cleanData($request->all(), $request->created_from);

        $binnacle->fill($dataClean);
        $binnacle->consecutive = $request->consecutive;
        $binnacle->user_review_support_follow_id = $this->getIdUserReview()->support_tracing_monitoring_id;
        $binnacle->user_review_manager_cultural_id = $this->getIdUserReview()->gestor_id;
        $binnacle->user_review_instructor_leader_id = $this->getIdUserReview()->instructor_leader_id;
        $save = $binnacle->save();

        if ($save) {
            $beneficiaries = json_decode($request->beneficiary_id, true);
            $binnacle->beneficiaries()->attach($beneficiaries);

            $base_query = Binnacle::query();

            $update = $base_query->find($binnacle->id);

            $save &= $update->save();

            $handle_1 = $this->send_file($request, 'development_activity_image', 'binnacles', $binnacle->id);
            $binnacle->update(['development_activity_image' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];

            $handle_2 = $this->send_file($request, 'evidence_participation_image', 'binnacles', $binnacle->id);
            $binnacle->update(['evidence_participation_image' => $handle_2['response']['payload']]);
            $save &= $handle_2['response']['success'];

            if ($request->hasFile('aforo_file')) {
                $handle_3 = $this->send_file($request, 'aforo_file', 'binnacles', $binnacle->id);
                $binnacle->update(['aforo_file' => $handle_3['response']['payload']]);
                $save &= $handle_3['response']['success'];
            }
        }
        DB::update(DB::RAW("UPDATE binnacles SET consecutive = CONCAT('JP', id) WHERE id=$binnacle->id"));
        // Guardamos en DataModel
        $this->control_data($binnacle, 'store');

        return true;
    }

    public function update(Request $request, $data, $id)
    {
        $dataClean = $this->cleanData($data, $request->updated_from);
        $binnacle = $this->model->find($id);

        $beneficiaries = json_decode($request->beneficiary_id, true);
        $binnacle->beneficiaries()->sync($beneficiaries);

        if ($request->hasFile('development_activity_image')) {
            $handle_1 = $this->update_file($request, 'development_activity_image', 'binnacles', $binnacle->id, $binnacle->development_activity_image);

            $binnacle->update(['development_activity_image' => $handle_1['response']['payload']]);

            // $save &= $handle_1['response']['success'];
        }
        if ($request->hasFile('evidence_participation_image')) {
            $handle_2 = $this->update_file($request, 'evidence_participation_image', 'binnacles', $binnacle->id, $binnacle->evidence_participation_image);

            $binnacle->update(['evidence_participation_image' => $handle_2['response']['payload']]);

            // $save &= $handle_2['response']['success'];
        }

        if ($request->hasFile('aforo_file')) {
            $handle_3 = $this->update_file($request, 'aforo_file', 'binnacles', $binnacle->id, $binnacle->aforo_file);

            $binnacle->update(['aforo_file' => $handle_3['response']['payload']]);
            // $save &= $handle_3['response']['success'];
        }

        $binnacle->fill($dataClean);

        if ($binnacle->status == 'REC') {
            $rol_id = $this->getIdRolUserAuth();
            if ($rol_id == config('roles.monitor') || $rol_id == config('roles.embajador') || $rol_id == config('roles.instructor')) {
                if (isset($binnacle->last_status)) {
                    $binnacle->status = $binnacle->last_status;
                } else {
                    $binnacle->status = 'ENREV';
                }
            }
        }
        $binnacle->save();
        // Guardamos en DataModel
        $this->control_data($binnacle, 'update');

        return $binnacle;
    }

    public function findById($id)
    {
        $find = $this->model->find($id);
        $find->beneficiaries;
        return new BinnacleResource($find);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function cleanData($data, $from)
    {
        $rol_id = $this->getIdRolUserAuth();

        $dataClean = NULL;

        if ($from == 'gestor') {
            $dataClean = Validator::make($data, [
                'consecutive' => ['required'],
                'binnacle_id' => ['required'],
                'cultural_right_id' => ['required'],
                'lineament_id' => ['required'],
                'goals_met' => ['required'],
                'start_time' => ['required'],
                'final_hour' => ['required'],
                'activity_name' => ['required'],
                'start_activity' => ['required'],
                'activity_development' => ['required'],
                'end_of_activity' => ['required'],
                'observations_activity' => ['required'],
                'place' => ['required'],
                'experiential_objective' => ['required'],
                'explain_goals_met' => ['required'],
                'activity_date' => ['required'],
                'nac_id' => ['required'],
                'expertise_id' => ['required'],
                'orientation_id' => ['required'],
            ])->validate();
        }
        if ($from == 'monitor') {
            $dataClean = Validator::make($data, [
                'consecutive' => ['required'],
                'binnacle_id' => ['required'],
                'cultural_right_id' => ['required'],
                'lineament_id' => ['required'],
                'goals_met' => ['required'],
                'start_time' => ['required'],
                'final_hour' => ['required'],
                'activity_name' => ['required'],
                'start_activity' => ['required'],
                'activity_development' => ['required'],
                'end_of_activity' => ['required'],
                'observations_activity' => ['required'],
                'place' => ['required'],
                'experiential_objective' => ['required'],
                'explain_goals_met' => ['required'],
                'activity_date' => ['required'],
                'nac_id' => ['required'],
                'expertise_id' => ['required'],
                'orientation_id' => ['required'],
                // 'pec_id' => ['required'],
                // 'pedagogical_id' => ['required']
            ])->validate();
        }

        if ($data['pec_id'] != null && $from == 'monitor') {
            $dataClean['pec_id'] = $data['pec_id'];
        }
        if ($data['pedagogical_id'] != null && $from == 'monitor') {
            $dataClean['pedagogical_id'] = $data['pedagogical_id'];
        }

        if ($data['beneficiaries_or_capacity'] != 'aforo') {
            $dataClean['beneficiaries_or_capacity'] = 'beneficiaries';
        }
        if ($data['beneficiaries_or_capacity'] == 'aforo') {
            $dataClean['aforo_file'] = $data['aforo_file'];
            $dataClean['beneficiaries_capacity'] = $data['beneficiaries_capacity'];
            $dataClean['beneficiaries_or_capacity'] = $data['beneficiaries_or_capacity'];
        }
        if ($rol_id != config('roles.super_root' && $rol_id != config('roles.root'))) {
            $dataClean['created_by'] = Auth::id();
        }
        return $dataClean;
    }

    function getValidate($data, $from, $method)
    {

        $validate_monitor = [
            'binnacle_id' => 'required',
            'cultural_right_id' => 'required',
            'lineament_id' => 'required',
            'goals_met' => 'required',
            'start_time' => 'required',
            'final_hour' => 'required|after:start_time',
            'activity_name' => 'required|string|max:190',
            'start_activity' => 'required',
            'activity_development' => 'required',
            'end_of_activity' => 'required',
            'observations_activity' => 'required',
            'place' => 'required',
            'experiential_objective' => 'required',
            'explain_goals_met' => 'required',
            'development_activity_image' => $method != 'update' ? 'required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
            'evidence_participation_image' => $method != 'update' ? 'required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
            'activity_date' => 'required',
            'nac_id' => 'required',
            'expertise_id' => 'required',
            'orientation_id' => 'required',
        ];

        $validate_gestor = [
            'binnacle_id' => 'required',
            'cultural_right_id' => 'required',
            'lineament_id' => 'required',
            'goals_met' => 'required',
            'start_time' => 'required',
            'final_hour' => 'required|after:start_time',
            'activity_name' => 'required|string|max:190',
            'start_activity' => 'required',
            'activity_development' => 'required',
            'end_of_activity' => 'required',
            'observations_activity' => 'required',
            'place' => 'required',
            'experiential_objective' => 'required',
            'explain_goals_met' => 'required',
            'development_activity_image' => $method != 'update' ? 'required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
            'evidence_participation_image' => $method != 'update' ? 'required|mimes:application/pdf,pdf,png,webp,jpg,jpeg|max:' . (500 * 1049000) : 'bail',
            'activity_date' => 'required',
            'nac_id' => 'required',
            'expertise_id' => 'required',
            'orientation_id' => 'required',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un asistente con este :attribute.',
        ];

        $attrs = [
            'binnacle_id' => 'Bitacora',
            'cultural_right_id' => 'Derecho cultural',
            'lineament_id' => 'Lineamento',
            'goals_met' => 'Objetivos alcanzados',
            'start_time' => 'Hora inicio',
            'final_hour' => 'Hora final',
            'activity_name' => 'Nombre de la actividad',
            'start_activity' => 'Inicio actividad',
            'activity_development' => 'Desarrollo de actividad',
            'end_of_activity' => 'Final de la actividad',
            'observations_activity' => 'Observaciones de la actividad',
            'place' => 'Lugar',
            'experiential_objective' => 'Objetivo experimental',
            'explain_goals_met' => 'Explicar objectivos cumplidos',
            'development_activity_image' => 'Imagen desarrollo de actividad',
            'evidence_participation_image' => 'Evidencia de participación',
            'activity_date' => 'Fecha actividad',
            'nac_id' => 'Nac',
            'expertise_id' => 'Expertise',
            'orientation_id' => 'Orientación',
        ];

        if ($from == 'monitor')
            return $this->validator($data, $validate_monitor, $messages, $attrs);
        if ($from == 'gestor')
            return $this->validator($data, $validate_gestor, $messages, $attrs);
    }
}
