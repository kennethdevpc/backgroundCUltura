<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class ActivityLog extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "activity_log";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    protected $fillable = ['description', 'subject_id', 'subject_type'];

    public function subject() // Traer las relaciones del subjeto no importa el modelo
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }

}
