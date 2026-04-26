<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'position',
        'organization',
        'content',
        'image',
        'rating',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: Get only active testimonials
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by sort_order
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->latest();
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
