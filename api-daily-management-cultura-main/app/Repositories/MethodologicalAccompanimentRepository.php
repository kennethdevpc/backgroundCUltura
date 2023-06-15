<?php

namespace App\Repositories;

use App\Models\MethodologicalAccompaniment;
use App\Http\Resources\V1\MethodologicalAccompanimentCollection;
use App\Http\Resources\V1\MethodologicalAccompanimentResource;
use App\Traits\FunctionGeneralTrait;
use App\Traits\UserDataTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\DB;

class MethodologicalAccompanimentRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;

    function __construct()
    {
        $this->model = new  MethodologicalAccompaniment();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query();

        if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root')) {
            $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'DESC')
                ->get();
        }

        if ($rol_id == config('roles.coordinador_metodologico')) {
            $query->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'DESC')
                ->whereNotIn('created_by', [1,2])
                ->get();
        }

        if ($rol_id == config('roles.apoyo_metodologico')) {
            $query->where('created_by', '=', Auth::id())
                ->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")->orderBy('id', 'DESC')
                ->get();
        }

        // Aparece toda la data excepto lo de los admins
        if ($rol_id == config('roles.secretaria_cultural')) {
            $query->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','REV','REC','APRO') ASC")
                ->orderBy('id', 'DESC')->get();
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_methodologicalAccompaniment');
        session()->put('count_page_methodologicalAccompaniment', ceil($query->get()->count()/$paginate));

        return new MethodologicalAccompanimentCollection($query->simplePaginate($paginate));

    }

    public function create($request)
    {
        try {
            $methodologicalAccompaniment = $this->model;
            $dataClean = $request->all();
            $dataClean['metho_coordinator_id'] = config('user_default.methodological_coordinator');
            $methodologicalAccompaniment->fill($dataClean);
            $methodologicalAccompaniment->consecutive = $request->consecutive;

            $save = $methodologicalAccompaniment->save();

            if ($save) {
                $assitants = json_decode($request->assistants, true);
                $methodologicalAccompaniment->assistants()->attach($assitants);

                $base_query =  MethodologicalAccompaniment::query();

                $update = $base_query->find($methodologicalAccompaniment->id);

                $save &= $update->save();

                $handle_1 = $this->send_file($request, 'development_activity_image', 'methodologicalAccompaniments', $methodologicalAccompaniment->id);
                $methodologicalAccompaniment->update(['development_activity_image' => $handle_1['response']['payload']]);
                $save &= $handle_1['response']['success'];

                $handle_2 = $this->send_file($request, 'evidence_participation_image', 'methodologicalAccompaniments', $methodologicalAccompaniment->id);
                $methodologicalAccompaniment->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                $save &= $handle_2['response']['success'];
            }
            DB::update(DB::RAW("UPDATE methodological_accompaniments SET consecutive = CONCAT('MA', id) WHERE id=$methodologicalAccompaniment->id"));
            // Guardamos en DataModel
            // $this->control_data($methodologicalAccompaniment, 'store');

            return response()->json(['items' => 'Se ha guardado correctamente', 'success' => true]);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }

    public function findById($id)
    {
        $methodologicalAccompaniment =  MethodologicalAccompaniment::findOrFail($id);
        $result = new  MethodologicalAccompanimentResource($methodologicalAccompaniment);
        return $result;
    }

    public function update($request, $id)
    {

        try {
            $data = $request->all();
            $methodologicalAccompaniment =  MethodologicalAccompaniment::findOrFail($id);
            $updated  = $methodologicalAccompaniment->update($data);

            if ($updated) {
                $assitants = json_decode($data['assistants'], true);
                $methodologicalAccompaniment->assistants()->detach($assitants);

                if (count($assitants) > 0) {
                    $methodologicalAccompaniment->assistants()->attach($assitants);
                }

                if ($request->hasFile('development_activity_image')) {
                    $handle_1 = $this->update_file($request, 'development_activity_image', 'methodologicalAccompaniments', $methodologicalAccompaniment->id, $methodologicalAccompaniment->development_activity_image);
                    $methodologicalAccompaniment->update(['development_activity_image' => $handle_1['response']['payload']]);
                    $updated  &= $handle_1['response']['success'];
                }
                if ($request->hasFile('evidence_participation_image')) {
                    $handle_2 = $this->update_file($request, 'evidence_participation_image', 'methodologicalAccompaniments', $methodologicalAccompaniment->id, $methodologicalAccompaniment->evidence_participation_image);
                    $methodologicalAccompaniment->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                    $updated  &= $handle_2['response']['success'];
                }
            }
            if ($methodologicalAccompaniment->status == 'REC') {
                $methodologicalAccompaniment->update(['status' => 'ENREV']);
            }

            // $this->control_data($methodologicalAccompaniment, 'update');
            $result = new  MethodologicalAccompanimentResource($methodologicalAccompaniment);
            return response()->json(['items' => 'Se ha actualizado correctamente', 'success' => true]);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Error obteniendo el dato ' . $ex->getMessage() . ', buscar en linea de codigo ' . $ex->getLine(), 'success' => false], 404);
        }
    }


    public function delete($id)
    {
        $methodologicalAccompaniment =  MethodologicalAccompaniment::findOrFail($id);
        $methodologicalAccompaniment->delete();

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
