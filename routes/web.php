<?php

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SalesReportController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Registration Routes removed - not needed in simplified version

// Dashboard Route (hanya untuk admin)
Route::get('/dashboard', function () {
    return view('admin.dashboard', ['titleShop' => 'RAVAZKA - Dashboard']);
})->middleware('admin')->name('dashboard');







// Route beranda - admin diarahkan ke dashboard, customer ke welcome page
Route::get('/', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return redirect()->route('dashboard');
    }
    return app(\App\Http\Controllers\Public\WelcomeController::class)->index();
});

// Public pages (admin diblokir dari halaman customer)
Route::middleware('block.admin.customer')->group(function () {
    Route::get('/about', [\App\Http\Controllers\Public\AboutController::class, 'index'])->name('about.index');
    Route::get('/contact', [\App\Http\Controllers\Public\ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact/send', [\App\Http\Controllers\Public\ContactController::class, 'send'])->name('contact.send');
    Route::get('/products', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('customer.products');
});

// Detail produk (admin diblokir)
Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();
    return view('public.product', ['titleShop' => 'RAVAZKA', 'product' => $product]);
})->middleware('block.admin.customer')->name('customer.product.detail');

// Customer Order Routes (admin diblokir)
Route::prefix('orders')->name('customer.orders.')->middleware(['auth', 'block.admin.customer'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('index');
    Route::get('/{orderNumber}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('show');
    Route::post('/{order}/upload-payment', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPaymentProof'])->name('upload-payment');
    Route::post('/{order}/upload-delivery', [\App\Http\Controllers\Customer\OrderController::class, 'uploadDeliveryProof'])->name('upload-delivery');
    Route::post('/{order}/mark-completed', [\App\Http\Controllers\Customer\OrderController::class, 'markAsCompleted'])->name('mark-completed');
});


// Rute untuk manajemen inventaris (hanya admin)
Route::prefix('inventory')->middleware('admin')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/{inventory}/products', [InventoryController::class, 'getProducts'])->name('inventory.products');
    Route::post('/{inventory}/update-stock', [InventoryController::class, 'updateStockFromProducts'])->name('inventory.update-stock');
    Route::get('/{inventory}/summary', [InventoryController::class, 'getSummary'])->name('inventory.summary');
    Route::delete('/{inventory}/products', [InventoryController::class, 'deleteProductsBySize'])->name('inventory.delete-products-by-size');
    
    // Routes untuk operasi stok
    Route::post('/{inventory}/add-stock', [InventoryController::class, 'addStock'])->name('inventory.add-stock');
    Route::post('/{inventory}/reduce-stock', [InventoryController::class, 'reduceStock'])->name('inventory.reduce-stock');
    Route::get('/{inventory}/edit-products/{size}', [InventoryController::class, 'editProductsBySize'])->name('inventory.edit-products-by-size');
    
    // Route untuk membuat inventaris baru
    Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create');
    
    // Route untuk menyimpan inventaris baru
    Route::post('/store', [InventoryController::class, 'store'])->name('inventory.store');
    
    // Route untuk laporan inventaris
    Route::get('/report', [InventoryController::class, 'report'])->name('inventory.report');
    

    
    // Route untuk export inventaris
    Route::get('/export', [InventoryController::class, 'export'])->name('inventory.export');
    
    // Route untuk edit inventaris
    Route::get('/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    
    // Route untuk update inventaris
    Route::put('/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    
    // Route untuk hapus inventaris
    Route::delete('/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');


    
    // Route untuk adjust stock (tambah/kurang stok per ukuran)
    Route::post('/adjust-stock/{id}', function ($id) {
        $item = Inventory::with('products')->findOrFail($id);
        
        // Validasi input
        $validated = request()->validate([
            'adjustment_type' => 'required|in:increase,decrease',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255'
        ]);
        
        $size = $validated['size'];
        $quantity = $validated['quantity'];
        $type = $validated['adjustment_type'];
        $notes = $validated['notes'] ?? '';
        
        // Cari produk dengan ukuran yang sesuai
        $product = $item->products()->where('size', $size)->first();
        
        if (!$product) {
            // Jika produk dengan ukuran tersebut belum ada, buat baru untuk penambahan stok
            if ($type === 'increase') {
                $product = $item->products()->create([
                    'name' => $item->name . ' - ' . $size,
                    'slug' => \Str::slug($item->name . ' ' . $size),
                    'size' => $size,
                    'stock' => 0,
                    'price' => $item->selling_price,
                    'description' => $item->description ?? 'Produk ' . $item->name . ' ukuran ' . $size,
                    'category' => $item->category
                ]);
            } else {
                return redirect()->back()
                    ->with('error', "Produk dengan ukuran {$size} tidak ditemukan.");
            }
        }
        
        $oldStock = $product->stock;
        
        // Hitung stok baru
        if ($type === 'increase') {
            $newStock = $oldStock + $quantity;
            $message = "Stok ukuran {$size} berhasil ditambah {$quantity} unit. Stok sekarang: {$newStock}";
            $historyType = 'in';
            $historyNotes = $notes ?: "Penambahan stok manual ukuran {$size}: +{$quantity}";
        } else {
            // Pastikan stok tidak negatif
            if ($oldStock < $quantity) {
                return redirect()->back()
                    ->with('error', "Tidak dapat mengurangi stok ukuran {$size}. Stok saat ini ({$oldStock}) kurang dari jumlah yang akan dikurangi ({$quantity}).");
            }
            
            $newStock = $oldStock - $quantity;
            $message = "Stok ukuran {$size} berhasil dikurangi {$quantity} unit. Stok sekarang: {$newStock}";
            $historyType = 'out';
            $historyNotes = $notes ?: "Pengurangan stok manual ukuran {$size}: -{$quantity}";
        }
        
        // Update stok produk
        $product->update(['stock' => $newStock]);
        
        // Sinkronkan total stok inventory dengan jumlah stok semua products
        $totalStock = $item->products()->sum('stock');
        
        // Update last_restock inventory dan total stock
        $item->update([
            'last_restock' => now()->toDateString(),
            'stock' => $totalStock
        ]);
        
        // Tambahkan ke riwayat stok
        $stockHistory = $item->stock_history;
        
        // Pastikan stock_history adalah array
        if (!is_array($stockHistory)) {
            $stockHistory = [];
        }
        
        $stockHistory[] = [
            'date' => now()->format('Y-m-d H:i:s'),
            'type' => $historyType,
            'size' => $size,
            'quantity' => $quantity,
            'notes' => $historyNotes,
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'total_stock_after' => $totalStock
        ];
        
        $item->update(['stock_history' => $stockHistory]);
        
        return redirect()->back()->with('success', $message);
    })->name('inventory.adjust-stock');
    
    // Route untuk detail inventaris berdasarkan kode
    Route::get('/{code}', function ($code) {
        $item = Inventory::with('products')
            ->where('code', $code)
            ->firstOrFail();
        
        // Hitung total stok dari semua produk terkait
        $totalStock = $item->products->sum('stock');
        
        // Update stok di inventory jika berbeda
        if ($item->stock != $totalStock) {
            $item->update(['stock' => $totalStock]);
            $item->stock = $totalStock; // Update instance untuk view
        }
        
        return view('admin.inventory.detail', [
            'titleShop' => 'RAVAZKA - Detail Inventaris',
            'item' => $item
        ]);
    })->name('inventory.detail');
});

// Unified routes removed - functionality integrated into product management

// Routes untuk manajemen produk admin
Route::prefix('admin/products')->middleware('admin')->name('admin.products.')->group(function () {

    Route::get('/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('destroy');
    Route::delete('/bulk-destroy', [\App\Http\Controllers\Admin\ProductController::class, 'bulkDestroy'])->name('bulk-destroy');
    Route::post('/{product}/adjust-stock', [\App\Http\Controllers\Admin\ProductController::class, 'adjustStock'])->name('adjust-stock');
    Route::get('/inventory/{inventory}/products', [\App\Http\Controllers\Admin\ProductController::class, 'getByInventory'])->name('by-inventory');
    

});

// Route untuk bulk delete produk dan inventaris


// Routes untuk fitur keranjang belanja (memerlukan login, admin diblokir)
Route::prefix('cart')->middleware(['require.login', 'block.admin.customer'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [\App\Http\Controllers\Customer\CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{cart}', [\App\Http\Controllers\Customer\CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{cart}', [\App\Http\Controllers\Customer\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [\App\Http\Controllers\Customer\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [\App\Http\Controllers\Customer\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/process-order', [\App\Http\Controllers\Customer\CartController::class, 'processOrder'])->name('cart.process-order');
});

// Route untuk cart count (tidak perlu login untuk menampilkan jumlah, admin diblokir)
Route::get('/cart/count', [\App\Http\Controllers\Customer\CartController::class, 'getCartCount'])
    ->middleware('block.admin.customer')
    ->name('cart.count');

// Routes untuk manajemen pesanan admin
Route::prefix('admin/orders')->middleware('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/{order}/payment-proof', [\App\Http\Controllers\Admin\OrderController::class, 'uploadPaymentProof'])->name('admin.orders.upload-payment-proof');
    Route::post('/{order}/delivery-proof', [\App\Http\Controllers\Admin\OrderController::class, 'uploadDeliveryProof'])->name('admin.orders.upload-delivery-proof');
    Route::delete('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
});

// Routes untuk laporan penjualan admin
Route::prefix('admin/sales')->middleware('admin')->name('admin.sales.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('index');
    Route::get('/data', [\App\Http\Controllers\Admin\SalesReportController::class, 'getData'])->name('data');
    Route::get('/export-pdf', [\App\Http\Controllers\Admin\SalesReportController::class, 'exportPdf'])->name('export-pdf');
});

// SEO Routes
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');
