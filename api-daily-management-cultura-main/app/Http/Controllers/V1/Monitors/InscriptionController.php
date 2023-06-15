<?php

namespace App\Http\Controllers\V1\Monitors;

use App\Repositories\InscriptionRepository;
use App\Http\Resources\V1\GroupCollection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Traits\UserDataTrait;
use Illuminate\Http\Request;
use App\Models\Group;

class InscriptionController extends Controller
{

    use UserDataTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $inscriptionRepository;

    function __construct(InscriptionRepository $inscriptionRepository)
    {
        $this->inscriptionRepository = $inscriptionRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->inscriptionRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listado de Inscripción' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // throw new \Exception('Algo salio mal en Inscripciónel beneficiario no se creo correctamente');
        Gate::authorize('haveaccess');
        $type = $request->query('type'); // uncharacterized | characterized | uncharacterizedWithAccudent

        $data = $request->all();
        $data['created_by'] = Auth::id();
        // $beneficiary = json_decode($request->beneficiary);
        // $beneficiary_sociodemographic_characterization =json_decode($request->beneficiary_sociodemographic_characterization);
        // $beneficiary_health_data  = json_decode($request->beneficiary_health_data);
        // $attendant = json_decode($request->attendant);
        // $attendant_sociodemographic_characterization  = json_decode($request->attendant_sociodemographic_characterization);
        // $attendant_health_data = json_decode($request->attendant_health_data);
        // return response()->json([
        //     'beneficiary'=>$beneficiary,
        //     'beneficiary_sociodemographic_characterization'=>$beneficiary_sociodemographic_characterization,
        //     'beneficiary_health_data'=>$beneficiary_health_data,
        //     'attendant'=>$attendant,
        //     'attendant_sociodemographic_characterization' => $attendant_sociodemographic_characterization,
        //     'attendant_health_data'=>$attendant_health_data
        // ],500);

        DB::beginTransaction();
        try {
            $validator = $this->inscriptionRepository->validator($data, $type);

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            DB::commit();
            return $this->inscriptionRepository->create($request, $type);

        } catch (\Exception $ex) {
            DB::rollBack();
            return  $this->createErrorResponse([], 'Algo salio mal al guardado de Inscripción' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inscription  $inscription
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            Gate::authorize('haveaccess');
            $result = $this->inscriptionRepository->findById($request->inscription);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró inscripción', 404);
            }
            return $this->createResponse($result, 'La inscripción fue encontrada');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal en el ver Inscripción' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inscription  $inscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Gate::authorize('haveaccess');
        $type = $request->query('type'); // uncharacterized | characterized | uncharacterizedWithAccudent
        $data =  $request->all();
        $data['created_by'] = Auth::id();

        DB::beginTransaction();
        try {
            // return response()->json(['data' => $request->all()], 500);
            $validator = $this->inscriptionRepository->validatorUpdate($data, $type);

            if ($validator->fails()) {
                return $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            DB::commit();
            return $this->inscriptionRepository->update($request, $type);
        } catch (\Exception $ex) {
            DB::rollBack();
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar la Inscripción' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inscription  $inscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Gate::authorize('haveaccess');
        $inscription = $this->inscriptionRepository->delete($request->inscription);
        return response()->json(['message' => 'La Inscripción de id ' . $request->inscription . ' fue eliminada con éxito.']);
    }

    public function getGroupBeneficiaries($id, $user_id)
    {
        try {

            if ($id == '' || $id == null || $id == 'undefined') {
                return  $this->createErrorResponse([], 'Se requiere enviar el id del grupo.');
            }
            $rol_id = $this->getIdRolUserAuth();
            $userId = $this->getIdUserAuth();
            $query = Group::query();

            $beneficiaries = $query->find($id);

            $groupBeneficiaries = [];
            if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root')) {
                $groupBeneficiaries =    $beneficiaries->whereHas('beneficiaries', function ($beneficiary) use ($id) {
                    $beneficiary->where('group_id', $id);
                })->get();
            }

            if ($rol_id == config('roles.monitor') || $rol_id == config('roles.instructor')) {
                $groupBeneficiaries =    $beneficiaries->whereHas('beneficiaries', function ($beneficiary) use ($userId, $id) {
                    $beneficiary->where('beneficiaries.created_by', $userId)->where('group_id', $id);
                })->get();
            }
            if ($user_id != 'without_beneficiaries') {
                $groupBeneficiaries =    $beneficiaries->whereHas('beneficiaries', function ($beneficiary) use ($user_id, $id) {
                    $beneficiary->where('beneficiaries.created_by', $user_id)->where('group_id', $id);
                })->get();
            }
            return response()->json(new GroupCollection($groupBeneficiaries));
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar beneficiario por grupo ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

}
