<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_email',
        'action',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'details',
        'created_at',
    ];

    public static function log(string $action, ?int $userId = null, ?string $userEmail = null, ?string $modelType = null, ?int $modelId = null, ?string $details = null): void
    {
        try {
            self::create([
                'user_id' => $userId,
                'user_email' => $userEmail,
                'action' => $action,
                'model_type' => $modelType,
                'model_id' => $modelId,
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
                'details' => $details,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Fail safely without blocking main application flow
        }
    }
}
