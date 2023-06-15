<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ControlChangeData;
use App\Models\Inscriptions\Beneficiary;
use App\Models\ParentSchools\AssistanceMonitors;
use App\Models\PsychopedagogicalLogbooks\AssistanceMonitors as PsychopedagogicalLogbooksAssistanceMonitors;
use Illuminate\Database\Eloquent\SoftDeletes;


class MethodologicalSheetsTwo extends Model
{

    use SoftDeletes;
    use HasFactory;

    protected $table = 'methodological_sheets_two';
    protected $fillable = [
        'consecutive',
        'datasheet',
        'activity_type',
        'date_ini',
        'date_fin',
        'keyactors_participating_community',
        'objective_process',
        'reached_target',
        'sustein',
        'development_activity_image',
        'evidence_participation_image',
        'aforo_pdf',
        'number_attendees',
        'created_by',
    ];

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function beneficiary()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_methodological_sheets_two', 'm_s_two_id', 'beneficiary_id');
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
                    $query->where('consecutive', 'like', '%' . $searchValue . '%');
                },
                'datasheet' => function ($query) use ($searchValue) {
                    $query->where('datasheet', 'like', '%' . $searchValue . '%');
                },
                'created_by.name' => function ($query) use ($searchValue) {
                    $query->whereHas('createdBy', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'date' => function ($query) use ($searchValue) {
                    $query->where('date', 'like', '%' . $searchValue . '%');
                },
                'activity_type' => function ($query) use ($searchValue) {
                    $query->where('activity_type', 'like', '%' . $searchValue . '%');
                },
                'reached_target' => function ($query) use ($searchValue) {
                    $query->where('reached_target', 'like', '%' . $searchValue . '%');
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
