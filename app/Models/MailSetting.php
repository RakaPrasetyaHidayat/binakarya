<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    protected $fillable = [
        'driver', 'host', 'port', 'encryption', 'username', 'password',
        'from_address', 'from_name',
        'mailketing_api_key', 'mailketing_sender_email', 'recipient_email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'port' => 'integer'
    ];

    protected $hidden = ['password', 'mailketing_api_key'];

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}
