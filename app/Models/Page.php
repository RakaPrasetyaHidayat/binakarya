<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory, LogsAudit;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_published',
        'meta_description',
        'content_mode',
        'content_blocks',
        'custom_css',
        'custom_js',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'content_blocks' => 'array',
    ];

    public function isBuilderMode(): bool
    {
        return $this->content_mode === 'builder';
    }

    public function hasBuilderBlocks(): bool
    {
        return $this->isBuilderMode()
            && is_array($this->content_blocks)
            && count($this->content_blocks) > 0;
    }
}
