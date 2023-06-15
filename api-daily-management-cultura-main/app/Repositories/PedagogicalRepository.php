<?php

namespace App\Repositories;

use App\Http\Resources\V1\PedagogicalCollection;
use App\Http\Resources\V1\PedagogicalResource;
use App\Models\Pedagogical;
use App\Traits\UserDataTrait;
use App\Traits\FunctionGeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class PedagogicalRepository
{
    use UserDataTrait, FunctionGeneralTrait;
    private $model;
    function __construct()
    {
        $this->model = new Pedagogical();
    }
    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();

        $paginate = config('global.paginate');

        $query = $this->model->query()->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
        ->orderBy('id', 'DESC');

        if ($rol_id == config('roles.gestor')) {
            $query->where('user_review_manager_cultural_id', $user_id);
        }
        if ($rol_id == config('roles.lider_instructor')) {
            $pedagogicals =$query->where('user_review_instructor_leader_id', $user_id);
        }

        if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root')) {
            $pedagogicals =$query;
        }
        if($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')){
            $query->whereHas('user', function ($query) {
                $query->whereHas('profile',function ($profile) {
                    $profile->whereNotIn('role_id',[config('roles.super_root'),config('roles.root')]);
                });
            });
        }
        if ($rol_id == config('roles.monitor')  || $rol_id == config('roles.instructor')) {
            $query->where('created_by', $user_id)
                ->orderBy('id', 'DESC');
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_pedagogicals');
        session()->put('count_page_pedagogicals', ceil($query->get()->count()/$paginate));

        return new PedagogicalCollection($query->simplePaginate($paginate));

        // return new PedagogicalCollection($pedagogicals);
    }
    public function create($request)
    {
        $user_id = $this->getIdUserAuth();
        $pedagogical = Pedagogical::where('activity_date',$request['activity_date'])->where('created_by',$user_id)->first();
        if($pedagogical){

            return response()->json(['message'=>'La fecha de actividad debe ser unica, ya fue asociada a este usuario, por favor seleccionar otra fecha.'],422);

        }else{
            $pedagogicals = $this->model->create($request);

            // Guardamos en DataModel
            $this->control_data($pedagogicals, 'store');
            DB::update(DB::RAW("UPDATE pedagogicals SET consecutive = CONCAT('FP', id) WHERE id=$pedagogicals->id"));
            return  new PedagogicalResource($pedagogicals);

        }

    }
    public function findById($id)
    {
        $pedagogicals = $this->model->findOrFail($id);
        $result = new PedagogicalResource($pedagogicals);
        return $result;
    }

    public function update($data, $id)
    {
        $pedagogical = $this->model->findOrFail($id);

        if ($pedagogical->status == 'REC') {
            $pedagogical->update(['status' => 'ENREV']);
        }
        //$data['activity_date'] = Carbon::createFromFormat('d/m/Y', $data['activity_date'])->format('Y/m/d');
        $pedagogical->update($data);
        if ($pedagogical->status == 'REC') {
            $rol_id = $this->getIdRolUserAuth();
            if ($rol_id == config('roles.monitor') || $rol_id == config('roles.instructor')) {
                $pedagogical->update([
                    'status' => 'ENREV'
                ]);
            }
        }
        // Guardamos en ModelData
        $this->control_data($pedagogical, 'update');

        $result = new PedagogicalRepository($pedagogical);
        return $result;
    }

    public function delete($id)
    {
        $pedagogicals = $this->model->findOrFail($id);
        $pedagogicals->delete();

        return response()->json(['items' => 'Se ha eliminado correctamente']);
    }

    public function getValidate($data, $method)
    {
        // $method != 'update' ? ['bail','required',Rule::unique('pedagogicals', 'activity_date')] :
        $validate = [
            'consecutive' => 'required',
            'cultural_right_id' => 'required',
            'nac_id' => 'required',
            'activity_date' => ['bail','required'],
            'activity_name' => 'required|string|max:190',
            'expertise_id' => 'required|integer',
            'experiential_objective' => 'required|string|max:3500',
            'lineament_id' => 'required',
            'orientation_id' => 'required',
            'manifestation' => 'required|string|max:3500',
            'process' => 'required|string|max:3500',
            'product' => 'required|string|max:3500',
            'resources' => 'required|string|max:3500',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'mimes' => ':attribute debe ser pdf,png,jpg,jpeg.',
            'max' => ':attribute es muy grande.',
            'unique' => 'Ya existe un registro con este :attribute.',
        ];

        $attrs = [
            'consecutive' => 'Consecutivo',
            'cultural_right_id' => 'Derecho cultural',
            'nac_id' => 'Nac',
            'activity_date' => 'Fecha de actividad',
            'activity_name' => 'Nombre de la actividad',
            'expertise_id' => 'Expertise',
            'experiential_objective' => 'Objetivo experimental',
            'lineament_id' => 'Lineamento',
            'orientation_id' => 'Orientacion',
            'manifestation' => 'Manifestacion',
            'process' => 'Procesos',
            'product' => 'Productos',
            'resources' => 'Recursos',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }

    public function getByRangeActivityDate($filter, $initDate, $lastDate)
    {

        /* $carbono = Carbon::parse(now());
        $day = $firtsDays < 9 ? "09" : $firtsDays;
        $month = $carbono->month;
        $year = $carbono->year;
        $firtDay = $year . "/" . $month . "/01";
        $lastDay = $year . "/" . $month . "/" . $day; */
        $rol_id = $this->getIdRolUserAuth();
        if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root') || $rol_id == config('roles.gestor') || $rol_id == config('roles.apoyo_al_seguimiento_monitoreo') || $rol_id == config('roles.lider_instructor') || $rol_id == config('roles.lider_embajador')) {
            return  $this->model
                ->select(['id', 'consecutive', 'activity_date'])
                // ->where('consecutive', 'like', '%'. $filter . '%')
                // ->whereBetween('activity_date', [$initDate, $lastDate])
                ->orderBy('activity_date')
                ->get();
        }

        return $this->model
            ->select(['id', 'consecutive', 'activity_date'])
            // ->where('consecutive', 'like', '%'. $filter . '%')
            // ->whereBetween('activity_date', [$initDate, $lastDate])
            ->where('created_by', '=', Auth::id())
            ->orderBy('activity_date')
            ->get();
        //return DB::table('pecs')->where('activity_date', '>=', $firtDay)->where('activity_date', '<=', $lastDay)->get();
    }
}
