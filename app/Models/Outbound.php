<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $fillable = [
        'outgoing_id',
        'date',
        'no_do',
        'nett',
        'gross',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'nett' => 'decimal:2',
        'gross' => 'decimal:2',
    ];

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class, 'no_do', 'no_do');
    }

    // Event: ketika status diubah menjadi "Dikirim", kurangi stok barang berdasarkan detail DO
    protected static function booted()
    {
        static::created(function ($outbound) {
            if ($outbound->status === 'Dikirim') {
                $outbound->updateStock('decrease');
            }
        });

        static::updated(function ($outbound) {
            if ($outbound->isDirty('status')) {
                $oldStatus = $outbound->getOriginal('status');
                $newStatus = $outbound->status;

                // Jika status berubah dari Dikirim ke status lain, tambah stok kembali
                if ($oldStatus === 'Dikirim' && $newStatus !== 'Dikirim') {
                    $outbound->updateStock('increase');
                }
                // Jika status berubah ke Dikirim dari status lain, kurangi stok
                elseif ($oldStatus !== 'Dikirim' && $newStatus === 'Dikirim') {
                    $outbound->updateStock('decrease');
                }
            }
        });
    }

    public function updateStock($action = 'decrease')
    {
        // Ambil detail dari delivery order
        $deliveryOrder = $this->deliveryOrder;
        if (!$deliveryOrder) return;
        
        $details = $deliveryOrder->details;

        foreach ($details as $detail) {
            $product = Product::where('sku', $detail->sku)->first();
            if ($product) {
                if ($action === 'decrease') {
                    $product->stock -= $detail->quantity;
                } else {
                    $product->stock += $detail->quantity;
                }
                $product->save();
            }
        }
    }
}
