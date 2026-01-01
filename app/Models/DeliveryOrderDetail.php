<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderDetail extends Model
{
    protected $fillable = [
        'delivery_order_id',
        'sku',
        'quantity',
        'unit'
    ];

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }
}
