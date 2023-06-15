<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ControlChangeData extends Model
{
    use HasFactory;
    protected $table = "data_models";
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'action',
        'data_original',
        'data_change',
    ];

    protected $casts = [
        'data_original' => 'array',
        'data_change' => 'array',
    ];

    public function data_model()
    {
        return $this->morphTo();
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
                'id' => function ($query) use ($searchValue) {
                    $query->where('id', 'like', '%' . $searchValue . '%');
                },
                'user.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('users.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'data_model_type' => function ($query) use ($searchValue) {
                    $query->where('data_model_type', 'like', '%' . $searchValue . '%');
                },
                'action' => function ($query) use ($searchValue) {
                    $query->where('action', 'like', '%' . $searchValue . '%');
                },
                'data_model_id' => function ($query) use ($searchValue) {
                    $query->where('data_model_id', 'like', '%' . $searchValue . '%');
                },
                'created_at_date' => function ($query) use ($searchValue) {
                    $query->where('created_at_date', 'like', '%' . $searchValue . '%');
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
