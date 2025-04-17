<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'total_price',
        'bayar',
        'change',
        'points',
        'used_points',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi dengan DetailTransaction
    public function details()
    {
        return $this->hasMany(DetailTransaction::class);
    }


}
