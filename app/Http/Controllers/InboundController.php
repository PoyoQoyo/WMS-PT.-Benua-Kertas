<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;

class InboundController extends Controller
{
    public function index()
    {
        $inbounds = Inbound::with('deliveryOrder.details.product')->latest()->get();
        return view('wms.inbound', compact('inbounds'));
    }

    public function create()
    {
        // Ambil ID DO yang sudah pernah dipakai (status apa pun) di Incoming
        $usedDeliveryOrderIds = Inbound::pluck('delivery_order_id')->toArray();
        
        // Ambil DO yang belum pernah dipakai dan tipe "Barang Masuk"
        $deliveryOrders = DeliveryOrder::with('details.product')
            ->where('type', 'Barang Masuk')
            ->whereNotIn('id', $usedDeliveryOrderIds)
            ->get();
            
        return view('wms.inbound-form', compact('deliveryOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'incoming_id' => 'required|unique:inbounds',
            'delivery_order_id' => 'required|exists:delivery_orders,id',
            'date_received' => 'required|date',
            'nett' => 'required|numeric|min:0',
            'gross' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Diterima,Ditolak',
        ]);

        Inbound::create($validated);
        return redirect()->route('inbound.index')->with('success', 'Incoming berhasil dicatat');
    }

    public function edit($id)
    {
        $inbound = Inbound::findOrFail($id);
        
        // Ambil ID DO yang sudah pernah dipakai (status apa pun), kecuali DO dari inbound yang sedang diedit
        $usedDeliveryOrderIds = Inbound::where('id', '!=', $id)
            ->pluck('delivery_order_id')
            ->toArray();
        
        // Ambil DO yang belum digunakan atau DO dari inbound yang sedang diedit dan tipe "Barang Masuk"
        $deliveryOrders = DeliveryOrder::with('details.product')
            ->where('type', 'Barang Masuk')
            ->where(function($query) use ($usedDeliveryOrderIds, $inbound) {
                $query->whereNotIn('id', $usedDeliveryOrderIds)
                      ->orWhere('id', $inbound->delivery_order_id);
            })
            ->get();
            
        return view('wms.inbound-form', compact('inbound', 'deliveryOrders'));
    }

    public function update(Request $request, $id)
    {
        $inbound = Inbound::findOrFail($id);
        
        $validated = $request->validate([
            'incoming_id' => 'required|unique:inbounds,incoming_id,' . $id,
            'delivery_order_id' => 'required|exists:delivery_orders,id',
            'date_received' => 'required|date',
            'nett' => 'required|numeric|min:0',
            'gross' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Diterima,Ditolak',
        ]);

        $inbound->update($validated);
        return redirect()->route('inbound.index')->with('success', 'Incoming berhasil diperbarui');
    }

    public function destroy($id)
    {
        Inbound::findOrFail($id)->delete();
        return redirect()->route('inbound.index')->with('success', 'Incoming berhasil dihapus');
    }

    public function show($id)
    {
        $inbound = Inbound::with('deliveryOrder.details.product')->findOrFail($id);
        return view('wms.inbound-detail', compact('inbound'));
    }

    public function updateStatus(Request $request, $id)
    {
        $inbound = Inbound::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:Pending,Diterima,Ditolak',
        ]);

        $inbound->update($validated);
        
        $message = $validated['status'] === 'Diterima' 
            ? 'Status diubah menjadi Diterima dan stok telah diperbarui' 
            : 'Status berhasil diperbarui';
            
        return redirect()->route('inbound.index')->with('success', $message);
    }

    public function print($id)
    {
        $inbound = Inbound::with('deliveryOrder.details.product')->findOrFail($id);
        return view('wms.prints.inbound-print', compact('inbound'));
    }

    public function printAll()
    {
        $inbounds = Inbound::with('deliveryOrder.details.product')->latest()->get();
        return view('wms.prints.inbound-print-all', compact('inbounds'));
    }
}

