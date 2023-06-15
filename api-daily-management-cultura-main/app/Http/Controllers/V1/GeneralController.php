<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ControlChangeDataCollection;
use App\Http\Resources\V1\GroupCollection;
use App\Http\Resources\V1\MethodologicalSheetsOneCollection;
use App\Http\Resources\V1\MethodologicalSheetsTwoCollection;
use App\Http\Resources\V1\GroupResource;
use App\Models\CulturalRight;
use App\Models\MethodologicalSheetsOne;
use App\Models\Asistant;
use App\Models\Binnacle;
use App\Models\ControlChangeData;
use App\Models\EntityName;
use App\Models\Expertise;
use App\Models\Group;
use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\Inscription;
use App\Models\Module;
use App\Models\Nac;
use App\Models\Neighborhood;
use App\Models\Orientation;
use App\Models\Inscriptions\Pec;
use App\Models\MethodologicalInstructionModel;
use App\Models\MethodologicalSheetsTwo;
use App\Models\Pedagogical;
use App\Models\Poll;
use App\Models\PollDesertion;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Http\Request;
use App\Traits\UserDataTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Undefined;

class GeneralController extends Controller
{
    use UserDataTrait, FunctionGeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataSelects()
    {
        $monitors = [];
        $managers = [];
        $rol_id = $this->getIdRolUserAuth();
        $group_beneficiaries = [];
        $beneficiaries_table = [];

        if (config('roles.gestor') == $this->getIdRolUserAuth()) {

            $monitors = User::whereHas('roles', function ($query) {
                $roles_where =  [14, 15, 16];
                $gestor_id =  $this->getIdUserAuth();
                $query->whereHas('users.profile', function ($query_role) use ($gestor_id) {
                    $query_role->where("profiles.gestor_id",  $gestor_id);
                });
                $query->whereHas('users.roles', function ($query_role) use ($roles_where) {
                    $query_role->whereIn("roles.id",  $roles_where);
                });
            })->select('users.name as label', 'users.id as value')->get();

            $managers = User::whereHas('roles', function ($query) {
                $roles_where =  13;
                $gestor_id =  $this->getIdUserAuth();
                $query->whereHas('users.profile', function ($query_role) use ($gestor_id) {
                    $query_role->where("profiles.gestor_id",  $gestor_id);
                });
                $query->whereHas('users.roles', function ($query_role) use ($roles_where) {
                    $query_role->where("roles.id",  $roles_where);
                });
            })->select('users.name as label', 'users.id as value')->get();
        } else {


            $monitors = User::whereHas('roles', function ($query) {
                $query->where('roles.id', 14);
            })->select('users.name as label', 'users.id as value')->get();


            $managers = User::whereHas('roles', function ($query) {
                $query->where('roles.id', 13);
            })->select('users.name as label', 'users.id as value')->get();
        }


        $assistants = Asistant::get();
        $beneficiaries = Beneficiary::select('id', 'id as value', 'full_name as label', 'document_number as nuip')->where('created_by', $this->getIdUserAuth())->get();


        if (config('roles.root') == $this->getIdRolUserAuth() || config('roles.super_root') == $this->getIdRolUserAuth()) {
            $beneficiaries_table = Beneficiary::select('id', 'full_name', 'document_number as nuip')->get();
        } else {
            $beneficiaries_table = Beneficiary::select('id', 'full_name', 'document_number as nuip')->where('created_by', $this->getIdUserAuth())->get();
        }

        $culturalRights = CulturalRight::select('name as label', 'id as value')->get();
        $methodologicalsheetsone = MethodologicalSheetsOne::select('datasheet as label', 'id as value')->get();
        $data = config('selectsDefault');
        $entityNames = EntityName::select('name as label', 'id as value')->get();
        $expertises = Expertise::select('name as label', 'id as value')->orderBy('name', 'ASC')->get();
        $modules = Module::select('name as label', 'id as value')->get();
        $nacs = Nac::select('name as label', 'id as value')->get();
        $neighborhoods = Neighborhood::select('name as label', 'id as value')->get();
        $orientations = Orientation::select('name as label', 'id as value')->get();
        $pecs = Pec::query()->select('consecutive as label', 'id as value')->get();
        $pedagogicals = Pedagogical::query()->select('consecutive as label', 'id as value')->get();
        $roles = Role::select('name as label', 'slug as value')->get();
        $roles_display = Role::select('name as label', 'id as value')->get();
        $methodologicalSheetsOne = MethodologicalSheetsOne::select('semillero_name as label', 'id as value')->get();
        $users = User::select('name as label', 'id as value')->get();
        $users_table = User::query()->with(['profile' => function ($query) {
            $query->select('user_id', 'document_number', 'contractor_full_name');
        }])->select('id', 'name')->get();

        if (config('roles.gestor') == $this->getIdRolUserAuth()) {
            $monitors_table = User::query()->whereHas('roles', function ($query) {
                $query->whereIn('slug', ['monitor_cultural']);
            })->with(['profile' => function ($query) {
                $query->select('user_id', 'document_number', 'contractor_full_name', 'nac_id', 'role_id');
            }])->get();
        } else {

            $monitors_table = User::query()->whereHas('roles', function ($query) {
                $query->whereIn('slug', ['monitor_cultural', 'embajador', 'instructor']);
            })->with(['profile' => function ($query) {
                $query->select('user_id', 'document_number', 'contractor_full_name', 'nac_id', 'role_id');
            }])->get();
        }

        $monitors_parentschools = User::query()->whereHas('roles', function ($query) {
            $query->whereIn('slug', ['monitor_cultural', 'embajador', 'instructor']);
        })->with(['profile' => function ($query) {
            $query->select('user_id', 'document_number', 'contractor_full_name');
        }])->get();


        // $user_id = $this->getIdUserAuth();
        if ($rol_id == config('roles.root') || $rol_id == config('roles.gestor') || $rol_id == config('roles.super_root')
            || $rol_id == config('roles.apoyo_al_seguimiento_monitoreo') || $rol_id == config('roles.lider_instructor')) {
            $group_beneficiaries = Group::whereHas('beneficiaries')->select('groups.id as value', 'groups.name as label')->get();
        } else {
            $group_beneficiaries = Group::whereHas('beneficiaries', function ($query) {
                $query->where('created_by',  $this->getIdUserAuth());
            })->select('groups.id as value', 'groups.name as label')->get();
        }

        // Trae solo roles instructores y monitores
        $rolesMoniInstru = Role::select('name as label', 'slug as value')
            ->where('id', [config('roles.monitor'), config('roles.instructor')])->get();
        // // instructores,envajadores,monitores
        $data['assistants'] = $assistants;
        $data['beneficiaries_table'] = $beneficiaries_table;
        $data['beneficiaries'] = $beneficiaries;
        $data['cultural_rights'] = $culturalRights;
        $data['methodologicalsheetsone'] = $methodologicalsheetsone;

        $data['entity_names'] = $entityNames;
        $data['expertises'] = $expertises;
        $data['managers'] = $managers;
        $data['modules'] = $modules;
        $data['monitors_parentschools'] = $monitors_parentschools ?? [];
        $data['monitors_table'] = $monitors_table ?? [];
        $data['monitors'] = $monitors ?? [];
        $data['nacs'] = $nacs;
        $data['neighborhoods'] = $neighborhoods;
        $data['orientations'] = $orientations;
        $data['pecs'] = $pecs;
        $data['pedagogicals'] = $pedagogicals;
        $data['roles'] = $roles;
        $data['roles_display'] = $roles_display;
        $data['users_table'] = $users_table;
        $data['group_beneficiaries'] = $group_beneficiaries;
        $data['methodologicalSheetsOne'] = $methodologicalSheetsOne;
        $data['users'] = $users;
        $data['rolesMoniInstru'] = $rolesMoniInstru;
        return response()->json(
            $data
        );
    }

