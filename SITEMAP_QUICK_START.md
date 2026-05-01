# 🚀 Sitemap & Google Scholar - Quick Start Guide

Panduan cepat untuk menggunakan sitemap dinamis dan integrasi Google Scholar.

## ✅ File yang Sudah Dibuat

### 1. **Sitemap Dinamis**
- ✅ `public/sitemap.php` - Sitemap utama yang dapat diakses di `/sitemap.php`
- ✅ `public/sitemap-index.xml` - Index semua sitemap
- ✅ `public/robots.txt` - Updated dengan referensi sitemap

### 2. **Google Scholar Integration**
- ✅ `app/Services/GoogleScholarService.php` - Service untuk integrasi
- ✅ `app/Helpers/ScholarHelper.php` - Helper functions
- ✅ `config/scholar.php` - Konfigurasi Google Scholar
- ✅ `database/migrations/2026_05_01_000000_add_scholar_fields_to_books_table.php` - Migration untuk field tambahan

### 3. **Views & Routes**
- ✅ `resources/views/sitemaps/scholar.blade.php` - View untuk scholar sitemap
- ✅ `resources/views/sitemaps/sitemap-index.blade.php` - View untuk sitemap index
- ✅ `routes/web.php` - Updated dengan route sitemap-index

### 4. **Dokumentasi**
- ✅ `GOOGLE_SCHOLAR_SETUP.md` - Dokumentasi lengkap
- ✅ `SITEMAP_QUICK_START.md` - File ini

---

## 🔧 Setup Steps

### Step 1: Run Migration (Opsional)

Jika ingin menambahkan field Google Scholar ke tabel books:

```bash
php artisan migrate
```

### Step 2: Test Sitemap

Buka di browser atau terminal:

```bash
# Test sitemap.php
curl https://yourdomain.com/sitemap.php | head -20

# Test sitemap-index.xml
curl https://yourdomain.com/sitemap-index.xml

# Test robots.txt
curl https://yourdomain.com/robots.txt
```

### Step 3: Submit ke Google Search Console

