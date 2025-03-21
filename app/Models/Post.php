<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'post_title',
        'post_content',
        'post_image',
        'meta_description',
        'meta_title',
        'meta_keywords',
        'post_title_slug',
        'category_id',
        'user_id',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the post belongs to.
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Get the SEO data for the post.
     */
    public function seo()
    {
        return $this->morphOne(Seo::class, 'model');
    }
} 