# 📖 Implementation Guide - Google Scholar & Sitemap

Panduan implementasi untuk mengintegrasikan Google Scholar meta tags ke dalam view Anda.

---

## 1. Update Book Show View

Edit file `resources/views/books/show.blade.php` dan tambahkan meta tags di section head:

```blade
@extends('layouts.app')

@section('head')
    <!-- Google Scholar Meta Tags -->
    {!! \App\Helpers\ScholarHelper::getScholarMetaTags($book) !!}
    
    <!-- Structured Data (JSON-LD) -->
    {!! \App\Helpers\ScholarHelper::getStructuredData($book) !!}
    
    <!-- Open Graph Tags -->
    <meta property="og:type" content="book">
    <meta property="og:title" content="{{ $book->title }}">
    <meta property="og:description" content="{{ Str::limit($book->description, 160) }}">
    @if($book->cover_url)
    <meta property="og:image" content="{{ $book->cover_url }}">
    @endif
    <meta property="og:url" content="{{ route('books.show', $book->slug) }}">
@endsection

@section('content')
    <div class="book-container">
        <h1>{{ $book->title }}</h1>
        
        @if($book->author)
        <p class="authors">
            <strong>Author(s):</strong> {{ $book->author }}
        </p>
        @endif
        
        @if($book->publication_year)
        <p class="publication-year">
            <strong>Published:</strong> {{ $book->publication_year }}
        </p>
        @endif
        
        @if($book->cover_url)
        <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="book-cover">
        @endif
        
        <div class="description">
            {!! $book->description !!}
        </div>
        
        @if($book->citation_count)
        <p class="citation-count">
            <strong>Citations:</strong> {{ $book->citation_count }}
        </p>
        @endif
        
        @if($book->external_url)
        <a href="{{ $book->external_url }}" target="_blank" class="btn btn-primary">
            View on Google Scholar
        </a>
        @endif
    </div>
@endsection
```

---

## 2. Update Blog Post Show View

Edit file `resources/views/blog/show.blade.php`:

```blade
@extends('layouts.app')

@section('head')
    <!-- Open Graph Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ Str::limit($post->excerpt ?? $post->content, 160) }}">
    @if($post->featured_image_url)
    <meta property="og:image" content="{{ $post->featured_image_url }}">
    @endif
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    
    <!-- Article Meta Tags -->
    <meta property="article:published_time" content="{{ $post->published_at->toAtomString() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toAtomString() }}">
    @if($post->user)
    <meta property="article:author" content="{{ $post->user->name }}">
    @endif
@endsection

@section('content')
    <article class="blog-post">
        <h1>{{ $post->title }}</h1>
        
        @if($post->featured_image_url)
        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="featured-image">
        @endif
        
        <div class="post-meta">
            <span class="date">{{ $post->published_at->format('d M Y') }}</span>
            @if($post->user)
            <span class="author">By {{ $post->user->name }}</span>
            @endif
            @if($post->category)
            <span class="category">{{ $post->category->name }}</span>
            @endif
        </div>
        
        <div class="content">
            {!! $post->content !!}
        </div>
    </article>
@endsection
```

---

## 3. Update Service Show View

Edit file `resources/views/services/show.blade.php`:

```blade
@extends('layouts.app')

@section('head')
    <!-- Open Graph Tags -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $service->name }}">
    <meta property="og:description" content="{{ Str::limit($service->description, 160) }}">
    @if($service->image_url)
    <meta property="og:image" content="{{ $service->image_url }}">
    @endif
    <meta property="og:url" content="{{ route('services.show', $service->slug) }}">
    
    <!-- Structured Data for Service -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Service",
        "name": "{{ $service->name }}",
        "description": "{{ Str::limit($service->description, 160) }}",
        "url": "{{ route('services.show', $service->slug) }}",
        @if($service->image_url)
        "image": "{{ $service->image_url }}",
        @endif
        "provider": {
            "@type": "Organization",
            "name": "{{ config('app.name') }}"
        }
    }
    </script>
@endsection

@section('content')
    <div class="service-container">
        <h1>{{ $service->name }}</h1>
        
        @if($service->image_url)
        <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="service-image">
        @endif
        
        <div class="description">
            {!! $service->description !!}
        </div>
    </div>
@endsection
```

---

## 4. Update Layout Head Section

Edit file `resources/views/layouts/app.blade.php` dan tambahkan di section head:

```blade
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Default Meta Tags -->
    <meta name="description" content="{{ $metaDescription ?? config('app.description') }}">
    <meta name="keywords" content="{{ $metaKeywords ?? config('app.keywords') }}">
    
    <!-- Robots Meta -->
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Sitemap Links -->
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap.xml') }}">
    <link rel="sitemap" type="application/xml" href="{{ url('/sitemap-index.xml') }}">
    
    <!-- Yield custom head content -->
    @yield('head')
    
    <!-- Other head content -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

---

## 5. Create Helper Function (Optional)

Jika ingin membuat helper yang lebih mudah digunakan, tambahkan ke `app/Helpers/ScholarHelper.php`:

```php
/**
 * Get all meta tags for a book
 */
