<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StrengtheningOfMonitoring extends Model
{
    use HasFactory;
    use ImageTrait, LogsActivity, SoftDeletes;

    protected $table = "strengthening_of_monitorings";

    protected $fillable = [
        'consecutive',
        'nac_id',
        'role_id',
        'user_id',
        'activity_date',
        'start_time',
        'final_hour',
        'place',
        'strategic_objectives_area',
        'purpose_visit',
        'topics_covered',
        'participants_perception',
        'problems_identified',
        'recommendations_actions',
        'comments_analysis',
        'development_activity_image',
        'evidence_participation_image',
        'reviewed_by',
        'created_by',
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
		return $this->belongsTo(User::class,'user_id','id');
	}

    public function created_user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'strengthening_of_monitorings', 'id', 'role_id');
    }

    public function nac(){
        return $this->belongsTo(Nac::class, 'nac_id', 'id');
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
                'user_id' => function ($query) use ($searchValue) {
                    $query->whereHas('created_user', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'activity_date' => function ($query) use ($searchValue) {
                    $query->where('activity_date', 'like', '%' . $searchValue . '%');
                },
                'start_time' => function ($query) use ($searchValue) {
                    $query->where('start_time', 'like', '%' . $searchValue . '%');
                },
                'final_hour' => function ($query) use ($searchValue) {
                    $query->where('final_hour', 'like', '%' . $searchValue . '%');
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('nacs.name', 'like', '%' . $searchValue . '%');
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
