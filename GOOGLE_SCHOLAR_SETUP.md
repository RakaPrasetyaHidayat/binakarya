# Integrasi Google Scholar & Sitemap Dinamis

Dokumentasi lengkap untuk mengintegrasikan Google Scholar dan membuat sitemap dinamis yang dapat dicrawl oleh Google.

## 📋 Daftar Isi

1. [Sitemap Dinamis](#sitemap-dinamis)
2. [Integrasi Google Scholar](#integrasi-google-scholar)
3. [Setup di Google Search Console](#setup-di-google-search-console)
4. [Meta Tags & Structured Data](#meta-tags--structured-data)
5. [Troubleshooting](#troubleshooting)

---

## 🗺️ Sitemap Dinamis

### File Sitemap Utama

**URL:** `https://yourdomain.com/sitemap.php`

File ini terletak di `public/sitemap.php` dan menghasilkan sitemap XML dinamis yang mencakup:

- ✅ Halaman statis (homepage, about, contact, privacy, terms)
- ✅ Blog posts dengan featured images
- ✅ Books/Publications dengan cover images
- ✅ Services
- ✅ Custom pages
- ✅ Last modified dates
- ✅ Priority dan change frequency
- ✅ Image URLs untuk SEO

### Fitur Sitemap

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
        <loc>https://yourdomain.com/buku/judul-buku</loc>
        <lastmod>2026-05-01T10:30:00+00:00</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <image:image>
            <image:loc>https://yourdomain.com/storage/covers/book.jpg</image:loc>
        </image:image>
    </url>
</urlset>
```

### Cara Kerja

1. **Static Pages**: Ditambahkan secara manual dengan priority tinggi
2. **Dynamic Content**: Diambil dari database SQLite
3. **Caching**: Tidak ada caching untuk memastikan data selalu fresh
4. **Error Handling**: Jika database tidak tersedia, tetap menampilkan static pages

### Testing Sitemap

```bash
# Test dari terminal
curl https://yourdomain.com/sitemap.php | head -20

# Atau buka di browser
https://yourdomain.com/sitemap.php
```

---

## 🎓 Integrasi Google Scholar

### Apa itu Google Scholar?

Google Scholar adalah search engine khusus untuk literatur akademik. Integrasi ini memungkinkan publikasi Anda ditemukan oleh peneliti dan akademisi.

### Setup Google Scholar

#### 1. Dapatkan Author ID

1. Kunjungi [Google Scholar](https://scholar.google.com)
2. Cari nama Anda atau institusi Anda
3. Klik profil Anda
4. Copy Author ID dari URL: `https://scholar.google.com/citations?user=AUTHOR_ID`

#### 2. Konfigurasi di Admin Panel

1. Login ke admin panel: `https://yourdomain.com/cendikiaByRidwanullah`
2. Navigasi ke **Settings → Google Scholar Settings**
3. Isi form:
   - **Author ID**: Paste Author ID dari step 1
   - **Institution**: Nama institusi (opsional)
   - **API Key**: Untuk integrasi Semantic Scholar (opsional)
   - **Auto Sync**: Aktifkan untuk sinkronisasi otomatis
   - **Sync Interval**: Interval sinkronisasi dalam jam (1-168)
   - **Active**: Centang untuk mengaktifkan

#### 3. Sinkronisasi Manual

Klik tombol **"Sync Now"** untuk sinkronisasi manual publikasi dari Google Scholar.

### API yang Digunakan

Proyek ini menggunakan **Semantic Scholar API** (gratis) sebagai alternatif Google Scholar API:

- **Endpoint**: `https://api.semanticscholar.org/graph/v1/author/{authorId}`
- **Dokumentasi**: https://www.semanticscholar.org/product/api
- **Rate Limit**: 100 requests per 5 minutes

### Struktur Data

Publikasi yang disinkronisasi disimpan di tabel `books` dengan field tambahan:

```php
$book->scholar_id;      // ID dari Semantic Scholar
$book->citation_count;  // Jumlah citation
$book->author;          // Nama-nama author
$book->publication_year; // Tahun publikasi
$book->external_url;    // Link ke publikasi asli
```

---

## 🔍 Setup di Google Search Console

### 1. Verifikasi Domain

1. Kunjungi [Google Search Console](https://search.google.com/search-console)
2. Klik **"Add Property"**
3. Pilih **"URL prefix"** dan masukkan domain Anda
4. Verifikasi ownership (HTML file, DNS record, atau Google Analytics)

### 2. Submit Sitemap

#### Metode 1: Via Search Console UI

1. Di Search Console, buka **Sitemaps** (menu kiri)
2. Klik **"Add/test sitemap"**
3. Masukkan URL: `sitemap.php`
4. Klik **"Submit"**

#### Metode 2: Via robots.txt

Tambahkan ke `public/robots.txt`:

```
User-agent: *
Allow: /

Sitemap: https://yourdomain.com/sitemap.php
```

#### Metode 3: Via XML Sitemap Index

Buat `public/sitemap-index.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>https://yourdomain.com/sitemap.php</loc>
        <lastmod>2026-05-01T00:00:00Z</lastmod>
    </sitemap>
    <sitemap>
        <loc>https://yourdomain.com/sitemaps/pages.xml</loc>
        <lastmod>2026-05-01T00:00:00Z</lastmod>
    </sitemap>
    <sitemap>
        <loc>https://yourdomain.com/sitemaps/books.xml</loc>
        <lastmod>2026-05-01T00:00:00Z</lastmod>
    </sitemap>
    <sitemap>
        <loc>https://yourdomain.com/sitemaps/blog.xml</loc>
        <lastmod>2026-05-01T00:00:00Z</lastmod>
    </sitemap>
    <sitemap>
        <loc>https://yourdomain.com/sitemaps/services.xml</loc>
        <lastmod>2026-05-01T00:00:00Z</lastmod>
    </sitemap>
</sitemapindex>
```

### 3. Monitor Indexing

1. Di Search Console, buka **Coverage** untuk melihat status indexing
2. Buka **Sitemaps** untuk melihat statistik
3. Gunakan **URL Inspection** untuk test URL individual

---

## 📝 Meta Tags & Structured Data

### Meta Tags untuk Google Scholar

Setiap halaman buku secara otomatis menambahkan meta tags:

```html
<meta name="citation_title" content="Judul Publikasi">
<meta name="citation_author" content="Nama Author">
<meta name="citation_publication_date" content="2026">
<meta name="citation_abstract" content="Abstrak publikasi...">
<meta name="citation_pdf_url" content="https://...">
<meta name="citation_doi" content="...">
```

### Structured Data (JSON-LD)

Setiap publikasi juga memiliki structured data:

```json
{
  "@context": "https://schema.org",
  "@type": "ScholarlyArticle",
  "headline": "Judul Publikasi",
  "description": "Deskripsi...",
  "datePublished": "2026-01-01",
  "author": [
    {
      "@type": "Person",
      "name": "Nama Author"
    }
  ],
  "image": "https://...",
  "url": "https://yourdomain.com/buku/slug"
}
```

### Implementasi di View

Untuk menambahkan meta tags di halaman buku, edit `resources/views/books/show.blade.php`:

```blade
@extends('layouts.app')

@section('head')
    {!! \App\Helpers\ScholarHelper::getScholarMetaTags($book) !!}
    {!! \App\Helpers\ScholarHelper::getStructuredData($book) !!}
@endsection

@section('content')
    <!-- Konten buku -->
@endsection
```

---

## 🔗 Rute & Endpoint

### Sitemap Routes

```
GET /sitemap.php                    # Sitemap dinamis utama
GET /sitemap.xml                    # Sitemap index (XML)
GET /sitemaps/pages.xml             # Sitemap halaman statis
GET /sitemaps/books.xml             # Sitemap buku/publikasi
GET /sitemaps/blog.xml              # Sitemap blog posts
GET /sitemaps/services.xml          # Sitemap services
GET /sitemaps/news.xml              # Sitemap berita terbaru
GET /robots.txt                     # Robots.txt
```

### Admin Routes

```
GET  /cendikiaByRidwanullah/scholar-settings          # View settings
PUT  /cendikiaByRidwanullah/scholar-settings          # Update settings
POST /cendikiaByRidwanullah/scholar-settings/sync     # Manual sync
```

---

## 🛠️ Troubleshooting

### Sitemap tidak muncul

**Masalah**: `404 Not Found` saat akses `/sitemap.php`

**Solusi**:
1. Pastikan file `public/sitemap.php` ada
2. Cek permission file: `chmod 644 public/sitemap.php`
3. Restart web server

### Database tidak terkoneksi

**Masalah**: Sitemap hanya menampilkan static pages

**Solusi**:
1. Cek path database di `public/sitemap.php`
2. Pastikan `database/database.sqlite` ada
3. Cek permission: `chmod 644 database/database.sqlite`

### Google Scholar tidak sinkronisasi

**Masalah**: Tombol sync tidak bekerja

**Solusi**:
1. Pastikan Author ID benar
2. Cek koneksi internet
3. Lihat log: `storage/logs/laravel.log`
4. Cek rate limit Semantic Scholar API

### Meta tags tidak muncul

**Masalah**: Meta tags tidak terlihat di source code

**Solusi**:
1. Pastikan view sudah diupdate dengan helper
2. Clear cache: `php artisan cache:clear`
3. Cek apakah book sudah published

### Sitemap terlalu besar

**Masalah**: Sitemap XML melebihi 50MB

**Solusi**:
1. Gunakan sitemap index (`sitemap-index.xml`)
2. Split sitemap per tipe konten
3. Limit jumlah URL per sitemap (max 50,000)

---

## 📊 Best Practices

### 1. Update Frequency

- **Homepage**: Daily
- **Blog Posts**: Monthly
- **Books/Publications**: Monthly
- **Services**: Weekly
- **Legal Pages**: Yearly

### 2. Priority

- **Homepage**: 1.0
- **Main Pages**: 0.9
- **Content**: 0.7-0.8
- **Legal Pages**: 0.5

### 3. Image Optimization

- Gunakan format modern (WebP, AVIF)
- Compress images sebelum upload
- Gunakan responsive images
- Tambahkan alt text

### 4. Monitoring

- Monitor indexing di Search Console
- Check crawl errors regularly
- Monitor Core Web Vitals
- Track ranking changes

---

## 📚 Referensi

- [Google Search Console Help](https://support.google.com/webmasters)
- [Sitemap Protocol](https://www.sitemaps.org/)
- [Google Scholar](https://scholar.google.com)
- [Semantic Scholar API](https://www.semanticscholar.org/product/api)
- [Schema.org ScholarlyArticle](https://schema.org/ScholarlyArticle)

---

## 🚀 Quick Start

```bash
# 1. File sudah dibuat otomatis:
# - public/sitemap.php
# - app/Services/GoogleScholarService.php
# - app/Helpers/ScholarHelper.php

# 2. Test sitemap
curl https://yourdomain.com/sitemap.php

# 3. Submit ke Google Search Console
# https://search.google.com/search-console

# 4. Monitor di Search Console
# Sitemaps → Lihat statistik indexing
```

---

**Last Updated**: May 1, 2026
**Version**: 1.0.0