public static function getAllMetaTags($book)
{
    $html = '';
    
    // Scholar meta tags
    $html .= self::getScholarMetaTags($book);
    
    // Structured data
    $html .= self::getStructuredData($book);
    
    // Open Graph
    $html .= '<meta property="og:type" content="book">' . "\n";
    $html .= '<meta property="og:title" content="' . htmlspecialchars($book->title) . '">' . "\n";
    if ($book->description) {
        $html .= '<meta property="og:description" content="' . htmlspecialchars(substr($book->description, 0, 160)) . '">' . "\n";
    }
    if ($book->cover_url) {
        $html .= '<meta property="og:image" content="' . htmlspecialchars($book->cover_url) . '">' . "\n";
    }
    $html .= '<meta property="og:url" content="' . route('books.show', $book->slug) . '">' . "\n";
    
    return $html;
}
```

---

## 6. Database Migration (If Needed)

Jika belum menjalankan migration, jalankan:

```bash
php artisan migrate
```

Ini akan menambahkan field berikut ke tabel `books`:
- `scholar_id` - ID dari Semantic Scholar
- `citation_count` - Jumlah citation
- `publication_year` - Tahun publikasi
- `external_url` - Link ke publikasi asli
- `pdf_url` - Link ke PDF
- `doi` - Digital Object Identifier
- `indexed_at` - Timestamp indexing

---

## 7. Test Implementation

### Test Meta Tags

```bash
# Buka halaman buku di browser
https://yourdomain.com/buku/judul-buku

# Lihat source code (Ctrl+U atau Cmd+U)
# Cari meta tags:
# - citation_title
# - citation_author
# - citation_publication_date
# - og:type
# - og:title
# - og:image
```

### Test Structured Data

```bash
# Gunakan Google's Structured Data Testing Tool
https://search.google.com/test/rich-results

# Paste URL halaman buku
# Lihat apakah structured data terdeteksi dengan benar
```

### Test Sitemap

```bash
# Test sitemap.php
curl https://yourdomain.com/sitemap.php | head -30

# Cek apakah URL buku ada di sitemap
# Cek apakah image URLs ada
```

---

## 8. Submit to Google Search Console

1. **Verify Domain**
   - https://search.google.com/search-console
   - Add property
   - Verify ownership

2. **Submit Sitemap**
   - Sitemaps menu
   - Add/test sitemap
   - Masukkan: `sitemap.php`
   - Submit

3. **Monitor Indexing**
   - Coverage - lihat status
   - Sitemaps - lihat statistik
   - URL Inspection - test URL

---

## 9. Optimize for Google Scholar

### Best Practices

1. **Complete Metadata**
   - Pastikan semua field terisi (title, author, year, abstract)
   - Gunakan format yang konsisten

2. **Proper Formatting**
   - Author names: "First Last" format
   - Publication year: YYYY format
   - Abstract: 160-200 characters

3. **External Links**
   - Tambahkan link ke publikasi asli
   - Tambahkan link ke PDF jika ada
   - Gunakan DOI jika tersedia

4. **Images**
   - Gunakan cover image berkualitas tinggi
   - Compress images untuk performa
   - Gunakan format modern (WebP)

5. **Regular Updates**
   - Update citation count secara berkala
   - Sync dengan Google Scholar/Semantic Scholar
   - Monitor indexing status

---

## 10. Troubleshooting

### Meta tags tidak muncul

```bash
# Clear cache
php artisan cache:clear

# Check view file
cat resources/views/books/show.blade.php | grep "ScholarHelper"

# Check helper file
cat app/Helpers/ScholarHelper.php
```

### Structured data tidak valid

```bash
# Test dengan Google's tool
https://search.google.com/test/rich-results

# Check JSON-LD syntax
# Pastikan JSON valid (gunakan jsonlint.com)
```

### Sitemap tidak include buku

```bash
# Check database
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM books WHERE is_published = 1;"

# Check sitemap
curl https://yourdomain.com/sitemap.php | grep "buku"
```

---

## 📚 Additional Resources

- [Google Scholar](https://scholar.google.com)
- [Semantic Scholar API](https://www.semanticscholar.org/product/api)
- [Schema.org ScholarlyArticle](https://schema.org/ScholarlyArticle)
- [Google Search Console](https://search.google.com/search-console)
- [Sitemap Protocol](https://www.sitemaps.org/)

---

**Last Updated**: May 1, 2026
**Version**: 1.0.0
