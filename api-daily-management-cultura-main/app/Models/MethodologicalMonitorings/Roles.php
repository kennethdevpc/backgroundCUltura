<?php

namespace App\Models\MethodologicalMonitorings;

use App\Models\MethodologicalMonitoring;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Roles extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "methodological_monitorings_roles";

    protected $fillable = [
        "monitoring_id",
        "role_id",
    ];

    protected $guarded = [
        'created_at', 'updated_at'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function methodological_monitoring()
    {
        $this->belongsTo(MethodologicalMonitoring::class, 'monitoring_id', 'id');
    }

    public function role()
    {
        $this->belongsTo('roles', 'role_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }
}
