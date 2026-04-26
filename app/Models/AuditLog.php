<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an admin action
     */
    public static function logAction(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): void {
        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
        ]);
    }

    /**
     * Log a login
     */
    public static function logLogin(User $user): void
    {
        self::logAction(
            'login',
            'App\Models\User',
            $user->id,
            null,
            ['user_id' => $user->id, 'email' => $user->email],
            "User {$user->email} has logged in"
        );
    }

    /**
     * Log a logout
     */
    public static function logLogout(): void
    {
        if (auth()->check()) {
            $user = auth()->user();
            self::logAction(
                'logout',
                'App\Models\User',
                $user->id,
                null,
                null,
                "User {$user->email} has logged out"
            );
        }
    }

    /**
     * Get action label in Indonesian
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create' => 'Dibuat',
            'update' => 'Diubah',
            'delete' => 'Dihapus',
            'login' => 'Login',
            'logout' => 'Logout',
            'view' => 'Dilihat',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get a summary of changes for display
     */
    public function getChangesSummaryAttribute(): string
    {
        if ($this->action === 'delete') {
            return 'Data dihapus permanen';
        }

        if ($this->new_values && $this->old_values) {
            $changes = [];
            foreach ($this->new_values as $key => $newValue) {
                if (isset($this->old_values[$key]) && $this->old_values[$key] !== $newValue) {
                    $changes[] = "{$key}: {$this->old_values[$key]} → {$newValue}";
                }
            }
            return implode(', ', $changes) ?: 'Tidak ada perubahan terdeteksi';
        }

        return $this->description ?? '-';
    }
}
