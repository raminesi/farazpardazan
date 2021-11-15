<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'quantity',
        'price',
        'total_price',
        'transaction',
        'status_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function status()
    {
        return $this->belongsTo(OrderStatus::class , 'status_id');
    }
}
