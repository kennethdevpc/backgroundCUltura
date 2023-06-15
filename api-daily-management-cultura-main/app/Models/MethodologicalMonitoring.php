<?php

namespace App\Models;

use App\Models\MethodologicalMonitorings\Aggregates;
use App\Models\MethodologicalMonitorings\Roles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MethodologicalMonitoring extends Model
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = "methodological_monitorings";

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function getPublishedAtAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }
    public function aggregates()
    {
        return $this->hasMany(Aggregates::class, 'monitoring_id', 'id');
    }
    public function roles()
    {
        return $this->hasMany(Roles::class, 'monitoring_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function nac()
    {
        return $this->belongsTo(Nac::class)->select('name');
    }
    public function orientation()
    {
        return $this->belongsTo(Orientation::class)->select('name');
    }
    public function cultural_right()
    {
        return $this->belongsTo(CulturalRight::class , 'cultural_right_id', 'id')->select('name');
    }
    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
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
                'datasheet' => function ($query) use ($searchValue) {
                    $query->where('datasheet', 'like', '%' . $searchValue . '%');
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('nacs.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'date_realization' => function ($query) use ($searchValue) {
                    $query->where('date_realization', 'like', '%' . $searchValue . '%');
                },
                'date_planning_ini' => function ($query) use ($searchValue) {
                    $query->where('date_planning_ini', 'like', '%' . $searchValue . '%');
                },
                'date_planning_fin' => function ($query) use ($searchValue) {
                    $query->where('date_planning_fin', 'like', '%' . $searchValue . '%');
                },
                'user.name' => function ($query) use ($searchValue) {
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
