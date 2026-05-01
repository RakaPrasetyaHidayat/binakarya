<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Post;
use App\Models\ScholarSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleScholarService
{
    /**
     * Fetch publications from Google Scholar API
     * 
     * @param ScholarSetting $settings
     * @return array
     */
    public static function fetchPublications(ScholarSetting $settings)
    {
        try {
            // Using Semantic Scholar API (free alternative to Google Scholar)
            // https://www.semanticscholar.org/product/api
            
            if (!$settings->author_id) {
                return [];
            }

            $response = Http::timeout(30)
                ->get('https://api.semanticscholar.org/graph/v1/author/' . $settings->author_id, [
                    'fields' => 'papers,name,affiliations,citationCount,hIndex'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return self::processPapers($data['papers'] ?? [], $settings);
            }

            Log::warning('Google Scholar API failed', ['status' => $response->status()]);
            return [];
        } catch (\Exception $e) {
            Log::error('Google Scholar Service Error', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Process papers from API response
     * 
     * @param array $papers
     * @param ScholarSetting $settings
     * @return array
     */
    private static function processPapers(array $papers, ScholarSetting $settings)
    {
        $processed = [];

        foreach ($papers as $paper) {
            $processed[] = [
                'title' => $paper['title'] ?? '',
                'paperId' => $paper['paperId'] ?? '',
                'url' => $paper['url'] ?? '',
                'year' => $paper['year'] ?? null,
                'citationCount' => $paper['citationCount'] ?? 0,
                'authors' => $paper['authors'] ?? [],
                'abstract' => $paper['abstract'] ?? '',
                'venue' => $paper['venue'] ?? '',
            ];
        }

        return $processed;
    }

    /**
     * Sync publications to books table
     * 
     * @param ScholarSetting $settings
     * @return int Number of synced publications
     */
    public static function syncPublicationsToBooks(ScholarSetting $settings)
    {
        $publications = self::fetchPublications($settings);
        $synced = 0;

        foreach ($publications as $pub) {
            try {
                // Check if book already exists
                $book = Book::where('title', $pub['title'])->first();

                if (!$book) {
                    $book = new Book();
                }

                $book->title = $pub['title'];
                $book->description = $pub['abstract'] ?? '';
                $book->author = implode(', ', array_map(function ($author) {
                    return $author['name'] ?? '';
                }, $pub['authors'] ?? []));
                $book->publication_year = $pub['year'];
                $book->external_url = $pub['url'];
                $book->scholar_id = $pub['paperId'];
                $book->citation_count = $pub['citationCount'];
                $book->is_published = true;
                $book->save();

                $synced++;
            } catch (\Exception $e) {
                Log::error('Error syncing publication', [
                    'publication' => $pub['title'] ?? 'Unknown',
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $synced;
    }

    /**
     * Generate Google Scholar meta tags for a book
     * 
     * @param Book $book
     * @return string HTML meta tags
     */
    public static function generateScholarMetaTags(Book $book)
    {
        $html = '';

        // Citation meta tags for Google Scholar
        if ($book->title) {
            $html .= '<meta name="citation_title" content="' . htmlspecialchars($book->title) . '">' . "\n";
        }

        if ($book->author) {
            $authors = explode(',', $book->author);
            foreach ($authors as $author) {
                $html .= '<meta name="citation_author" content="' . htmlspecialchars(trim($author)) . '">' . "\n";
            }
        }

        if ($book->publication_year) {
            $html .= '<meta name="citation_publication_date" content="' . $book->publication_year . '">' . "\n";
        }

        if ($book->description) {
            $html .= '<meta name="citation_abstract" content="' . htmlspecialchars(substr($book->description, 0, 160)) . '">' . "\n";
        }

        if ($book->cover_url) {
            $html .= '<meta name="citation_pdf_url" content="' . htmlspecialchars($book->cover_url) . '">' . "\n";
        }

        if ($book->scholar_id) {
            $html .= '<meta name="citation_doi" content="' . htmlspecialchars($book->scholar_id) . '">' . "\n";
        }

        // Open Graph tags for better sharing
        $html .= '<meta property="og:type" content="book">' . "\n";
        $html .= '<meta property="og:title" content="' . htmlspecialchars($book->title) . '">' . "\n";
        if ($book->description) {
            $html .= '<meta property="og:description" content="' . htmlspecialchars(substr($book->description, 0, 160)) . '">' . "\n";
        }
        if ($book->cover_url) {
            $html .= '<meta property="og:image" content="' . htmlspecialchars($book->cover_url) . '">' . "\n";
        }

        return $html;
    }

    /**
     * Generate structured data (JSON-LD) for Google Scholar
     * 
     * @param Book $book
     * @return string JSON-LD script tag
     */
    public static function generateStructuredData(Book $book)
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'ScholarlyArticle',
            'headline' => $book->title,
            'description' => substr($book->description ?? '', 0, 160),
            'datePublished' => $book->publication_year ? $book->publication_year . '-01-01' : null,
            'author' => [],
            'image' => $book->cover_url,
            'url' => route('books.show', $book->slug),
        ];

        // Add authors
        if ($book->author) {
            $authors = explode(',', $book->author);
            foreach ($authors as $author) {
                $data['author'][] = [
                    '@type' => 'Person',
                    'name' => trim($author),
                ];
            }
        }

        // Add citation count if available
        if ($book->citation_count) {
            $data['citation'] = [
                '@type' => 'CreativeWork',
                'name' => 'Citation Count',
                'text' => $book->citation_count,
            ];
        }

        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generate sitemap entry for Google Scholar
     * 
     * @param Book $book
     * @return array
     */
    public static function generateSitemapEntry(Book $book)
    {
        return [
            'loc' => route('books.show', $book->slug),
            'lastmod' => $book->updated_at->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8',
            'image' => $book->cover_url,
        ];
    }
}
