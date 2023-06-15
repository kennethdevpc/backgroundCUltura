<?php

namespace App\Models;

use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\Pec;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Binnacle extends Model
{
    use ImageTrait, LogsActivity,SoftDeletes;

    protected $table = "binnacles";

    protected $fillable = [
        "consecutive",
        "binnacle_id",
        "type",
        "cultural_right_id",
        "lineament_id",
        "activation_mode",
        "goals_met",
        "start_time",
        "final_hour",
        "activity_name",
        "start_activity",
        "activity_development",
        "end_of_activity",
        "observations_activity",
        "place",
        "experiential_objective",
        "explain_goals_met",
        "development_activity_image",
        "evidence_participation_image",
        "activity_date",
        "nac_id",
        "expertise_id",
        "orientation_id",
        "pec_id",
        "last_status",
        "pedagogical_id",
        "beneficiaries_capacity",
        "aforo_file",
        // "assistants",
        "created_by",
        'user_review_manager_cultural_id',
        'user_review_support_follow_id',
        'user_review_instructor_leader_id',
        'user_method_support_id',
        'beneficiaries_or_capacity'
    ];

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class);
    }
    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
	}
    public function user(){
		return $this->belongsTo(User::class,'created_by','id');
	}

    public function nac(){
        return $this->belongsTo(Nac::class, 'nac_id', 'id');
    }
    public function pec(){
        return $this->belongsTo(Pec::class, 'pec_id', 'id');
    }
    public function pedagogical(){
        return $this->belongsTo(Pedagogical::class, 'pedagogical_id', 'id');
    }
    public function cultural_right(){
        return $this->belongsTo(CulturalRight::class, 'cultural_right_id', 'id');
    }

    public function expertise(){
        return $this->belongsTo(Expertise::class, 'expertise_id', 'id');
    }
    public function orientation(){
        return $this->belongsTo(Orientation::class, 'orientation_id', 'id');
    }

    public function scopeFilterByUrl($query)
    {
        $this->searchFilter($query);

        $this->dateFilter($query);

        $this->statusFilter($query);

        return $query;
    }

    private function searchFilter($query)
    {
        if (request()->filled('search_field') && request()->filled('search_value')) {
            $searchField = request('search_field');
            $searchValue = request('search_value');

            $validSearchFields = [
                'binnacle_name' => function ($query) use ($searchValue) {
                    $query->where('binnacle_id', $searchValue);
                },
                'profile.document_number' => function ($query) use ($searchValue) {
                    $query->whereHas('user.profile', function ($query) use ($searchValue) {
                        $query->where('document_number', 'like', '%' . $searchValue . '%');
                    });
                },
                'user.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'cultural_right.name' => function ($query) use ($searchValue) {
                    $query->whereHas('cultural_right', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'expertise.name' => function ($query) use ($searchValue) {
                    $query->whereHas('expertise', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'orientation.name' => function ($query) use ($searchValue) {
                    $query->whereHas('orientation', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'final_hour' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue.' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('final_hour', 'like', '%' . $modifiedSearchValue . '%');
                },
                'start_time' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue.' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('start_time', 'like', '%' . $modifiedSearchValue . '%');
                }

            ];

            if (array_key_exists($searchField, $validSearchFields)) {
                $validSearchFields[$searchField]($query);
            } else {
                $query->where($searchField, 'like', '%' . $searchValue . '%');
            }
        }
    }

    private function dateFilter($query)
    {
        if (request()->filled('date_criteria_start') && request()->filled('date_criteria_end')) {
            $startDate = request('date_criteria_start');
            $endDate = request('date_criteria_end');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    }

    private function statusFilter($query)
    {
        if (request()->filled('status_criteria')) {
            $status = request('status_criteria');
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }
    }


}
