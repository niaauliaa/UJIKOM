<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'member_status',
        'joined_at',
        'points',
    ];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'customer_id');
    }
}
