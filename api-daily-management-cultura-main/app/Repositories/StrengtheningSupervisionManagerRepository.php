<?php

namespace App\Repositories;

use App\Http\Resources\V1\StrengtheningSupervisionManagerCollection;
use App\Http\Resources\V1\StrengtheningSupervisionManagerResource;
use App\Models\MethodologicalInstructionModel;
use App\Models\Nac;
use App\Models\Profile;
use App\Models\Role;
use App\Models\StrengtheningSupervisionManagers;
use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use App\Utilities\Resources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StrengtheningSupervisionManagerRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new StrengtheningSupervisionManagers();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query()->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
            ->orderBy('id', 'DESC');

        if ($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision')) {
            $query->whereHas('user', function ($query) {
                $query->whereHas('profile', function ($profile) {
                    $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                });
            });
        }

        if ($rol_id == config('roles.apoyo_supervision')) {
            $query->where('created_by', $user_id);
        }

        if ($rol_id == config('roles.coordinador_supervision')) {
            $query->where('super_coordinator_id', $user_id);
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_strengtheningSupervisionManager');
        session()->put('count_page_strengtheningSupervisionManager', ceil($query->get()->count()/$paginate));

        return new StrengtheningSupervisionManagerCollection($query->simplePaginate($paginate));

        // return new StrengtheningSupervisionManagerCollection($results);
    }

    public function create(Request $request)
    {
        $strengtheningSupervisionManager = $this->model;
        $strengtheningSupervisionManager->consecutive = $request['consecutive'];
        $strengtheningSupervisionManager->revision_date = $request['revision_date'];
        $strengtheningSupervisionManager->address = $request['address'];
        $strengtheningSupervisionManager->methodological_instruction_reached_target = $request['methodological_instruction_reached_target'];
        $strengtheningSupervisionManager->frequency = $request['frequency'];
        $strengtheningSupervisionManager->binnacle_registered_plataform = $request['binnacle_registered_plataform'];
        $strengtheningSupervisionManager->description = $request['description'];
        $strengtheningSupervisionManager->start_time = $request['start_time'];
        $strengtheningSupervisionManager->final_time = $request['final_time'];
        $strengtheningSupervisionManager->comments = $request['comments'];
        $strengtheningSupervisionManager->created_by = Auth::id();
        $strengtheningSupervisionManager->super_coordinator_id = config('user_default.supervision_coordinator');
        $strengtheningSupervisionManager->nac_id = $request['nac_id'];
        $strengtheningSupervisionManager->user_associate_id = $request['user_associate_id'];
        $strengtheningSupervisionManager->methodological_instruction_id = $request['methodological_instruction_id'];
        $save =  $strengtheningSupervisionManager->save();
        DB::update(DB::RAW("UPDATE strengthening_super_mangs SET consecutive = CONCAT('SGT', id) WHERE id=$strengtheningSupervisionManager->id"));

        if ($save) {
            $handle_1 = $this->send_file($request, 'development_activity_image', 'strengthening_super_mangs', $strengtheningSupervisionManager->id);
            $strengtheningSupervisionManager->update(['development_activity_image' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];

            $handle_2 = $this->send_file($request, 'evidence_participation_image', 'strengthening_super_mangs', $strengtheningSupervisionManager->id);
            $strengtheningSupervisionManager->update(['evidence_participation_image' => $handle_2['response']['payload']]);
            $save &= $handle_2['response']['success'];
        }

        // Guardamos en DataModel
        // $this->control_data($strengtheningSupervisionManager, 'store');

        return $strengtheningSupervisionManager;
    }

    public function update(Request $request, $data, $id)
    {

        $strengtheningSupervisionManager = $this->model->findOrFail($id);
        $strengtheningSupervisionManager->consecutive = $request['consecutive'];
        $strengtheningSupervisionManager->revision_date = $request['revision_date'];
        $strengtheningSupervisionManager->address = $request['address'];
        $strengtheningSupervisionManager->methodological_instruction_reached_target = $request['methodological_instruction_reached_target'];
        $strengtheningSupervisionManager->frequency = $request['frequency'];
        $strengtheningSupervisionManager->binnacle_registered_plataform = $request['binnacle_registered_plataform'];
        $strengtheningSupervisionManager->description = $request['description'];
        $strengtheningSupervisionManager->start_time = $request['start_time'];
        $strengtheningSupervisionManager->final_time = $request['final_time'];
        $strengtheningSupervisionManager->comments = $request['comments'];
        $strengtheningSupervisionManager->nac_id = $request['nac_id'];
        $strengtheningSupervisionManager->user_associate_id = $request['user_associate_id'];
        $strengtheningSupervisionManager->methodological_instruction_id = $request['methodological_instruction_id'];

        if ($request->hasFile('development_activity_image')) {
            $handle_1 = $this->update_file($request, 'development_activity_image', 'strengthening_super_mangs', $strengtheningSupervisionManager->id, $strengtheningSupervisionManager->development_activity_image);
            $strengtheningSupervisionManager->update(['development_activity_image' => $handle_1['response']['payload']]);
        }

        if ($request->hasFile('evidence_participation_image')) {
            $handle_2 = $this->update_file($request, 'evidence_participation_image', 'strengthening_super_mangs', $strengtheningSupervisionManager->id, $strengtheningSupervisionManager->evidence_participation_image);
            $strengtheningSupervisionManager->update(['evidence_participation_image' => $handle_2['response']['payload']]);
        }

        if ($strengtheningSupervisionManager->status == 'REC') {
            $strengtheningSupervisionManager->status = 'ENREV';
        }

        $strengtheningSupervisionManager->save();

        // $strengtheningSupervisionManager->save();

        // Guardamos en DataModel
        // $this->control_data($strengtheningSupervisionManager, 'update');

        return $strengtheningSupervisionManager;
    }

    public function findById($id)
    {
        $result = $this->model->findOrFail($id);
        return new StrengtheningSupervisionManagerResource($result);
    }

    public function delete($id)
    {
        $result = $this->model->findOrFail($id);
        return $result->delete();
    }


    function getValidate($data, $method)
    {

        $validate = [
            'consecutive' => 'bail|required',
            'revision_date' => 'bail|required',
            'address' => 'bail|required',
            'methodological_instruction_reached_target' => 'bail|required',
            'frequency' => 'bail|required|numeric',
            'binnacle_registered_plataform' => 'bail|required|numeric',
            'description' => 'bail|required|max:3500',
            'start_time' => 'bail|required',
            'final_time' => ['bail', 'required', 'after:start_time'],
            'comments' => 'bail|required|max:3500',
            'nac_id' => 'bail|required',
            'user_associate_id' => 'bail|required',
            'development_activity_image' => $method != 'update' ? 'bail|required|max:' . (500 * 1049000) : 'bail',
            'evidence_participation_image' => $method != 'update' ? 'bail|required|max:' . (500 * 1049000) : 'bail',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un asistente con este :attribute.',
            'number' => ':attribute debe ser un número',
        ];

        $attrs = [
            'consecutive' => 'Consecutivo',
            'revision_date' => 'Fecha revisión',
            'address' => 'Dirección',
            'methodological_instruction_reached_target' => 'Cumplio?',
            'frequency' => 'Frecuencia',
            'binnacle_registered_plataform' => 'Bitacoras registradas en la plataforma',
            'description' => 'Descripción',
            'start_time' => 'Hora inicio',
            'final_time' => 'Hora final',
            'comments' => 'Comentarios',
            'development_activity_image' => 'Desarrollo de la visita territorial',
            'evidence_participation_image' => 'Evidencia de participación',
            'nac_id' => 'Nac',
            'user_associate_id' => 'Usuario gestor',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }

    public function users($id)
    {
        if ($id == 0) return null;
        $rol = Role::find($id);

        $roles = ['gestores_culturales'];
        $usuarios = Profile::query()->whereHas('role', function ($query) use ($roles) {
            $query->whereIn('slug', $roles);
        })->where('nac_id', $id)->select('user_id as value',  'contractor_full_name as label')
            ->get();

        return $usuarios;
    }
    public function methodologicalInstructionGestor($id)
    {
        if ($id == 0) return null;

        $methodological_instructions  = MethodologicalInstructionModel::select('id as value', DB::raw('CONCAT(consecutive,"-",activity_date,"-",start_time,"-",final_hour) AS label'))
            ->where('created_by', $id)->get();

        return $methodological_instructions;
    }
}
