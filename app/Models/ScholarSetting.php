<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarSetting extends Model
{
    protected $fillable = [
        'api_key', 'author_id', 'institution',
        'auto_sync', 'sync_interval', 'last_sync', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_sync' => 'boolean',
        'sync_interval' => 'integer',
        'last_sync' => 'datetime'
    ];

    protected $hidden = ['api_key'];

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
