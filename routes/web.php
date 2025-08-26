<?php

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\InventoryController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Registration Routes (protected by admin middleware)
Route::middleware('admin')->group(function () {
    Route::get('/admin/register', [AuthController::class, 'showAdminRegisterForm'])->name('admin.register');
    Route::post('/admin/register', [AuthController::class, 'registerAdmin']);
});

// Dashboard Route (hanya untuk admin)
Route::get('/dashboard', function () {
    return view('admin.dashboard', ['titleShop' => 'RAVAZKA - Dashboard']);
})->middleware('admin')->name('dashboard');







Route::get('/', [\App\Http\Controllers\Public\WelcomeController::class, 'index']);

Route::get('/about', [\App\Http\Controllers\Public\AboutController::class, 'index'])->name('about.index');
Route::get('/contact', [\App\Http\Controllers\Public\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact/send', [\App\Http\Controllers\Public\ContactController::class, 'send'])->name('contact.send');
Route::get('/products', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('customer.products');

// 1. Detail produk
Route::get('/products/{slug}', function ($slug) {
    $product = Product::where('slug', $slug)->firstOrFail();
    return view('public.product', ['titleShop' => 'RAVAZKA', 'product' => $product]);
})->name('customer.product.detail');

// Customer Order Routes
Route::prefix('orders')->name('customer.orders.')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('index');
    Route::get('/track', [\App\Http\Controllers\Customer\OrderController::class, 'track'])->name('track');
    Route::get('/{orderNumber}', [\App\Http\Controllers\Customer\OrderController::class, 'show'])->name('show');
    Route::post('/{order}/upload-payment', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPaymentProof'])->name('upload-payment');
    Route::post('/{order}/upload-delivery', [\App\Http\Controllers\Customer\OrderController::class, 'uploadDeliveryProof'])->name('upload-delivery');
    Route::post('/{order}/mark-completed', [\App\Http\Controllers\Customer\OrderController::class, 'markAsCompleted'])->name('mark-completed');
});

// Customer Testimonial Routes
Route::prefix('testimonials')->name('customer.testimonials.')->middleware('auth')->group(function () {
    Route::post('/store', [\App\Http\Controllers\Customer\TestimonialController::class, 'store'])->name('store');
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
    Route::get('/create', function () {
        return view('admin.inventory.create', [
            'titleShop' => 'RAVAZKA - Tambah Inventaris'
        ]);
    })->name('inventory.create');
    
    // Route untuk menyimpan inventaris baru
    Route::post('/store', function () {
        // Validasi input
        $validated = request()->validate([
            'code' => 'required|string|max:50|unique:inventories,code',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);
        
        // Set nilai default (akan dikelola melalui produk)
        $validated['stock'] = 0;
        $validated['min_stock'] = 1;
        $validated['purchase_price'] = 0;
        $validated['selling_price'] = 0;
        $validated['sizes_available'] = json_encode([]);
        
        // Set default values untuk field yang kosong
        if (empty($validated['supplier'])) {
            $validated['supplier'] = 'Supplier Tidak Diketahui';
        }
        if (empty($validated['location'])) {
            $validated['location'] = 'Lokasi Tidak Ditentukan';
        }
        
        // Set tanggal restock terakhir dan stock_history
        $validated['last_restock'] = now()->toDateString();
        $validated['stock_history'] = [
            [
                'date' => now()->toDateString(),
                'type' => 'initial',
                'quantity' => 0,
                'note' => 'Inventaris dibuat - stok akan dikelola melalui data produk'
            ]
        ];
        
        // Buat item inventory baru
        $item = Inventory::create($validated);
        
        return redirect()->route('inventory.index')
            ->with('success', "Item inventaris '{$item->name}' berhasil ditambahkan.");
    })->name('inventory.store');
    
    // Route untuk laporan inventaris
    Route::get('/report', [InventoryController::class, 'report'])->name('inventory.report');
    

    
    // Route untuk export inventaris
    Route::get('/export', function () {
        // Logic untuk export Excel akan ditambahkan nanti
        return redirect()->route('inventory.index')->with('success', 'Data berhasil diekspor');
    })->name('inventory.export');
    
    // Route untuk edit inventaris
    Route::get('/edit/{id}', function ($id) {
        $item = Inventory::find($id);
        
        if (!$item) {
            return redirect()->route('inventory.index')
                ->with('error', "Item inventaris dengan ID {$id} tidak ditemukan. Silakan pilih item yang valid dari daftar di bawah.");
        }
        
        return view('admin.inventory.edit', [
            'titleShop' => 'RAVAZKA - Edit Inventaris',
            'item' => $item
        ]);
    })->name('inventory.edit');
    
    // Route untuk update inventaris
    Route::put('/update/{id}', function ($id) {
        $item = Inventory::find($id);
        
        if (!$item) {
            return redirect()->route('inventory.index')
                ->with('error', "Item inventaris dengan ID {$id} tidak ditemukan.");
        }
        
        // Validasi input
        $validated = request()->validate([
            'code' => 'required|string|max:50|unique:inventories,code,' . $id,
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Harga akan dikelola melalui produk, jangan ubah nilai yang sudah ada
        // Hanya update jika tidak ada produk terkait atau untuk keperluan khusus
        if (!$item->products()->exists()) {
            $validated['purchase_price'] = $item->purchase_price ?? 0;
            $validated['selling_price'] = $item->selling_price ?? 0;
        }
        
        // Ukuran akan otomatis dikelola melalui produk, tidak perlu diubah manual
        
        // Update item
        $item->update($validated);
        
        return redirect()->route('inventory.index')
            ->with('success', "Item inventaris '{$item->name}' berhasil diperbarui.");
    })->name('inventory.update');
    
    // Route untuk hapus inventaris
    Route::delete('/destroy/{id}', function ($id) {
        $item = Inventory::findOrFail($id);
        
        // Hapus semua produk yang terkait dengan inventory ini
        $item->products()->delete();
        
        // Simpan nama item untuk pesan sukses
        $itemName = $item->name;
        
        // Hapus item inventory
        $item->delete();
        
        return redirect()->route('inventory.index')
            ->with('success', "Item inventaris '{$itemName}' dan semua produk terkait berhasil dihapus.");
    })->name('inventory.destroy');


    
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
        return view('admin.inventory.detail', [
            'titleShop' => 'RAVAZKA - Detail Inventaris',
            'item' => $item
        ]);
    })->name('inventory.detail');
});

// Routes untuk tampilan terpadu produk dan inventaris
Route::prefix('admin/unified')->middleware('admin')->name('admin.unified.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\UnifiedController::class, 'index'])->name('index');
    Route::get('/export', [\App\Http\Controllers\Admin\UnifiedController::class, 'export'])->name('export');
    Route::get('/report', [\App\Http\Controllers\Admin\UnifiedController::class, 'report'])->name('report');
    Route::get('/{inventory}/products', [\App\Http\Controllers\Admin\UnifiedController::class, 'getProducts'])->name('products');
});

// Routes untuk manajemen produk admin
Route::prefix('admin/products')->middleware('admin')->name('admin.products.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('destroy');
    Route::delete('/bulk-destroy', [\App\Http\Controllers\Admin\ProductController::class, 'bulkDestroy'])->name('bulk-destroy');
    Route::post('/{product}/adjust-stock', [\App\Http\Controllers\Admin\ProductController::class, 'adjustStock'])->name('adjust-stock');
    Route::get('/inventory/{inventory}/products', [\App\Http\Controllers\Admin\ProductController::class, 'getByInventory'])->name('by-inventory');
    
    // Routes untuk manajemen produk dari inventaris
    Route::prefix('manage')->name('manage.')->group(function () {
        Route::get('/quantity/{inventory}/{size}', [\App\Http\Controllers\Admin\ProductController::class, 'manageQuantity'])->name('quantity');
        Route::post('/quantity/{inventory}/{size}', [\App\Http\Controllers\Admin\ProductController::class, 'updateQuantity'])->name('quantity.update');
        Route::get('/edit/{inventory}/{size}', [\App\Http\Controllers\Admin\ProductController::class, 'manageEdit'])->name('edit');
        Route::post('/edit/{inventory}/{size}', [\App\Http\Controllers\Admin\ProductController::class, 'updateProductInfo'])->name('edit.update');
        Route::delete('/delete/{inventory}/{size}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteBySize'])->name('delete');
    });
});

