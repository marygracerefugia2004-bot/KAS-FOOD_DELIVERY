<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percent',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isValid()
    {
        return $this->is_active && 
               ($this->used_count < $this->max_uses) && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }
}