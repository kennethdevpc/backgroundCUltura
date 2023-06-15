<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Poll extends Model
{
    use HasFactory, LogsActivity;
    use SoftDeletes;

    protected $table = "polls";
    protected $guarded = [
        'created_at', 'updated_at'
    ];
    protected $fillable = [
        'gender',
        'age',
        'birth_date',
        'marital_state',
        'stratum',
        'neighborhood_id',
        'other_neighborhoods',
        'phone',
        'email',
        'number_children',
        'dependents',
        'relationship_head_household',
        'ethnicity',
        'victim_armed_conflict',
        'single_registry_victims',
        'study',
        'educational_level',
        'medical_service',
        'entity_name_id',
        'other_entity_name',
        'health_condition',
        'takes_medication',
        'suffers_disease',
        'type_disease',
        'other_disease_type',
        'disability',
        'disability_type',
        'assessed_disability',
        'receives_therapy',
        'expertises',
        'artistic_experience',
        'artistic_group',
        'artistic_group_name',
        'role_artistic_group',
        'user_id',
        'other_disability_type'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function getPublishedAtAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function neighborhood()
    {
        return $this->hasOne(Neighborhood::class, 'id', 'neighborhood_id');
    }

    public function neighborhoodV2()
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function entity_name()
    {
        return $this->belongsTo(EntityName::class);
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expertise()
    {
        return $this->belongsTo(Expertise::class, 'expertises');
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
                'neighborhood' => function ($query) use ($searchValue) {
                    $query->whereHas('neighborhood', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'entity_name.name' => function ($query) use ($searchValue) {
                    $query->whereHas('entity_name', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
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
