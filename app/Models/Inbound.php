<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $fillable = [
        'incoming_id',
        'container_no',
        'delivery_order_id',
        'date_received',
        'nett',
        'gross',
        'status'
    ];

    protected $casts = [
        'date_received' => 'date',
        'nett' => 'decimal:2',
        'gross' => 'decimal:2',
    ];

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    // Event: ketika status diubah menjadi "Diterima", update stok barang
    protected static function booted()
    {
        static::created(function ($inbound) {
            if ($inbound->status === 'Diterima') {
                $inbound->updateStock();
            }
        });

        static::updated(function ($inbound) {
            if ($inbound->isDirty('status') && $inbound->status === 'Diterima') {
                $inbound->updateStock();
            }
        });
    }

    public function updateStock()
    {
        // Ambil detail dari delivery order
        $details = $this->deliveryOrder->details;

        foreach ($details as $detail) {
            $product = Product::where('sku', $detail->sku)->first();
            if ($product) {
                $product->stock += $detail->quantity;
                $product->save();
            }
        }
    }
}
