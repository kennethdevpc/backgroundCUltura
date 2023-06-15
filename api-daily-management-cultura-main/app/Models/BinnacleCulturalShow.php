<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\ImageTrait;


class BinnacleCulturalShow extends Model
{
    use ImageTrait, LogsActivity, SoftDeletes;
    protected $table = "binnacle_cultural_show";

    protected $fillable = [
        'consecutive',
        'status',
        'last_status',
        'date_range',
        'activity',
        'expertise',
        'artistic_participation',
        'reached_target',
        'sustein',
        'development_activity_image',
        'evidence_participation_image',
        'aforo_pdf',
        'number_attendees',
        'created_by',
        'audited',
        'user_review_support_follow_id',
        'user_review_ambassador_leader_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user_review_ambassador_leader()
    {
        return $this->belongsTo(User::class, 'user_review_support_follow_id', 'id');
    }

    public function user_review_support_follow()
    {
        return $this->belongsTo(User::class, 'user_review_ambassador_leader_id', 'id');
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
                'date_range' => function ($query) use ($searchValue) {
                    $query->where('date_range', 'like', '%' . $searchValue . '%');
                },
                'reached_target' => function ($query) use ($searchValue) {
                    $query->where('reached_target', $searchValue != 'SI' ? '0' : '1');
                },
                'consecutive' => function ($query) use ($searchValue) {
                    $query->where('consecutive', 'like', '%' . $searchValue . '%');
                },
                'created_by.name' => function ($query) use ($searchValue) {
                    $query->whereHas('created_user', function ($query) use ($searchValue) {
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
