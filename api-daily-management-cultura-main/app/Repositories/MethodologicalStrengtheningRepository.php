<?php

namespace App\Repositories;

use App\Models\MethodologicalStrengthening;
use App\Http\Resources\V1\MethodologicalStrengtheningCollection;
use App\Http\Resources\V1\MethodologicalStrengtheningResource;
use App\Traits\FunctionGeneralTrait;
use App\Traits\UserDataTrait;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;

class MethodologicalStrengtheningRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new MethodologicalStrengthening();
    }

    public function getAll()
    {

        $rol_id = $this->getIdRolUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query();

        if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root')) {
            $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'DESC');
        }

        if ($rol_id == config('roles.coordinador_metodologico')) {
            $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'DESC')
                ->whereNotIn('created_by', [1,2]);
        }

        if ($rol_id == config('roles.apoyo_metodologico')) {
            $query->where('created_by', '=', Auth::id())
                ->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'DESC');
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
        session()->forget('count_page_methodologicalStrengthening');
        session()->put('count_page_methodologicalStrengthening', ceil($query->get()->count()/$paginate));

        return new MethodologicalStrengtheningCollection($query->simplePaginate($paginate));
        // return new MethodologicalStrengtheningCollection($results);

    }

    public function create($request)
    {

        try {
            $methodologicalStrengthening = $this->model;
            $dataClean = $request->all();
            $dataClean['metho_coordinator_id'] = config('user_default.methodological_coordinator');

            $methodologicalStrengthening->fill($dataClean);

            $methodologicalStrengthening->consecutive = $request->consecutive;

            $save = $methodologicalStrengthening->save();

            if ($save) {
                // $beneficiaries = json_decode($request->beneficiary_id, true);
                $assitants = json_decode($request->assistants, true);
                $methodologicalStrengthening->assistants()->attach($assitants);

                $base_query = MethodologicalStrengthening::query();

                $update = $base_query->find($methodologicalStrengthening->id);

                $save &= $update->save();

                $handle_1 = $this->send_file($request, 'development_activity_image', 'MethodologicalStrengthenings', $methodologicalStrengthening->id);
                $methodologicalStrengthening->update(['development_activity_image' => $handle_1['response']['payload']]);
                $save &= $handle_1['response']['success'];

                $handle_2 = $this->send_file($request, 'evidence_participation_image', 'MethodologicalStrengthenings', $methodologicalStrengthening->id);
                $methodologicalStrengthening->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                $save &= $handle_2['response']['success'];
                DB::update(DB::RAW("UPDATE methodological_strengthenings SET consecutive = CONCAT('FM', id) WHERE id=$methodologicalStrengthening->id"));
            }

            return response()->json(['items' => 'Se ha guardado correctamente', 'success' => true]);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }

        // Guardamos en DataModel
        // $this->control_data($methodologicalStrengthening, 'store');


    }

    public function findById($id)
    {
        $MethodologicalStrengthening = MethodologicalStrengthening::findOrFail($id);
        $result = new MethodologicalStrengtheningResource($MethodologicalStrengthening);
        return $result;
    }

    public function update($request, $id)
    {
        try {
            $data = $request->all();
            $methodologicalStrengthening = MethodologicalStrengthening::findOrFail($id);
            $updated = $methodologicalStrengthening->update($data);

            if ($updated) {
                $assitants = json_decode($data['assistants'], true);
                $methodologicalStrengthening->assistants()->detach($assitants);
                if (count($assitants) > 0) {

                    $methodologicalStrengthening->assistants()->attach($assitants);
                }
            }
            if ($request->hasFile('development_activity_image')) {
                $handle_1 = $this->update_file($request, 'development_activity_image', 'methodologicalStrengthenings', $methodologicalStrengthening->id, $methodologicalStrengthening->development_activity_image);
                $methodologicalStrengthening->update(['development_activity_image' => $handle_1['response']['payload']]);
                $updated  &= $handle_1['response']['success'];
            }
            if ($request->hasFile('evidence_participation_image')) {
                $handle_2 = $this->update_file($request, 'evidence_participation_image', 'methodologicalStrengthenings', $methodologicalStrengthening->id, $methodologicalStrengthening->evidence_participation_image);
                $methodologicalStrengthening->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                $updated  &= $handle_2['response']['success'];
            }
            if ($methodologicalStrengthening->status == 'REC') {
                $methodologicalStrengthening->update(['status' => 'ENREV']);
            }

            // $this->control_data($methodologicalStrengthening,'update');
            $result = new MethodologicalStrengtheningResource($methodologicalStrengthening);
            return response()->json(['items' => 'Se ha actualizado correctamente', 'success' => true]);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function delete($id)
    {
        $methodologicalStrengthening = MethodologicalStrengthening::findOrFail($id);
        $methodologicalStrengthening->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }

    function getValidate($data, $method)
    {
        $validate = [
            'description' =>  'max:3500',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
        ];

        $attrs = [
            'description' => 'Descripcion',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }
}
