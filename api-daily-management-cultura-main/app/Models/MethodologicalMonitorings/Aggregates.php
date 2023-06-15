<?php

namespace App\Models\MethodologicalMonitorings;

use App\Models\MethodologicalMonitoring;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Aggregates extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "methodological_monitorings_aggregates";

    protected $fillable = [
        "monitoring_id",
        "aggregate_id",
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

    public function aggregate()
    {
        $this->belongsTo('users', 'aggregate_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function assistant(){
        return $this->belongsTo(Asistant::class);
    }

    public function control_data()
    {
        return $this->morphMany(ControlChangeData::class, 'data_model');
    }
}
