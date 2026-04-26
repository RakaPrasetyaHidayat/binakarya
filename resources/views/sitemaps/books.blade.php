<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <url>
        <loc>{{ route("books.index") }}</loc>
        <lastmod>{{ $books->first()?->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>

    @foreach($books as $book)
    <url>
        <loc>{{ route("books.show", $book->slug) }}</loc>
        <lastmod>{{ ($book->updated_at ?? $book->created_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.75</priority>
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
</urlset>
