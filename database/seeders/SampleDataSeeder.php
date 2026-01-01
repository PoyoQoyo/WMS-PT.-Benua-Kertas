<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample products with stock 0
        Product::create([
            'sku' => 'BK-A4-80',
            'name' => 'Kertas A4 80 gsm',
            'category' => 'Kertas',
            'unit' => 'Rim',
            'stock' => 0,
            'location' => 'Rak A1',
            'status' => 'Aktif'
        ]);

        Product::create([
            'sku' => 'BK-A3-100',
            'name' => 'Kertas A3 100 gsm',
            'category' => 'Kertas',
            'unit' => 'Rim',
            'stock' => 0,
            'location' => 'Rak A2',
            'status' => 'Aktif'
        ]);

        // Create delivery order
        $do = DeliveryOrder::create([
            'no_do' => 'DO-001',
            'driver' => 'Budi',
            'date' => '2025-01-10',
            'notes' => 'Pengiriman dari supplier'
        ]);

        // Create delivery order details
        DeliveryOrderDetail::create([
            'delivery_order_id' => $do->id,
            'sku' => 'BK-A4-80',
            'quantity' => 100,
            'unit' => 'Rim'
        ]);

        DeliveryOrderDetail::create([
            'delivery_order_id' => $do->id,
            'sku' => 'BK-A3-100',
            'quantity' => 50,
            'unit' => 'Rim'
        ]);

        $this->command->info('Sample data created successfully!');
    }
}
