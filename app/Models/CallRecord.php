<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'call_id',
        'agent_id',
        'from_number',
        'to_number',
        'direction',
        'call_status',
        'start_timestamp',
        'end_timestamp',
        'duration_ms',
        'transcript',
        'recording_url',
        'call_summary',
        'user_sentiment',
        'call_successful',
        'metadata',
    ];

    protected $casts = [
        'start_timestamp' => 'datetime',
        'end_timestamp' => 'datetime',
        'metadata' => 'array',
        'call_successful' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 