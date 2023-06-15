<?php

namespace App\Models;

use App\Models\Inscriptions\Beneficiary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinnacleCulturalSeedbedBeneficiary extends Model
{
    protected $table = 'beneficiary_cultural_seedbed';

    public function binnacle()
    {
        return $this->belongsTo(CulturalSeedbed::class,'cultural_seedbed_id','id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class,'beneficiary_id','id');
    }
}
