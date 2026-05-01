# 🚀 Quick Reference - Sitemap & Google Scholar

Referensi cepat untuk implementasi dan penggunaan.

---

## 📋 File Locations

```
public/sitemap.php                                    ← MAIN SITEMAP
public/sitemap-index.xml
public/robots.txt

app/Services/GoogleScholarService.php
app/Helpers/ScholarHelper.php
config/scholar.php

database/migrations/2026_05_01_000000_add_scholar_fields_to_books_table.php

resources/views/sitemaps/scholar.blade.php
resources/views/sitemaps/sitemap-index.blade.php

routes/web.php                                        ← UPDATED
app/Http/Controllers/SitemapController.php            ← UPDATED
```

---

## 🔗 URLs

| URL | Purpose |
|-----|---------|
| `/sitemap.php` | **Main sitemap** |
| `/sitemap.xml` | XML index |
| `/sitemap-index.xml` | Sitemap index |
| `/sitemaps/pages.xml` | Pages |
| `/sitemaps/books.xml` | Books |
| `/sitemaps/blog.xml` | Blog |
| `/sitemaps/services.xml` | Services |
| `/sitemaps/news.xml` | News |
| `/robots.txt` | Robots |

---

## 🧪 Quick Tests

### Test Sitemap
```bash
curl https://yourdomain.com/sitemap.php
curl -I https://yourdomain.com/sitemap.php
curl https://yourdomain.com/sitemap.php | xmllint --noout -
curl https://yourdomain.com/sitemap.php | grep -c "<url>"
```

### Test robots.txt
```bash
curl https://yourdomain.com/robots.txt
curl https://yourdomain.com/robots.txt | grep "Sitemap"
```

### Test Database
```bash
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM books WHERE is_published = 1;"
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM posts WHERE is_published = 1;"
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM services WHERE is_active = 1;"
```

---

## 🎯 3-Step Implementation

### Step 1: Test
```bash
curl https://yourdomain.com/sitemap.php
```

### Step 2: Submit
1. Go to https://search.google.com/search-console
2. Sitemaps → Add/test sitemap
3. Enter: `sitemap.php`
4. Submit

### Step 3: Monitor
- Check Coverage report
- Monitor indexing status
- Check for errors

---

## 🎓 Google Scholar Setup

### Get Author ID
1. Visit https://scholar.google.com
2. Search for your name
3. Click profile
4. Copy Author ID from URL

### Configure
1. Admin → Google Scholar Settings
2. Paste Author ID
3. Click Save & Sync

### Verify
```bash
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM books WHERE scholar_id IS NOT NULL;"
```

---

## 📝 Add Meta Tags to Views

### Book View
```blade
@section('head')
    {!! \App\Helpers\ScholarHelper::getScholarMetaTags($book) !!}
    {!! \App\Helpers\ScholarHelper::getStructuredData($book) !!}
@endsection
```

### Blog View
```blade
@section('head')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:image" content="{{ $post->featured_image_url }}">
@endsection
```

---

## 🔧 Common Commands

```bash
# Clear cache
php artisan cache:clear

# Run migration
php artisan migrate

# Check logs
tail -f storage/logs/laravel.log

# Test with curl
curl https://yourdomain.com/sitemap.php

# Validate XML
curl https://yourdomain.com/sitemap.php | xmllint --noout -

# Count URLs
curl https://yourdomain.com/sitemap.php | grep -c "<url>"

# Check database
sqlite3 database/database.sqlite ".tables"
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM books;"
```

---

## 🛠️ Troubleshooting

### 404 Error
```bash
ls -la public/sitemap.php
chmod 644 public/sitemap.php
```

### Invalid XML
```bash
curl https://yourdomain.com/sitemap.php | xmllint --noout -
```

### No URLs
```bash
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM books WHERE is_published = 1;"
```

### Meta Tags Missing
```bash
php artisan cache:clear
# Check view file
cat resources/views/books/show.blade.php | grep "ScholarHelper"
```

---

## 📊 Priorities & Frequencies

| Content | Priority | Frequency |
|---------|----------|-----------|
| Homepage | 1.0 | Daily |
| Main Pages | 0.9 | Monthly |
| Services | 0.8 | Weekly |
| Blog Posts | 0.7 | Monthly |
| Books | 0.7 | Monthly |
| Custom Pages | 0.6 | Monthly |
| Legal Pages | 0.5 | Yearly |

---

## 🔗 External Links

- [Google Search Console](https://search.google.com/search-console)
- [Google Scholar](https://scholar.google.com)
- [Semantic Scholar API](https://www.semanticscholar.org/product/api)
- [Sitemap Protocol](https://www.sitemaps.org/)
- [Schema.org](https://schema.org/)

---

## 📚 Documentation

| File | Purpose |
|------|---------|
| `README_SITEMAP.md` | Overview |
| `SITEMAP_QUICK_START.md` | Quick start |
| `GOOGLE_SCHOLAR_SETUP.md` | Setup guide |
| `IMPLEMENTATION_GUIDE.md` | Code examples |
| `TESTING_GUIDE.md` | Testing |
| `INTEGRATION_SUMMARY.md` | Summary |
| `IMPLEMENTATION_CHECKLIST.md` | Checklist |

---

## ✅ Checklist

- [ ] Test sitemap: `curl https://yourdomain.com/sitemap.php`
- [ ] Verify XML valid
- [ ] Submit to Google Search Console
- [ ] Monitor indexing
- [ ] Setup Google Scholar (optional)
- [ ] Add meta tags (optional)
- [ ] Monitor rankings

---

## 🎯 Success Metrics

- ✅ Sitemap accessible
- ✅ XML valid
- ✅ All URLs included
- ✅ Images included
- ✅ Submitted to GSC
- ✅ URLs being crawled
- ✅ Indexing improving
- ✅ Traffic increasing

---

**Last Updated**: May 1, 2026
**Version**: 1.0.0
