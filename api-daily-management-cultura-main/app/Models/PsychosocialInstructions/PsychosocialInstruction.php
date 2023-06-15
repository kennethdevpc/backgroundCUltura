<?php

namespace App\Models\PsychosocialInstructions;

use App\Models\User;
use App\Models\Asistant;
use App\Models\Nac;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\ControlChangeData;

class PsychosocialInstruction extends Model
{
    use HasFactory, ImageTrait, LogsActivity;
    use SoftDeletes;

    protected $table = "psychosocial_instructions";

    protected $fillable = [
        'consecutive',
        'activity_date',
        'start_time',
        'final_hour',
        'nac_id',
        'objective_day',
        'themes_day',
        'evidence_participation_image',
        'development_activity_image',
        'user_id',
        'user_psychoso_coordinator_id',
        'development_themes',
        'conclusions_reflections_commitments',
        'report_followup_alerts'
    ];

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

    public function nac()
    {
        return $this->belongsTo(Nac::class, 'nac_id');
    }

    public function assistants()
    {
        return $this->belongsToMany(Asistant::class, 'assistant_psicosocial_instructions', 'psycho_inst_id', 'assistant_id');
    }
    public function assistanceMonitors()
    {
        return $this->belongsToMany(User::class, 'monitor_psicosocial_instructions', 'psycho_inst_id', 'monitor_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function scopeFilterByUrl($query)
    {
        $this->searchFilter($query);

        $this->dateFilter($query);

        return $query;
    }

    private function searchFilter($query)
    {
        if (request()->filled('search_field') && request()->filled('search_value')) {
            $searchField = request('search_field');
            $searchValue = request('search_value');

            $validSearchFields = [
                'name' => function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                },
                'user' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'role' => function ($query) use ($searchValue) {
                    $query->whereHas('user.roles', function ($query) use ($searchValue) {
                        $query->where('roles.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'final_hour' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue.' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('final_hour', 'like', '%' . $modifiedSearchValue . '%');
                },
                'start_time' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue.' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
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
}
