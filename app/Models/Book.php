<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Book extends Model
{
    use HasSlug, LogsAudit;

    protected $fillable = [
        'category_id', 'title', 'slug', 'author', 'isbn', 'doi',
        'published_year', 'abstract', 'cover_image', 'pdf_file',
        'wa_number', 'shopee_url', 'tokopedia_url', 'custom_url',
        'custom_url_label', 'price', 'is_published',
        'keywords', 'edition', 'preview_file', 'preview_url',
    ];

    protected $casts = ['is_published' => 'boolean', 'price' => 'decimal:2'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $this->cover_image)) {
            return $this->cover_image;
        }

        return asset('storage/' . $this->cover_image);
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_file ? asset('storage/' . $this->pdf_file) : null;
    }

    public function getPreviewUrlAttribute(): ?string
    {
        // Priority: uploaded preview_file → raw external preview_url
        if ($this->preview_file) {
            return asset('storage/' . $this->preview_file);
        }
        
        $rawUrl = $this->getRawOriginal('preview_url');
        if ($rawUrl) {
            return $rawUrl;
        }
        
        return null;
    }

    public function getWaLinkAttribute(): ?string
    {
        if (!$this->wa_number) return null;
        $msg = urlencode("Halo, saya ingin membeli buku: {$this->title}");
        return "https://wa.me/{$this->wa_number}?text={$msg}";
    }
}
