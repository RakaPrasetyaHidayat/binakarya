<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use LogsAudit;

    protected $fillable = ['label', 'url', 'order', 'is_external', 'is_active', 'target', 'parent_id', 'icon', 'subtitle', 'description', 'thumbnail'];

    protected $casts = [
        'is_external' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }
}
