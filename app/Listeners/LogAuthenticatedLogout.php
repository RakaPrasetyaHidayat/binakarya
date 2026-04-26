<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Auth\Events\Logout;

class LogAuthenticatedLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        AuditLog::logLogout();
    }
}
