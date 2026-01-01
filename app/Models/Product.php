<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sku', 'name', 'category', 'unit', 'stock', 'location', 'status'];

    public function outbounds()
    {
        return $this->hasMany(Outbound::class, 'sku', 'sku');
    }

    public function deliveryOrderDetails()
    {
        return $this->hasMany(DeliveryOrderDetail::class, 'sku', 'sku');
    }
}
