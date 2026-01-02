<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
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
            'sku' => 'required|unique:products',
            'name' => 'required',
            'category' => 'required',
            'unit' => 'required',
            'stock' => 'required|numeric|min:0',
            'location' => 'required',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        Product::create($validated);
        return redirect()->route('inventory.index')->with('success', 'Barang berhasil ditambahkan');
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
            'sku' => 'required|unique:products,sku,' . $id,
            'name' => 'required',
            'category' => 'required',
            'unit' => 'required',
            'stock' => 'required|numeric|min:0',
            'location' => 'required',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $product->update($validated);
        return redirect()->route('inventory.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('inventory.index')->with('success', 'Barang berhasil dihapus');
    }

    public function print($id)
    {
        $product = Product::findOrFail($id);
        return view('wms.prints.inventory-print', compact('product'));
    }

    public function printAll()
    {
        $products = Product::all();
        return view('wms.prints.inventory-print-all', compact('products'));
    }
}
