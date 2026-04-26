<?php

namespace App\Models;

use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use LogsAudit;
    protected $fillable = ['name', 'email', 'subject', 'message', 'ip_address', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];
}
