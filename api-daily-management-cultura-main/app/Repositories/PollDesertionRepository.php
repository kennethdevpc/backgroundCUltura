<?php

namespace App\Repositories;

use App\Http\Resources\V1\PollDesertionCollection;
use App\Http\Resources\V1\PollDesertionResource;
use App\Models\PollDesertion;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\Auth;

/**
 * Class PollDesertionRepository.
 */
class PollDesertionRepository
{
    use FunctionGeneralTrait;
    /**
     * @return string
     *  Return the model
     */
    private $model;

    function __construct()
    {
        $this->model = new PollDesertion();
    }
    public function getAll()
    {
        $rol_id = Auth::user()->profile->role->id;
        $user_id = Auth::user()->id;

        $paginate = config('global.paginate');

        $query = $this->model->query();

        if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root')) {
            $query->get();
        }
        else if($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')){
            $query->whereHas('user', function ($query) {
                $query->whereHas('profile',function ($profile) {
                    $profile->whereNotIn('role_id',[config('roles.super_root'),config('roles.root')]);
                });
            })->get();
        }
        else {
            $query->where('user_id', $user_id)->get();
        }

        // Aplicar filtros adicionales desde la URL
        $query = $this->model->scopeFilterByUrl($query);

        // Calcular número de páginas para paginación
        session()->forget('count_page_pollDesertion');
        session()->put('count_page_pollDesertion', ceil($query->get()->count()/$paginate));

        return new PollDesertionCollection($query->simplePaginate($paginate));

    }

    public function create($request)
    {
        $pollDesertion =  $this->model->create($request);

        // Guardamos en DataModel
        $this->control_data($pollDesertion, 'store');

        $results = new PollDesertionResource($pollDesertion);
        return $results;
    }

    public function findById($id)
    {
        $pollDesertion =  $this->model->findOrFail($id);
        $result = new PollDesertionResource($pollDesertion);
        return $result;
    }

    public function update($data, $id)
    {
        $pollDesertion =  $this->model->findOrFail($id);
        $pollDesertion->update($data);

        // Guardamos en DataModel
        $this->control_data($pollDesertion, 'update');

        $result = new PollDesertionResource($pollDesertion);
        return $result;
    }

    public function delete($id)
    {
         $this->model->findOrFail($id)->delete();

        return response()->json(['message' => 'Se ha eliminado correctamente']);
    }

    public function getValidate($data)
    {

        $validate = [
            'user_id' => 'required|exists:users,id,deleted_at,NULL',
            'beneficiary_id' => 'required',
            'date' => 'required|date',
            'nac_id' => 'required',
            'beneficiary_attrition_factors' => 'required',
            //'beneficiary_attrition_factor_other' => 'required|string|max:250',
            'disinterest_apathy' => 'required|integer|between:0,1',
            'disinterest_apathy_explanation' => 'required|string|max:3500',
            'reintegration' => 'required|integer|between:0,1',
            'reintegration_explanation' => 'required|string|max:3500',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'mimes' => ':attribute debe ser pdf,png,jpg,jpeg.',
            'max' => ':attribute es muy grande.',
            'unique' => 'Ya existe un modulo con este :attribute.',
        ];

        $attrs = [
            'user_id' => 'User',
            'beneficiary_id' => 'Beneficiario',
            'date' => 'Fecha',
            'nac_id' => 'Nac',
            'beneficiary_attrition_factors' => 'factores de abandono de beneficiarios',
            'disinterest_apathy' => 'Desinteres apatia',
            'disinterest_apathy_explanation' => 'Desinteres apatia explicacion',
            'reintegration' => 'Reintegracion',
            'reintegration_explanation' => 'Reintegracion explicación',
        ];

        return $this->validator($data, $validate, $messages, $attrs);
    }
}
