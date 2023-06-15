<?php

namespace App\Models;

use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\Pec;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CulturalCirculation extends Model
{
    use ImageTrait, LogsActivity,SoftDeletes;

    protected $table = "cultural_circulations";

    protected $fillable = [
        'consecutive',
        'datasheet',
        'date',
        'keyactors_circulation_alliance',
        'pec_id',
        'datasheet_planning_id',
        'event_name',
        'filter_level',
        'description',
        'nac_id',
        'other_nac',
       'quantity_members',
        'public_characteristics',
        'cultural_right_id',
        'lineament_id',
        'orientation_id',
        'values',
        'artistic_expertise',
        'participation_observations',
        'development_activity_image',
        'evidence_participation_image',
        'aforo_pdf',
        'number_attendees',
        'reject_message',
        'created_by',
        'datasheet'
    ];

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
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

    public function datasheet_planning(){
        return $this->belongsTo(MethodologicalSheetsOne::class, 'datasheet_planning_id', 'id');
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
                'consecutive' => function ($query) use ($searchValue) {
                    $query->where('consecutive', 'like', '%' . $searchValue . '%');
                },
                'date' => function ($query) use ($searchValue) {
                    $query->where('date', 'like', '%' . $searchValue . '%');
                },
                'datasheet' => function ($query) use ($searchValue) {
                    $query->where('datasheet', 'like', '%' . $searchValue . '%');
                },
                'cultural_right_id' => function ($query) use ($searchValue) {
                    $query->whereHas('cultural_right', function ($query) use ($searchValue) {
                        $query->where('cultural_rights.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'lineament_id' => function ($query) use ($searchValue) {
                    $query->where('lineament_id', 'like', '%' . $searchValue . '%');
                },
                'orientation_id' => function ($query) use ($searchValue) {
                    $query->whereHas('orientation', function ($query) use ($searchValue) {
                        $query->where('orientations.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'created_by.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
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
