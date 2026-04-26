<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasSlug, LogsAudit;

    protected $fillable = ['name', 'slug', 'type'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
