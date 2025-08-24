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
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'sizes_available' => 'nullable|array',
            'description' => 'nullable|string',
        ]);
        
        // Konversi sizes_available ke JSON jika ada
        if (isset($validated['sizes_available'])) {
            $validated['sizes_available'] = json_encode($validated['sizes_available']);
        }
        
        // Set tanggal restock terakhir
        $validated['last_restock'] = now()->toDateString();
        
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
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'sizes_available' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        
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
        
        return view('admin.inventory.reports.stock', [
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
        $stockHistory = $item->stock_history ?? [];
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
    Route::get('/inventory/{inventory}/products', [\App\Http\Controllers\Admin\ProductController::class, 'getByInventory'])->name('by-inventory');
});

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
