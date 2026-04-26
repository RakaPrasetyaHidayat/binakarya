<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">

    @foreach($posts as $post)
    <url>
        <loc>{{ route("blog.show", $post->slug) }}</loc>
        <lastmod>{{ ($post->updated_at ?? $post->published_at ?? now())->toAtomString() }}</lastmod>
        @if($post->published_at)
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
</urlset>
