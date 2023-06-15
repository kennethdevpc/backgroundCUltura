<?php

namespace App\Models\PsychopedagogicalLogbooks;

use App\Models\Nac;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\ControlChangeData;

class PsychopedagogicalLogbook extends Model
{
    use HasFactory, ImageTrait, LogsActivity;
    use SoftDeletes;

    protected $table = "psychopedagogical_logbooks";

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'deleted_at',
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
        return $this->belongsTo(User::class);
    }

    public function addedWizards()
    {
        return $this->hasMany(AddedWizards::class, 'psychopedagogical_logbook_id', 'id');
    }

    public function assistanceMonitors()
    {
        return $this->hasMany(AssistanceMonitors::class, 'psychopedagogical_logbook_id', 'id');
    }

    public function nac()
    {
        return $this->belongsTo(Nac::class, 'nac_id');
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
                'start_time' => function ($query) use ($searchValue) {
                    $query->where('start_time', 'like', '%' . $searchValue . '%');
                },
                'final_time' => function ($query) use ($searchValue) {
                    $query->where('final_time', 'like', '%' . $searchValue . '%');
                },
                'consecutive' => function ($query) use ($searchValue) {
                    $query->where('consecutive', 'like', '%' . $searchValue . '%');
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
                'final_hour' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue . ' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('final_hour', 'like', '%' . $modifiedSearchValue . '%');
                },
                'start_time' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue . ' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('start_time', 'like', '%' . $modifiedSearchValue . '%');
                }
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