    /*****************************************************
     * SE COMIENZA A TRABAJAR LOS SELECT INDEPENDIENTES *
    *****************************************************/


    public function getSelectPlaceTypes(Request $request){
        $data = $this->dataSelect('place_types');
        return response()->json($data);
    }

    public function getSelectBinnacles(Request $request){
        $data = $this->dataSelect('binnacles');
        return response()->json($data);
    }

    public function getSelectFilterLevel(Request $request){
        $data = $this->dataSelect('filter_level');
        return response()->json($data);
    }

    public function getSelectValues(Request $request){
        $data = $this->dataSelect('values');
        return response()->json($data);
    }

    public function getSelectLineaments(Request $request){
        $data = $this->dataSelect('lineaments');
        return response()->json($data);
    }

    public function getSelectLinkageProjects(Request $request){
        $data = $this->dataSelect('linkage_projects');
        return response()->json($data);
    }

    public function getSelectDisabilityTypes(Request $request){
        $data = $this->dataSelect('disability_types');
        return response()->json($data);
    }

    public function getSelecteducationallevels(Request $request){
        $data = $this->dataSelect('educational_levels');
        return response()->json($data);
    }

    public function getSelectethnicities(Request $request){
        $data = $this->dataSelect('ethnicities');
        return response()->json($data);
    }

