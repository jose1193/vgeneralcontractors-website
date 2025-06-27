<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllianceCompany extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'alliance_company_name',
        'email',
        'phone',
        'address',
        'website',
        'user_id',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function claims()
    {
        return $this->belongsToMany(Claim::class, 'claim_alliances')
                    ->withPivot('assignment_date')
                    ->withTimestamps();
    }

    public function documentTemplateAlliance()
    {
        return $this->hasMany(DocumentTemplateAlliance::class, 'uploaded_by');
    }

    public function prohibitedInsurances()
    {
        return $this->belongsToMany(InsuranceCompany::class, 'alliance_prohibited_insurances', 'insurance_company_id', 'alliance_company_id');
    }

    public function ClaimAgreementAlliance()
    {
        return $this->hasMany(ClaimAgreementAlliance::class, 'alliance_company_id');
    }
}
