<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    protected $fillable = [
        'driver_id',
        'service_type',
        'description',
        'cost',
        'odometer_km',
        'service_date',
        'next_service_due',
        'service_center',
        'receipt_image',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'odometer_km' => 'integer',
        'service_date' => 'date',
        'next_service_due' => 'date',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
