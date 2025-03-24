<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;

class FeedController extends Controller
{
    public function rss()
    {
        try {
            // Primero intentamos con post_status si existe
            $posts = Post::where('post_status', 'published')
                ->latest()
                ->take(20)
                ->get();
        } catch (QueryException $e) {
            // Si post_status no existe, simplemente obtenemos los posts más recientes
            $posts = Post::latest()
                ->take(20)
                ->get();
        }

        $content = view('feeds.rss', compact('posts'));
        
        return (new Response($content))
            ->header('Content-Type', 'application/xml');
    }
} 