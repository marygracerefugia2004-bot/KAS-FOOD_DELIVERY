<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'driver_id',
        'amount',
        'payment_method',
        'payment_details',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