// Route untuk bulk delete produk dan inventaris


// Routes untuk fitur keranjang belanja (memerlukan login)
Route::prefix('cart')->middleware('require.login')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [\App\Http\Controllers\Customer\CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{cart}', [\App\Http\Controllers\Customer\CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{cart}', [\App\Http\Controllers\Customer\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [\App\Http\Controllers\Customer\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [\App\Http\Controllers\Customer\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/process-order', [\App\Http\Controllers\Customer\CartController::class, 'processOrder'])->name('cart.process-order');
});

// Route untuk cart count (tidak perlu login untuk menampilkan jumlah)
Route::get('/cart/count', [\App\Http\Controllers\Customer\CartController::class, 'getCartCount'])->name('cart.count');

// Routes untuk manajemen pesanan admin
Route::prefix('admin/orders')->middleware('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/{order}/payment-proof', [\App\Http\Controllers\Admin\OrderController::class, 'uploadPaymentProof'])->name('admin.orders.upload-payment-proof');
    Route::post('/{order}/delivery-proof', [\App\Http\Controllers\Admin\OrderController::class, 'uploadDeliveryProof'])->name('admin.orders.upload-delivery-proof');
    Route::delete('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
});

// Sales Report Routes
Route::prefix('admin/sales')->name('admin.sales.')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('index');
    Route::get('/export-pdf', [\App\Http\Controllers\Admin\SalesReportController::class, 'exportPdf'])->name('export-pdf');
    Route::get('/data', [\App\Http\Controllers\Admin\SalesReportController::class, 'getData'])->name('data');
});

// SEO Routes
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');
