<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <url>
        <loc>{{ route("blog.index") }}</loc>
        <lastmod>{{ $posts->first()?->updated_at?->toAtomString() ?? $posts->first()?->published_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.85</priority>
    </url>

    @foreach($posts as $post)
    <url>
        <loc>{{ route("blog.show", $post->slug) }}</loc>
        <lastmod>{{ ($post->updated_at ?? $post->published_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>
            @php
                $daysOld = $post->published_at ? $post->published_at->diffInDays(now()) : 999;
                echo match(true) {
                    $daysOld <= 7 => 'weekly',
                    $daysOld <= 30 => 'weekly',
                    $daysOld <= 365 => 'monthly',
                    default => 'yearly'
                };
            @endphp
        </changefreq>
        <priority>
            @php
                echo match(true) {
                    $daysOld <= 30 => '0.8',
                    $daysOld <= 90 => '0.7',
                    default => '0.6'
                };
            @endphp
        </priority>
        @if($post->featured_image || $post->featured_image_url)
        <image:image>
            <image:loc>{{ $post->featured_image ? asset('storage/' . $post->featured_image) : ($post->featured_image_url ?? '') }}</image:loc>
            @if($post->title)
            <image:title>{{ htmlspecialchars($post->title, ENT_XML1, 'UTF-8') }}</image:title>
            @endif
        </image:image>
        @endif
    </url>
    @endforeach
</urlset>
