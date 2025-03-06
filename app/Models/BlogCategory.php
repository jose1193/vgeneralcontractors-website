<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'blog_category_name', 'blog_category_description','blog_category_image','user_id','status'
    ];

    public function user()
{
return $this->belongsTo(User::class);
}

 
}