    public function getSelectgenders(Request $request){
        $data = $this->dataSelect('genders');
        return response()->json($data);
    }

    public function getSelectHealthConditions(Request $request){
        $data = $this->dataSelect('health_conditions');
        return response()->json($data);
    }

    public function getSelectConditions(Request $request){
        $data = $this->dataSelect('conditions');
        return response()->json($data);
    }

    public function getSelectMedicalServices(Request $request){
        $data = $this->dataSelect('medical_services');
        return response()->json($data);
    }

    public function getSelectParticipantTypes(Request $request){
        $data = $this->dataSelect('participant_types');
        return response()->json($data);
    }

    public function getSelectDecisions(Request $request){
        $data = $this->dataSelect('decisions');
        return response()->json($data);
    }

    public function getSelectStratums(Request $request){
        $data = $this->dataSelect('stratums');
        return response()->json($data);
    }

    public function getSelectTypeDocuments(Request $request){
        $data = $this->dataSelect('type_documents');
        return response()->json($data);
    }

    public function getSelectZones(Request $request){
        $data = $this->dataSelect('zones');
        return response()->json($data);
    }

    public function getSelectActivationMode(Request $request){
        $data = $this->dataSelect('activation_mode');
        return response()->json($data);
    }

    public function getSelectDiseaseTypes(Request $request){
        $data = $this->dataSelect('disease_types');
        return response()->json($data);
    }

    public function getSelectMaritalStatus(Request $request){
        $data = $this->dataSelect('marital_status');
        return response()->json($data);
    }

    public function getSelectRelationshipHouseholds(Request $request){
        $data = $this->dataSelect('relationship_households');
        return response()->json($data);
    }

    public function getSelectSingleRegistryVictims(Request $request){
        $data = $this->dataSelect('single_registry_victims');
        return response()->json($data);
    }

    public function getSelectTypeDiseases(Request $request){
        $data = $this->dataSelect('type_diseases');
        return response()->json($data);
    }

    public function getSelectBeneficiaryAttritionFactors(Request $request){
        $data = $this->dataSelect('beneficiary_attrition_factors');
        return response()->json($data);
    }

    public function getSelectRelationships(Request $request){
        $data = $this->dataSelect('relationships');
        return response()->json($data);
    }

    public function getSelectStrengthening_types(Request $request){
        $data = $this->dataSelect('strengthening_types');
        return response()->json($data);
    }

    public function getSelectAspects(Request $request){
        $data = $this->dataSelect('aspects');
        return response()->json($data);
    }

    public function getSelectEvaluate_aspects(Request $request){
        $data = $this->dataSelect('evaluate_aspects');
        return response()->json($data);
    }

    public function getSelectGroupBeneficiaries(Request $request){
        $data = [];
        if ($this->getIdRolUserAuth() == config('roles.root') || $this->getIdRolUserAuth() == config('roles.gestor') || $this->getIdRolUserAuth() == config('roles.super_root')
            || $this->getIdRolUserAuth() == config('roles.apoyo_al_seguimiento_monitoreo') || $this->getIdRolUserAuth() == config('roles.lider_instructor')) {
            $data = Group::whereHas('beneficiaries')->select('groups.id as value', 'groups.name as label')->get();
        } else {
            $data = Group::whereHas('beneficiaries', function ($query) {
                $query->where('created_by',  $this->getIdUserAuth());
            })->select('groups.id as value', 'groups.name as label')->get();
        }
        return response()->json($data);
    }

    public function getSelectBeneficiariesTable(Request $request){
        $data = [];
        if (config('roles.root') == $this->getIdRolUserAuth() || config('roles.super_root') == $this->getIdRolUserAuth()) {
            $data = Beneficiary::select('id', 'full_name', 'document_number as nuip')->get();
        } else {
            $data = Beneficiary::select('id', 'full_name', 'document_number as nuip')->where('created_by', $this->getIdUserAuth())->get();
        }
        return response()->json($data);
    }

    public function getSelectStatus() {
        $data = $this->dataSelect('status');
        return response()->json($data);
    }

    /*****************************************************
     *              FIN SELECT INDEPENDIENTES           *
    *****************************************************/

    public function selectFromTable(Request $request)
    {
        $table = $request->table;

        $query = DB::table($table)->select(['id as value', 'name as label'])->get();

        return response()->json(
            $query
        );
    }

