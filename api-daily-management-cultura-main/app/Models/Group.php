<?php

namespace App\Models;

use App\Models\Inscriptions\Beneficiary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $table = "groups";
    protected $guarded = [
        'created_at', 'updated_at'
    ];

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class,'group_id','id')->select('beneficiaries.id','beneficiaries.full_name','beneficiaries.document_number as nuip');
    }
    public function getPublishedAtAttribute(){
        return $this->created_at->format('d/m/Y');
    }
    public function user(){
		return $this->belongsTo(User::class);
	}
    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
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
