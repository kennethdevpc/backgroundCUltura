<?php

namespace App\Http\Controllers\V1;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    use PasswordValidationRules;
    private $profileRepository;
    function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $results = $this->profileRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar los perfiles ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
        try {
            // return response()->json($request->all(),500);
            $result = $this->profileRepository->create($request->all());

            return $this->createResponse($result, 'Perfil creado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar el perfil ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $result = $this->profileRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró el perfil', 202);
            }
            return $this->createResponse($result, 'Perfil encontrado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver el perfil ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'document_number' => $this->profileRepository->uniqueDocumentNumber($request->document_number, $id),
                'email' => $this->profileRepository->uniquEmail($request->email, $id),
                //'password' => $this->passwordRules(),
            ],
            [
                'required' => ':attribute es obligatorio.',
                'unique' => 'Ya existe un usuario con este :attribute.',
            ],
            [
                'document_number' => 'Numero de documento',
                'email' => 'Correo',
            ]);
            $data = $request->all();

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->profileRepository->update($data, $id);

            return $this->createResponse($result, 'Perfil editado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar el perfil ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $result = $this->profileRepository->delete($id);

            return $this->createResponse($result, 'Usuario eliminado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar el perfil ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function findByGestorId($gestorId)
    {
        try {
            $results = $this->profileRepository->findByGestorId($gestorId);
            return $this->createResponse($results, 'Se encontraron resultados');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al buscar el perfil por gestor ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
}
