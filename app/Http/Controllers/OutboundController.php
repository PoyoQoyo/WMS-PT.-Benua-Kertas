<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\Product;
use Illuminate\Http\Request;

class OutboundController extends Controller
{
    public function index()
    {
        $outbounds = Outbound::with('product')->latest()->get();
        return view('wms.outbound', compact('outbounds'));
    }

    public function create()
    {
        $products = Product::where('status', 'Aktif')->where('stock', '>', 0)->get();
        return view('wms.outbound-form', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outgoing_id' => 'required|unique:outbounds',
            'date' => 'required|date',
            'sku' => 'required|exists:products,sku',
            'quantity' => 'required|numeric|min:1',
            'no_do' => 'required',
            'status' => 'required|in:Pending,Dikirim,Dibatalkan',
        ]);

        // Validasi stok
        $product = Product::where('sku', $validated['sku'])->first();
        if ($validated['status'] === 'Dikirim' && $product->stock < $validated['quantity']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock]);
        }

        Outbound::create($validated);
        return redirect()->route('outbound.index')->with('success', 'Outgoing berhasil dicatat');
    }

    public function edit($id)
    {
        $outbound = Outbound::findOrFail($id);
        $products = Product::where('status', 'Aktif')->get();
        return view('wms.outbound-form', compact('outbound', 'products'));
    }

    public function update(Request $request, $id)
    {
        $outbound = Outbound::findOrFail($id);
        
        $validated = $request->validate([
            'outgoing_id' => 'required|unique:outbounds,outgoing_id,' . $id,
            'date' => 'required|date',
            'sku' => 'required|exists:products,sku',
            'quantity' => 'required|numeric|min:1',
            'no_do' => 'required',
            'status' => 'required|in:Pending,Dikirim,Dibatalkan',
        ]);

        // Validasi stok jika status diubah menjadi Dikirim
        if ($validated['status'] === 'Dikirim' && $outbound->status !== 'Dikirim') {
            $product = Product::where('sku', $validated['sku'])->first();
            if ($product->stock < $validated['quantity']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantity' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock]);
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
            $product = Product::where('sku', $outbound->sku)->first();
            if ($product->stock < $outbound->quantity) {
                return redirect()->back()
                    ->withErrors(['status' => 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock]);
            }
        }

        $outbound->update($validated);
        
        $message = $validated['status'] === 'Dikirim' 
            ? 'Status diubah menjadi Dikirim dan stok telah dikurangi' 
            : 'Status berhasil diperbarui';
            
        return redirect()->route('outbound.index')->with('success', $message);
    }
}

