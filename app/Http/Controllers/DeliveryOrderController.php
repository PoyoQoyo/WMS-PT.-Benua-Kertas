<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        $deliveryOrders = DeliveryOrder::with('details.product')->get();
        return view('wms.delivery-order', compact('deliveryOrders'));
    }

    public function create()
    {
        $products = Product::where('status', 'Aktif')->get();
        return view('wms.delivery-order-form', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_do' => 'required|unique:delivery_orders',
            'driver' => 'required',
            'date' => 'required|date',
            'notes' => 'nullable',
            'type' => 'required|in:Barang Masuk,Barang Keluar',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            $deliveryOrder = DeliveryOrder::create([
                'no_do' => $validated['no_do'],
                'driver' => $validated['driver'],
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
                'type' => $validated['type'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::where('sku', $item['sku'])->first();
                DeliveryOrderDetail::create([
                    'delivery_order_id' => $deliveryOrder->id,
                    'sku' => $item['sku'],
                    'quantity' => $item['quantity'],
                    'unit' => $product->unit,
                ]);
            }
        });

        return redirect()->route('delivery-orders.index')->with('success', 'Delivery Order berhasil ditambahkan');
    }

    public function edit($id)
    {
        $deliveryOrder = DeliveryOrder::with('details')->findOrFail($id);
        $products = Product::where('status', 'Aktif')->get();
        return view('wms.delivery-order-form', compact('deliveryOrder', 'products'));
    }

    public function update(Request $request, $id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);
        
        $validated = $request->validate([
            'no_do' => 'required|unique:delivery_orders,no_do,' . $id,
            'driver' => 'required',
            'date' => 'required|date',
            'notes' => 'nullable',
            'type' => 'required|in:Barang Masuk,Barang Keluar',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|exists:products,sku',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($validated, $deliveryOrder) {
            $deliveryOrder->update([
                'no_do' => $validated['no_do'],
                'driver' => $validated['driver'],
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
                'type' => $validated['type'],
            ]);

            // Delete old details and create new ones
            $deliveryOrder->details()->delete();

            foreach ($validated['items'] as $item) {
                $product = Product::where('sku', $item['sku'])->first();
                DeliveryOrderDetail::create([
                    'delivery_order_id' => $deliveryOrder->id,
                    'sku' => $item['sku'],
                    'quantity' => $item['quantity'],
                    'unit' => $product->unit,
                ]);
            }
        });

        return redirect()->route('delivery-orders.index')->with('success', 'Delivery Order berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);
        
        // Cek apakah DO ini sudah digunakan di Incoming
        if ($deliveryOrder->inbounds()->count() > 0) {
            return redirect()->route('delivery-orders.index')
                ->with('error', 'Delivery Order tidak dapat dihapus karena sudah digunakan di Incoming.');
        }
        
        // Hapus details terlebih dahulu
        $deliveryOrder->details()->delete();
        
        // Kemudian hapus DO
        $deliveryOrder->delete();
        
        return redirect()->route('delivery-orders.index')->with('success', 'Delivery Order berhasil dihapus');
    }

    public function show($id)
    {
        $deliveryOrder = DeliveryOrder::with('details.product')->findOrFail($id);
        return view('wms.delivery-order-detail', compact('deliveryOrder'));
    }

    public function print($id)
    {
        $deliveryOrder = DeliveryOrder::with('details.product')->findOrFail($id);
        return view('wms.prints.delivery-order-print', compact('deliveryOrder'));
    }

    public function printAll()
    {
        $deliveryOrders = DeliveryOrder::with('details.product')->get();
        return view('wms.prints.delivery-order-print-all', compact('deliveryOrders'));
    }
}
