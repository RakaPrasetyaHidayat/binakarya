<?php

namespace App\Traits;

use App\Models\AuditLog;

trait LogsAudit
{
    protected static function bootLogsAudit()
    {

        /**
         * Log when a model is created
         */
        static::created(function ($model) {
            AuditLog::logAction(
                'create',
                get_class($model),
                $model->getKey(),
                null,
                $model->getAttributes(),
                "Data {$model->getTable()} baru dibuat: ID {$model->getKey()}"
            );
        });

        /**
         * Log when a model is updated
         */
        static::updated(function ($model) {
            $changes = $model->getChanges();
            AuditLog::logAction(
                'update',
                get_class($model),
                $model->getKey(),
                $model->getOriginal(),
                $changes,
                "Data {$model->getTable()} dengan ID {$model->getKey()} telah diubah"
            );
        });

        /**
         * Log when a model is deleted
         */
        static::deleted(function ($model) {
            AuditLog::logAction(
                'delete',
                get_class($model),
                $model->getKey(),
                $model->getAttributes(),
                null,
                "Data {$model->getTable()} dengan ID {$model->getKey()} telah dihapus"
            );
        });
    }
}
