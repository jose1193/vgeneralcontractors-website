<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\BlogCategory;
use Carbon\Carbon;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class PostController extends Controller
{
    /**
     * Mostrar la página principal del blog con paginación
     */
    public function index()
    {
        try {
            // Get published posts and scheduled posts that have reached their publication date
            $posts = Post::where(function($query) {
                $query->where('post_status', 'published')
                      ->orWhere(function($q) {
                          $q->where('post_status', 'scheduled')
                            ->where('scheduled_at', '<=', now());
                      });
            })
            ->latest()
            ->paginate(9);
        } catch (QueryException $e) {
            // Si post_status no existe, simplemente obtenemos los posts más recientes
            $posts = Post::latest()
                ->paginate(9);
        }
        
        return view('blog.index', compact('posts'));
    }
    
    /**
     * Mostrar un post específico
     */
    public function show($slug)
    {
        $post = Post::where('post_title_slug', $slug)
            ->where(function($query) {
                $query->where('post_status', 'published')
                      ->orWhere(function($q) {
                          $q->where('post_status', 'scheduled')
                            ->where('scheduled_at', '<=', now());
                      });
            })
            ->firstOrFail();
            
        // Obtener posts relacionados (misma categoría)
        $relatedPosts = Post::where('post_status', 'published')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest()
            ->take(3)
            ->get();
            
        // Si no hay suficientes posts en la misma categoría, obtener los más recientes
        if ($relatedPosts->count() < 3) {
            $additionalPosts = Post::where('post_status', 'published')
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id')->toArray())
                ->latest()
                ->take(3 - $relatedPosts->count())
                ->get();
                
            $relatedPosts = $relatedPosts->concat($additionalPosts);
        }
        
        // Formatear la fecha para fácil visualización
        $post->formatted_date = $post->created_at->format('F d, Y');
        
        // Configuración SEO
        SEOTools::setTitle($post->meta_title ?? $post->post_title);
        SEOTools::setDescription($post->meta_description ?? substr(strip_tags($post->post_content), 0, 160));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'article');
        
        // Si hay imagen del post, la añadimos a los metadatos
        if($post->post_image) {
            SEOTools::jsonLd()->addImage($post->post_image);
            SEOTools::opengraph()->addImage($post->post_image);
        }
        
        // Añadir keywords si están disponibles
        if($post->meta_keywords) {
            SEOMeta::addKeyword(explode(',', $post->meta_keywords));
        }
        
        // Metadatos adicionales para artículos
        SEOMeta::addMeta('article:published_time', $post->created_at->toIso8601String(), 'property');
        SEOMeta::addMeta('article:section', $post->category->blog_category_name ?? '', 'property');
        
        return view('blog.show', compact('post', 'relatedPosts'));
    }
    
    /**
     * Mostrar posts por categoría
     */
    public function showPostsByCategory($categorySlug)
    {
        $category = BlogCategory::where('blog_category_name', $categorySlug)
            ->orWhere('id', $categorySlug)
            ->firstOrFail();
            
        $posts = Post::where(function($query) {
                $query->where('post_status', 'published')
                      ->orWhere(function($q) {
                          $q->where('post_status', 'scheduled')
                            ->where('scheduled_at', '<=', now());
                      });
            })
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(9);
            
        // Configuración SEO
        SEOTools::setTitle($category->blog_category_name . ' | V General Contractors Blog');
        SEOTools::setDescription('Articles and insights about ' . $category->blog_category_name . ' from V General Contractors.');
        
        return view('blog.category', compact('posts', 'category'));
    }
    
    /**
     * Búsqueda de posts
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->route('blog.index');
        }
        
        $posts = Post::where(function($query) {
                $query->where('post_status', 'published')
                      ->orWhere(function($q) {
                          $q->where('post_status', 'scheduled')
                            ->where('scheduled_at', '<=', now());
                      });
            })
            ->where(function($q) use ($query) {
                $q->where('post_title', 'like', '%' . $query . '%')
                  ->orWhere('post_content', 'like', '%' . $query . '%')
                  ->orWhere('meta_keywords', 'like', '%' . $query . '%')
                  ->orWhere('meta_description', 'like', '%' . $query . '%');
            })
            ->latest()
            ->paginate(9);
            
        // Configuración SEO
        SEOTools::setTitle("Search results for: {$query} | V General Contractors Blog");
        SEOTools::setDescription("Search results for {$query} in V General Contractors Blog");
        
        return view('blog.search', compact('posts', 'query'));
    }

    /**
     * Mostrar la página principal del blog con paginación
     */
    public function showLatestPosts()
    {
        try {
            // Get published posts and scheduled posts that have reached their publication date
            $posts = Post::where(function($query) {
                $query->where('post_status', 'published')
                      ->orWhere(function($q) {
                          $q->where('post_status', 'scheduled')
                            ->where('scheduled_at', '<=', now());
                      });
            })
            ->latest()
            ->paginate(9);
        } catch (QueryException $e) {
            // Si post_status no existe, simplemente obtenemos los posts más recientes
            $posts = Post::latest()
                ->paginate(9);
        }
        
        return view('blog.index', compact('posts'));
    }

    // Función auxiliar para cálculo de tiempo de lectura
    private function calculateReadingTime($content, $wordsPerMinute = 200)
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / $wordsPerMinute);
        return max(1, $minutes); // Al menos 1 minuto
    }

    public function showPost($postId)
    {
        $post = Post::where('post_title_slug', $postId)->firstOrFail();
         
        // SEO Tools
        SEOTools::setTitle($post->post_title);
        SEOTools::setDescription($post->meta_description);
        SEOTools::opengraph()->setUrl('https://aiosrealestate.com/');
        SEOTools::setCanonical('https://aiosrealestate.com');
        SEOTools::opengraph()->addProperty('type', 'articles');
        SEOTools::jsonLd()->addImage('https://www.example.com/assets/img/logo.png');
        SEOMeta::addKeyword($post->meta_keywords);
        SEOMeta::addMeta('article:published_time', $post->post_date = Carbon::parse($post->created_at)->format('F d, Y'), 'property');

        return view('livewire.show-post', compact('post'))
            ->layout('layouts.app');
    }
} 