<?php

namespace App\Models;

use App\Models\Inscriptions\Beneficiary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinnacleBeneficiary extends Model
{
    protected $table = 'beneficiary_binnacle';

    public function binnacle()
    {
        return $this->belongsTo(Binnacle::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}
