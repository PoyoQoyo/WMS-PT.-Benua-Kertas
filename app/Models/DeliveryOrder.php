<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $fillable = [
        'no_do',
        'driver',
        'date',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function details()
    {
        return $this->hasMany(DeliveryOrderDetail::class);
    }

    public function inbounds()
    {
        return $this->hasMany(Inbound::class);
    }
}
