<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyData extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'company_name',
        'signature_path',
        'email',
        'phone',
        'address',
        'website',
        'user_id',
        'latitude',
        'longitude'
    ];

    protected $table = 'company_data';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
