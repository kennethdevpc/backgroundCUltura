<?php

namespace App\Models\Inscriptions;

use App\Models\User;

use App\Models\Inscriptions\Beneficiary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\ControlChangeData;
use App\Models\Group;
use App\Models\Nac;
use PhpParser\Node\Expr\FuncCall;

class Inscription extends Model
{
    use HasFactory, LogsActivity;
    use SoftDeletes;

    protected $table = "inscriptions";

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'beneficiary_id', 'user_id'
    ];
    public function getPublishedAtAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }
    // public function health_data(){
    // 	return $this->belongsTo(HealthData::class,'health_data_id','id');
    // }
    // public function sociodemographic_characterization(){
    // 	return $this->belongsTo(SociodemographicCharacterization::class,'sociodemographic_characterization_id','id');
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function benefiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
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
                'benefiary.full_name' => function ($query) use ($searchValue) {
                    $query->whereHas('benefiary', function ($query) use ($searchValue) {
                        $query->where('full_name', 'like', '%' . $searchValue . '%');
                    });
                },
                'benefiary.document_number' => function ($query) use ($searchValue) {
                    $query->whereHas('benefiary', function ($query) use ($searchValue) {
                        $query->where('document_number', 'like', '%' . $searchValue . '%');
                    });
                },
                'user.name' => function ($query) use ($searchValue) {
                    $query->whereHas('user', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'role' => function ($query) use ($searchValue) {
                    $query->whereHas('user.profile.role', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                },
                'attendant.document_number' => function ($query) use ($searchValue) {
                    $query->whereHas('benefiary.attendant', function ($query) use ($searchValue) {
                        $query->where('document_number', 'like', '%' . $searchValue . '%');
                    });
                },
                'attendant.full_name' => function ($query) use ($searchValue) {
                    $query->whereHas('benefiary.attendant', function ($query) use ($searchValue) {
                        $query->where('full_name', 'like', '%' . $searchValue . '%');
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
