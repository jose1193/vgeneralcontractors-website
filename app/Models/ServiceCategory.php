<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ServiceCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        
        'description',
        
        'type',
        'status',
        'user_id'
    ];

    protected $casts = [
        'type' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->slug = Str::slug($model->name);
        });
    }

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
