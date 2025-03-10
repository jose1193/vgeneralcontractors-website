<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'phone',
        'email',
        'city',
        'zipcode',
        'insurance',
        'message',
        'sms_consent'
    ];

    protected $casts = [
        'sms_consent' => 'boolean'
    ];
} 