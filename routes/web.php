<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WmsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/wms/dashboard');
    }
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// WMS Routes - Protected by auth middleware
Route::prefix('wms')->middleware('auth')->group(function () {
    Route::get('/dashboard', [WmsController::class, 'dashboard'])->name('wms.dashboard');
    
    // Inventory/Barang CRUD
    Route::resource('inventory', InventoryController::class, [
        'only' => ['index', 'create', 'store', 'edit', 'update', 'destroy'],
    ]);
    
    // Delivery Order CRUD
    Route::resource('delivery-orders', DeliveryOrderController::class, [
        'names' => [
            'index' => 'delivery-orders.index',
            'create' => 'delivery-orders.create',
            'store' => 'delivery-orders.store',
            'show' => 'delivery-orders.show',
            'edit' => 'delivery-orders.edit',
            'update' => 'delivery-orders.update',
            'destroy' => 'delivery-orders.destroy',
        ]
    ]);
    
    // Inbound/Incoming CRUD
    Route::resource('inbound', InboundController::class, [
        'only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
    ]);
    Route::post('inbound/{id}/status', [InboundController::class, 'updateStatus'])->name('inbound.updateStatus');
    
    // Outbound/Outgoing CRUD
    Route::resource('outbound', OutboundController::class, [
        'only' => ['index', 'create', 'store', 'edit', 'update', 'destroy'],
    ]);
    Route::post('outbound/{id}/status', [OutboundController::class, 'updateStatus'])->name('outbound.updateStatus');
});
