<?php

namespace App\Repositories;

use App\Http\Resources\V1\PsychosocialInstructionCollection;
use App\Http\Resources\V1\PsychosocialInstructionResource;
use App\Models\Asistant;
use App\Models\AssistantPsicosocialInstruction;
use App\Models\MonitorPsicosocialInstruction;
use App\Models\PsychosocialInstructions\PsychosocialInstruction;
use App\Models\User;
use App\Utilities\Resources;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;
use App\Traits\FunctionGeneralTrait;
use App\Traits\UserDataTrait;

class PsychosocialInstructionRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
   /**
    * This is a constructor function that initializes a new instance of the PsychosocialInstruction
    * class.
    */
    private $model;

    function __construct()
    {
        $this->model = new PsychosocialInstruction();
    }
    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * Crear los datos de la tabla principal PsychosocialInstruction y luego sube sus respectivo archivos
     * se cambian las horas de 2:30 pm a 14:30 pm ya que mysql no acepta el horario am-pm
     * se almace en $save cada accion para garantizar de que las accciones estan correctas (true) de lo contrario (false)
     */
    public function createPsychosocialInstruction($request)
    {
        DB::beginTransaction();
        try {

            $psychosocial_instruction = PsychosocialInstruction::create([
                'consecutive' => $request->consecutive,
                'activity_date' => $request->activity_date,
                'nac_id' => $request->nac_id,
                'start_time' => $request->start_time,
                'final_hour' => $request->final_hour,
                'objective_day' => Resources::getUpperString($request->objective_day),
                'themes_day' => Resources::getUpperString($request->themes_day),
                'user_id' => Auth::id(),
                'development_themes' => $request->development_themes,
                'conclusions_reflections_commitments' => $request->conclusions_reflections_commitments,
                'report_followup_alerts' => $request->report_followup_alerts
            ]);

            $save = 0;

            if ($psychosocial_instruction) {
                if ($request->hasFile('development_activity_image')) {
                    $handle_1 = $this->send_file($request, 'development_activity_image', 'psychosocial_instructions', $psychosocial_instruction->id);

                    if ($handle_1['response']['success']) {
                        $psychosocial_instruction->update(['development_activity_image' => $handle_1['response']['payload']]);
                    }
                }
                if ($request->hasFile('evidence_participation_image')) {
                    $handle_2 = $this->send_file($request, 'evidence_participation_image', 'psychosocial_instructions', $psychosocial_instruction->id);

                    if ($handle_1['response']['success']) {
                        $psychosocial_instruction->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                    }
                }

                $save = 1;
            }

            // Guardamos en ModelData
            $this->control_data($psychosocial_instruction, 'store');
            DB::update(DB::RAW("UPDATE psychosocial_instructions SET consecutive = CONCAT('IP', id) WHERE id=$psychosocial_instruction->id"));

            if ($save == 1) {
                // $wizards = json_decode($request->added_wizards);
                // $errors = [];

                // foreach ($wizards as $wizard) {
                //     $data = [];
                //     $data['nac_id'] = $wizard->nac_id;
                //     $data['assistant_name'] = $wizard->assistant_name;
                //     $data['assistant_document_number'] = $wizard->assistant_document_number;
                //     $data['assistant_position'] = $wizard->assistant_position;
                //     $data['assistant_phone'] = $wizard->assistant_phone;
                //     if ($wizard->assistant_email ?? NULL) {
                //         $data['assistant_email'] = $wizard->assistant_email;
                //     } else {
                //         $data['assistant_email'] = NULL;
                //     }
                //     $validator = Resources::validator($data);
                //     if ($validator->fails()) {
                //         array_push($errors, $validator->errors());
                //     }
                // }
                // if (count($errors) > 0) {
                //     return response()->json($errors, 403);
                // }
                $this->saveWizardsAssistance($request, $psychosocial_instruction->id);
            }
            DB::commit();
            return $save == 1 ?
                response()->json([
                    'items' => 'Datos almacenado correctamente',
                    'success' => true

                ]) :
                response()->json([
                    'error' => 'Ocurrio un problema en el almacenamiento',
                    'success' => false
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            DB::rollBack();
            report($ex);
            return response()->json(['error' => 'Error almacenado los datos ' . $ex->getMessage() . ' ' . $ex->getline() . ' ' . $ex->getCode(), 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $request
     * @param int $psychosocial_instruction_id
     * @return bool
     * Almacenando datos relacionales de Asistentes Agregados y Asistencia Monitores
     */
    private function saveWizardsAssistance($request, int $psychosocial_instruction_id): bool
    {
        DB::beginTransaction();
        try {

            $wizards = json_decode($request->added_wizards);

            foreach ($wizards as $wizard) {

                $assistant = Asistant::where('assistant_document_number', $wizard->assistant_document_number)->first();

                $assistantPsicosocialInstruction = new AssistantPsicosocialInstruction;
                $assistantPsicosocialInstruction->psycho_inst_id = $psychosocial_instruction_id;

                if (!$assistant) {

                    $assistantNew = new Asistant;
                    $assistantNew->assistant_name = $wizard->assistant_name;
                    $assistantNew->assistant_document_number = $wizard->assistant_document_number;
                    $assistantNew->assistant_position = $wizard->assistant_position;
                    $assistantNew->nac_id = $wizard->nac_id;
                    $assistantNew->assistant_phone = $wizard->assistant_phone;
                    if ($wizard->assistant_email ?? NULL) {
                        $assistantNew->assistant_email = $wizard->assistant_email;
                    } else {
                        $assistantNew->assistant_email = NULL;
                    }
                    $assistantNew->save();

                    // Guardamos en DataModel
                    $this->control_data($assistantNew, 'store');

                    $assistantPsicosocialInstruction->assistant_id = $assistantNew->id;
                } else {
                    $assistantPsicosocialInstruction->assistant_id = $assistant->id;
                }

                $assistantPsicosocialInstruction->save();
            }

            $monitors = json_decode($request->assistance_monitors);
            foreach ($monitors as $monitor) {

                $monitorPsicosocialInstruction = new MonitorPsicosocialInstruction;
                $monitorPsicosocialInstruction->psycho_inst_id = $psychosocial_instruction_id;
                $monitorPsicosocialInstruction->monitor_id = $monitor;
                $monitorPsicosocialInstruction->save();
            }
            DB::commit();
            return true;
        } catch (Exception $ex) {
            report($ex);
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * actualiza los datos de PsychosocialInstruction y  sus respectivo archivos
     */
    public function updatePsychosocialInstruction($request, $id)
    {
        try {
            $psychosocial_instruction = PsychosocialInstruction::find($id);

            $psychosocial_instruction->activity_date = $request->activity_date;
            $psychosocial_instruction->nac_id = $request->nac_id;
            $psychosocial_instruction->start_time = $request->start_time;
            $psychosocial_instruction->final_hour = $request->final_hour;
            $psychosocial_instruction->objective_day = Resources::getUpperString($request->objective_day);
            $psychosocial_instruction->themes_day = Resources::getUpperString($request->themes_day);
            $psychosocial_instruction->development_themes = $request->development_themes;
            $psychosocial_instruction->conclusions_reflections_commitments = $request->conclusions_reflections_commitments;
            $psychosocial_instruction->report_followup_alerts = $request->report_followup_alerts;

            if ($psychosocial_instruction->status == 'REC') {
                $rol_id = $this->getIdRolUserAuth();
                if ($rol_id == config('roles.psicosocial')) {
                    $psychosocial_instruction->status = 'ENREV';
                }
            }

            $save = $psychosocial_instruction->save();

            if ($save) {
                if ($request->hasFile('development_activity_image')) {
                    $handle_1 = $this->update_file($request, 'development_activity_image', 'psychosocial_instructions', $psychosocial_instruction->id, $psychosocial_instruction->development_activity_image);
                    $psychosocial_instruction->update(['development_activity_image' => $handle_1['response']['payload']]);
                    $save &= $handle_1['response']['success'];
                }
                if ($request->hasFile('evidence_participation_image')) {
                    $handle_2 = $this->update_file($request, 'evidence_participation_image', 'psychosocial_instructions', $psychosocial_instruction->id, $psychosocial_instruction->evidence_participation_image);
                    $psychosocial_instruction->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                    $save &= $handle_2['response']['success'];
                }
                $save &= $this->updateWizardsAssistance($request, $psychosocial_instruction->id);
                if ($psychosocial_instruction->status == 'REC') {
                    $rol_id = $this->getIdRolUserAuth();
                    if ($rol_id == config('roles.psicosocial')) {
                        $psychosocial_instruction->update([
                            'status' => 'ENREV'
                        ]);
                    }
                }
            }

            // Guardamos en ModelData
            $this->control_data($psychosocial_instruction, 'update');

            return  $this->toBoolean($save) ?
                response()->json(['items' => 'Datos actualizados correctamente', 'success' => true,]) :
                response()->json(['error' => 'Ocurrió un problema en la actualización', 'success' => false,], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error actualizando los datos ' . $ex->getMessage() . ' ' . $ex->getline() . ' ' . $ex->getCode(), 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $request
     * @param int $psychosocial_instruction_id
     * @return bool
     * se eliminan los datos relacionales de Asistentes Agregados y Asistencia Monitores y se crean los nuevos
     */
    private function updateWizardsAssistance($request, int $psychosocial_instruction_id): bool
    {
        try {
            // $wizards = json_decode($request->added_wizards);
            // $errors = [];

            // foreach ($wizards as $wizard) {
            //     $data = [];
            //     $data['nac_id'] = $wizard->nac_id;
            //     $data['assistant_name'] = $wizard->assistant_name;
            //     $data['assistant_document_number'] = $wizard->assistant_document_number;
            //     $data['assistant_position'] = $wizard->assistant_position;
            //     $data['assistant_phone'] = $wizard->assistant_phone;
            //     if ($wizard->assistant_email ?? NULL) {
            //         $data['assistant_email'] = $wizard->assistant_email;
            //     } else {
            //         $data['assistant_email'] = NULL;
            //     }
            // $validator = Resources::validator($data);
            // if ($validator->fails()) {
            //     array_push($errors, $validator->errors());
            // }
            // }
            // if (count($errors) > 0) {
            //     return response()->json($errors, 403);
            // }
            AssistantPsicosocialInstruction::query()->where('psycho_inst_id', '=', $psychosocial_instruction_id)->delete();
            MonitorPsicosocialInstruction::query()->where('psycho_inst_id', '=', $psychosocial_instruction_id)->delete();
            $is_saved = $this->saveWizardsAssistance($request, $psychosocial_instruction_id);
            return $is_saved;
        } catch (Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * @param $id
     * @return false|\Illuminate\Http\JsonResponse
     * elimina datos de PsychosocialInstruction (usa SoftDeletes)
     */
    public function deletePsychosocialInstruction($id)
    {
        try {
            $psychosocial_instruction = PsychosocialInstruction::query()->find($id)->delete();
            if ($psychosocial_instruction) {
                return response()->json(['message' => 'Datos eliminados correctamente', 'success' => true]);
            } else {
                return response()->json(['message' => 'Error eliminando los datos', 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * @param $id
     * @return false|\Illuminate\Http\JsonResponse
     * restaura datos eliminados (usa SoftDeletes)
     */
    public function restorePsychosocialInstruction($id)
    {
        try {
            $psychosocial_instruction = PsychosocialInstruction::query()->onlyTrashed()->find($id)->restore();
            if ($psychosocial_instruction) {
                return response()->json(['items' => 'Datos restaurados correctamente', 'success' => true]);
            } else {
                return response()->json(['error' => 'Error restaurando los datos', 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * @param $id
     * @return PsychosocialInstructionResource|\Illuminate\Http\JsonResponse
     * retorna un PsychosocialInstruction por su id y regresa una url validad par los archivos
     * php artisan storage:link necesario para el url
     */
    public function getPsychosocialInstructionById($id)
    {
        try {
            $psychosocial_instruction = $this->baseQuery()->find($id);
            return new PsychosocialInstructionResource($psychosocial_instruction);
        } catch (Exception $ex) {
            report($ex);
            return response()->json(['message' => 'Error obteniendo el dato', 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * Base query para reutilizar
     */
    private function baseQuery()
    {
        return PsychosocialInstruction::query()->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
            ->orderBy('id', 'DESC');
        // ->with([
        //     'nac:id,name', 'addedWizards'
        // ]);
    }

    /**
     * @return PsychosocialInstructionCollection|\Illuminate\Http\JsonResponse
     * retorna todos los datos de PsychosocialInstruction con las url de los archivos
     */
    public function getAll()
    {
        try {
            $paginate = config('global.paginate');
            $query = $this->baseQuery();

            // Aplicar filtros adicionales desde la URL
            $query = $this->model->scopeFilterByUrl($query);

            // Calcular número de páginas para paginación
            $count_page_group = ceil($query->get()->count() / $paginate);
            session()->put('count_page_group', $count_page_group);

            $psychosocial_instructions = [];
            $rol_id = Auth::user()->profile->role->id;

            if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root')) {
                $psychosocial_instructions =  $query->orderBy('id', 'DESC')->simplePaginate($paginate);
            }
            if ($rol_id == config('roles.psicosocial')) {
                $psychosocial_instructions = $query->where('user_id', Auth::id())->orderBy('id', 'DESC')->simplePaginate($paginate);
            }
            /* if ($rol_id == config('roles.coordinador_psicosocial')) {
                $psychosocial_instructions = $query->where('user_psychoso_coordinator_id', Auth::id())->orderBy('id', 'DESC')->simplePaginate($paginate);
            } */
            if ($rol_id == config('roles.direccion') || $rol_id == config('roles.coordinador_psicosocial') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
                $psychosocial_instructions = $query->whereHas('user', function ($query) {
                    $query->whereHas('profile', function ($profile) {
                        $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                    });
                })->simplePaginate($paginate);
            }
            $psychosocial_instructions->map(function ($obj) {
                $obj->development_activity_image = Storage::url($obj->development_activity_image);
                $obj->evidence_participation_image = Storage::url($obj->evidence_participation_image);

                return $obj;
            });

            session()->forget('count_page_monthlyMonitoringReports');
            session()->put('count_page_monthlyMonitoringReports', ceil($query->get()->count() / $paginate));
            return new PsychosocialInstructionCollection($query->simplePaginate($paginate));
        } catch (Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo los datos ' . $ex->getMessage() . ' ' . $ex->getline() . ' ' . $ex->getCode(), 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @return
     * retorna un listado de monitores
     */
    public function getMonitors()
    {
        try {
            $monitors = User::query()->whereHas('roles', function (Builder $query) {
                $query->whereIn('slug', ['monitor', 'embajador', 'instructor']);
            })->select(['id', 'name'])->latest()->paginate(Resources::PAGINATE_DEFAULT);
            return  $monitors;
        } catch (Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Error obteniendo los monitores', 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
