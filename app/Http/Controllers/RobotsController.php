<?php

namespace App\Http\Controllers;

class RobotsController extends Controller
{
    public function index()
    {
        $robots = "# Robot Rules\n";
        $robots .= "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /cendikiaByRidwanullah/\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /*?*=\n";
        $robots .= "Disallow: /search\n";
        $robots .= "\n";
        $robots .= "# Google\n";
        $robots .= "User-agent: Googlebot\n";
        $robots .= "Allow: /\n";
        $robots .= "\n";
        $robots .= "# Bing\n";
        $robots .= "User-agent: Bingbot\n";
        $robots .= "Allow: /\n";
        $robots .= "\n";
        $robots .= "# Bad bots\n";
        $robots .= "User-agent: AhrefsBot\n";
        $robots .= "Disallow: /\n";
        $robots .= "User-agent: SemrushBot\n";
        $robots .= "Disallow: /\n";
        $robots .= "\n";
        $robots .= "# Sitemap\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($robots, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
