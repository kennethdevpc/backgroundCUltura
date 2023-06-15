<?php

namespace App\Models\Inscriptions;

use App\Models\Nac;
use App\Models\Neighborhood;
use App\Models\ControlChangeData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ImageTrait;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pec extends Model
{
    use HasFactory, SoftDeletes, ImageTrait, LogsActivity;
    protected $table = "pecs";
    protected $fillable = [
        'nac_id',
        'user_id',
        'consecutive',
        'neighborhood_id',
        'place',
        'place_address',
        'activity_date',
        'start_time',
        'final_hour',
        'place_type',
        'place_description',
        'place_image1',
        'place_image2',
        'user_review_manager_cultural_id',
        'user_review_instructor_leader_id',
    ];

    public function getPublishedAtAttribute(){
        return $this->created_at->format('d/m/Y');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function nac()
    {
        return $this->belongsTo(Nac::class, 'nac_id', 'id');
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class, 'neighborhood_id', 'id');
    }
    public function pecsBeneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class)->select('beneficiaries.id','full_name','document_number as nuip');
    }
    public function pecsbeneficiariesExport()
    {
        return $this->belongsToMany(Beneficiary::class);
    }

    public function get_activity_date(){
        return Carbon::parse($this->activity_date)->format('d/m/Y');
    }
//   public function beneficiarypecs()
//   {
//       return $this->hasMany(BeneficiaryPec::class,'pec_id','id');
//   }
    /*Relación polimorfica */
    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
	}
    public function user(){
		return $this->belongsTo(User::class,'created_by','id');
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
