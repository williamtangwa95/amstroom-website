<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'country',
        'city',
        'device_type',
        'platform',
        'browser',
        'url',
        'method',
        'user_id'
    ];

    /**
     * Get the user that is associated with this visitor log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
