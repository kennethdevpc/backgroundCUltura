<?php

namespace App\Repositories;

use App\Models\CulturalEnsemble;
use App\Http\Resources\V1\CulturalEnsembleCollection;
use App\Traits\FunctionGeneralTrait;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CulturalEmsembleRepository
{
    use FunctionGeneralTrait, UserDataTrait;
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;


    private $model;

    function __construct()
    {
        $this->model = new CulturalEnsemble();
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query();

        // Solo aparezca lo relacionado a cada rol
        if ($rol_id == config('roles.apoyo_al_seguimiento_monitoreo')) {
            $query->whereHas('user.profile', function ($query) use ($user_id) {
                    $query->where('profiles.support_tracing_monitoring_id', $user_id);
                })->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Solo revisa los usuarios que le pertenezcan
        if ($rol_id == config('roles.lider_instructor')) {
            $query->whereHas('user.profile', function ($query) use ($user_id) {
                    $query->where('profiles.instructor_leader_id', $user_id);
                })->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','ENREV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Solo aparece lo creado por el
        if ($rol_id == config('roles.instructor')) {
            $query->where('created_by', '=', $user_id)->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Aparece toda la data excepto lo de los admins
        if ($rol_id == config('roles.secretaria_cultural')) {
            $query->whereNotIn('created_by', [1, 2])
                ->orderByRaw("FIELD(status,'REV','REV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');
        }
        // Aparece toda la data
        if ($rol_id == config('roles.super_root') || $rol_id == config('roles.root')) {
            $query->orderByRaw("FIELD(status,'REC','REV','ENREV','APRO') ASC");
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_culturalEmsemble');
        session()->put('count_page_culturalEmsemble', ceil($query->get()->count()/$paginate));

        return new CulturalEnsembleCollection($query->simplePaginate($paginate));
    }
    public function create($request)
    {
        try {

            $culturalEnsemble = $this->model;
            $dataClean = $request->all();
            $dataClean['created_by'] = Auth::id();

            $culturalEnsemble->fill($dataClean);
            $culturalEnsemble->consecutive = $request->consecutive;

            $save = $culturalEnsemble->save();

            if ($save) {
                // $beneficiaries = json_decode($request->beneficiary_id, true);
                // $culturalCirculation->beneficiaries()->attach($beneficiaries);

                $base_query = CulturalEnsemble::query();

                $update = $base_query->find($culturalEnsemble->id);

                $save &= $update->save();

                $handle_1 = $this->send_file($request, 'development_activity_image', 'culturalEnsemble', $culturalEnsemble->id);
                $culturalEnsemble->update(['development_activity_image' => $handle_1['response']['payload']]);
                $save &= $handle_1['response']['success'];

                $handle_2 = $this->send_file($request, 'evidence_participation_image', 'culturalEnsemble', $culturalEnsemble->id);
                $culturalEnsemble->update(['evidence_participation_image' => $handle_2['response']['payload']]);
                $save &= $handle_2['response']['success'];

                if ($request->hasFile('aforo_pdf')) {
                    $handle_3 = $this->send_file($request, 'aforo_pdf', 'culturalEnsemble', $culturalEnsemble->id);
                    $culturalEnsemble->update(['aforo_pdf' => $handle_3['response']['payload']]);
                    $save &= $handle_3['response']['success'];
                }
            }

            $this->control_data($culturalEnsemble, 'store');
            DB::update(DB::RAW("UPDATE cultural_ensembles SET consecutive = CONCAT('EC', id) WHERE id=$culturalEnsemble->id"));
            return $culturalEnsemble;
        } catch (\Exception $ex) {
            return 'Algo salio mal al guardar emsable cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode();
        }
    }

    public function findById($id)
    {
        $culturalEnsemble = CulturalEnsemble::findOrFail($id);
        $evaluate_aspects = explode(',', $culturalEnsemble->evaluate_aspects);
        if (count($evaluate_aspects) > 0) {
            $culturalEnsemble->evaluate_aspects = $evaluate_aspects;
        }
        //$result = new CulturalEnsembleCollection($culturalEnsemble);
        return $culturalEnsemble;
    }

    public function update(Request $request, $data, $id)
    {
        try {

            $culturalEnsemble = CulturalEnsemble::findOrFail($id);
            $culturalEnsemble->fill($data);

            if (isset($culturalEnsemble->last_status)) {
                $culturalEnsemble->status = $culturalEnsemble->last_status;
            } else {
                $culturalEnsemble->status = 'ENREV';
            }

            if ($request->hasFile('development_activity_image')) {
                $handle_1 = $this->update_file($request, 'development_activity_image', 'culturalcirculations', $culturalEnsemble->id, $culturalEnsemble->development_activity_image);
                $culturalEnsemble->update(['development_activity_image' => $handle_1['response']['payload']]);
            }

            if ($request->hasFile('development_activity_image')) {
                $handle_2 = $this->update_file($request, 'evidence_participation_image', 'culturalcirculations', $culturalEnsemble->id, $culturalEnsemble->evidence_participation_image);
                $culturalEnsemble->update(['evidence_participation_image' => $handle_2['response']['payload']]);
            }

            if ($request->hasFile('aforo_pdf')) {
                $handle_3 = $this->update_file($request, 'aforo_pdf', 'culturalcirculations', $culturalEnsemble->id, $culturalEnsemble->aforo_pdf);
                $culturalEnsemble->update(['aforo_pdf' => $handle_3['response']['payload']]);
            }

            $culturalEnsemble->save();

            $this->control_data($culturalEnsemble, 'update');

            return $culturalEnsemble;
        } catch (\Exception $ex) {
            return 'Algo salio mal al guardar emsable cultural ' . $ex->getMessage() . ' linea ' . $ex->getCode();
        }
    }

    public function delete($id)
    {
        $culturalEnsemble = CulturalEnsemble::findOrFail($id);
        $culturalEnsemble->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }

    function getValidate($data, $method)
    {
        $validate = [
            'description' => 'max:3500',
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
