<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('sitemap.pages') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap.books') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap.blog') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap.services') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ route('sitemap.news') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
</sitemapindex>
