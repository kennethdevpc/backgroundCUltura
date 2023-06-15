<?php

namespace App\Observers\V1;

use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        DB::beginTransaction();

        try {

            $user->profile->delete();

            RoleUser::where('user_id', $user->id)->delete();

            if ($user->activation_culturalsV2 !== null) {
                foreach ($user->activation_culturalsV2 as $item) {
                    if ($item instanceof Model) {
                        $item->delete();
                    }
                }
            }


            if ($user->poll !== null) {
                $user->poll->delete();
            }

            if ($user->profile !== null) {
                $user->profile->delete();
            }

            if ($user->inscriptions !== null) {
                foreach ($user->inscriptions as $inscription) {
                    if ($inscription instanceof Model) {
                        $inscription->delete();
                    }
                }
            }

            if ($user->pecs !== null) {
                foreach ($user->pecs as $pec) {
                    if ($pec instanceof Model) {
                        $pec->delete();
                    }
                }
            }

            if ($user->beneficiaries !== null) {
                foreach ($user->beneficiaries as $beneficiary) {
                    if ($beneficiary instanceof Model) {
                        $beneficiary->delete();
                    }
                }
            }

            if ($user->binnacles !== null) {
                foreach ($user->binnacles as $binnacle) {
                    if ($binnacle instanceof Model) {
                        $binnacle->delete();
                    }
                }
            }

            if ($user->pedagogicals !== null) {
                foreach ($user->pedagogicals as $pedagogical) {
                    if ($pedagogical instanceof Model) {
                        $pedagogical->delete();
                    }
                }
            }

            if ($user->pollDesertion !== null) {
                foreach ($user->pollDesertion as $desertion) {
                    if ($desertion instanceof Model) {
                        $desertion->delete();
                    }
                }
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            Log::error('Error en la eliminación del usuario: ' . $ex->getMessage());
            return  response()->json([
                'linea' => $ex->getLine(),
                'error' => $ex->getMessage()
            ], 404);
        }
    }


    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
