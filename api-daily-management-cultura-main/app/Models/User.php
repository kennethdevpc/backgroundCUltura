<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Inscriptions\Attendant;
use App\Models\Inscriptions\Beneficiary;
use App\Models\Inscriptions\Inscription;
use App\Models\Inscriptions\Pec;
use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UserTrait,ImageTrait,SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'document_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    /**
     * Relationship many to many
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Relationship one to one
     */
    public function profile()
    {
        return $this->hasOne(Profile::class,'user_id','id');
    }
   /**
     * Relationship one to many
     */
    public function inscriptions(){
        return $this->hasMany(Inscription::class,'created_by','id');
    }
    public function notifications_received(){
        return $this->hasMany(Notification::class, 'receptor', 'id');
    }
    public function notifications_sended(){
        return $this->hasMany(Notification::class, 'sender', 'id');
    }
   /**
     * Relationship morp
     */
    public function user_review_form(){
		return $this->morphMany(UserReviewForm::class,'user_review_form');
	}
    public function getPublishedAtAttribute(){
        return $this->created_at->format('d/m/Y');
    }
    public function control_data(){
		return $this->morphMany(ControlChangeData::class,'data_model');
	}

    public function loginaccess()
    {
        return $this->hasMany(AccessLogin::class, 'user_id', 'id');
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
                'name' => function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                },
                'email' => function ($query) use ($searchValue) {
                    $query->where('email', 'like', '%' . $searchValue . '%');
                },
                'roles' => function ($query) use ($searchValue) {
                    $query->whereHas('roles', function ($query) use ($searchValue) {
                        $query->where('roles.slug', 'like', '%' . $searchValue . '%');
                    });
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
        if (request()->filled('user_status_criteria')) {
            $status = request('user_status_criteria');
            $query->where('status', $status);
        }
    }

    public function pecs()
    {
        return $this->hasMany(Pec::class, 'created_by', 'id');
    }

    public function binnacles()
    {
        return $this->hasMany(Binnacle::class, 'created_by', 'id')->where("type","other");
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class, 'created_by', 'id');
    }

    public function activation_culturals()
    {
        return $this->hasMany(Binnacle::class, 'created_by', 'id')->where("type","manager");
    }

  /*  public function attendants()
    {
        return $this->hasMany(Attendant::class, 'created_by', 'id');
    }*/

    public function pedagogicals()
    {
        return $this->hasMany(Pedagogical::class, 'created_by', 'id');
    }

    public function pollDesertion()
    {
        return $this->hasOne(PollDesertion::class,'user_id','id');
    }

    public function poll()
    {
        return $this->hasOne(Poll::class,'user_id','id');
    }

    public function activation_culturalsV2()
    {
        return $this->hasMany(BinnacleCulturalShow::class, 'created_by');
    }
}
