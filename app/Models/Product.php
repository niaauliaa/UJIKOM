<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_product',	
        'picture', 
        'price',        
        'stock',        
    ];

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'product_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2', // Harga dengan 2 desimal
    ];

    public function getFormattedPriceAttribute()
    {
    return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}

