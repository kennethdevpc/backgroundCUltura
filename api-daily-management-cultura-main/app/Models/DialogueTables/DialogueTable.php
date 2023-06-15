<?php

namespace App\Models\DialogueTables;

use App\Models\Nac;
use Illuminate\Database\Eloquent\Model;
use App\Models\Asistant;
use App\Models\ControlChangeData;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class DialogueTable extends Model
{
    use SoftDeletes;
    protected $table = "dialogue_tables";
    protected $fillable = [
        "activity_date",
        "start_time",
        "final_hour",
        "consecutive",
        "nac_id",
        "target_workday",
        "theme",
        "schedule_day",
        "workday_description",
        "achievements_difficulties",
        "alerts",
        "place_image1",
        "place_image2",
        'status',
        'reject_message',
        'user_id',
        'user_method_support_id'
    ];
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public function getPublishedAtAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function assistant()
    {
        return $this->belongsToMany(Asistant::class, 'assistant_dialogue_table', 'dialogue_table_id', 'assistant_id');
    }

    public function nac()
    {
        return $this->belongsTo(Nac::class);
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_review_manager_cultural_id', 'id');
    }

    public function method_support()
    {
        return $this->belongsTo(User::class, 'user_method_support_id', 'id');
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
                'profile.document_number' => function ($query) use ($searchValue) {
                    $query->whereHas('user.profile', function ($query) use ($searchValue) {
                        $query->where('document_number', 'like', '%' . $searchValue . '%');
                    });
                },
                'user.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'neighborhood.name' => function ($query) use ($searchValue) {
                    $query->whereHas('neighborhood', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'role' => function ($query) use ($searchValue) {
                    $query->whereHas('user.profile.role', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
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
