<?php

namespace App\Models\ParentSchools;

use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\ControlChangeData;

class ParentSchool extends Model
{
    use HasFactory, ImageTrait, LogsActivity;
    use SoftDeletes;

    protected $table = "parent_schools";

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        // 'start_time' => 'datetime:g:i A',
        // 'final_time' => 'datetime:g:i A',
        'date' => "datetime:Y-m-d",

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function monitor()
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function addedWizards()
    // {
    //     return $this->belongsTo(AddedWizards::class,'parent_school_added_wizards', 'assistant_id','parent_school_id');
    // }

    public function addedWizards()
    {
        return $this->hasMany(AddedWizards::class, 'parent_school_id', 'id');
    }
    // 'parent_school_added_wizards',
    // public function assistants()
    // {
    //     return $this->belongsToMany(User::class, 'assistant_methodological_instruction', 'm_i_id', 'assistant_id');
    // }

    public function assistanceMonitors()
    {
        return $this->hasMany(AssistanceMonitors::class, 'parent_school_id', 'id');
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
                'date' => function ($query) use ($searchValue) {
                    $query->where('date', 'like', '%' . $searchValue . '%');
                },
                'final_hour' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue . ' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('final_hour', 'like', '%' . $modifiedSearchValue . '%');
                },
                'start_time' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue . ' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('start_time', 'like', '%' . $modifiedSearchValue . '%');
                },
                'consecutive' => function ($query) use ($searchValue) {
                    $query->where('consecutive', 'like', '%' . $searchValue . '%');
                },
                'contact' => function ($query) use ($searchValue) {
                    $query->where('contact', 'like', '%' . $searchValue . '%');
                },
                'place_attention' => function ($query) use ($searchValue) {
                    $query->where('place_attention', 'like', '%' . $searchValue . '%');
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'user.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'monitor.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user.profile', function ($query) use ($searchValue) {
                        $query->where('profiles.contractor_full_name', 'like', '%' . $searchValue . '%');
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