    public function selectFromDefaults(Request $request)
    {
        //$table = $request->table;

        $select = $request->select;

        $data = config('selectsDefault');
        $query = $data[$select];

        return response()->json(
            $query
        );
    }

    public function usersInUserEdit(Request $request)
    {
        return User::with(['roles' => function ($query) {
            $query->select('user_id', 'slug');
        }])->get(['id', 'name']);
    }

    public function getUsersByNac(Request $request)
    {
        return User::query()->with(['profile'])->whereRelation('profile', 'nac_id', '=', $request->nac)->whereNotIn('id', [1, 2])->get();
    }

    public function getRolesByNac(Request $request)
    {
        $nac_id = $request->nac;

        return Role::query()->whereHas('profile', function ($query) use ($nac_id) {
            $query->where('nac_id', $nac_id);
        })->whereNotIn('roles.id', [1, 2])->select('roles.id as value', 'roles.name as label')->without('permissions')->get();
    }

    public function getRoles(Request $request)
    {
        return Role::query()->whereNotIn('roles.id', [1, 2])->select('roles.id as value', 'roles.name as label')->without('permissions')->get();
    }

    // public function usuarios($id)
    // {
    //     if ($id == 0) return null;
    //     $rol = Role::find($id);
    //     $profile = Profile::where('role_id', $rol->id)->get();

    //     $usuariosId = [];
    //     foreach ($profile as $key => $value) {
    //         array_push($usuariosId, $value->user_id);
    //     }

    //     $usuarios = [];
    //     foreach ($usuariosId as $key => $user_id) {
    //         $users_query = Profile::where('user_id', $user_id)->select(['user_id as value', 'contractor_full_name as label'])->orderBy('id', 'DESC')->get();

    //         foreach ($users_query as $user_key => $user) {
    //             array_push($usuarios, $user);
    //         }
    //     }

    //     return $usuarios;
    // }

