<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\ControlChangeData;
use App\Models\Inscriptions\Pec;
use Illuminate\Support\Facades\DB;

class StrengtheningSupervisionManagers extends Model
{
    use ImageTrait, LogsActivity, SoftDeletes;

    protected $table = "strengthening_super_mangs";

    protected $fillable = [
        'consecutive',
        'revision_date',
        'address',
        'methodological_instruction_reached_target',
        'frequency',
        'binnacle_registered_plataform',
        'description',
        'start_time',
        'final_time',
        'comments',
        'development_activity_image',
        'evidence_participation_image',
        'created_by',
        'super_coordinator_id',
        'status',
        'reject_message',
        'nac_id',
        'rol_id',
        'user_full_name_id',
        'methodological_instruction_id',
    ];

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public function getPublishedAtAttribute(){
        return $this->created_at->format('d/m/Y');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
	}


    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user_manager()
    {
        return $this->belongsTo(User::class, 'user_associate_id','id');
    }

    public function nac(){
        return $this->belongsTo(Nac::class, 'nac_id', 'id');
    }

    public function methodological_instruction(){
        return $this->belongsTo(MethodologicalInstructionModel::class)->select(DB::raw('CONCAT(consecutive,"-",activity_date,"-",start_time,"-",final_hour) AS name'));
    }

    public function superCoordinator()
    {
        return $this->belongsTo(User::class, 'super_coordinator_id', 'id');
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
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('nacs.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'methodological_instruction.name' => function ($query) use ($searchValue) {
                    $query->whereHas('methodological_instruction', function ($query) use ($searchValue) {
                        $query->where('methodological_instructions.consecutive', 'like', '%' . $searchValue . '%')
                            ->orWhere('methodological_instructions.activity_date', 'like', '%'. $searchValue. '%')
                            ->orWhere('methodological_instructions.start_time', 'like', '%'. $searchValue. '%')
                            ->orWhere('methodological_instructions.final_hour', 'like', '%'. $searchValue. '%');
                    });
                },
                'address' => function ($query) use ($searchValue) {
                    $query->where('address', 'like', '%' . $searchValue . '%');
                },
                'final_hour' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue.' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('final_hour', 'like', '%' . $modifiedSearchValue . '%');
                },
                'start_time' => function ($query) use ($searchValue) {
                    $modifiedSearchValue = date('H:i', strtotime($searchValue.' +12 hours')); // Convertir a formato de 24 horas y sumar 12 horas
                    $query->where('start_time', 'like', '%' . $modifiedSearchValue . '%');
                },
                'user_manager.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user_manager', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
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
