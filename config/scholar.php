<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Scholar Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Scholar integration and academic indexing
    |
    */

    'enabled' => env('SCHOLAR_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Semantic Scholar API
    |--------------------------------------------------------------------------
    |
    | Using Semantic Scholar API as a free alternative to Google Scholar API
    | https://www.semanticscholar.org/product/api
    |
    */
    'api' => [
        'provider' => env('SCHOLAR_API_PROVIDER', 'semantic-scholar'),
        'base_url' => 'https://api.semanticscholar.org/graph/v1',
        'timeout' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Meta Tags Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for citation meta tags that Google Scholar reads
    |
    */
    'meta_tags' => [
        'enabled' => true,
        'include_open_graph' => true,
        'include_structured_data' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Scholar sitemap generation
    |
    */
    'sitemap' => [
        'enabled' => true,
        'include_images' => true,
        'max_urls' => 50000,
        'cache_duration' => 86400, // 24 hours
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Sync Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic synchronization of publications
    |
    */
    'auto_sync' => [
        'enabled' => env('SCHOLAR_AUTO_SYNC', false),
        'interval' => env('SCHOLAR_SYNC_INTERVAL', 24), // hours
        'batch_size' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Indexing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for academic indexing
    |
    */
    'indexing' => [
        'google_scholar' => true,
        'semantic_scholar' => true,
        'crossref' => true,
        'pubmed' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Mapping
    |--------------------------------------------------------------------------
    |
    | Map database fields to citation fields
    |
    */
    'field_mapping' => [
        'title' => 'title',
        'authors' => 'author',
        'publication_date' => 'publication_year',
        'abstract' => 'description',
        'doi' => 'scholar_id',
        'url' => 'external_url',
        'pdf_url' => 'pdf_url',
        'citation_count' => 'citation_count',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache settings for scholar data
    |
    */
    'cache' => [
        'enabled' => true,
        'duration' => 86400, // 24 hours
        'prefix' => 'scholar_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Log scholar operations
    |
    */
    'logging' => [
        'enabled' => true,
        'channel' => 'single',
        'log_sync' => true,
        'log_errors' => true,
    ],
];
