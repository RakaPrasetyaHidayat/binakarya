<?php
/**
 * Dynamic Sitemap Generator for Google Search Console
 * URL: /sitemap.php
 * 
 * This file generates a comprehensive XML sitemap that includes:
 * - Static pages (homepage, about, contact, privacy, terms)
 * - Dynamic content (blog posts, books, services, pages)
 * - Proper priority and change frequency
 * - Last modified dates for better indexing
 */

// Set headers for XML sitemap
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=86400');
header('X-Robots-Tag: index, follow');

// Get the base URL from environment or construct it
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseUrl = $protocol . $_SERVER['HTTP_HOST'];

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
echo '         xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' . "\n";
echo '         xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

// Function to add URL entry
function addUrl($loc, $lastmod = null, $changefreq = 'weekly', $priority = '0.8', $images = []) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
    
    if ($lastmod) {
        echo "    <lastmod>" . date('c', strtotime($lastmod)) . "</lastmod>\n";
    }
    
    echo "    <changefreq>" . htmlspecialchars($changefreq) . "</changefreq>\n";
    echo "    <priority>" . htmlspecialchars($priority) . "</priority>\n";
    
    // Add images if provided
    if (!empty($images)) {
        foreach ($images as $image) {
            echo "    <image:image>\n";
            echo "      <image:loc>" . htmlspecialchars($image, ENT_XML1, 'UTF-8') . "</image:loc>\n";
            echo "    </image:image>\n";
        }
    }
    
    echo "  </url>\n";
}

// ============================================
// STATIC PAGES (High Priority)
// ============================================

// Homepage
addUrl($baseUrl . '/', date('c'), 'daily', '1.0');

// Main Pages
addUrl($baseUrl . '/tentang-kami', date('c'), 'monthly', '0.9');
addUrl($baseUrl . '/layanan', date('c'), 'weekly', '0.9');
addUrl($baseUrl . '/blog', date('c'), 'daily', '0.9');
addUrl($baseUrl . '/buku', date('c'), 'weekly', '0.9');
addUrl($baseUrl . '/kontak', date('c'), 'monthly', '0.8');

// Legal Pages
addUrl($baseUrl . '/kebijakan-privasi', date('c'), 'yearly', '0.5');
addUrl($baseUrl . '/syarat-ketentuan', date('c'), 'yearly', '0.5');

// ============================================
// DYNAMIC CONTENT - SERVICES
// ============================================

try {
    // Connect to database to fetch services
    $dbPath = __DIR__ . '/../database/database.sqlite';
    
    if (file_exists($dbPath)) {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        
        // Fetch active services
        $servicesQuery = $pdo->query("
            SELECT id, slug, updated_at, created_at 
            FROM services 
            WHERE is_active = 1 
            ORDER BY updated_at DESC
        ");
        
        if ($servicesQuery) {
            while ($service = $servicesQuery->fetch(PDO::FETCH_ASSOC)) {
                $lastmod = $service['updated_at'] ?: $service['created_at'];
                addUrl(
                    $baseUrl . '/layanan/' . htmlspecialchars($service['slug']),
                    $lastmod,
                    'weekly',
                    '0.8'
                );
            }
        }
        
        // ============================================
        // DYNAMIC CONTENT - BLOG POSTS
        // ============================================
        
        $postsQuery = $pdo->query("
            SELECT id, slug, updated_at, published_at, featured_image_url, title 
            FROM posts 
            WHERE is_published = 1 
            ORDER BY published_at DESC 
            LIMIT 1000
        ");
        
        if ($postsQuery) {
            while ($post = $postsQuery->fetch(PDO::FETCH_ASSOC)) {
                $images = [];
                if (!empty($post['featured_image_url'])) {
                    $images[] = $post['featured_image_url'];
                }
                
                addUrl(
                    $baseUrl . '/blog/' . htmlspecialchars($post['slug']),
                    $post['published_at'] ?: $post['updated_at'],
                    'monthly',
                    '0.7',
                    $images
                );
            }
        }
        
        // ============================================
        // DYNAMIC CONTENT - BOOKS
        // ============================================
        
        $booksQuery = $pdo->query("
            SELECT id, slug, updated_at, created_at, cover_url, title 
            FROM books 
            WHERE is_published = 1 
            ORDER BY updated_at DESC 
            LIMIT 500
        ");
        
        if ($booksQuery) {
            while ($book = $booksQuery->fetch(PDO::FETCH_ASSOC)) {
                $images = [];
                if (!empty($book['cover_url'])) {
                    $images[] = $book['cover_url'];
                }
                
                addUrl(
                    $baseUrl . '/buku/' . htmlspecialchars($book['slug']),
                    $book['updated_at'] ?: $book['created_at'],
                    'monthly',
                    '0.7',
                    $images
                );
            }
        }
        
        // ============================================
        // DYNAMIC CONTENT - PAGES
        // ============================================
        
        $pagesQuery = $pdo->query("
            SELECT id, slug, updated_at, created_at 
            FROM pages 
            WHERE is_published = 1 
            ORDER BY updated_at DESC 
            LIMIT 500
        ");
        
        if ($pagesQuery) {
            while ($page = $pagesQuery->fetch(PDO::FETCH_ASSOC)) {
                addUrl(
                    $baseUrl . '/p/' . htmlspecialchars($page['slug']),
                    $page['updated_at'] ?: $page['created_at'],
                    'monthly',
                    '0.6'
                );
            }
        }
        
        $pdo = null;
    }
} catch (Exception $e) {
    // Silently fail if database connection fails
    // The static pages will still be included
}

// Close XML
echo "</urlset>\n";
?>
