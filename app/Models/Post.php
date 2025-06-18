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
        'post_status',
        'scheduled_at'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // Agrega atributo virtual para post_status
    protected $attributes = [
        'post_status' => 'published',
    ];

    // Accesorio para siempre devolver el estado correcto
    public function getPostStatusAttribute($value)
    {
        // Si no hay valor en la BD, devuelve 'published' por defecto
        return $value ?: 'published';
    }

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
     * Get the post content with decoded HTML entities and cleaned spacing.
     */
    public function getPostContentAttribute($value)
    {
        // Decodificar entidades HTML para mostrar correctamente los caracteres especiales
        $content = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
        
        // Limpiar espacios excesivos y br tags múltiples
        $content = preg_replace('/(<br\s*\/?>){3,}/', '<br><br>', $content); // Máximo 2 br consecutivos
        $content = preg_replace('/(<p[^>]*>\s*&nbsp;\s*<\/p>)/', '', $content); // Remover párrafos con solo &nbsp;
        $content = preg_replace('/(<p[^>]*>\s*<\/p>)/', '', $content); // Remover párrafos vacíos
        $content = preg_replace('/\s{3,}/', ' ', $content); // Reducir espacios múltiples a uno solo
        
        return $content;
    }

    /**
     * Get the SEO data for the post.
     */
    public function seo()
    {
        return $this->morphOne(Seo::class, 'model');
    }
} 