<?php

namespace App\Models;

use App\Traits\FunctionGeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MethodologicalSheetsOne extends Model
{
    use HasFactory;
    use FunctionGeneralTrait;

    protected $table = 'methodological_sheets_one';

    protected $fillable = [
        'consecutive',
        'datasheet',
        'semillero_name',
        'date_ini',
        'date_fin',
        'filter_level',
        'worked_expertise',
        'characteristics_process',
        'objective_process',
        'comments',
        'status',
        'group_id',
        'cultural_right_id',
        'orientation_id',
        'valor_id',
        'value',
        'lineament_id',
        'created_by'
    ];


    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function cultural_right(){
        return $this->belongsTo(CulturalRight::class, 'cultural_right_id', 'id');
    }

    public function orientation(){
        return $this->belongsTo(Orientation::class, 'orientation_id', 'id');
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
                'semillero_name' => function ($query) use ($searchValue) {
                    $query->where('semillero_name', 'like', '%' . $searchValue . '%');
                },
                'lineament_id' => function ($query) use ($searchValue) {
                    $query->where('lineament_id', 'like', '%' . $searchValue . '%');
                },
                'orientation_id' => function ($query) use ($searchValue) {
                    $query->where('orientation_id', 'like', '%' . $searchValue . '%');
                },
                'created_by.name' => function ($query) use ($searchValue) {
                    $query->whereHas('created_by', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'group.name' => function ($query) use ($searchValue) {
                    $query->whereHas('group', function ($query) use ($searchValue) {
                        $query->where('groups.name', 'like', '%' . $searchValue . '%');
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
