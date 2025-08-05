<?php

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route (hanya untuk admin)
Route::get('/dashboard', function () {
    return view('dashboard', ['titleShop' => 'RAVAZKA - Dashboard']);
})->middleware('admin')->name('dashboard');







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

// Rute untuk manajemen inventaris (hanya admin)
Route::prefix('inventory')->middleware('admin')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    
    // Route untuk membuat inventaris baru
    Route::get('/create', function () {
        return view('inventory.create', [
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
        
        return view('inventory.edit', [
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
        
        // Update last_restock inventory
        $item->update(['last_restock' => now()->toDateString()]);
        
        // Tambahkan ke riwayat stok
        $stockHistory = $item->stock_history ?? [];
        $stockHistory[] = [
            'date' => now()->format('Y-m-d H:i:s'),
            'type' => $historyType,
            'size' => $size,
            'quantity' => $quantity,
            'notes' => $historyNotes,
            'old_stock' => $oldStock,
            'new_stock' => $newStock
        ];
        
        $item->update(['stock_history' => $stockHistory]);
        
        return redirect()->back()->with('success', $message);
    })->name('inventory.adjust-stock');
    
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

// Routes untuk fitur keranjang belanja (memerlukan login)
Route::prefix('cart')->middleware('require.login')->group(function () {
    Route::get('/', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/update/{cart}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{cart}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/process-order', [\App\Http\Controllers\CartController::class, 'processOrder'])->name('cart.process-order');
    
    // API routes untuk ongkos kirim
    Route::get('/api/cities', [\App\Http\Controllers\CartController::class, 'getCities'])->name('cart.api.cities');
    Route::post('/api/shipping-cost', [\App\Http\Controllers\CartController::class, 'getShippingCost'])->name('cart.api.shipping-cost');
});

// Route untuk cart count (tidak perlu login untuk menampilkan jumlah)
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'getCartCount'])->name('cart.count');
