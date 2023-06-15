<?php

namespace App\Models;

use App\Models\Inscriptions\Beneficiary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PollDesertion extends Model
{
    use HasFactory, LogsActivity;
    use SoftDeletes;
    protected $table = "polls_desertion";
    protected $fillable = [
        'consecutive',
        'beneficiary_id',
        'date',
        'nac_id',
        'beneficiary_attrition_factors',
        'beneficiary_attrition_factor_other',
        'disinterest_apathy',
        'disinterest_apathy_explanation',
        'reintegration',
        'reintegration_explanation',
        'user_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function nac(){
		return $this->belongsTo(Nac::class);
	}
    public function beneficiary(){
		return $this->belongsTo(Beneficiary::class);
	}
    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
	}
    public function user(){
		return $this->belongsTo(User::class);
	}

    public function scopeFilterByUrl($query)
    {
        $this->searchFilter($query);

        $this->dateFilter($query);

        return $query;
    }

    private function searchFilter($query)
    {
        if (request()->filled('search_field') && request()->filled('search_value')) {
            $searchField = request('search_field');
            $searchValue = request('search_value');

            $validSearchFields = [
                'beneficiary.full_name' => function ($query) use ($searchValue) {
                    $query->whereHas('beneficiary', function ($query) use ($searchValue) {
                        $query->where('beneficiaries.full_name', 'like', '%' . $searchValue . '%');
                    });
                },
                'date' => function ($query) use ($searchValue) {
                    $query->where('date', 'like', '%' . $searchValue . '%');
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('nacs.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'beneficiary_attrition_factors' => function ($query) use ($searchValue) {
                    $query->where('beneficiary_attrition_factors', 'like', '%' . $searchValue . '%');
                },
                'beneficiary_attrition_factor_other' => function ($query) use ($searchValue) {
                    $query->where('beneficiary_attrition_factor_other', 'like', '%' . $searchValue . '%');
                },
                'disinterest_apathy' => function ($query) use ($searchValue) {
                    $query->where('disinterest_apathy', $searchValue == 'SI' or 'Si' or 'si' ? '1' : '0');
                },
                'reintegration' => function ($query) use ($searchValue) {
                    $query->where('reintegration', $searchValue == 'SI' or 'Si' or 'si' ? '1' : '0');
                },
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

}
