<?php

namespace App\Models;

use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\Pec;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CulturalSeedbed extends Model
{
    use SoftDeletes;
    use HasFactory, LogsActivity;

    protected $table = "cultural_seedbeds";

    protected $fillable = [
        'date',
        'datasheet',
        'consecutive',
        'pec_id',
        'datasheet_planning_id',
        'filter_level',
        'quantity_members',
        'level_domain_description',
        'objective_process',
        'cultural_right_id',
        'lineament_id',
        'orientation_id',
        'values',
        'artistic_expertise',
        'observations',
        'development_activity_image',
        'evidence_participation_image',
        'group_id',
        'audited',
        'reject_message',
        'created_by',
        'datasheet'
    ];

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function getPublishedAtAttribute(){
        return $this->created_at->format('d/m/Y');
    }
	public function user(){
		return $this->belongsTo(User::class, 'created_by', 'id');
	}
    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
	}

    public function datasheetPlanning(){
        return $this->belongsTo(MethodologicalSheetsOne::class, 'datasheet_planning_id', 'id');
    }
    public function cultural_right(){
        return $this->belongsTo(CulturalRight::class, 'cultural_right_id', 'id');
    }

    public function orientation(){
        return $this->belongsTo(Orientation::class, 'orientation_id', 'id');
    }

    public function group(){
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function beneficiary()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_cultural_seedbed', 'cultural_seedbed_id', 'beneficiary_id');
    }

    public function pec(){
        return $this->belongsTo(Pec::class, 'pec_id', 'id');
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
                'date' => function ($query) use ($searchValue) {
                    $query->where('date', 'like', '%' . $searchValue . '%');
                },
                'datasheet' => function ($query) use ($searchValue) {
                    $query->where('datasheet', 'like', '%' . $searchValue . '%');
                },
                'quantity_members' => function ($query) use ($searchValue) {
                    $query->where('quantity_members', 'like', '%' . $searchValue . '%');
                },
                'created_by.name' => function ($query) use ($searchValue) {
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
