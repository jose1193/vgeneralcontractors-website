<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>'; ?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ config('app.name') }} Blog</title>
        <link>{{ route('blog.index') }}</link>
        <description>Latest roofing news, tips, and industry insights from {{ config('app.name') }}</description>
        <language>en-us</language>
        <pubDate>{{ now()->toRssString() }}</pubDate>
        <atom:link href="{{ route('feeds.rss') }}" rel="self" type="application/rss+xml" />

        @foreach ($posts as $post)
            <item>
                <title>{{ $post->post_title }}</title>
                <link>{{ route('blog.show', $post->post_title_slug) }}</link>
                <description>
                    <![CDATA[{!! Str::limit(strip_tags($post->post_content), 300) !!}]]>
                </description>
                <author>{{ $post->user->email ?? config('mail.from.address') }}
                    ({{ $post->user->name ?? config('app.name') }})
                </author>
                <pubDate>{{ $post->created_at->toRssString() }}</pubDate>
                <guid>{{ route('blog.show', $post->post_title_slug) }}</guid>
                <category>{{ $post->category->blog_category_name ?? 'Blog' }}</category>
                @if ($post->post_image)
                    <enclosure url="{{ $post->post_image }}" length="0" type="image/jpeg" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
