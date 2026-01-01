<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inbound;
use App\Models\Outbound;
use Illuminate\Http\Request;

class WmsController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        // Hitung jumlah incoming yang diterima hari ini
        $inboundCount = Inbound::whereDate('date_received', today())
            ->where('status', 'Diterima')
            ->count();
            
        $outboundCount = Outbound::whereDate('created_at', today())->sum('quantity') ?? 0;
        $skuCount = Product::count() ?? 0;
        $newSkuThisWeek = Product::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() ?? 0;
        
        $recentActivities = [];
        
        // Recent incoming
        $latestInbound = Inbound::with('deliveryOrder')->latest()->first();
        if ($latestInbound) {
            $recentActivities[] = 'Incoming ' . $latestInbound->incoming_id . ' (' . $latestInbound->deliveryOrder->no_do . ') — Status: ' . $latestInbound->status;
        }
        
        // Recent outgoing
        $latestOutbound = Outbound::with('product')->latest()->first();
        if ($latestOutbound) {
            $recentActivities[] = 'Outgoing ' . $latestOutbound->outgoing_id . ' — ' . $latestOutbound->quantity . ' ' . $latestOutbound->product->unit;
        }
        
        // Recent product
        $latestProduct = Product::latest()->first();
        if ($latestProduct) {
            $recentActivities[] = 'New SKU ' . $latestProduct->sku . ' - ' . $latestProduct->name . ' added';
        }

        return view('wms.dashboard', compact('inboundCount', 'outboundCount', 'skuCount', 'newSkuThisWeek', 'recentActivities'));
    }

    // ===== INVENTORY METHODS =====
    public function index()
    {
        $products = Product::all();
        return view('wms.inventory', compact('products'));
    }

    public function create()
    {
        return view('wms.inventory-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:products',
            'name' => 'required',
            'stock' => 'required|numeric|min:0',
            'location' => 'required',
            'price' => 'numeric|min:0',
        ]);

        Product::create($validated);
        return redirect('/wms/inventory')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('wms.inventory-form', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|unique:products,code,' . $id,
            'name' => 'required',
            'stock' => 'required|numeric|min:0',
            'location' => 'required',
            'price' => 'numeric|min:0',
        ]);

        $product->update($validated);
        return redirect('/wms/inventory')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect('/wms/inventory')->with('success', 'Produk berhasil dihapus');
    }
}
