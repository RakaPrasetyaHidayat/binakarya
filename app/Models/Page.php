<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use LogsAudit;

    protected $fillable = ['title', 'slug', 'content', 'is_published', 'meta_description'];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
