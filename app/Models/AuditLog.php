<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model {
    public $timestamps = false;

    protected $fillable = [
        'user_id','role','action','description',
        'ip_address','user_agent','old_values','new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // Immutable — no updates allowed
    public static function boot() {
        parent::boot();
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }

    public function user() { return $this->belongsTo(User::class); }

    public static function record(string $action, string $description, $oldVals = null, $newVals = null): void {
        static::create([
            'user_id'     => auth()->id(),
            'role'        => auth()->user()?->role ?? 'guest',
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'old_values'  => $oldVals,
            'new_values'  => $newVals,
            'created_at'  => now(),
        ]);
    }
}