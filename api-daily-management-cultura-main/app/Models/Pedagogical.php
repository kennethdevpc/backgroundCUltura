<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pedagogical extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;
    protected $table = "pedagogicals";

	protected $fillable = [
        'consecutive',
        'monitor_id',
        'activity_name',
        'activity_date',
        'cultural_right_id',
        'nac_id',
        'expertise_id',
        'experiential_objective',
        'lineament_id',
        'orientation_id',
        'manifestation',
        'process',
        'product',
        'resources',
        'status',
        'user_review_manager_cultural_id',
        'user_review_instructor_leader_id',
        'created_by'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function getPublishedAtAttribute(){

        return is_null($this->created_at) ?'':$this->created_at->format('d/m/Y') ?? '';
    }

    public function get_activity_date(){
        return Carbon::parse($this->activity_date)->format('d/m/Y');
    }
    // public function monitor(){
	// 	return $this->belongsTo(User::class,'monitor_id','id');
	// }
	public function user(){
		return $this->belongsTo(User::class,'created_by','id');
	}
    public function cultural_right(){
    return $this->belongsTo(CulturalRight::class,'cultural_right_id','id');
	}
    public function nac(){
		return $this->belongsTo(Nac::class,'nac_id','id');
	}
    public function expertise(){
		return $this->belongsTo(Expertise::class,'expertise_id','id');
	}
    public function orientation(){
		return $this->belongsTo(Orientation::class,'orientation_id','id');
	}
    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
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
                'activity_name' => function ($query) use ($searchValue) {
                    $query->where('activity_name', 'like', '%' . $searchValue . '%');
                },
                'activity_date' => function ($query) use ($searchValue) {
                    $query->where('activity_date', 'like', '%' . $searchValue . '%');
                },
                'expertise.name' => function ($query) use ($searchValue) {
                    $query->whereHas('expertise', function ($query) use ($searchValue) {
                        $query->where('expertises.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('nacs.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'cultural_right.name' => function ($query) use ($searchValue) {
                    $query->whereHas('cultural_right', function ($query) use ($searchValue) {
                        $query->where('cultural_rights.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'lineament_id' => function ($query) use ($searchValue) {
                    $query->where('lineament_id', 'like', '%' . $searchValue . '%');
                },
                'user.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'profile.document_number' => function ($query) use ($searchValue) {
                    $query->whereHas('user.profile', function ($query) use ($searchValue) {
                        $query->where('profiles.document_number', 'like', '%' . $searchValue . '%');
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
