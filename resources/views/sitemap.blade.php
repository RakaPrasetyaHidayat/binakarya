<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">

    <!-- Homepage - Most Important -->
    <url>
        <loc>{{ url("/") }}</loc>
        <lastmod>{{ $homepage_update ? ($homepage_update->updated_at ?? $homepage_update->published_at ?? now())->toAtomString() : now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Core Pages -->
    <url>
        <loc>{{ route("about") }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.95</priority>
    </url>

    <url>
        <loc>{{ route("contact") }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Collections / Index Pages - High Priority -->
    <url>
        <loc>{{ route("books.index") }}</loc>
        <lastmod>{{ $books->first()?->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.95</priority>
    </url>

    <url>
        <loc>{{ route("blog.index") }}</loc>
        <lastmod>{{ $posts->first()?->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.95</priority>
    </url>

    <url>
        <loc>{{ route("services.index") }}</loc>
        <lastmod>{{ $services->first()?->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.95</priority>
    </url>

    <!-- Legal Pages -->
    <url>
        <loc>{{ route("privacy") }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc>{{ route("terms") }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

    <!-- Books - Publication Content (High Value Content) -->
    @foreach($books as $book)
    <url>
        <loc>{{ route("books.show", $book->slug) }}</loc>
        <lastmod>{{ $book->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.85</priority>
        @if($book->cover_image || $book->cover_url)
        <image:image>
            <image:loc>{{ $book->cover_image ? asset('storage/' . $book->cover_image) : ($book->cover_url ?? '') }}</image:loc>
            @if($book->title)
            <image:title>{{ htmlspecialchars($book->title, ENT_XML1, 'UTF-8') }}</image:title>
            @endif
        </image:image>
        @endif
    </url>
    @endforeach

    <!-- Blog Articles with News Metadata - Dynamic Priority Based on Age -->
    @foreach($posts as $post)
    <url>
        <loc>{{ route("blog.show", $post->slug) }}</loc>
        <lastmod>{{ ($post->updated_at ?? $post->published_at)->toAtomString() }}</lastmod>
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
                    $daysOld <= 30 => '0.9',
                    $daysOld <= 90 => '0.8',
                    default => '0.7'
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
        @if($post->published_at && $post->published_at->diffInDays(now()) <= 7)
        <news:news>
            <news:publication>
                <news:name>{{ htmlspecialchars(config('app.name', 'Bina Karya Cendekia'), ENT_XML1, 'UTF-8') }}</news:name>
                <news:language>id</news:language>
            </news:publication>
            <news:publication_date>{{ $post->published_at->toAtomString() }}</news:publication_date>
            <news:title>{{ htmlspecialchars($post->title, ENT_XML1, 'UTF-8') }}</news:title>
        </news:news>
        @endif
    </url>
    @endforeach

    <!-- Services - Core Offerings -->
    @foreach($services as $service)
    <url>
        <loc>{{ route("services.show", $service->slug) }}</loc>
        <lastmod>{{ $service->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.85</priority>
    </url>
    @endforeach

    <!-- Custom Pages -->
    @foreach($pages as $page)
    <url>
        <loc>{{ route("pages.show", $page->slug) }}</loc>
        <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.75</priority>
    </url>
    @endforeach

    <!-- Post Categories (if available) -->
    @if($categories?->count())
    @foreach($categories as $category)
    <url>
        <loc>{{ url('/blog?category=' . $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @endif

</urlset>
