<?php

namespace App\Observers\V1;

use App\Models\CulturalEnsemble;
use App\Traits\UserDataTrait;
use Illuminate\Support\Facades\Auth;

class CulturalEnsembleObserver
{
    /**
     * Handle the CulturalEnsemble "created" event.
     *
     * @param  \App\Models\CulturalEnsemble  $culturalEnsemble
     * @return void
     */
    use UserDataTrait;
    public function created(CulturalEnsemble $culturalEnsemble)
    {
        if (Auth::check()) {
            $culturalEnsemble->update([
                'support_tracing_monitoring_id' => $this->getIdUserReview()->support_tracing_monitoring_id,
                'instructor_leader_id' => $this->getIdUserReview()->instructor_leader_id
            ]);
        }
    }

}