1. Kunjungi [Google Search Console](https://search.google.com/search-console)
2. Pilih property Anda
3. Buka **Sitemaps** (menu kiri)
4. Klik **"Add/test sitemap"**
5. Masukkan salah satu:
   - `sitemap.php` (recommended - dynamic)
   - `sitemap-index.xml` (index semua sitemap)
   - `sitemap.xml` (XML format)

### Step 4: Setup Google Scholar (Opsional)

1. Login ke admin: `https://yourdomain.com/cendikiaByRidwanullah`
2. Navigasi ke **Settings → Google Scholar Settings**
3. Isi Author ID dari [Google Scholar](https://scholar.google.com)
4. Klik **"Save"** dan **"Sync Now"**

---

## 📊 Sitemap URLs

Akses sitemap dari URL berikut:

| URL | Deskripsi |
|-----|-----------|
| `/sitemap.php` | **Sitemap dinamis utama** (recommended) |
| `/sitemap.xml` | Sitemap index (XML format) |
| `/sitemap-index.xml` | Index semua sitemap |
| `/sitemaps/pages.xml` | Halaman statis |
| `/sitemaps/books.xml` | Buku/publikasi |
| `/sitemaps/blog.xml` | Blog posts |
| `/sitemaps/services.xml` | Services |
| `/sitemaps/news.xml` | Berita terbaru |
| `/robots.txt` | Robots.txt dengan sitemap references |

---

## 🎯 Fitur Sitemap

### ✨ Sitemap.php Features

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url>
        <loc>https://yourdomain.com/buku/judul</loc>
        <lastmod>2026-05-01T10:30:00+00:00</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        <image:image>
            <image:loc>https://yourdomain.com/storage/covers/book.jpg</image:loc>
        </image:image>
    </url>
</urlset>
```

### 📋 Konten yang Diinclude

- ✅ Homepage (priority: 1.0)
- ✅ Main pages (priority: 0.9)
- ✅ Services (priority: 0.8)
- ✅ Blog posts dengan featured images (priority: 0.7)
- ✅ Books dengan cover images (priority: 0.7)
- ✅ Custom pages (priority: 0.6)
- ✅ Legal pages (priority: 0.5)

### 🖼️ Image Support

Sitemap mendukung image URLs untuk:
- Blog featured images
- Book cover images
- Membantu Google memahami konten visual

---

## 🔍 Google Search Console Integration

### Submit Sitemap

**Method 1: Via UI**
1. Search Console → Sitemaps
2. Add/test sitemap
3. Masukkan: `sitemap.php`
4. Submit

**Method 2: Via robots.txt**
Sudah ditambahkan otomatis:
```
Sitemap: https://yourdomain.com/sitemap.php
Sitemap: https://yourdomain.com/sitemap-index.xml
```

### Monitor Indexing

1. **Coverage** - Lihat status indexing
2. **Sitemaps** - Lihat statistik
3. **URL Inspection** - Test URL individual

---

## 🎓 Google Scholar Integration

### Apa itu Google Scholar?

Google Scholar adalah search engine untuk literatur akademik. Integrasi ini memungkinkan publikasi Anda ditemukan oleh peneliti.

### Setup

1. **Dapatkan Author ID**
   - Kunjungi [Google Scholar](https://scholar.google.com)
   - Cari nama Anda
   - Copy Author ID dari URL

2. **Konfigurasi di Admin**
   - Admin → Google Scholar Settings
   - Paste Author ID
   - Klik Save & Sync

3. **Meta Tags Otomatis**
   - Setiap buku mendapat citation meta tags
   - Structured data (JSON-LD) ditambahkan otomatis

### Meta Tags yang Ditambahkan

```html
<meta name="citation_title" content="Judul">
<meta name="citation_author" content="Author">
<meta name="citation_publication_date" content="2026">
<meta name="citation_abstract" content="...">
<meta name="citation_pdf_url" content="...">
```

---

## 🛠️ Troubleshooting

### Sitemap tidak muncul (404)

```bash
# Check file exists
ls -la public/sitemap.php

# Check permissions
chmod 644 public/sitemap.php

# Restart web server
# (depends on your setup)
```

### Database connection error

```bash
# Check database file
ls -la database/database.sqlite

# Check permissions
chmod 644 database/database.sqlite
```

### Google Scholar sync tidak bekerja

```bash
# Check logs
tail -f storage/logs/laravel.log

# Verify Author ID
# https://scholar.google.com/citations?user=YOUR_ID
```

### Meta tags tidak muncul

```bash
# Clear cache
php artisan cache:clear

# Check view is updated
# resources/views/books/show.blade.php
```

---

## 📈 Best Practices

### 1. Update Frequency
- Homepage: Daily
- Blog: Monthly
- Books: Monthly
- Services: Weekly
- Legal: Yearly

### 2. Priority Levels
- Homepage: 1.0
- Main pages: 0.9
- Content: 0.7-0.8
- Legal: 0.5

### 3. Image Optimization
- Compress images
- Use modern formats (WebP)
- Add alt text
- Use responsive images

### 4. Monitoring
- Check Search Console weekly
- Monitor crawl errors
- Track indexing status
- Monitor Core Web Vitals

---

## 📚 Additional Resources

- [Sitemap Protocol](https://www.sitemaps.org/)
- [Google Search Console Help](https://support.google.com/webmasters)
- [Google Scholar](https://scholar.google.com)
- [Semantic Scholar API](https://www.semanticscholar.org/product/api)
- [Schema.org ScholarlyArticle](https://schema.org/ScholarlyArticle)

---

## 🚀 Next Steps

1. ✅ Test sitemap: `https://yourdomain.com/sitemap.php`
2. ✅ Submit to Google Search Console
3. ✅ Setup Google Scholar (optional)
4. ✅ Monitor indexing in Search Console
5. ✅ Optimize content for SEO

---

## 📞 Support

Untuk bantuan lebih lanjut, lihat:
- `GOOGLE_SCHOLAR_SETUP.md` - Dokumentasi lengkap
- `app/Services/GoogleScholarService.php` - Source code
- `app/Helpers/ScholarHelper.php` - Helper functions

---

**Last Updated**: May 1, 2026
**Version**: 1.0.0
