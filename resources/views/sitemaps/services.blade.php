<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc>{{ route("services.index") }}</loc>
        <lastmod>{{ $services->first()?->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>

    @foreach($services as $service)
    <url>
        <loc>{{ route("services.show", $service->slug) }}</loc>
        <lastmod>{{ ($service->updated_at ?? $service->created_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.75</priority>
    </url>
    @endforeach
</urlset>
