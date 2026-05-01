<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone',
        'avatar', 'is_active', 'dark_mode',
        'last_login_at', 'login_attempts', 'locked_until',
        'email_verified_at', 'otp', 'otp_expires_at',
        // Driver specific
        'vehicle_type', 'vehicle_model', 'license_plate',
        'driver_license_number', 'driver_license_expiry', 'years_of_experience',
        'total_earnings', 'available_balance', 'pending_balance',
        'last_service_date', 'next_service_due', 'service_interval_km', 'last_service_km',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'otp_expires_at' => 'datetime',
        'driver_license_expiry' => 'date',
        'last_service_date' => 'date',
        'next_service_due' => 'date',
        'is_active' => 'boolean',
        'dark_mode' => 'boolean',
        'password' => 'hashed',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function driverOrders()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDriver()
    {
        return $this->role === 'driver';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function recvMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')->where('is_read', false);
    }

    // Driver specific relationships
    public function earnings()
    {
        return $this->hasMany(DriverEarning::class, 'driver_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'driver_id');
    }

    public function vehicleMaintenances()
    {
        return $this->hasMany(VehicleMaintenance::class, 'driver_id');
    }

    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class, 'driver_id');
    }
}
