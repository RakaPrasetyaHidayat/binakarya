<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Service extends Model
{
    use HasSlug, LogsAudit;

    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'icon', 'external_url', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('title')->saveSlugsTo('slug');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(ServicePlan::class)->orderBy('order');
    }
}
