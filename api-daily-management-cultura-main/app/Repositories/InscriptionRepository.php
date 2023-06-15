<?php

namespace App\Repositories;

use App\Http\Resources\V1\InscriptionsCollection;
use App\Http\Resources\V1\InscriptionsResource;
use App\Models\GroupBeneficiary;
use App\Models\Inscriptions\Attendant;
use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\HealthData;
use App\Models\Inscriptions\Inscription;
use App\Models\Inscriptions\SociodemographicCharacterization;
use App\Models\Neighborhood;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Traits\ImageTrait;
use App\Traits\UserDataTrait;
use Illuminate\Support\Facades\DB;
use App\Traits\FunctionGeneralTrait;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class InscriptionRepository
{
    use ImageTrait, UserDataTrait, FunctionGeneralTrait;
    private $model;
    private $attendant;
    private $beneficiary;
    private $sociodemographic;
    private $health_data;
    private $sociodemographic_attendant;
    private $health_data_attendant;
    private $neighborhood;
    private $groupBeneficiary;

    function __construct()
    {
        $this->model = new Inscription();
        $this->attendant = new Attendant();
        $this->beneficiary = new Beneficiary();
        $this->health_data = new HealthData();
        $this->sociodemographic = new SociodemographicCharacterization();
        $this->neighborhood = new Neighborhood();
        $this->groupBeneficiary = new GroupBeneficiary();
    }

    private function getValidSearchFields($searchValue)
    {
        return [
            'benefiary.full_name' => function ($query) use ($searchValue) {
                $query->whereHas('benefiary', function ($query) use ($searchValue) {
                    $query->where('full_name', 'like', '%' . $searchValue . '%');
                });
            },
            'benefiary.document_number' => function ($query) use ($searchValue) {
                $query->whereHas('benefiary', function ($query) use ($searchValue) {
                    $query->where('document_number', 'like', '%' . $searchValue . '%');
                });
            },
            'user.name' => function ($query) use ($searchValue) {
                $query->whereHas('user', function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                });
            },
            'role' => function ($query) use ($searchValue) {
                $query->whereHas('user.profile.role', function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                });
            },
            'attendant.document_number' => function ($query) use ($searchValue) {
                $query->whereHas('benefiary.attendant', function ($query) use ($searchValue) {
                    $query->where('document_number', 'like', '%' . $searchValue . '%');
                });
            },
            'attendant.full_name' => function ($query) use ($searchValue) {
                $query->whereHas('benefiary.attendant', function ($query) use ($searchValue) {
                    $query->where('full_name', 'like', '%' . $searchValue . '%');
                });
            },
        ];
    }

    public function getAll()
    {
        $rol_id = $this->getIdRolUserAuth();
        $user_id = $this->getIdUserAuth();
        $paginate = request('limit', config('global.paginate'));
        $query = $this->model->query()->orderByRaw("FIELD(status,'REC','ENREV','REV','APRO') ASC")
            ->orderBy('id', 'DESC');

        $inscriptions = [];
        if ($rol_id == config('roles.monitor') || $rol_id == config('roles.instructor')) {

            $inscriptions = $query->where('created_by', $user_id);
        }
        if ($rol_id == config('roles.apoyo_al_seguimiento_monitoreo')) {
            $inscriptions = $query = $this->model->query()->orderByRaw("FIELD(status,'ENREV','REV','REC','APRO') ASC")
                ->orderBy('id', 'DESC');

            $inscriptions->where('user_review_support_follow_id', $user_id)->get();
        }

        if ($rol_id == config('roles.root') || $rol_id == config('roles.super_root')) {
            $inscriptions = $query;
        }


        if ($rol_id == config('roles.direccion') || $rol_id == config('roles.secretaria_cultural') || $rol_id == config('roles.coordinador_supervision') || $rol_id == config('roles.apoyo_supervision')) {
            $inscriptions = $query->whereHas('user', function ($query) {
                $query->whereHas('profile', function ($profile) {
                    $profile->whereNotIn('role_id', [config('roles.super_root'), config('roles.root')]);
                });
            });
        }

        $inscriptions = $this->model->scopeFilterByUrl($query);

        session()->forget('count_page_inscriptions');
        session()->put('count_page_inscriptions', ceil($inscriptions->count() / $paginate));

        return new InscriptionsCollection($inscriptions->simplePaginate($paginate));
    }


    public function createBeneficiary($request)
    {

        DB::beginTransaction();
        try {

            $user_id = $this->getIdUserAuth();

            $beneficiary = json_decode($request->beneficiary);
            // preg_replace('/[^A-Za-z0-9\ ]/', '', $value->activity_development ?? '')
            $this->beneficiary->accept = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->accept);
            $this->beneficiary->document_number = $beneficiary->document_number;
            $this->beneficiary->email = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->email);
            $this->beneficiary->full_name = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->full_name);

            if ($beneficiary->institution_entity_referred != "" || $beneficiary->institution_entity_referred != null) {
                $this->beneficiary->institution_entity_referred = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->institution_entity_referred);
            } else {
                $this->beneficiary->institution_entity_referred = NULL;
            }

            $this->beneficiary->linkage_project = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->linkage_project);
            $this->beneficiary->nac_id = $beneficiary->nac_id;
            $this->beneficiary->neighborhood_id = $beneficiary->neighborhood_id;

            if ($beneficiary->neighborhood_new != "" || $beneficiary->neighborhood_new != null) {
                $this->beneficiary->neighborhood_new = $beneficiary->neighborhood_new;
            } else {
                $this->beneficiary->neighborhood_new = NULL;
            }

            $this->beneficiary->participant_type = $beneficiary->participant_type;
            $this->beneficiary->phone = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->phone);

            if ($beneficiary->referrer_name != "" || $beneficiary->referrer_name != null) {
                $this->beneficiary->referrer_name = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->referrer_name);
            } else {
                $this->beneficiary->referrer_name = NULL;
            }

            $this->beneficiary->stratum = $beneficiary->stratum;
            $this->beneficiary->type_document = $beneficiary->type_document;

            if ($beneficiary->user_id != "" || $beneficiary->user_id != null) {
                $this->beneficiary->user_id = $beneficiary->user_id;
            } else {
                $this->beneficiary->user_id = NULL;
            }

            $this->beneficiary->zone = $beneficiary->zone;
            if ($beneficiary->group_id) {
                $this->beneficiary->group_id = $beneficiary->group_id;
            }
            $this->beneficiary->created_by = $user_id;
            $saved = $this->beneficiary->save();


            if ($beneficiary->neighborhood_new != "" || $beneficiary->neighborhood_new != null) {
                $neighborhood = $this->neighborhood->where('name', 'LIKE', "%{$beneficiary->neighborhood_new}%")->first();
                if (!$neighborhood && $saved) {
                    $this->neighborhood->name = $beneficiary->neighborhood_new;
                    $this->neighborhood->user_id = $user_id;
                    $this->neighborhood->save();
                    // Guardamos en DataModel
                    $this->control_data($this->neighborhood, 'store');
                }
            }

            if (!is_null($request->beneficiary_sociodemographic_characterization)) {
                $beneficiary_sociodemographic_characterization = json_decode($request->beneficiary_sociodemographic_characterization);

                $disability_type = $beneficiary_sociodemographic_characterization->disability_type;
                if ($saved) {
                    $socio_demo = $this->beneficiary->socio_demo()->create(
                        [
                            'gender' => $beneficiary_sociodemographic_characterization->gender,
                            'age' => $beneficiary_sociodemographic_characterization->age,
                            'decision_study' =>
                            strval($beneficiary_sociodemographic_characterization->decision_study),
                            'educational_level' => $beneficiary_sociodemographic_characterization->educational_level,
                            'decision_disability' =>
                            strval($beneficiary_sociodemographic_characterization->decision_disability),
                            'disability_type' => $disability_type != '' ? $disability_type : null,
                            'ethnicity' => $beneficiary_sociodemographic_characterization->ethnicity,
                            'condition' => $beneficiary_sociodemographic_characterization->condition,
                        ]
                    );
                    // Guardamos en DataModel
                    $this->control_data($socio_demo, 'store');
                }
            }

            if (!is_null($request->beneficiary_health_data)) {
                $beneficiary_health_data = json_decode($request->beneficiary_health_data);

                if ($saved) {
                    $health_data = $this->beneficiary->health_data()->create(
                        [
                            'other_entity_name' => preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->other_entity_name),
                            'medical_service' => preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->medical_service),
                            'entity_name_id' => $beneficiary_health_data->entity_name_id,
                            'health_condition' => preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->health_condition),
                            'other_disability' => preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->other_disability)
                        ]
                    );
                    // Guardamos en DataModel
                    $this->control_data($health_data, 'store');
                }
            }

            if ($request->hasFile('beneficiary_file')) {
                if ($saved) {
                    $this->beneficiary->query()->withTrashed();

                    $handle_1 = $this->send_file($request, 'beneficiary_file', 'beneficiary', $this->beneficiary->id);
                    if ($handle_1['response']['success']) {
                        $this->beneficiary->update(['file' => $handle_1['response']['payload']]);
                    }
                }
            }

            // Guardamos en ModelData
            $this->control_data($this->beneficiary, 'store');

            DB::commit();
            return $this->beneficiary->id;
        } catch (\Exception $ex) {
            report($ex);
            DB::rollBack();
            return [
                'save' => false,
                'message' => $ex->getMessage() . ' Linea ' . $ex->getLine() . ' Codigo ' . $ex->getCode()
            ];
        }
    }
    public function createAttendant($request, $beneficiary_id)
    {
        DB::beginTransaction();
        $user_id = $this->getIdUserAuth();
        try {
            $attendant = json_decode($request->attendant);
            $this->attendant->beneficiary_id = $beneficiary_id;
            $this->attendant->document_number = $attendant->document_number;
            $this->attendant->email = preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant->email);
            $this->attendant->full_name = preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant->full_name);
            $this->attendant->phone = preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant->phone);
            $this->attendant->relationship = preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant->relationship);
            $this->attendant->other_relationship = preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant->other_relationship);
            // $this->attendant->other_disability =  $attendant->other_disability;
            $this->attendant->type_document = $attendant->type_document;
            $this->attendant->zone = $attendant->zone;
            $this->attendant->created_by = $user_id;

            $saved = $this->attendant->save();

            if ($saved) {
                if (!is_null($request->attendant_sociodemographic_characterization)) {
                    $attendant_sociodemographic_characterization = json_decode($request->attendant_sociodemographic_characterization);

                    $disability_type = $attendant_sociodemographic_characterization->disability_type;

                    $socio_demo = $this->attendant->socio_demo()->create(
                        [
                            'gender' => preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant_sociodemographic_characterization->gender),
                            'age' => preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant_sociodemographic_characterization->age),
                            'decision_study' =>
                            strval($attendant_sociodemographic_characterization->decision_study),
                            'educational_level' => $attendant_sociodemographic_characterization->educational_level,
                            'decision_disability' =>
                            strval($attendant_sociodemographic_characterization->decision_disability),
                            'disability_type' => $disability_type != '' ? $disability_type : null,
                            'ethnicity' => $attendant_sociodemographic_characterization->ethnicity,
                            'condition' => $attendant_sociodemographic_characterization->condition
                        ]
                    );
                    // Guardamos en DataModel
                    $this->control_data($socio_demo, 'store');
                }
                if (!is_null($request->attendant_health_data)) {
                    $attendant_health_data = json_decode($request->attendant_health_data);
                    $health_data = $this->attendant->health_data()->create(
                        [
                            'other_entity_name' => preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant_sociodemographic_characterization->other_entity_name),
                            'entity_name_id' => $attendant_health_data->entity_name_id,
                            'medical_service' => preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant_sociodemographic_characterization->medical_service),
                            'health_condition' => preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant_sociodemographic_characterization->health_condition),
                            'other_disability' => preg_replace('/[^A-Za-z0-9\ ]/', '', $attendant_sociodemographic_characterization->other_disability)
                        ]
                    );
                    // Guardamos en DataModel
                    $this->control_data($health_data, 'store');
                }
            }
            DB::commit();

            // Guardamos en DataModel
            $this->control_data($this->attendant, 'store');

            return $this->attendant->id;
        } catch (\Exception $ex) {
            report($ex);
            DB::rollBack();
            return [
                'save' => false,
                'message' => $ex->getMessage() . ' Linea ' . $ex->getLine() . ' Codigo ' . $ex->getCode()
            ];
        }
    }

    public function create($request, $type)
    {
        DB::beginTransaction();
        try {

            $user_id = $this->getIdUserAuth();

            $beneficiary = json_decode($request->beneficiary);
            $this->beneficiary->accept = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->accept);
            $this->beneficiary->document_number = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->document_number);
            $this->beneficiary->email = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->email);
            $this->beneficiary->full_name = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->full_name);
            $this->beneficiary->institution_entity_referred = preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->institution_entity_referred) != "" ? preg_replace('/[^A-Za-z0-9\ ]/', '', $beneficiary->institution_entity_referred) : NULL;


            // if ($beneficiary->institution_entity_referred != "" || $beneficiary->institution_entity_referred != null) {
            //     $this->beneficiary->institution_entity_referred = $beneficiary->institution_entity_referred;
            // } else {
            //     $this->beneficiary->institution_entity_referred = NULL;
            // }

            $this->beneficiary->linkage_project = $beneficiary->linkage_project;
            $this->beneficiary->nac_id = $beneficiary->nac_id;
            $this->beneficiary->neighborhood_id = $beneficiary->neighborhood_id;

            $this->beneficiary->neighborhood_new = $beneficiary->neighborhood_new != "" ? $beneficiary->neighborhood_new : NULL;

            // if ($beneficiary->neighborhood_new != "" || $beneficiary->neighborhood_new != null) {
            //     $this->beneficiary->neighborhood_new = $beneficiary->neighborhood_new;
            // } else {
            //     $this->beneficiary->neighborhood_new = NULL;
            // }

            $this->beneficiary->participant_type = $beneficiary->participant_type;
            $this->beneficiary->phone = $beneficiary->phone;

            $this->beneficiary->referrer_name = $beneficiary->referrer_name != "" ? $beneficiary->referrer_name : NULL;
            // if ($beneficiary->referrer_name != "" || $beneficiary->referrer_name != null) {
            //     $this->beneficiary->referrer_name = $beneficiary->referrer_name;
            // } else {
            //     $this->beneficiary->referrer_name = NULL;
            // }

            $this->beneficiary->stratum = $beneficiary->stratum;
            $this->beneficiary->type_document = $beneficiary->type_document;
            $this->beneficiary->user_id = $beneficiary->user_id != "" ? $beneficiary->user_id : NULL;

            // if ($beneficiary->user_id != "" || $beneficiary->user_id != null) {
            //     $this->beneficiary->user_id = $beneficiary->user_id;
            // } else {
            //     $this->beneficiary->user_id = NULL;
            // }

            $this->beneficiary->zone = $beneficiary->zone;

            $this->beneficiary->group_id = $beneficiary->group_id != "" ? $beneficiary->group_id : NULL;
            // if ($beneficiary->group_id) {
            //     $this->beneficiary->group_id = $beneficiary->group_id;
            // }
            $this->beneficiary->created_by = $user_id;
            $saved = $this->beneficiary->save();


            if ($beneficiary->neighborhood_new != "" || $beneficiary->neighborhood_new != null) {
                $neighborhood = $this->neighborhood->where('name', 'LIKE', "%{$beneficiary->neighborhood_new}%")->first();
                if (!$neighborhood && $saved) {
                    $this->neighborhood->name = $beneficiary->neighborhood_new;
                    $this->neighborhood->user_id = $user_id;
                    $this->neighborhood->save();
                    // Guardamos en DataModel
                    $this->control_data($this->neighborhood, 'store');
                }
            }

            if (!is_null($request->beneficiary_sociodemographic_characterization)) {
                $beneficiary_sociodemographic_characterization = json_decode($request->beneficiary_sociodemographic_characterization);

                $disability_type = $beneficiary_sociodemographic_characterization->disability_type != "" ? $beneficiary_sociodemographic_characterization->disability_type : NULL;
                if ($saved) {
                    $socio_demo = $this->beneficiary->socio_demo()->create(
                        [
                            'gender' => $beneficiary_sociodemographic_characterization->gender,
                            'age' => $beneficiary_sociodemographic_characterization->age,
                            'decision_study' =>
                            strval($beneficiary_sociodemographic_characterization->decision_study),
                            'educational_level' => $beneficiary_sociodemographic_characterization->educational_level,
                            'decision_disability' =>
                            strval($beneficiary_sociodemographic_characterization->decision_disability),
                            'disability_type' => $disability_type,
                            'ethnicity' => $beneficiary_sociodemographic_characterization->ethnicity,
                            'condition' => $beneficiary_sociodemographic_characterization->condition,
                        ]
                    );
                    // Guardamos en DataModel
                    $this->control_data($socio_demo, 'store');
                }
            }

            if (!is_null($request->beneficiary_health_data)) {
                $beneficiary_health_data = json_decode($request->beneficiary_health_data);

                if ($saved) {
                    $health_data = $this->beneficiary->health_data()->create(
                        [
                            'other_entity_name' => $beneficiary_health_data->other_entity_name,
                            'medical_service' => $beneficiary_health_data->medical_service,
                            'entity_name_id' => $beneficiary_health_data->entity_name_id,
                            'health_condition' => $beneficiary_health_data->health_condition,
                            'other_disability' => $beneficiary_health_data->other_disability
                        ]
                    );
                    // Guardamos en DataModel
                    $this->control_data($health_data, 'store');
                }
            }

            if ($request->hasFile('beneficiary_file')) {
                if ($saved) {
                    $this->beneficiary->query()->withTrashed();

                    $handle_1 = $this->send_file($request, 'beneficiary_file', 'beneficiary', $this->beneficiary->id);
                    if ($handle_1['response']['success']) {
                        $this->beneficiary->update(['file' => $handle_1['response']['payload']]);
                    }
                }
            }

            // Guardamos en ModelData
            $this->control_data($this->beneficiary, 'store');

            if ($type == 'characterizedWithAccudent') {


                $attendant = json_decode($request->attendant);
                $this->attendant->beneficiary_id = $this->beneficiary->id;
                $this->attendant->document_number = $attendant->document_number;
                $this->attendant->email = $attendant->email;
                $this->attendant->full_name = $attendant->full_name;
                $this->attendant->phone = $attendant->phone;
                $this->attendant->relationship = $attendant->relationship;
                $this->attendant->other_relationship = $attendant->other_relationship;
                // $this->attendant->other_disability =  $attendant->other_disability;
                $this->attendant->type_document = $attendant->type_document;
                $this->attendant->zone = $attendant->zone;
                $this->attendant->created_by = $user_id;

                $saved = $this->attendant->save();

                if ($saved) {
                    if (!is_null($request->attendant_sociodemographic_characterization)) {
                        $attendant_sociodemographic_characterization = json_decode($request->attendant_sociodemographic_characterization);

                        $disability_type = $attendant_sociodemographic_characterization->disability_type != "" ? $attendant_sociodemographic_characterization->disability_type : NULL;

                        $socio_demo = $this->attendant->socio_demo()->create(
                            [
                                'gender' => $attendant_sociodemographic_characterization->gender,
                                'age' => $attendant_sociodemographic_characterization->age,
                                'decision_study' =>
                                strval($attendant_sociodemographic_characterization->decision_study),
                                'educational_level' => $attendant_sociodemographic_characterization->educational_level,
                                'decision_disability' =>
                                strval($attendant_sociodemographic_characterization->decision_disability),
                                'disability_type' => $disability_type,
                                'ethnicity' => $attendant_sociodemographic_characterization->ethnicity,
                                'condition' => $attendant_sociodemographic_characterization->condition
                            ]
                        );
                        // Guardamos en DataModel
                        $this->control_data($socio_demo, 'store');
                    }
                    if (!is_null($request->attendant_health_data)) {
                        $attendant_health_data = json_decode($request->attendant_health_data);
                        $health_data = $this->attendant->health_data()->create(
                            [
                                'other_entity_name' => $attendant_health_data->other_entity_name,
                                'entity_name_id' => $attendant_health_data->entity_name_id,
                                'medical_service' => $attendant_health_data->medical_service,
                                'health_condition' => $attendant_health_data->health_condition,
                                'other_disability' => $attendant_health_data->other_disability
                            ]
                        );
                        // Guardamos en DataModel
                        $this->control_data($health_data, 'store');
                    }
                }
                // Guardamos en DataModel
                $this->control_data($this->attendant, 'store');
            }


            $this->model->created_by = $user_id;
            $this->model->beneficiary_id = $this->beneficiary->id;
            $this->model->consecutive = $request->consecutive;
            $this->model->user_review_support_follow_id = $this->getIdUserReview()->support_tracing_monitoring_id;
            $this->model->save();

            $id = $this->model->id;
            DB::update(DB::RAW("UPDATE inscriptions SET consecutive = CONCAT('F', id) WHERE id=$id"));
            DB::commit();


            // // Guardamos en DataModel
            $this->control_data($this->model, 'store');
            return response()->json(['items' => 'Se ha guardado correctamente', 'success' => true]);

            // $id_beneficiary = NULL;
            // switch ($type) {
            //     case 'uncharacterized':
            //         $id_beneficiary = $this->createBeneficiary($request);
            //         if (isset($id_beneficiary['save'])) {
            //             throw new \Exception('el beneficiario no se creo correctamente ' . $id_beneficiary['message']);
            //             return 0;
            //         }
            //         break;
            //     case 'characterized':

            //         $id_beneficiary = $this->createBeneficiary($request);
            //         if (isset($id_beneficiary['save'])) {
            //             throw new \Exception('el beneficiario no se creo correctamente ' . $id_beneficiary['message']);
            //             return 0;
            //         }
            //         break;

            //     case 'characterizedWithAccudent':

            //         $id_beneficiary = $this->createBeneficiary($request);
            //         if (isset($id_beneficiary['save'])) {
            //             throw new \Exception('el beneficiario no se creo correctamente ' . $id_beneficiary['message']);
            //             return 0;
            //         }

            //         $id_attendant = $this->createAttendant($request, $id_beneficiary);
            //         if (isset($id_attendant['save'])) {
            //             throw new \Exception('el acudiente no se creo correctamente ' . $id_beneficiary['message']);
            //             return 0;
            //         }

            //         break;
            //     default:
            //         break;
            // }
            // $user_id = $this->getIdUserAuth();

        } catch (\Exception $ex) {
            report($ex);
            DB::rollBack();
            return response()->json(['error' => 'Algo salio mal, por favor intentar de nuevo', 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function findById($id)
    {
        $inscription = Inscription::findOrFail($id);
        $result = new InscriptionsResource($inscription);
        return $result;
    }

    private function is_filled($data): bool
    {
        $is_fill = true;
        $props = get_object_vars($data);
        foreach ($props as $key => $prop) {
            if ($prop == '') {
                $is_fill = false;
            }
        }

        return $is_fill;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $identifiers = json_decode($request->identifiers);

            $inscription = Inscription::findOrFail($identifiers->inscription_id);

            if (!$inscription) {
                return response()->json(['success' => false, 'message' => 'Inscripción a editar no encontrada.'], 404);
            }

            $beneficiary = json_decode($request->beneficiary);
            if (!is_null($inscription->benefiary)) {
                if ($beneficiary->group_id == ""){
                    unset($beneficiary->group_id);
                }
                if ($beneficiary->user_id == ""){
                    unset($beneficiary->user_id);
                }
                $inscription->benefiary->update((array) $beneficiary);
            } else {
                if ($beneficiary->document_number) {
                    $inscription->benefiary->create((array) $beneficiary);
                }
            }

            $beneficiary_health_data = json_decode($request->beneficiary_health_data);

            if ($identifiers->health_data_beneficiary_id ==null && $beneficiary_health_data) {
                $health_data = $inscription->benefiary->health_data()->create([
                    'other_entity_name' => $beneficiary_health_data->other_entity_name,
                    'entity_name_id' => $beneficiary_health_data->entity_name_id,
                    'medical_service' => $beneficiary_health_data->medical_service,
                    'health_condition' => $beneficiary_health_data->health_condition,
                    'other_disability' => $beneficiary_health_data->other_disability
                ]);
                // Guardamos en DataModel
                $this->control_data($health_data, 'store');
            } else {
                $this->health_data->where('health_data_type', $inscription->benefiary->getMorphClass())->where('health_data_id', $inscription->benefiary->id)
                    ->update((array) $beneficiary_health_data);
            }


            $beneficiary_sociodemographic_characterization = json_decode($request->beneficiary_sociodemographic_characterization);
            if ($identifiers->socio_demo_beneficiary_id == null && $beneficiary_sociodemographic_characterization) {
                $disability_type = $beneficiary_sociodemographic_characterization->disability_type;

                $socio_demo = $inscription->benefiary->socio_demo()->create(
                    [
                        'gender' => $beneficiary_sociodemographic_characterization->gender,
                        'age' => $beneficiary_sociodemographic_characterization->age,
                        'decision_study' =>
                        strval($beneficiary_sociodemographic_characterization->decision_study),
                        'educational_level' => $beneficiary_sociodemographic_characterization->educational_level,
                        'decision_disability' =>
                        strval($beneficiary_sociodemographic_characterization->decision_disability),
                        'disability_type' => $disability_type != '' ? $disability_type : null,
                        'ethnicity' => $beneficiary_sociodemographic_characterization->ethnicity,
                        'condition' => $beneficiary_sociodemographic_characterization->condition,
                    ]
                );
                // Guardamos en DataModel
                $this->control_data($socio_demo, 'store');

            } else {
                // Log::debug(var_dump($beneficiary_sociodemographic_characterization));

                if($beneficiary_sociodemographic_characterization->decision_disability ==='0'){
                    unset($beneficiary_sociodemographic_characterization->disability_type);
                }
                $this->sociodemographic->where('socio_demo_type', $inscription->benefiary->getMorphClass())->where('socio_demo_id', $inscription->benefiary->id)
                    ->update((array) $beneficiary_sociodemographic_characterization);

            }

            $attendant = json_decode($request->attendant);
                if (!is_null($identifiers->attendant_id)) {
                    $inscription->benefiary->attendant->update((array) $attendant);
                } else {
                    if (!empty($attendant->document_number)) {
                        $this->createAttendant($request, $inscription->benefiary->id);
                    }
                }


            $attendant_health_data = json_decode($request->attendant_health_data);

            if ($identifiers->attendant_id) {
                if ($identifiers->health_data_attendant_id ==null &&  $attendant_health_data) {
                    $health_data = $inscription->benefiary->attendant->health_data()->create(
                        [
                            'other_entity_name' => $attendant_health_data->other_entity_name,
                            'entity_name_id' => $attendant_health_data->entity_name_id,
                            'medical_service' => $attendant_health_data->medical_service,
                            'health_condition' => $attendant_health_data->health_condition,
                            'other_disability' => $attendant_health_data->other_disability
                        ]
                    );
                    // Guardamos en DataModel
                    $this->control_data($health_data, 'store');

                } else {

                    $this->health_data->where('health_data_type', $inscription->benefiary->attendant->getMorphClass())->where('health_data_id', $inscription->benefiary->attendant->id)
                        ->update((array) $attendant_health_data);

                }
            }
            $attendant_sociodemographic_characterization = json_decode($request->attendant_sociodemographic_characterization);

            if ($identifiers->attendant_id) {
                if ($identifiers->socio_demo_attendant_id ==null &&  $attendant_sociodemographic_characterization) {
                    $socio_demo = $inscription->benefiary->attendant->socio_demo()->create([
                        'gender' => $attendant_sociodemographic_characterization->gender,
                        'age' => $attendant_sociodemographic_characterization->age,
                        'decision_study' =>
                        strval($attendant_sociodemographic_characterization->decision_study),
                        'educational_level' => $attendant_sociodemographic_characterization->educational_level,
                        'decision_disability' =>
                        strval($attendant_sociodemographic_characterization->decision_disability),
                        'disability_type' => $disability_type != '' ? $disability_type : null,
                        'ethnicity' => $attendant_sociodemographic_characterization->ethnicity,
                        'condition' => $attendant_sociodemographic_characterization->condition
                    ]);
                    // Guardamos en DataModel
                    $this->control_data($socio_demo, 'store');

                } else {
                    if($attendant_sociodemographic_characterization->decision_disability ==='0'){
                        unset($attendant_sociodemographic_characterization->disability_type);
                    }
                    $this->sociodemographic->where('socio_demo_type', $inscription->benefiary->attendant->getMorphClass())->where('socio_demo_id', $inscription->benefiary->attendant->id)
                        ->update((array) $attendant_sociodemographic_characterization);

                }
            }


            if ($request->hasFile('beneficiary_file')) {
                if ($request->beneficiary_file != 'undefined') {
                    $handle_2 = $this->update_file($request, 'beneficiary_file', 'beneficiaries', $inscription->benefiary->id, $request->beneficiary_file);
                    $inscription->benefiary->update(['file' => $handle_2['response']['payload']]);
                }
            }

            // Guardamos en DataModel
            $this->control_data($inscription, 'update');

            if ($inscription->status == 'REC') {
                $rol_id = $this->getIdRolUserAuth();
                if ($rol_id == config('roles.monitor') || $rol_id == config('roles.instructor') || $rol_id == config('roles.lider_instructor')) {
                    $inscription->status = 'ENREV';
                }
            }

            $inscription->save();

            $result = new InscriptionsResource($inscription);
            DB::commit();
            return response()->json(['items' => 'Se ha guardado correctamente', 'success' => true]);
        } catch (\Exception $ex) {
            report($ex);
            DB::rollBack();
            return response()->json(['error' => 'Algo salio mal mensaje de error ' . $ex->getMessage() . ' Linea de codigo' . $ex->getLine(), 'success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->delete();

        return response()->json(['items' => 'Se ha eliminado correctamente']);
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

    public function validator($data, $type)
    {
        $validator = NULL;

        $beneficiary_validate = [
            'nac_id' => 'required',
            'neighborhood_id' => 'required',
            'full_name' => 'required',
            'accept' => 'required',
            'linkage_project' => 'required',
            'participant_type' => 'required',
            'type_document' => 'required',
            'document_number' => ['required', Rule::unique(Beneficiary::class, ), 'integer'],
            'email' => [Rule::unique(Beneficiary::class), 'email'],
            'zone' => 'required',
            'stratum' => 'required',
            'phone' => 'required|integer',
        ];

        $attendant_validate = [
            'full_name' => 'required',
            'type_document' => 'required',
            'document_number' => ['required', 'integer'],
            'email' => 'email',
            'zone' => 'required',
            'phone' => 'required|integer',
        ];

        $soc_validate = [
            'gender' => 'required',
            'age' => 'required',
            'decision_study' => 'required',
            'decision_disability' => 'required',
            'educational_level' => 'required',
            'ethnicity' => 'required',
            'condition' => 'required',
        ];

        $health_validate = [
            'entity_name_id' => 'required',
            'medical_service' => 'required',
            'health_condition' => 'required',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un beneficiario con este :attribute.',
        ];

        $attrs = [
            'document_number' => 'numero de identidad',
            'entity_name_id' => 'EPS'
        ];

        function uncharacterized($data, $validation, $messages, $attrs): \Illuminate\Validation\Validator
        {
            $validator = Validator::make($data, $validation, $messages, $attrs);
            return $validator;
        }

        function characterized($d, $s, $h, $vd, $vs, $vh, $messages, $attrs): \Illuminate\Validation\Validator
        {
            $validator = uncharacterized($d, $vd, $messages, $attrs);

            if ($validator->fails()) {
                return $validator;
            } else {
                $validator = Validator::make($s, $vs, $messages, $attrs);

                if ($validator->fails()) {
                    return $validator;
                } else {
                    $validator = Validator::make($h, $vh, $messages, $attrs);
                    return $validator;
                }
            }
        }

        function attendant($d, $s, $h, $vd, $vs, $vh): \Illuminate\Validation\Validator
        {
            $validator = Validator::make($d, $vd);

            if ($validator->fails()) {
                return $validator;
            } else {
                $validator = Validator::make($s, $vs);

                if ($validator->fails()) {
                    return $validator;
                } else {
                    $validator = Validator::make($h, $vh);
                    return $validator;
                }
            }
        }

        if ($type == 'uncharacterized') {
            $beneficiary_data = json_decode($data['beneficiary']);

            return uncharacterized((array) $beneficiary_data, $beneficiary_validate, $messages, $attrs);
        } else if ($type == 'characterized') {
            $beneficiary_data = json_decode($data['beneficiary']);
            $beneficiary_soc = json_decode($data['beneficiary_sociodemographic_characterization']);
            $beneficiary_health_data = json_decode($data['beneficiary_health_data']);

            return characterized(
                (array) $beneficiary_data,
                (array) $beneficiary_soc,
                (array) $beneficiary_health_data,
                $beneficiary_validate,
                $soc_validate,
                $health_validate,
                $messages,
                $attrs
            );
        } else {
            $beneficiary_data = json_decode($data['beneficiary']);
            $beneficiary_soc = json_decode($data['beneficiary_sociodemographic_characterization']);
            $beneficiary_health_data = json_decode($data['beneficiary_health_data']);

            $validator = characterized((array) $beneficiary_data, (array) $beneficiary_soc, (array) $beneficiary_health_data, $beneficiary_validate, $soc_validate, $health_validate, $messages, $attrs);

            if ($validator->fails()) {
                return $validator;
            } else {
                $attendant_data = json_decode($data['attendant']);
                $attendant_soc = json_decode($data['attendant_sociodemographic_characterization']);
                $attendant_health_data = json_decode($data['attendant_health_data']);

                return attendant((array) $attendant_data, (array) $attendant_soc, (array) $attendant_health_data, $attendant_validate, $soc_validate, $health_validate);
            }
        }
    }
    public function validatorUpdate($data, $type)
    {
        $validator = NULL;

        $beneficiary_data = json_decode($data['beneficiary']);
        $beneficiary_soc = json_decode($data['beneficiary_sociodemographic_characterization']);
        $beneficiary_health_data = json_decode($data['beneficiary_health_data']);
        $attendant_data = json_decode($data['attendant']);
        $attendant_soc = json_decode($data['attendant_sociodemographic_characterization']);
        $attendant_health_data = json_decode($data['attendant_health_data']);
        $identifiers = json_decode($data['identifiers']);

        $beneficiary_validate = [
            'nac_id' => 'required',
            'neighborhood_id' => 'required',
            'full_name' => 'required',
            'accept' => 'required',
            'linkage_project' => 'required',
            'participant_type' => 'required',
            'type_document' => 'required',
            'document_number' => 'required|integer|unique:beneficiaries,document_number,' . $identifiers->beneficiary_id,
            'email' => 'email|unique:beneficiaries,email,' . $identifiers->beneficiary_id,
            'zone' => 'required',
            'stratum' => 'required',
            'phone' => 'required|integer',
        ];
        // |unique:beneficiaries,document_number,' . $identifiers->attendant_id,
        $attendant_validate = [
            'full_name' => 'required',
            'type_document' => 'required',
            'document_number' => 'required|integer',
            'email' => 'email|unique:beneficiaries,email,' . $identifiers->attendant_id,
            'zone' => 'required',
            'phone' => 'required|integer',
        ];

        $soc_validate = [
            'gender' => 'required',
            'age' => 'required',
            'decision_study' => 'required',
            'decision_disability' => 'required',
            'educational_level' => 'required',
            'ethnicity' => 'required',
            'condition' => 'required',
        ];

        $health_validate = [
            'entity_name_id' => 'required',
            'medical_service' => 'required',
            'health_condition' => 'required',
        ];

        $messages = [
            'required' => ':attribute es obligatorio.',
            'unique' => 'Ya existe un beneficiario con este :attribute.',
        ];

        $attrs = [
            'document_number' => 'numero de identidad',
            'entity_name_id' => 'EPS'
        ];

        function uncharacterizedUpdate($data, $validation, $messages, $attrs): \Illuminate\Validation\Validator
        {
            $validator = Validator::make($data, $validation, $messages, $attrs);
            return $validator;
        }

        function characterizedUpdate($d, $s, $h, $vd, $vs, $vh, $messages, $attrs): \Illuminate\Validation\Validator
        {
            $validator = uncharacterizedUpdate($d, $vd, $messages, $attrs);

            if ($validator->fails()) {
                return $validator;
            } else {
                $validator = Validator::make($s, $vs, $messages, $attrs);

                if ($validator->fails()) {
                    return $validator;
                } else {
                    $validator = Validator::make($h, $vh, $messages, $attrs);
                    return $validator;
                }
            }
        }

        function attendantUpdate($d, $s, $h, $vd, $vs, $vh): \Illuminate\Validation\Validator
        {
            $validator = Validator::make($d, $vd);

            if ($validator->fails()) {
                return $validator;
            } else {
                $validator = Validator::make($s, $vs);

                if ($validator->fails()) {
                    return $validator;
                } else {
                    $validator = Validator::make($h, $vh);
                    return $validator;
                }
            }
        }

        $validator = uncharacterizedUpdate((array) $beneficiary_data, $beneficiary_validate, $messages, $attrs);

        if ($validator->fails()) {
            return $validator;
        } else {
            if ($this->is_filled($beneficiary_soc)) {
                $validator = Validator::make((array) $beneficiary_soc, $soc_validate, $messages, $attrs);

                if ($validator->fails()) {
                    return $validator;
                } else {
                    if ($this->is_filled($beneficiary_health_data)) {
                        $validator = Validator::make((array) $beneficiary_health_data, $health_validate, $messages, $attrs);
                        return $validator;
                    } else {
                        if ($this->is_filled($attendant_data)) {
                            return attendantUpdate((array) $attendant_data, (array) $attendant_soc, (array) $attendant_health_data, $attendant_validate, $soc_validate, $health_validate);
                        }
                    }
                }
            } else {
                return $validator;
            }
        }
        return $validator;
    }
}
