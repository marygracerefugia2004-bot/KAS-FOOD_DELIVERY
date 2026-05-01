<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverRequest extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
