<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePlan extends Model
{
    protected $fillable = [
        'service_id',
        'name',
        'subtitle',
        'price',
        'features',
        'is_popular',
        'order',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
