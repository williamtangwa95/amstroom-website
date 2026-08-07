<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait to record events automatically.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('created', $model);
        });

        static::updated(function ($model) {
            self::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model);
        });
    }

    /**
     * Log the activity to the database.
     */
    protected static function logActivity(string $action, $model)
    {
        // Activity logs track actions done by logged-in users
        if (!Auth::check()) {
            return;
        }

        $details = [];
        $modelName = class_basename($model);

        if ($action === 'created') {
            $details = [
                'message' => "Created a new {$modelName}: " . ($model->name ?? $model->title ?? "ID {$model->id}"),
                'attributes' => $model->toArray(),
            ];
        } elseif ($action === 'updated') {
            $dirty = $model->getDirty();
            $before = [];
            $after = [];

            foreach ($dirty as $key => $value) {
                // Don't log update timestamps or passwords
                if (in_array($key, ['updated_at', 'password', 'remember_token'])) {
                    continue;
                }
                $before[$key] = $model->getOriginal($key);
                $after[$key] = $value;
            }

            // Only log if something meaningful actually changed
            if (empty($after)) {
                return;
            }

            $details = [
                'message' => "Updated {$modelName}: " . ($model->name ?? $model->title ?? "ID {$model->id}"),
                'before' => $before,
                'after' => $after,
            ];
        } elseif ($action === 'deleted') {
            $details = [
                'message' => "Deleted {$modelName}: " . ($model->name ?? $model->title ?? "ID {$model->id}"),
                'attributes' => $model->toArray(),
            ];
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
