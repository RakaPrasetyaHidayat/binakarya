# 🧪 Testing Guide - Sitemap & Google Scholar

Panduan lengkap untuk testing sitemap dan Google Scholar integration.

---

## 1. Test Sitemap.php

### Test 1.1: Basic Accessibility

```bash
# Test apakah file dapat diakses
curl -I https://yourdomain.com/sitemap.php

# Expected output:
# HTTP/1.1 200 OK
# Content-Type: application/xml; charset=utf-8
# Cache-Control: public, max-age=86400
```

### Test 1.2: View Content

```bash
# Lihat isi sitemap
curl https://yourdomain.com/sitemap.php

# Atau gunakan browser
# https://yourdomain.com/sitemap.php
```

### Test 1.3: Validate XML

```bash
# Download dan validate XML
curl https://yourdomain.com/sitemap.php > sitemap.xml

# Validate dengan xmllint (jika installed)
xmllint --noout sitemap.xml

# Atau gunakan online validator
# https://www.xml-sitemaps.com/validate-xml-sitemap.html
```

### Test 1.4: Check Content

```bash
# Count URLs
curl https://yourdomain.com/sitemap.php | grep -c "<url>"

# Check for specific content
curl https://yourdomain.com/sitemap.php | grep "buku"
curl https://yourdomain.com/sitemap.php | grep "blog"
curl https://yourdomain.com/sitemap.php | grep "layanan"

# Check for images
curl https://yourdomain.com/sitemap.php | grep "image:image"
```

### Test 1.5: Performance

```bash
# Measure response time
time curl https://yourdomain.com/sitemap.php > /dev/null

# Expected: < 1 second
```

---

## 2. Test Sitemap Index

### Test 2.1: Access Sitemap Index

```bash
# Test sitemap-index.xml
curl https://yourdomain.com/sitemap-index.xml

# Expected: XML with list of sitemaps
```

### Test 2.2: Validate Format

```bash
# Check if all sitemaps are listed
curl https://yourdomain.com/sitemap-index.xml | grep "<loc>"

# Expected output:
# <loc>https://yourdomain.com/sitemap.php</loc>
# <loc>https://yourdomain.com/sitemaps/pages.xml</loc>
# <loc>https://yourdomain.com/sitemaps/books.xml</loc>
# etc.
```

---

## 3. Test Individual Sitemaps

### Test 3.1: Pages Sitemap

```bash
curl https://yourdomain.com/sitemaps/pages.xml | head -20
```

### Test 3.2: Books Sitemap

```bash
curl https://yourdomain.com/sitemaps/books.xml | head -20

# Check for images
curl https://yourdomain.com/sitemaps/books.xml | grep "image:image"
```

### Test 3.3: Blog Sitemap

```bash
curl https://yourdomain.com/sitemaps/blog.xml | head -20

# Check for featured images
curl https://yourdomain.com/sitemaps/blog.xml | grep "image:image"
```

### Test 3.4: Services Sitemap

```bash
curl https://yourdomain.com/sitemaps/services.xml | head -20
```

### Test 3.5: News Sitemap

```bash
curl https://yourdomain.com/sitemaps/news.xml | head -20
```

---

## 4. Test Robots.txt

### Test 4.1: Access Robots.txt

```bash
curl https://yourdomain.com/robots.txt
```

### Test 4.2: Check Sitemap References

```bash
# Should contain sitemap references
curl https://yourdomain.com/robots.txt | grep "Sitemap"

# Expected:
# Sitemap: https://yourdomain.com/sitemap.php
# Sitemap: https://yourdomain.com/sitemap-index.xml
```

### Test 4.3: Check Disallow Rules

```bash
# Check disallow rules
curl https://yourdomain.com/robots.txt | grep "Disallow"

# Expected:
# Disallow: /cendikiaByRidwanullah/
# Disallow: /admin/
# Disallow: /api/
```

---

## 5. Test Google Scholar Integration

### Test 5.1: Check Database Fields

```bash
# Connect to database
sqlite3 database/database.sqlite

# Check if scholar fields exist
.schema books

# Expected columns:
# scholar_id
# citation_count
# publication_year
# external_url
# pdf_url
# doi
# indexed_at
```

### Test 5.2: Test Service

```bash
# Create test file: test_scholar.php
<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Services\GoogleScholarService;
use App\Models\ScholarSetting;

$settings = ScholarSetting::first();
if ($settings) {
    $publications = GoogleScholarService::fetchPublications($settings);
    echo "Found " . count($publications) . " publications\n";
    print_r($publications[0] ?? []);
}
?>

# Run test
php test_scholar.php
```

### Test 5.3: Test Helper Functions

```bash
# Create test file: test_helper.php
<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Helpers\ScholarHelper;
use App\Models\Book;

$book = Book::first();
if ($book) {
    echo "Scholar Enabled: " . (ScholarHelper::isScholarEnabled() ? 'Yes' : 'No') . "\n";
    echo "Meta Tags:\n";
    echo ScholarHelper::getScholarMetaTags($book);
    echo "\n\nStructured Data:\n";
    echo ScholarHelper::getStructuredData($book);
}
?>

# Run test
php test_helper.php
```

