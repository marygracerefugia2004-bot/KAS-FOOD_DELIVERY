<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model {
    use HasFactory;

    protected $fillable = [
        'name','description','price','image_path',
        'category','available','prep_time',
    ];

    protected $casts = ['available' => 'boolean'];

    public function orders()    { return $this->hasMany(Order::class); }
    public function ratings()   { return $this->hasMany(Rating::class); }
    public function favorites() { return $this->hasMany(Favorite::class); }

    public function avgRating(): float {
    return round($this->ratings()->avg('food_rating') ?? 0, 1);
}


     protected $table = 'foods';   // ← ADD THIS LINE

   
    
}