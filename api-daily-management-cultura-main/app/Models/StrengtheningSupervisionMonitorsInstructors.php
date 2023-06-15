<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StrengtheningSupervisionMonitorsInstructors extends Model
{
    use HasFactory;
    use ImageTrait, LogsActivity, SoftDeletes;

    protected $table = "strengthening_super_mons_insts";

    protected $fillable = [
        'consecutive',
        'status',
        'reject_message',
        'revision_date',
        'nac_id',
        'role_id',
        'supervised_user_full_name',
        'platform_registration_datee',
        'address',
        'pec_reached_target',
        'pedagogicals_reached_target',
        'attendance_list',
        'validated_pec_time',
        'description',
        'comments',
        'development_activity_image',
        'evidence_participation_image',
        'created_by',
        'super_coordinator_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function nac()
    {
        return $this->belongsTo(Nac::class, 'nac_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'supervised_user_full_name', 'id');
    }

    public function pec()
    {
        return $this->belongsTo(Pec::class, 'pec_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
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
                    $query->where('consecutive', $searchValue);
                },
                'created_by.name' => function ($query) use ($searchValue) {
                    $query->whereHas('createdBy', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'platform_registration_date' => function ($query) use ($searchValue) {
                    $query->where('platform_registration_date', $searchValue);
                },
                'revision_date' => function ($query) use ($searchValue) {
                    $query->where('revision_date', $searchValue);
                },
                'role.name' => function ($query) use ($searchValue) {
                    $query->whereHas('role', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'user.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
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
