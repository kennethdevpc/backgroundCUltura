<?php

namespace App\Repositories;

use App\Http\Resources\V1\MethodologicalSheetsTwoCollection;
use App\Http\Resources\V1\MethodologicalSheetsTwoResource;
use App\Models\MethodologicalSheetsTwo;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MethodologicalSheetsTwoRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;
    function __construct()
    {
        $this->model = new MethodologicalSheetsTwo();
    }

    function getAll()
    {
        $query = $this->model->query();

        $paginate = config('global.paginate');

        $rol_id = $this->getIdRolUserAuth();

        $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'desc');;

        if ($rol_id == config('roles.direccion') ||  $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
            $query->whereHas('createdBy', function ($query) {
                $query->whereHas('profile', function ($profile) {
                    $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                });
            });
        }

        if($rol_id == config('roles.lider_instructor')){
            $query->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC");
        }

        if ($rol_id == config('roles.instructor')) {
            $query->where("created_by", $this->getIdUserAuth())
                ->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC");
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_methodologicalSheetsTwo');
        session()->put('count_page_methodologicalSheetsTwo', ceil($query->get()->count()/$paginate));

        return new MethodologicalSheetsTwoCollection($query->simplePaginate($paginate));
    }

    public function create($request)
    {
        try {
            $datasheet = $this->generateDataSheet($request->date_ini);

            $methodologicalSheetstwo = $this->model;
            $methodologicalSheetstwo->created_by = Auth::id();
            $methodologicalSheetstwo->datasheet = $datasheet;
            $methodologicalSheetstwo->consecutive = $request->consecutive;
            $methodologicalSheetstwo->activity_type = $request->activity_type;
            $methodologicalSheetstwo->date_ini = $request->date_ini;
            $methodologicalSheetstwo->date_fin = $request->date_fin;
            $methodologicalSheetstwo->keyactors_participating_community = $request->keyactors_participating_community;
            $methodologicalSheetstwo->objective_process = $request->objective_process;
            $methodologicalSheetstwo->reached_target = $request->reached_target;
            $methodologicalSheetstwo->sustein = $request->sustein;
            //  $methodologicalSheetstwo->participants_number = $request->participants_number;
            $methodologicalSheetstwo->development_activity_image = $request->development_activity_image;
            $methodologicalSheetstwo->evidence_participation_image = $request->evidence_participation_image;
            $methodologicalSheetstwo->number_attendees = $request->number_attendees;
            $methodologicalSheetstwo->aforo_pdf = $request->aforo_pdf;
            $save = $methodologicalSheetstwo->save();

            if ($save) {
                $handle_1 = $this->send_file($request, 'development_activity_image', 'methodologicalSheetsTwo', $methodologicalSheetstwo->id);
                $methodologicalSheetstwo->update(['development_activity_image' => $handle_1['response']['payload']]);
                $save &= $handle_1['response']['success'];
                $handle_2 = $this->send_file($request, 'evidence_participation_image', 'methodologicalSheetsTwo', $methodologicalSheetstwo->id);
                $methodologicalSheetstwo->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                $save &= $handle_2['response']['success'];

                if ($request->hasFile('aforo_pdf')) {
                    $handle_3 = $this->send_file($request, 'aforo_pdf', 'methodologicalSheetsTwo', $methodologicalSheetstwo->id);
                    $methodologicalSheetstwo->update(['aforo_pdf' => $handle_3['response']['payload']]);
                    $save &= $handle_3['response']['success'];
                } else {
                    $beneficiaries = json_decode($request->beneficiaries, true);
                    $methodologicalSheetstwo->beneficiary()->sync($beneficiaries);
                }
            }

            // Guardamos en DataModel
            $this->control_data($methodologicalSheetstwo, 'store');
            DB::update(DB::RAW("UPDATE methodological_sheets_two SET consecutive = CONCAT('FP', id) WHERE id=$methodologicalSheetstwo->id"));
            return $methodologicalSheetstwo;
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Algo salio mal ' . $ex->getMessage() . 'Linea ' . $ex->getCode(), 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $result = $this->model->findOrFail($id);
        return new MethodologicalSheetsTwoResource($result);
    }

    //recibe un registro por su id para actualizar
    public function update($request, $id)
    {
        try {

            $methodologicalSheetstwo = $this->model::findOrFail($id);
            // actualiza un pec por su id
            $methodologicalSheetstwo->activity_type = $request->activity_type;
            $methodologicalSheetstwo->date_ini = $request->date_ini;
            $methodologicalSheetstwo->date_fin = $request->date_fin;
            $methodologicalSheetstwo->keyactors_participating_community = $request->keyactors_participating_community;
            $methodologicalSheetstwo->objective_process = $request->objective_process;
            $methodologicalSheetstwo->reached_target = $request->reached_target;
            $methodologicalSheetstwo->sustein = $request->sustein;
            //  $methodologicalSheetstwo->participants_number = $request->participants_number;
            $methodologicalSheetstwo->development_activity_image = $request->development_activity_image;
            $methodologicalSheetstwo->evidence_participation_image = $request->evidence_participation_image;
            $methodologicalSheetstwo->number_attendees = $request->number_attendees;
            $methodologicalSheetstwo->aforo_pdf = $request->aforo_pdf;

            if ($request->status == 'REC') {
                $methodologicalSheetstwo->status = "ENREV";
            }

            $save = $methodologicalSheetstwo->save();

            if ($save) {
                if ($request->hasFile('development_activity_image')) {
                    $handle_1 = $this->send_file($request, 'development_activity_image', 'methodologicalSheetsTwo', $methodologicalSheetstwo->id);
                    $methodologicalSheetstwo->update(['development_activity_image' => $handle_1['response']['payload']]);
                    $save &= $handle_1['response']['success'];
                }
                if ($request->hasFile('development_activity_image')) {
                    $handle_2 = $this->send_file($request, 'evidence_participation_image', 'methodologicalSheetsTwo', $methodologicalSheetstwo->id);
                    $methodologicalSheetstwo->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                    $save &= $handle_2['response']['success'];
                }

                if ($request->hasFile('aforo_pdf')) {
                    $handle_3 = $this->send_file($request, 'aforo_pdf', 'binnacles', $methodologicalSheetstwo->id);
                    $methodologicalSheetstwo->update(['aforo_pdf' => $handle_3['response']['payload']]);
                    $save &= $handle_3['response']['success'];
                }
            }

            // Guardamos en DataModel
            $this->control_data($methodologicalSheetstwo, 'update');
            return response()->json(['items' => 'Datos actualizados correctamente', 'success' => true]);
        } catch (\Exception $ex) {
            report($ex);
            return response()->json(['error' => 'Algo salio mal ' . $ex->getMessage() . 'Linea ' . $ex->getCode(), 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //método para borrar un registro
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
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


    //método para validar campos
    public function getValidate($data, $method)
    {

        $validate = [
            'activity_type' => 'required',
            'date_range' => 'required',
            'keyactors_participating_community' => 'required|string|max:190',
            'objective_process' => 'required|string|max:190',
            'reached_target' => 'required',
            'sustein' => 'required',
            //  'participants_number' => 'required',
        ];


        $messages = [
            'required' => ':attribute es obligatorio.',
        ];

        $attrs = [
            'activity_type' => 'Tipo de actividades',
            'date_range' => 'Formato dia intervalo',
            'keyactors_participating_community' => 'Actores claves de la comunidad participantes',
            'objective_process' => 'Objetivo del proceso',
            'reached_target' => 'Se alcanzó el objetivo',
            'sustein' => 'Sustentacion',
            // 'participants_number' => 'Cantidad de participantes',
            'development_activity_image' => 'Foto de desarrollo',
            'evidence_participation_image' => 'Evidencia de participación',
            'number_attendees' => 'Capacidad',
            'aforo_pdf' => 'Documento del aforo',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }

    public function getCountLimit()
    {
        $count = $this->model->where('created_by', '=', Auth::id())->count();

        $limit = config('global.limitSheet');

        $continue = ($limit - ($count + 1)) >= 0;

        return ([
            'count' => $count + 1,
            'continue' => $continue,
            'limit' => $limit
        ]);
    }
}
