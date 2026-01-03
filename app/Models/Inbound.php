<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $fillable = [
        'incoming_id',
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
                $inbound->updateStock('increase');
            }
        });

        static::updated(function ($inbound) {
            if ($inbound->isDirty('status')) {
                $oldStatus = $inbound->getOriginal('status');
                $newStatus = $inbound->status;

                // Jika status berubah dari Diterima ke status lain, kurangi stok
                if ($oldStatus === 'Diterima' && $newStatus !== 'Diterima') {
                    $inbound->updateStock('decrease');
                }
                // Jika status berubah ke Diterima dari status lain, tambah stok
                elseif ($oldStatus !== 'Diterima' && $newStatus === 'Diterima') {
                    $inbound->updateStock('increase');
                }
            }
        });

        static::deleted(function ($inbound) {
            // Jika data dihapus dan statusnya adalah Diterima, kurangi stok
            if ($inbound->status === 'Diterima') {
                $inbound->updateStock('decrease');
            }
        });
    }

    public function updateStock($action = 'increase')
    {
        // Ambil detail dari delivery order
        $details = $this->deliveryOrder->details;

        foreach ($details as $detail) {
            $product = Product::where('sku', $detail->sku)->first();
            if ($product) {
                if ($action === 'increase') {
                    $product->stock += $detail->quantity;
                } else {
                    $product->stock -= $detail->quantity;
                }
                $product->save();
            }
        }
    }
}
