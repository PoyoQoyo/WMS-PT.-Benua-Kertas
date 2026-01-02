<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\Product;
use Illuminate\Http\Request;

class OutboundController extends Controller
{
    public function index()
    {
        $outbounds = Outbound::with('deliveryOrder')->latest()->get();
        return view('wms.outbound', compact('outbounds'));
    }

    public function create()
    {
        // Ambil DO dengan tipe "Barang Keluar" yang belum pernah digunakan (status apa pun)
        $usedDeliveryOrderNos = Outbound::pluck('no_do')->toArray();
        
        $deliveryOrders = \App\Models\DeliveryOrder::where('type', 'Barang Keluar')
            ->whereNotIn('no_do', $usedDeliveryOrderNos)
            ->get();
        
        return view('wms.outbound-form', compact('deliveryOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outgoing_id' => 'required|unique:outbounds',
            'date' => 'required|date',
            'no_do' => 'required',
            'nett' => 'required|numeric|min:0',
            'gross' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Dikirim,Dibatalkan',
        ]);

        // Validasi stok jika status adalah Dikirim
        if ($validated['status'] === 'Dikirim') {
            $deliveryOrder = \App\Models\DeliveryOrder::where('no_do', $validated['no_do'])->first();
            if ($deliveryOrder) {
                foreach ($deliveryOrder->details as $detail) {
                    $product = Product::where('sku', $detail->sku)->first();
                    if ($product && $product->stock < $detail->quantity) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['no_do' => "Stok tidak mencukupi untuk SKU {$detail->sku}. Stok tersedia: {$product->stock}"]);
                    }
                }
            }
        }

        Outbound::create($validated);
        return redirect()->route('outbound.index')->with('success', 'Outgoing berhasil dicatat');
    }

    public function edit($id)
    {
        $outbound = Outbound::findOrFail($id);
        
        // Ambil DO dengan tipe "Barang Keluar" yang sudah pernah digunakan (status apa pun), kecuali DO dari outbound yang sedang diedit
        $usedDeliveryOrderNos = Outbound::where('id', '!=', $id)
            ->pluck('no_do')
            ->toArray();
        
        $deliveryOrders = \App\Models\DeliveryOrder::where('type', 'Barang Keluar')
            ->where(function($query) use ($usedDeliveryOrderNos, $outbound) {
                $query->whereNotIn('no_do', $usedDeliveryOrderNos)
                      ->orWhere('no_do', $outbound->no_do);
            })
            ->get();
        
        return view('wms.outbound-form', compact('outbound', 'deliveryOrders'));
    }

    public function update(Request $request, $id)
    {
        $outbound = Outbound::findOrFail($id);
        
        $validated = $request->validate([
            'outgoing_id' => 'required|unique:outbounds,outgoing_id,' . $id,
            'date' => 'required|date',
            'no_do' => 'required',
            'nett' => 'required|numeric|min:0',
            'gross' => 'required|numeric|min:0',
            'status' => 'required|in:Pending,Dikirim,Dibatalkan',
        ]);

        // Validasi stok jika status diubah menjadi Dikirim
        if ($validated['status'] === 'Dikirim' && $outbound->status !== 'Dikirim') {
            $deliveryOrder = \App\Models\DeliveryOrder::where('no_do', $validated['no_do'])->first();
            if ($deliveryOrder) {
                foreach ($deliveryOrder->details as $detail) {
                    $product = Product::where('sku', $detail->sku)->first();
                    if ($product && $product->stock < $detail->quantity) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['no_do' => "Stok tidak mencukupi untuk SKU {$detail->sku}. Stok tersedia: {$product->stock}"]);
                    }
                }
            }
        }

        $outbound->update($validated);
        return redirect()->route('outbound.index')->with('success', 'Outgoing berhasil diperbarui');
    }

    public function destroy($id)
    {
        Outbound::findOrFail($id)->delete();
        return redirect()->route('outbound.index')->with('success', 'Outgoing berhasil dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $outbound = Outbound::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:Pending,Dikirim,Dibatalkan',
        ]);

        // Validasi stok jika status diubah menjadi Dikirim
        if ($validated['status'] === 'Dikirim' && $outbound->status !== 'Dikirim') {
            $deliveryOrder = \App\Models\DeliveryOrder::where('no_do', $outbound->no_do)->first();
            if ($deliveryOrder) {
                foreach ($deliveryOrder->details as $detail) {
                    $product = Product::where('sku', $detail->sku)->first();
                    if ($product && $product->stock < $detail->quantity) {
                        return redirect()->back()
                            ->withErrors(['status' => "Stok tidak mencukupi untuk SKU {$detail->sku}. Stok tersedia: {$product->stock}"]);
                    }
                }
            }
        }

        $outbound->update($validated);
        
        $message = $validated['status'] === 'Dikirim' 
            ? 'Status diubah menjadi Dikirim dan stok telah dikurangi' 
            : 'Status berhasil diperbarui';
            
        return redirect()->route('outbound.index')->with('success', $message);
    }

    public function print($id)
    {
        $outbound = Outbound::with('deliveryOrder.details.product')->findOrFail($id);
        return view('wms.prints.outbound-print', compact('outbound'));
    }

    public function printAll()
    {
        $outbounds = Outbound::with('deliveryOrder.details.product')->latest()->get();
        return view('wms.prints.outbound-print-all', compact('outbounds'));
    }
}

