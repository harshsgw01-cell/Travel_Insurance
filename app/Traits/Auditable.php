<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            self::writeAudit($model, 'created', [], $model->getAttributes());
        });

        static::updated(function ($model) {
            self::writeAudit($model, 'updated', $model->getOriginal(), $model->getChanges());
        });

        static::deleted(function ($model) {
            self::writeAudit($model, 'deleted', $model->getAttributes(), []);
        });
    }

    private static function writeAudit($model, string $action, array $old, array $new): void
    {
        try {
            AuditLog::create([
                'user_id'          => Auth::id(),
                'auditable_type'   => get_class($model),
                'auditable_id'     => $model->getKey(),
                'action'           => $action,
                'old_values'       => $old,
                'new_values'       => $new,
                'ip_address'       => Request::ip(),
                'user_agent'       => Request::userAgent(),
            ]);
        } catch (\Throwable) {
            // Never let audit failures break the main flow
        }
    }
}
