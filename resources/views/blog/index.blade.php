@extends('layouts.blog')

@section('content')
    <div class="pt-16 lg:pt-20">
        <!-- Header Section with Search -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-28 blog-hero-section">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Our Blog</h1>
                    <p class="text-lg md:text-xl text-gray-300 mb-8">
                        Stay informed with the latest roofing trends, industry insights, and home improvement tips.
                    </p>

                    <!-- Search Form -->
                    <form action="{{ route('blog.search') }}" method="GET" class="max-w-xl mx-auto">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" name="q" placeholder="Search articles..."
                                class="flex-grow px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition-shadow text-gray-800">
                            <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Search
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Blog Posts Grid -->
        <div class="bg-gray-50 py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($posts as $post)
                        <article
                            class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-1">
                            <a href="{{ route('blog.show', $post->post_title_slug) }}" class="block">
                                <div class="aspect-[16/9] overflow-hidden bg-gray-200">
                                    @if ($post->post_image)
                                        <img src="{{ $post->post_image }}" alt="{{ $post->post_title }}"
                                            class="w-full h-full object-cover object-center transition duration-500 hover:scale-105">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-r from-yellow-400 to-yellow-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white opacity-75" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-1M19 20H9a2 2 0 01-2-2v-5">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="p-6">
                                @if ($post->category)
                                    <a href="{{ route('blog.category', $post->category->blog_category_name) }}"
                                        class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium mb-3 hover:bg-yellow-200 transition">
                                        {{ $post->category->blog_category_name }}
                                    </a>
                                @endif

                                <a href="{{ route('blog.show', $post->post_title_slug) }}" class="block">
                                    <h2
                                        class="text-xl font-bold text-gray-900 mb-3 hover:text-yellow-600 transition line-clamp-2">
                                        {{ $post->post_title }}
                                    </h2>
                                </a>

                                <div class="text-gray-600 mb-4 line-clamp-3">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($post->post_content), 150) !!}
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-600 font-medium text-sm">
                                                {{ strtoupper(substr($post->user->name ?? 'A', 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-700">{{ $post->user->name ?? 'Admin' }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</span>
                                </div>

                                <a href="{{ route('blog.show', $post->post_title_slug) }}"
                                    class="inline-flex items-center mt-4 text-yellow-600 hover:text-yellow-700">
                                    Read More
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-3 text-center py-16">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-700 mb-2">No posts available</h3>
                            <p class="text-gray-500 mb-6">We're working on creating valuable content for you.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Add this to fix the hero section position */
            .blog-hero-section {
                margin-top: -5rem;

                /* Adjust to compensate for navbar */
            }
        </style>
    @endpush
@endsection
