<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url("/") }}</loc>
        <lastmod>{{ $lastStaticUpdate }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ route("about") }}</loc>
        <lastmod>{{ $lastStaticUpdate }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route("contact") }}</loc>
        <lastmod>{{ $lastStaticUpdate }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>{{ route("privacy") }}</loc>
        <lastmod>{{ $lastStaticUpdate }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <url>
        <loc>{{ route("terms") }}</loc>
        <lastmod>{{ $lastStaticUpdate }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
</urlset>
