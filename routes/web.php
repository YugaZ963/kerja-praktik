<?php

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route (protected)
Route::get('/dashboard', function () {
    return view('dashboard', ['titleShop' => 'RAVAZKA - Dashboard']);
})->middleware('auth')->name('dashboard');

Route::get('/', function () {
    return view('welcome', ['titleShop' => 'RAVAZKA']);
});

Route::get('/about', function () {
    return view('about', ['titleShop' => 'RAVAZKA']);
});
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');
Route::get('/products', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('customer.products');

// 1. Detail produk
Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();
    return view('product', ['titleShop' => 'RAVAZKA', 'product' => $product]);
})->name('customer.product.detail');

// Rute untuk manajemen inventaris
Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    
    // Route untuk membuat inventaris baru
    Route::get('/create', function () {
        return view('inventory.create', [
            'titleShop' => 'RAVAZKA - Tambah Inventaris'
        ]);
    })->name('inventory.create');
    
    // Route untuk laporan inventaris
    Route::get('/report', [InventoryController::class, 'report'])->name('inventory.report');
    
    // Route untuk export inventaris
    Route::get('/export', function () {
        // Logic untuk export Excel akan ditambahkan nanti
        return redirect()->route('inventory.index')->with('success', 'Data berhasil diekspor');
    })->name('inventory.export');
    
    // Route untuk edit inventaris
    Route::get('/edit/{id}', function ($id) {
        $item = Inventory::findOrFail($id);
        return view('inventory.edit', [
            'titleShop' => 'RAVAZKA - Edit Inventaris',
            'item' => $item
        ]);
    })->name('inventory.edit');
    
    // Route untuk update inventaris
    Route::put('/update/{id}', function ($id) {
        $item = Inventory::findOrFail($id);
        // Logic untuk update akan ditambahkan nanti
        return redirect()->route('inventory.index')->with('success', 'Data berhasil diperbarui');
    })->name('inventory.update');
    
    // Route untuk hapus inventaris
    Route::delete('/destroy/{id}', function ($id) {
        $item = Inventory::findOrFail($id);
        // Logic untuk delete akan ditambahkan nanti
        return redirect()->route('inventory.index')->with('success', 'Data berhasil dihapus');
    })->name('inventory.destroy');

    // Halaman laporan inventaris
    Route::get('/reports/stock', function () {
        // Mengambil semua kategori unik dari database
        $categories = Inventory::select('category')->distinct()->get()->pluck('category');
        
        // Menyiapkan data kategori
        $categoryData = [];
        foreach ($categories as $category) {
            $items = Inventory::where('category', $category)->get();
            $categoryData[$category] = [
                'total_items' => $items->count(),
                'total_stock' => $items->sum('stock'),
                'total_value' => $items->sum(function($item) {
                    return $item->stock * $item->purchase_price;
                }),
            ];
        }
        
        return view('inventory.reports.stock', [
            'titleShop' => 'RAVAZKA - Laporan Stok',
            'report_date' => date('Y-m-d'),
            'categories' => $categoryData,
            'low_stock_items' => Inventory::whereRaw('stock <= min_stock * 1.5')
                ->get()
                ->map(function($item) {
                    $status = 'Aman';
                    if ($item->stock <= $item->min_stock) {
                        $status = 'Kritis';
                    } elseif ($item->stock <= $item->min_stock * 1.5) {
                        $status = 'Rendah';
                    }
                    
                    return [
                        'code' => $item->code,
                        'name' => $item->name,
                        'current_stock' => $item->stock,
                        'min_stock' => $item->min_stock,
                        'status' => $status,
                    ];
                }),
        ]);
    })->name('inventory.reports.stock');
    
    // Route untuk detail inventaris berdasarkan kode
    Route::get('/{code}', function ($code) {
        $item = Inventory::with('products')
            ->where('code', $code)
            ->firstOrFail();
        return view('inventory.detail', [
            'titleShop' => 'RAVAZKA - Detail Inventaris',
            'item' => $item
        ]);
    })->name('inventory.detail');
});

// Routes untuk fitur keranjang belanja
Route::prefix('cart')->group(function () {
    Route::get('/', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{cart}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{cart}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/process-order', [\App\Http\Controllers\CartController::class, 'processOrder'])->name('cart.process-order');
    Route::get('/count', [\App\Http\Controllers\CartController::class, 'getCartCount'])->name('cart.count');
    
    // API routes untuk ongkos kirim
    Route::get('/api/cities', [\App\Http\Controllers\CartController::class, 'getCities'])->name('cart.api.cities');
    Route::post('/api/shipping-cost', [\App\Http\Controllers\CartController::class, 'getShippingCost'])->name('cart.api.shipping-cost');
});
