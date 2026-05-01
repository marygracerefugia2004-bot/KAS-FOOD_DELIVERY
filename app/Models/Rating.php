<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model {
    protected $fillable = ['user_id','order_id','food_id','stars','review'];
    public function user()  { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function food()  { return $this->belongsTo(Food::class); }
}