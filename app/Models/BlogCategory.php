<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'blog_category_name',
        'blog_category_description',
        'blog_category_image',
        'user_id',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

