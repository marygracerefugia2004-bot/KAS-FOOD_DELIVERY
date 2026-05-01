<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'food_id', 'driver_id', 'status',
        'delivery_address', 'latitude', 'longitude',
        'driver_latitude', 'driver_longitude',
        'estimated_arrival', 'quantity', 'total_price',
        'promo_code', 'discount', 'delivery_proof',
        'special_instructions',
    ];

    protected $casts = [
        'estimated_arrival' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'driver_latitude' => 'float',
        'driver_longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function countdownSeconds(): int
    {
        if (! $this->estimated_arrival) {
            return 0;
        }

        return max(0, now()->diffInSeconds($this->estimated_arrival, false));
    }
}
