<?php

namespace App\Models;

use App\Traits\FunctionGeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MethodologicalStrengthening extends Model
{
    use HasFactory, SoftDeletes, FunctionGeneralTrait;
    protected $table = 'methodological_strengthenings';
    protected $guarded = [
        'created_at', 'updated_at'
    ];
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    public function nac()
    {
        return $this->belongsTo(Nac::class,'nac_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function metho_coordinator()
    {
        return $this->belongsTo(User::class, 'metho_coordinator_id', 'id');
    }
    public function user_rol()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function assistants()
    {
        return $this->belongsToMany(Profile::class, 'methodological_strengthening_user', 'metho_strengthening_id', 'assistant_id')
        ->select('user_id as id', 'document_number', 'contractor_full_name as monitor_fullname','role_id','nac_id');
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
                'nac.name' => function ($query) use ($searchValue) {
                    $query->whereHas('nac', function ($query) use ($searchValue) {
                        $query->where('nacs.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'cultural_right.name' => function ($query) use ($searchValue) {
                    $query->whereHas('cultural_right', function ($query) use ($searchValue) {
                        $query->where('cultural_rights.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'orientation.name' => function ($query) use ($searchValue) {
                    $query->whereHas('orientation', function ($query) use ($searchValue) {
                        $query->where('orientations.name', 'like', '%' . $searchValue . '%');
                    });
                },
                'lineament' => function ($query) use ($searchValue) {
                    $query->where('lineament_id', 'like', '%' . $this->dataFilter($searchValue, 'lineaments') . '%');
                },
                'value_name' => function ($query) use ($searchValue) {
                    $query->where('value', 'like', '%' . $this->dataFilter($searchValue, 'values') . '%');
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
