<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasSlug, LogsAudit;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'excerpt',
        'body', 'featured_image', 'detail_image', 'meta_title', 'meta_description',
        'is_published', 'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        /**
         * Sanitize HTML content before saving to prevent XSS
         */
        static::saving(function ($post) {
            if ($post->isDirty('body')) {
                $post->body = \Purifier::clean($post->body);
            }
            if ($post->isDirty('excerpt')) {
                $post->excerpt = strip_tags($post->excerpt);
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get related posts by category (excluding current post)
     */
    public function relatedPosts($limit = 3)
    {
        return Post::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->published()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Scope to get published posts only
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    /**
     * Scope for pagination
     */
    public function scopePaginated($query, $perPage = 6)
    {
        return $query->paginate($perPage);
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getDetailImageUrlAttribute(): ?string
    {
        return $this->detail_image ? asset('storage/' . $this->detail_image) : null;
    }

    /**
     * Get sanitized body for display
     */
    public function getCleanBodyAttribute(): string
    {
        return \Purifier::clean($this->body);
    }
}

