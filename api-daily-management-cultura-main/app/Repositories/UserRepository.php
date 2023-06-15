<?php

namespace App\Repositories;

use App\Http\Resources\V1\UserCollection;
use App\Models\Inscriptions\Attendant;
use App\Models\Inscriptions\BeneficiaryPec;
use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\HealthData;
use App\Models\Inscriptions\Pec;
use App\Models\Inscriptions\SociodemographicCharacterization;
use App\Models\Profile;
use App\Models\User;
use App\Traits\FunctionGeneralTrait;
use App\Traits\UserDataTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserRepository
{
    use FunctionGeneralTrait, UserDataTrait;

    private $model;
    function __construct()
    {
        $this->model = new User();
    }

    function getAll()
    {

        $query = $this->model->query()->orderBy('id', 'ASC');
        $users = [];
        $paginate = config('global.paginate');
        $rol_id = $this->getIdRolUserAuth();
        if ($rol_id == config('roles.super_root')) {
            $users = $this->model;
        } else {
            $users = $query->whereHas('profile', function ($profile) {
                $profile->whereNotIn('role_id', [config('roles.super_root')]);
            });
        }

        $users = $this->model->scopeFilterByUrl($query);

        session()->forget('count_page_users');
        session()->put('count_page_users', ceil($users->count() / config('global.paginate')));
        return new UserCollection($users->simplePaginate($paginate));
    }

    function create($data_user, $data_profile)
    {

        DB::beginTransaction();
        try {
            $data_user['password'] = Hash::make($data_user['password']);
            $new_user = $this->model->create($data_user);
            // Guardamos en ModelData
            $this->control_data($new_user, 'store');

            $role = DB::table('roles')->where('slug', '=', $data_profile['role_id'])->get();

            $data_profile['role_id'] = $role[0]->id;
            $data_profile['user_id'] = $new_user->id;
            $new_user->roles()->attach($role[0]->id);
            $new_profile = Profile::create($data_profile);
            // Guardamos en ModelData
            $this->control_data($new_profile, 'store');
            DB::commit();
            return $new_user;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::debug('User_store' . $ex->getMessage() . ' Linea ' . $ex->getLine() . ' Codigo ' . $ex->getCode());
            return response()->json([
                'message' => 'Algo salio mal, en la creación del usuario y su debido perfil.'
            ], 500);
        }
    }

    function findById($id)
    {
        $user = $this->model->find($id);
        $user->roles;
        $repoProfile = new ProfileRepository();
        $profile = $repoProfile->findByUserId($id);
        if ($profile) {
            $profile->role;
            $user->profile = $profile;
        }
        return $user;
    }

    function update($data_user, $data_profile, $id)
    {
        $data_user['password'] = Hash::make($data_user['password']);
        $user_up = $this->model->findOrFail($id);
        // Validar que no repita un documento diferente al de él

        $user_up->update($data_user);
        $this->control_data($user_up, 'update');

        $role = DB::table('roles')->where('slug', '=', $data_profile['role_id'])->get();

        $data_profile['role_id'] = $role[0]->id;
        $data_profile['user_id'] = $user_up->id;
        $user_up->roles()->attach($role[0]->id);
        $new_profile = Profile::where('user_id', $user_up->id)->update($data_profile);

        // Guardamos en ModelData
        $this->control_data($new_profile, 'store');

        // Guardamos en ModelData


        return $user_up;
    }

    function delete($id)
    {
        try {
            return $this->model->destroy($id);
        
            //$user_id = 309;
            //$pro=DB::select("call delete_total_all_data_user_relation($user_id,'monitor')");
            // DB::beginTransaction();

            // $query = $this->model->query();
            // $user = $query->findOrFail($id);
            // $user->user_review_form()->delete();
            // $user->control_data()->delete();
            // $user->loginaccess()->delete();
           
            // if ($user->profile->role_id == config('roles.monitor')) {

            //     $user->binnacles()->forceDelete();
            //     foreach ($user->pecs as $pec) {
            //         $pec->pecsBeneficiaries()->detach();
            //         $pec->forceDelete();     
            //     }
            //     $user->pecs()->forceDelete();

            //         $healthData = HealthData::query();
            //         $socio = SociodemographicCharacterization::query();
                    
            //         $beneficiary = Beneficiary::query(); 
            //         $attendant = Attendant::query(); 

            //         foreach ($user->inscriptions as $inscription) {

            //             $healthData->where('health_data_type',  $inscription->benefiary->attendant->health_data->health_data_type)->where('health_data_id', $inscription->benefiary->attendant->id)
            //             ->forceDelete();

            //             $socio->where('socio_demo_type',  $inscription->benefiary->attendant->socio_demo->socio_demo_type)->where('socio_demo_id', $inscription->benefiary->attendant->id)
            //             ->forceDelete();

            //             $inscription->benefiary->attendant->forceDelete();

            //             $healthData->where('health_data_type',    $inscription->benefiary->health_data->health_data_type)->where('health_data_id', $inscription->benefiary->id)
            //             ->forceDelete();

            //             $socio->where('socio_demo_type',    $inscription->benefiary->socio_demo->socio_demo_type)->where('socio_demo_id', $inscription->benefiary->id)
            //             ->forceDelete();
    
            //             $inscription->benefiary->save();
            //             $inscription->benefiary->forceDelete();
            //             $inscription->forceDelete();
            //         }
    
            //     $user->pedagogicals()->forceDelete();


            //     $user->pollDesertion()->forceDelete();
            //     $user->poll()->forceDelete();
            //     $user->profile()->forceDelete();
            //     $user->forceDelete(); ç

                DB::commit();
               
            

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => 'Error' . ' ' . $ex->getMessage() . ' ' . $ex->getLine() . ' ' . $ex->getFile(), 500]);
        }
        return response()->json(['message' => 'Success']);
    }
    function noPaginate()
    {
        return new UserCollection($this->model->all());
        /* try {
        // $repoProfile = new ProfileRepository();
        // return $this->model->all()->map(function ($user) use  ($repoProfile) {
        //     $profile = $repoProfile->findByUserId($user->id);
        //     if ($profile) {
        //         $profile->role ?? '';
        //         $user->profile = $profile;
        //     }
        //     Log::debug('entro');
        //     $user->roles ?? '';
        //     return $user;
        // });
        } catch (\Exception $ex) {
        return $this->createErrorResponse([], 'Algo salio mal ' . $ex->getMessage() . ' linea ' . $ex->getCode());
        } */
    }
    function changePassword($request)
    {
        $user = $this->model->find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();
    }
    function changeStatus($request)
    {
        $user = $this->model->find($request->id);
        $user->status = $request->status;
        $user->save();
    }

    function getUserRole($slug)
    {
        $rol_id = config('roles.' . $slug);

        $result = null;

        if ($rol_id == config('roles.embajador')) {
            // apoyo metodologico, lider embajador y psicosocial
            $apoyo_metodologico = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.apoyo_metodologico'));
            })->get();
            $lider_embajador = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.lider_embajador'));
            })->get();
            $psicosocial = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.psicosocial'));
            })->get();
            // Pasamos la data
            $result = [
                'apoyo_metodologico' => $apoyo_metodologico,
                'lider_embajador' => $lider_embajador,
                'psicosocial' => $psicosocial,
            ];
        }

        if ($rol_id == config('roles.gestor') || $rol_id == config('roles.instructor')) {
            // psicosocial, apoyo metodologico y (apoyo seguimiento y monitoreo)
            $psicosocial = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.psicosocial'));
            })->get();
            $apoyo_metodologico = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.apoyo_metodologico'));
            })->get();
            $apoyo_al_seguimiento_monitoreo = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.apoyo_al_seguimiento_monitoreo'));
            })->get();
            // Pasamos la data
            $result = [
                'psicosocial' => $psicosocial,
                'apoyo_metodologico' => $apoyo_metodologico,
                'apoyo_al_seguimiento_monitoreo' => $apoyo_al_seguimiento_monitoreo,
            ];
        }

        if ($rol_id == config('roles.monitor')) {
            // gestor, psicosocial y (apoyo seguimiento y monitoreo)
            $psicosocial = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.psicosocial'));
            })->get();
            $gestor = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.gestor'));
            })->get();
            $apoyo_al_seguimiento_monitoreo = $this->model->whereHas('roles', function ($roles) {
                $roles->where('roles.id', config('roles.apoyo_al_seguimiento_monitoreo'));
            })->get();
            // Pasamos la data
            $result = [
                'psicosocial' => $psicosocial,
                'gestores' => $gestor,
                'apoyo_al_seguimiento_monitoreo' => $apoyo_al_seguimiento_monitoreo,
            ];
        }

        return $result;
    }
}