<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    @foreach($books as $book)
    <url>
        <loc>{{ route('books.show', $book->slug) }}</loc>
        <lastmod>{{ $book->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        @if($book->cover_url)
        <image:image>
            <image:loc>{{ htmlspecialchars($book->cover_url, ENT_XML1, 'UTF-8') }}</image:loc>
            <image:title>{{ htmlspecialchars($book->title, ENT_XML1, 'UTF-8') }}</image:title>
        </image:image>
        @endif
    </url>
    @endforeach
</urlset>
