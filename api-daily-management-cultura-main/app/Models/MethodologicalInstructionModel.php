<?php

namespace App\Models;

use App\Models\Asistant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MethodologicalInstructionModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'methodological_instructions';
    protected $fillable = [
        'place',
        'activity_date',
        'consecutive',
        'start_time',
        'final_hour',
        'expertise_id',
        'nac_id',
        'created_by',
        'goals_met',
        'explanation',
        'pedagogical_comments',
        'technical_practical_comments',
        'methodological_comments',
        'others_observations',
        'place_file1',
        'place_file2',
        'status',
        'reject_message',
        'user_method_support_id'
    ];

    public function assistants()
    {
        return $this->belongsToMany(User::class, 'assistant_methodological_instruction', 'm_i_id', 'assistant_id');
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function expertise()
    {
        return $this->belongsTo(Expertise::class, 'expertise_id', 'id');
    }

    public function methodologicalInstructions()
    {
        return $this->belongsTo(StrengtheningSupervisionManagers::class, 'expertise_id', 'id');
    }

    public function nac()
    {
        return $this->belongsTo(Nac::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
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
                'expertise.name' => function ($query) use ($searchValue) {
                    $query->whereHas('expertise', function ($query) use ($searchValue) {
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
