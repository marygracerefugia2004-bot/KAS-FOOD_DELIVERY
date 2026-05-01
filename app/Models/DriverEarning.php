<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverEarning extends Model
{
    protected $fillable = [
        'driver_id',
        'order_id',
        'order_amount',
        'commission_percent',
        'commission_amount',
        'net_amount',
        'status',
        'paid_at',
        'payment_method',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