    public function getData(Request $request)
    {
        $nac_id = 2;
        $roles = ['monitor_cultural', 'gestores_culturales'];

        $user_assistants = Profile::query()->whereHas('role', function ($query) use ($roles, $nac_id) {
            $query->whereIn('slug', $roles);
        })->where('nac_id', $nac_id)->select('user_id', 'document_number', 'contractor_full_name')
            ->get();


        $roles = Role::query()->whereHas('profile', function ($query) use ($nac_id) {
            $query->where('nac_id', $nac_id);
        })->select('roles.id', 'roles.name')->without('permissions')->get();

        return response()->json(
            [
                'user_assistants' => $user_assistants,
                'roles' => $roles
            ]

        );
    }
    public function getConsecutive(Request $request)
    {
        $count = DB::select("SELECT id as consecutive FROM $request->table ORDER BY ID DESC LIMIT 1");

        return response()->json(count($count) == 0 ? $request->abreviature . '1' : $request->abreviature . $count[0]->consecutive + 1, 200);
    }
    public function getChangeDataModels(Request $request)
    {
        $model = new ControlChangeData();
        try {
            $query = $model->query();

            $paginate = config('global.paginate');

            $query = $model->scopeFilterByUrl($query);

            session()->forget('count_page_data_model');
            session()->put('count_page_data_model', ceil($query->count() / $paginate));

            return new ControlChangeDataCollection($query->simplePaginate($paginate));

            /* $response = ControlChangeData::with('user')->simplePaginate(config('global.paginate'));

            return response()->json([
                'items' =>  $query,
                'count_page' => ceil(ControlChangeData::count() / config('global.paginate'))
            ]); */
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al listar la data' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function destroyChangeDataModel(Request $request)
    {
        try {
            $results =  ControlChangeData::find($request->id)->delete();
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al eliminar el data' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
        return $results;
    }

    public function getDataForm()
    {
        try {
            $user_id = $this->getIdUserAuth();
            $info = [
                'monitor' => [
                    'pecs' => Pec::where('created_by', $user_id)->count(),
                    'inscriptions' => Inscription::where('created_by', $user_id)->count(),
                    'pedagogicals' => Pedagogical::where('created_by', $user_id)->count(),
                    'binnacles' => Binnacle::where('created_by', $user_id)->count(),
                    'pollDesertions' => PollDesertion::where('user_id', $user_id)->count(),
                    'methodologicalSheetsOne' => MethodologicalSheetsOne::where('created_by', $user_id)->count(),
                ],
                'polls' => Poll::where('user_id', $user_id)->count()
                // 'manager' => [

                // ],
                // 'psychosocial' => [

                // ]

            ];

            return response()->json(['items' => $info]);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al mostar cantidad de datos de los formularios' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function getPollOnly()
    {
        try {
            $user_id = $this->getIdUserAuth();
            $info = [
                'polls' => Poll::where('user_id', $user_id)->count()
            ];

            return response()->json(['items' => $info]);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al mostrar el numero de Encuestas del usuario.' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function getAllDataCreatedBy(Request $request, $table_name)
    {
        try {
            $data = [];
            $rol_id = $this->getIdRolUserAuth();

            $paginate = config('global.paginate');

            if ($table_name == 'methodological_sheets_one') {


                $model = new MethodologicalSheetsOne();

                $queryMethodologicalSheetsOne = $model->query()->orderByRaw("FIELD(status,'REC','REV','ENREV','APRO') ASC");

                if ($rol_id == config('roles.direccion') ||  $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
                    $queryMethodologicalSheetsOne->whereHas('createdBy', function ($query) {
                        $query->whereHas('profile', function ($profile) {
                            $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                        });
                    });
                } else if ($rol_id == config('roles.root') ||  $rol_id == config('roles.super_root')) {
                    $queryMethodologicalSheetsOne;
                } else {
                    $queryMethodologicalSheetsOne->where("created_by", '=', $this->getIdUserAuth());
                }

                // Aplicar filtros adicionales desde la URL
                $queryMethodologicalSheetsOne = $model->scopeFilterByUrl($queryMethodologicalSheetsOne);

                // Calcular número de páginas para paginación
                session()->forget('count_page_methodologicalSheetsOne');
                session()->put('count_page_methodologicalSheetsOne', ceil($queryMethodologicalSheetsOne->get()->count()/$paginate));

                $data = new MethodologicalSheetsOneCollection($queryMethodologicalSheetsOne->simplePaginate($paginate));

            } else if ($table_name == 'methodological_sheets_two') {

                $model = new MethodologicalSheetsTwo();

                $queryMethodologicalSheetsTwo = $model->query()->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
                    ->orderBy('id', 'asc');

                if ($rol_id == config('roles.direccion') ||  $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
                    $queryMethodologicalSheetsTwo->whereHas('createdBy', function ($query) {
                        $query->whereHas('profile', function ($profile) {
                            $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                        });
                    });
                } else if ($rol_id == config('roles.root') ||  $rol_id == config('roles.super_root')) {
                    $queryMethodologicalSheetsTwo;
                } else {
                    $queryMethodologicalSheetsTwo->where("created_by", '=', $this->getIdUserAuth());
                }

                // Aplicar filtros adicionales desde la URL
                $queryMethodologicalSheetsTwo = $model->scopeFilterByUrl($queryMethodologicalSheetsTwo);

                // Calcular número de páginas para paginación
                session()->forget('count_page_methodologicalSheetsTwo');
                session()->put('count_page_methodologicalSheetsTwo', ceil($queryMethodologicalSheetsTwo->get()->count()/$paginate));

                $data = new MethodologicalSheetsTwoCollection($queryMethodologicalSheetsTwo->simplePaginate($paginate));

            } else {
                throw 'No se ha encontrado la tabla';
            }
            return $data->toArray($request);
            // return $this->createResponse($data, 'Colleccion encontrada de ' . $table_name);
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }

    public function getDataSheet(Request $request, $table, $date)
    {
        $carbon = Carbon::create($date);
        $dateIni = $carbon->firstOfMonth()->toDateString();
        $dateFin = $carbon->endOfMonth()->toDateString();
        $user_id = $this->getIdUserAuth();
        try {
            $count = DB::table($table)->whereBetween($request->column ? 'date' : 'date_ini', [$dateIni, $dateFin])->where('created_by', $user_id)->get()->count();
        } catch (\Throwable $th) {
            $count = DB::table($table)->whereBetween('date_realization', [$dateIni, $dateFin])->where('created_by', $user_id)->get()->count();
        }
        return  $count + 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function profileRoleUserNac(Request $request)
    {
        try {
            // return $this->createResponse($request->all(), 'Roles y asistentes');

            $result = $this->getRolUserForNac($request->id);
            return $this->createResponse($result, 'Roles y asistentes');
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al traer roles y asistentes' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRolesUsers(Request $request)
    {
        try {
            // return $this->createResponse($request->all(), 'Roles y asistentes');
            $result = $this->getUsers($request->roles);
            return $result;
        } catch (\Exception $ex) {
            return  $this->createErrorResponse([], 'Algo salio mal al traer usuario por roles' . $ex->getMessage() . ' linea ' . $ex->getCode());
        }
    }
}
