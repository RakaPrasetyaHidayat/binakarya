<?php

namespace App\Helpers;

use App\Models\Book;
use App\Services\GoogleScholarService;

class ScholarHelper
{
    /**
     * Get Google Scholar meta tags for a book
     * 
     * @param Book $book
     * @return string
     */
    public static function getScholarMetaTags(Book $book)
    {
        return GoogleScholarService::generateScholarMetaTags($book);
    }

    /**
     * Get structured data for a book
     * 
     * @param Book $book
     * @return string
     */
    public static function getStructuredData(Book $book)
    {
        return GoogleScholarService::generateStructuredData($book);
    }

    /**
     * Get all books for Google Scholar sitemap
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getScholarBooks()
    {
        return Book::where('is_published', true)
            ->orderByDesc('updated_at')
            ->get();
    }

    /**
     * Check if Google Scholar is enabled
     * 
     * @return bool
     */
    public static function isScholarEnabled()
    {
        return \App\Models\ScholarSetting::where('is_active', true)->exists();
    }

    /**
     * Get Google Scholar settings
     * 
     * @return \App\Models\ScholarSetting|null
     */
    public static function getScholarSettings()
    {
        return \App\Models\ScholarSetting::getActive();
    }
}
