<?php

namespace App\Repositories;

use App\Models\CulturalCirculation;
use App\Http\Resources\V1\CulturalCirculationCollection;
use App\Http\Resources\V1\CulturalCirculationResource;
use App\Traits\FunctionGeneralTrait;
use App\Traits\UserDataTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;

class CulturalCirculationRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new CulturalCirculation();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id =  $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $queryCirculation = $this->model->query();

        // Solo aparezca lo relacionado a cada rol
        if ($rol_id == config('roles.apoyo_al_seguimiento_monitoreo')) {
            $queryCirculation->whereHas('user.profile', function ($query) use ($user_id) {
                $query->where('profiles.support_tracing_monitoring_id',  $user_id);
            })->whereNotIn('created_by', [1, 2])
            ->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC")
            ->orderBy('id', 'DESC');
        }
        // Solo revisa los usuarios que le pertenezcan
        if ($rol_id == config('roles.lider_instructor')) {
            $results = new CulturalCirculationCollection(
                $this->model->whereHas('user.profile', function ($query) use ($user_id) {
                    $query->where('profiles.instructor_leader_id',  $user_id);
                })->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','ENREV','REC','APRO') ASC")
                    ->orderBy('id', 'DESC')
            );
        }
        // Solo aparece lo creado por el
        if ($rol_id == config('roles.instructor')) {
            $queryCirculation->where('created_by', '=', $user_id)->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Aparece toda la data
        if ($rol_id == config('roles.super_root') || $rol_id == config('roles.root')) {
            $queryCirculation->orderByRaw("FIELD(status,'REC','REV','ENREV','APRO') ASC");
        }

        // Aparece toda la data excepto lo de los admins
        if ($rol_id == config('roles.secretaria_cultural')) {
            $queryCirculation->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','REV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');
        }

        // Aplicar filtros adicionales desde la URL
        $queryCirculation = $this->model->scopeFilterByUrl($queryCirculation);

        // Calcular número de páginas para paginación
        session()->forget('count_page_culturalCirculation');
        session()->put('count_page_culturalCirculation', ceil($queryCirculation->get()->count()/$paginate));

        return new CulturalCirculationCollection($queryCirculation->simplePaginate($paginate));
    }

    public function create($request)
    {
        $culturalCirculation = $this->model;
        $dataClean = $request->all();
        $dataClean['created_by'] = Auth::id();

        $culturalCirculation->fill($dataClean);
        $culturalCirculation->consecutive = $request->consecutive;
        // $culturalCirculation->user_review_support_follow_id = $this->getIdUserReview()->support_tracing_monitoring_id;
        // $culturalCirculation->user_review_manager_cultural_id = $this->getIdUserReview()->gestor_id;
        // $culturalCirculation->user_review_instructor_leader_id  = $this->getIdUserReview()->instructor_leader_id;
        $save = $culturalCirculation->save();

        if ($save) {
            // $beneficiaries = json_decode($request->beneficiary_id, true);
            // $culturalCirculation->beneficiaries()->attach($beneficiaries);

            $base_query = CulturalCirculation::query();

            $update = $base_query->find($culturalCirculation->id);

            $save &= $update->save();

            $handle_1 = $this->send_file($request, 'development_activity_image', 'culturalcirculations', $culturalCirculation->id);
            $culturalCirculation->update(['development_activity_image' => $handle_1['response']['payload']]);
            $save &= $handle_1['response']['success'];

            $handle_2 = $this->send_file($request, 'evidence_participation_image', 'culturalcirculations', $culturalCirculation->id);
            $culturalCirculation->update(['evidence_participation_image' => $handle_2['response']['payload']]);
            $save &= $handle_2['response']['success'];

            if ($request->hasFile('aforo_pdf')) {
                $handle_3 = $this->send_file($request, 'aforo_pdf', 'culturalcirculations', $culturalCirculation->id);
                $culturalCirculation->update(['aforo_pdf' => $handle_3['response']['payload']]);
                $save &= $handle_3['response']['success'];
            }
        }

        // Guardamos en DataModel
        $this->control_data($culturalCirculation, 'store');
        DB::update(DB::RAW("UPDATE cultural_circulations SET consecutive = CONCAT('BCC', id) WHERE id=$culturalCirculation->id"));
        return true;
    }

    public function findById($id)
    {
        $CulturalCirculation = CulturalCirculation::findOrFail($id);
        //$result = new CulturalCirculationResource($CulturalCirculation);
        return $CulturalCirculation;
    }

    public function update(Request $request, $data, $id)
    {
        $CulturalCirculation = CulturalCirculation::findOrFail($id);
        $CulturalCirculation->date = $request->date;
        $CulturalCirculation->keyactors_circulation_alliance = $request->keyactors_circulation_alliance;
        $CulturalCirculation->pec_id = $request->pec_id;
        $CulturalCirculation->datasheet_planning_id = $request->datasheet_planning_id;
        $CulturalCirculation->event_name = $request->event_name;
        $CulturalCirculation->filter_level = $request->filter_level;
        $CulturalCirculation->description = $request->description;
        $CulturalCirculation->nac_id = $request->nac_id;
        $CulturalCirculation->other_nac = $request->other_nac;
        $CulturalCirculation->quantity_members = $request->quantity_members;
        $CulturalCirculation->public_characteristics = $request->public_characteristics;
        $CulturalCirculation->cultural_right_id = $request->cultural_right_id;
        $CulturalCirculation->lineament_id = $request->lineament_id;
        $CulturalCirculation->orientation_id = $request->orientation_id;
        $CulturalCirculation->values = $request->values;
        $CulturalCirculation->artistic_expertise = $request->artistic_expertise;
        $CulturalCirculation->participation_observations = $request->participation_observations;
        $CulturalCirculation->number_attendees = $request->number_attendees;
        $CulturalCirculation->datasheet = $request->datasheet;



        if ($CulturalCirculation->status == 'REC') {
            $rol_id = $this->getIdRolUserAuth();
            if ($rol_id == config('roles.instructor') || $rol_id == config('roles.apoyo_al_seguimiento_monitoreo') || $rol_id == config('roles.lider_instructor')) {
                if (isset($CulturalCirculation->last_status)) {
                    $CulturalCirculation->status = $CulturalCirculation->last_status;
                } else {
                    $CulturalCirculation->status = 'ENREV';
                }
            }
        }

        if ($request->hasFile('development_activity_image')) {
            $handle_1 = $this->update_file($request, 'development_activity_image', 'culturalcirculations', $CulturalCirculation->id, $CulturalCirculation->development_activity_image);
            $CulturalCirculation->update(['development_activity_image' => $handle_1['response']['payload']]);
        }

        if ($request->hasFile('evidence_participation_image')) {
            $handle_2 = $this->update_file($request, 'evidence_participation_image', 'culturalcirculations', $CulturalCirculation->id, $CulturalCirculation->evidence_participation_image);
            $CulturalCirculation->update(['evidence_participation_image' => $handle_2['response']['payload']]);
        }

        if ($request->hasFile('aforo_pdf')) {
            $handle_3 = $this->update_file($request, 'aforo_pdf', 'culturalcirculations', $CulturalCirculation->id, $CulturalCirculation->aforo_pdf);
            $CulturalCirculation->update(['aforo_pdf' => $handle_3['response']['payload']]);
        }

        $CulturalCirculation->save();

        $this->control_data($CulturalCirculation, 'update');
        $result = new CulturalCirculationResource($CulturalCirculation);
        return $result;
    }

    public function delete($id)
    {
        $CulturalCirculation = CulturalCirculation::findOrFail($id);
        $CulturalCirculation->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }

    public function cleanData($data, $from)
    {
        $dataClean = NULL;
        /**
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
        $dataClean['created_by'] = Auth::id();
        return $dataClean;
         */
    }

    function getValidate($data, $from, $method)
    {
        $validate = [
            'date' => 'required',
            'consecutive' => 'required',
            'keyactors_circulation_alliance' => 'required|string|max:3500',
            'pec_id' => 'required',
            'datasheet_planning_id' => 'required',
            'event_name' => 'required|string|max:3500',
            'filter_level' => 'required',
            'description' => 'required|string|max:3500',
            'nac_id' => 'required',
            'quantity_members' => 'required',
            'public_characteristics' => 'required|string|max:3500',
            'cultural_right_id' => 'required',
            'lineament_id' => 'required',
            'orientation_id' => 'required',
            'values' => 'required',
            'artistic_expertise' => 'required|string|max:3500',
            'participation_observations' => 'required|string|max:3500',
            'development_activity_image' => 'required',
            'evidence_participation_image' => 'required',
            'aforo_pdf' => 'required',
            'number_attendees' => 'required',
            'datasheet' => 'required',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un asistente con este :attribute.',
        ];

        $attrs = [
            'date' => 'Fecha',
            'consecutive' => 'Consecutivo',
            'keyactors_circulation_alliance' => 'Actores Claves de alianza para la circulacion',
            'pec_id' => 'Filtro PEC',
            'datasheet_planning_id' => 'Ficha Metodologica Planeacion',
            'event_name' => 'Nombre del Evento',
            'filter_level' => 'Nivel de Dominio del Participante',
            'description' => 'Descripcion',
            'nac_id' => 'Territorio de Circulacion',
            'quantity_members' => 'Cantidad de integrantes del semillero',
            'public_characteristics' => 'Caracteristicas publico asistente',
            'cultural_right_id' => 'Derecho Cultural',
            'lineament_id' => 'Lineamientos',
            'orientation_id' => 'Orientaciones',
            'values' => 'Valor',
            'artistic_expertise' => 'Experticia Artistica a Trabajar',
            'participation_observations' => 'Observaciones de tu participacion',
            'development_activity_image' => 'Fotos del desarrollo',
            'evidence_participation_image' => 'Evidencia de participacion',
            'aforo_pdf' => 'Documneto del Aforo',
            'number_attendees' => 'Cantidad de asistentes',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }
}