---

## 6. Test Meta Tags

### Test 6.1: Check Meta Tags in HTML

```bash
# Get page source
curl https://yourdomain.com/buku/judul-buku | grep "citation_"

# Expected:
# <meta name="citation_title" content="...">
# <meta name="citation_author" content="...">
# <meta name="citation_publication_date" content="...">
```

### Test 6.2: Check Structured Data

```bash
# Get page source
curl https://yourdomain.com/buku/judul-buku | grep "application/ld+json"

# Expected: JSON-LD script tag with ScholarlyArticle
```

### Test 6.3: Check Open Graph Tags

```bash
# Get page source
curl https://yourdomain.com/buku/judul-buku | grep "og:"

# Expected:
# <meta property="og:type" content="book">
# <meta property="og:title" content="...">
# <meta property="og:image" content="...">
```

---

## 7. Test with Google Tools

### Test 7.1: Google Search Console

1. **Add Property**
   - Visit: https://search.google.com/search-console
   - Click "Add property"
   - Enter domain

2. **Verify Ownership**
   - Choose verification method (HTML file, DNS, Google Analytics, etc.)
   - Complete verification

3. **Submit Sitemap**
   - Go to Sitemaps section
   - Click "Add/test sitemap"
   - Enter: `sitemap.php`
   - Click Submit

4. **Monitor**
   - Check Coverage report
   - Monitor Sitemaps statistics
   - Check for errors

### Test 7.2: Google's Rich Results Test

1. **Test Structured Data**
   - Visit: https://search.google.com/test/rich-results
   - Enter URL: `https://yourdomain.com/buku/judul-buku`
   - Click Test
   - Check if ScholarlyArticle is detected

### Test 7.3: Google's Mobile-Friendly Test

1. **Test Mobile Compatibility**
   - Visit: https://search.google.com/test/mobile-friendly
   - Enter URL
   - Check if mobile-friendly

---

## 8. Test with Third-Party Tools

### Test 8.1: XML Sitemap Validator

```bash
# Online tool
https://www.xml-sitemaps.com/validate-xml-sitemap.html

# Upload or enter URL: https://yourdomain.com/sitemap.php
# Check for errors
```

### Test 8.2: Screaming Frog SEO Spider

```bash
# Download: https://www.screamingfrog.co.uk/seo-spider/

# Steps:
# 1. Open Screaming Frog
# 2. Enter URL: https://yourdomain.com/sitemap.php
# 3. Start crawl
# 4. Check for errors
# 5. Export report
```

### Test 8.3: SEMrush Site Audit

```bash
# Visit: https://www.semrush.com/

# Steps:
# 1. Create account
# 2. Add domain
# 3. Run site audit
# 4. Check sitemap issues
# 5. Review recommendations
```

---

## 9. Performance Testing

### Test 9.1: Load Time

```bash
# Test with Apache Bench
ab -n 100 -c 10 https://yourdomain.com/sitemap.php

# Expected: < 1 second average response time
```

### Test 9.2: Concurrent Requests

```bash
# Test with wrk
wrk -t4 -c100 -d30s https://yourdomain.com/sitemap.php

# Expected: No errors, consistent response time
```

### Test 9.3: Cache Headers

```bash
# Check cache headers
curl -I https://yourdomain.com/sitemap.php

# Expected:
# Cache-Control: public, max-age=86400
# X-Robots-Tag: index, follow
```

---

## 10. Database Testing

### Test 10.1: Check Data

```bash
# Connect to database
sqlite3 database/database.sqlite

# Check books
SELECT COUNT(*) FROM books WHERE is_published = 1;

# Check posts
SELECT COUNT(*) FROM posts WHERE is_published = 1;

# Check services
SELECT COUNT(*) FROM services WHERE is_active = 1;

# Check pages
SELECT COUNT(*) FROM pages WHERE is_published = 1;
```

### Test 10.2: Check Scholar Fields

```bash
# Check if scholar fields are populated
SELECT COUNT(*) FROM books WHERE scholar_id IS NOT NULL;
SELECT COUNT(*) FROM books WHERE citation_count > 0;
SELECT COUNT(*) FROM books WHERE publication_year IS NOT NULL;
```

### Test 10.3: Check Timestamps

```bash
# Check last modified dates
SELECT MAX(updated_at) FROM books;
SELECT MAX(published_at) FROM posts;
SELECT MAX(updated_at) FROM services;
```

---

## 11. Troubleshooting Tests

### Test 11.1: Database Connection

```bash
# Check if database file exists
ls -la database/database.sqlite

# Check permissions
stat database/database.sqlite

# Expected: readable by web server
```

### Test 11.2: File Permissions

```bash
# Check sitemap.php permissions
ls -la public/sitemap.php

# Expected: 644 or 755
chmod 644 public/sitemap.php
```

### Test 11.3: PHP Errors

