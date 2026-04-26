<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Login;

class LogAuthenticatedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        AuditLog::logLogin($event->user);
    }
}
