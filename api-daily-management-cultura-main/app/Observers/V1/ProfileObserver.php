<?php

namespace App\Observers\V1;

use App\Models\Profile;
use App\Models\User;
use App\Traits\UserDataTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileObserver
{
    use UserDataTrait;
    /**
     * Handle the Profile "created" event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    private $model;
    function __construct()
    {
        $this->model = new User();
    }

    public function created(Profile $profile)
    {
        $rol_id = $profile->role_id;
        if (Auth::check()) {

            //Coordinador supervisión
            if ($rol_id  == config('roles.apoyo_supervision')) {
                $profile->supervision_coordinator_id = $this->model->find(config('user_default.supervision_coordinator'))->id ?? NULL;
            }
            //Coordinador metodologico
            if ($rol_id  == config('roles.apoyo_metodologico') || $rol_id  == config('roles.lider_metodologico')) {

                $profile->methodological_coordinator_id =  $this->model->find(config('user_default.methodological_coordinator'))->id ?? NULL;
                $profile->save();
            }
            //Sub dirección
            if ($rol_id  == config('roles.coordinador_psicosocial') || $rol_id  == config('roles.coordinador_administrativo') || $rol_id  == config('roles.coordinador_seguimiento') || $rol_id  == config('roles.coordinador_metodologico')) {
                $profile->sub_direccion_id =  $this->model->find(config('user_default.sub_director'))->id ?? NULL;
            }
            //Dirección
            if ($rol_id  == config('roles.coordinador_supervision')) {
                $profile->direccion_id =  $this->model->find(config('user_default.director'))->id ?? NULL;
            }
            //Coordinador psicosocial
            if ($rol_id  == config('roles.psicosocial')) {
                $profile->psychosocial_coordinator_id = $this->model->find(config('user_default.psychosocial_coordinator'))->id ?? NULL;
            }

            //Coordinador segumiento
            if ($rol_id  == config('roles.apoyo_al_seguimiento_monitoreo')) {
                $profile->monitoring_coordinator_id =  $this->model->find(config('user_default.monitoring_coordinator'))->id ?? NULL;
            }
        }
    }
}