```bash
# Check PHP error log
tail -f storage/logs/laravel.log

# Look for errors related to sitemap or database
```

### Test 11.4: Network Issues

```bash
# Test DNS resolution
nslookup yourdomain.com

# Test connectivity
ping yourdomain.com

# Test HTTP connectivity
curl -v https://yourdomain.com/sitemap.php
```

---

## 12. Automated Testing

### Test 12.1: Create Test Script

```bash
#!/bin/bash
# test_sitemap.sh

DOMAIN="https://yourdomain.com"

echo "Testing Sitemap..."

# Test 1: Accessibility
echo "1. Testing accessibility..."
curl -s -o /dev/null -w "Status: %{http_code}\n" $DOMAIN/sitemap.php

# Test 2: XML validity
echo "2. Testing XML validity..."
curl -s $DOMAIN/sitemap.php | xmllint --noout - && echo "Valid XML" || echo "Invalid XML"

# Test 3: URL count
echo "3. Counting URLs..."
curl -s $DOMAIN/sitemap.php | grep -c "<url>"

# Test 4: Image count
echo "4. Counting images..."
curl -s $DOMAIN/sitemap.php | grep -c "image:image"

# Test 5: robots.txt
echo "5. Testing robots.txt..."
curl -s $DOMAIN/robots.txt | grep "Sitemap"

echo "Testing complete!"
```

### Test 12.2: Run Test Script

```bash
chmod +x test_sitemap.sh
./test_sitemap.sh
```

---

## 13. Monitoring

### Test 13.1: Setup Monitoring

```bash
# Monitor sitemap changes
watch -n 3600 'curl -s https://yourdomain.com/sitemap.php | grep -c "<url>"'

# Monitor database changes
watch -n 3600 'sqlite3 database/database.sqlite "SELECT COUNT(*) FROM books WHERE is_published = 1;"'
```

### Test 13.2: Log Monitoring

```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log | grep -i sitemap

# Monitor PHP errors
tail -f /var/log/php-fpm/error.log
```

---

## 14. Checklist

### Pre-Launch Checklist

- [ ] Sitemap.php accessible
- [ ] XML is valid
- [ ] All URLs included
- [ ] Images included
- [ ] robots.txt updated
- [ ] Sitemap index working
- [ ] Meta tags present
- [ ] Structured data valid
- [ ] Database connected
- [ ] No PHP errors
- [ ] Performance acceptable
- [ ] Cache headers correct

### Post-Launch Checklist

- [ ] Submitted to Google Search Console
- [ ] Sitemap discovered by Google
- [ ] URLs being crawled
- [ ] Coverage report shows progress
- [ ] No crawl errors
- [ ] Indexing status improving
- [ ] Rankings improving
- [ ] Traffic increasing

---

## 15. Common Issues & Solutions

### Issue: 404 Not Found

**Solution**:
```bash
# Check file exists
ls -la public/sitemap.php

# Check permissions
chmod 644 public/sitemap.php

# Restart web server
sudo systemctl restart nginx
# or
sudo systemctl restart apache2
```

### Issue: Invalid XML

**Solution**:
```bash
# Check XML syntax
curl https://yourdomain.com/sitemap.php | xmllint --noout -

# Check for special characters
curl https://yourdomain.com/sitemap.php | grep "&" | head -5
```

### Issue: Database Connection Error

**Solution**:
```bash
# Check database file
ls -la database/database.sqlite

# Check permissions
chmod 644 database/database.sqlite

# Check database integrity
sqlite3 database/database.sqlite ".tables"
```

### Issue: No URLs in Sitemap

**Solution**:
```bash
# Check database has content
sqlite3 database/database.sqlite "SELECT COUNT(*) FROM books WHERE is_published = 1;"

# Check is_published flag
sqlite3 database/database.sqlite "SELECT * FROM books LIMIT 1;"
```

---

## 📊 Test Results Template

```
Date: ___________
Domain: ___________

Sitemap Tests:
- Accessibility: [ ] Pass [ ] Fail
- XML Valid: [ ] Pass [ ] Fail
- URL Count: ___________
- Image Count: ___________
- Response Time: ___________ms

robots.txt Tests:
- Accessible: [ ] Pass [ ] Fail
- Sitemap References: [ ] Pass [ ] Fail
- Disallow Rules: [ ] Pass [ ] Fail

Meta Tags Tests:
- Citation Tags: [ ] Pass [ ] Fail
- Structured Data: [ ] Pass [ ] Fail
- Open Graph: [ ] Pass [ ] Fail

Database Tests:
- Connection: [ ] Pass [ ] Fail
- Scholar Fields: [ ] Pass [ ] Fail
- Data Count: ___________

Google Tools:
- Search Console: [ ] Pass [ ] Fail
- Rich Results: [ ] Pass [ ] Fail
- Mobile Friendly: [ ] Pass [ ] Fail

Notes:
_________________________________
_________________________________
```

---

**Last Updated**: May 1, 2026
**Version**: 1.0.0
