<?php

namespace App\Http\Controllers\V1;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    use PasswordValidationRules;
    private $userRepository;
    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('haveaccess');
        try {
            $results = $this->userRepository->getAll();
            return $results->toArray($request);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar usuarios ' . $ex->getMessage() . ' linea ' . $ex->getCode());
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
        Gate::authorize('haveaccess');
        try {
            $validator = Validator::make($request->user, [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ],
                //'password' => $this->passwordRules(),
            ]);

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            /* VALIDACION DEL DOCUMENTO REPETIDO DEL USUARIO */

            $validatorProfile = Validator::make($request->profile, [
                'document_number' => [
                    'required',
                    'numeric',
                    Rule::unique(Profile::class),
                ],
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

            if ($validatorProfile->fails()) {
                return  $this->createErrorResponse([], $validatorProfile->errors()->first(), 422);
            }

            $result = $this->userRepository->create($request->user,$request->profile);
            return $result;
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al guardar usuario ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->userRepository->findById($id);
            if (empty($result)) {
                return $this->createResponse($result, 'No se encontró usuario', 202);
            }
            return $this->createResponse($result, 'Usuario encontrado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al ver usuario ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        Gate::authorize('haveaccess');
        $editEmail = $request->get('editEmail');
        try {
            $validator = Validator::make($request->all(), [
                // 'document_number' => $this->userRepository->uniqueDocumentNumber($request->profile['document_number'],$id),
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ]
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
            unset($data['editEmail']);
            unset($data['password_confirm']);

            if ($validator->fails()) {
                return  $this->createErrorResponse([], $validator->errors()->first(), 422);
            }

            $result = $this->userRepository->update($request->user,$request->profile, $id);

            return $this->createResponse($result, 'Usuario editado');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al actualizar usuario ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('haveaccess');
        try {
            $result = $this->userRepository->delete($id);

            return $result;
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar usuario ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }


    public function noPaginate()
    {
        try {
            $results = $this->userRepository->noPaginate();
            return response()
                ->json($results)
                ->header('Access-Control-Allow-Origin', env('ALLOWED_ORIGIN'))
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');;
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al no paginar usuario ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function changePassword(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed|min:8'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            $results = $this->userRepository->changePassword($request);
            return $this->createResponse($results, 'Se ha realizado el cambio de contraseña de manera correcta');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al cambiar la contraseña del usuario ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
    public function changeStatus(Request $request)
    {
        try {

            $results = $this->userRepository->changeStatus($request);
            return $this->createResponse($results, 'Se ha realizado el cambio de estado de manera correcta');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al cambiar el estado del usuario ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
    public function getUserForRole($slug)
    {
        // Gate::authorize('haveaccess');
        try {
            $results = $this->userRepository->getUserRole($slug);
            return $this->createResponse($results, 'Usuarios encontrados');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar usuarios ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
}
