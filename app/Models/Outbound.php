<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $fillable = [
        'outgoing_id',
        'date',
        'sku',
        'quantity',
        'no_do',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }

    // Event: ketika status diubah menjadi "Dikirim", kurangi stok barang
    protected static function booted()
    {
        static::updated(function ($outbound) {
            if ($outbound->isDirty('status') && $outbound->status === 'Dikirim') {
                $outbound->updateStock();
            }
        });

        static::created(function ($outbound) {
            if ($outbound->status === 'Dikirim') {
                $outbound->updateStock();
            }
        });
    }

    public function updateStock()
    {
        $product = Product::where('sku', $this->sku)->first();
        if ($product) {
            $product->stock -= $this->quantity;
            $product->save();
        }
    }
}
