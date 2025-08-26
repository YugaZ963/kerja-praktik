<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('inventory');
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter berdasarkan ukuran
        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }
        
        // Filter berdasarkan inventaris
        if ($request->filled('inventory_id')) {
            $query->where('inventory_id', $request->inventory_id);
        }
        
        // Filter berdasarkan status stok
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('stock', '<=', 10)->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'available':
                    $query->where('stock', '>', 10);
                    break;
            }
        }
        
        // Sorting
        switch ($request->sort) {
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock-asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock-desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'size-asc':
                $query->orderBy('size', 'asc');
                break;
            case 'size-desc':
                $query->orderBy('size', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(15)->withQueryString();
        $inventories = Inventory::all();
        
        // Get unique categories and sizes for filters
        $categories = Product::select('category')->distinct()->pluck('category');
        $sizes = Product::select('size')->distinct()->pluck('size');
        
        return view('admin.products.index', [
            'titleShop' => '📊 Manajemen Produk Seragam - Admin RAVAZKA | Kelola Inventaris',
            'title' => '📊 Manajemen Produk Seragam - Admin RAVAZKA | Kelola Inventaris',
            'metaDescription' => '🔧 Panel admin untuk mengelola produk seragam sekolah RAVAZKA. Kelola stok, harga, kategori, dan inventaris dengan mudah. Dashboard lengkap untuk administrator.',
            'metaKeywords' => 'admin produk RAVAZKA, kelola seragam, manajemen inventaris, dashboard admin, stok produk',
            'products' => $products,
            'inventories' => $inventories,
            'categories' => $categories,
            'sizes' => $sizes
        ]);
    }
    
    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $inventories = Inventory::all();
        
        // Ambil ukuran dari daftar harga
        $availableSizes = $this->getAvailableSizesFromPriceList();
        
        // Cek inventory_id dan size dari request untuk validasi duplikasi
        $inventoryId = request('inventory_id');
        $selectedSize = request('size');
        $isDuplicate = false;
        
        if ($inventoryId && $selectedSize) {
            $isDuplicate = Product::where('inventory_id', $inventoryId)
                                ->where('size', $selectedSize)
                                ->exists();
        }
        
        return view('admin.products.create', [
            'titleShop' => '➕ Tambah Produk Baru - Admin RAVAZKA | Input Seragam Sekolah',
            'title' => '➕ Tambah Produk Baru - Admin RAVAZKA | Input Seragam Sekolah',
            'metaDescription' => '📝 Form tambah produk seragam sekolah baru di sistem RAVAZKA. Input detail produk, harga, stok, dan kategori dengan mudah melalui panel admin.',
            'metaKeywords' => 'tambah produk RAVAZKA, input seragam baru, form admin, manajemen produk',
            'inventories' => $inventories,
            'availableSizes' => $availableSizes,
            'isDuplicate' => $isDuplicate
        ]);
    }
    
    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'size' => 'required|string|max:10',
            'category' => 'required|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . $validated['name'] . '_' . $validated['size'] . '.' . $image->getClientOriginalExtension();
            $imageName = Str::slug(pathinfo($imageName, PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('images/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['image'] = $imageName;
        }
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['size']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $product = Product::create($validated);
        
        // Inventory stock akan otomatis terupdate melalui Product model event listeners
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Produk '{$product->name}' ukuran {$product->size} berhasil ditambahkan.",
                'product' => $product
            ]);
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$product->name}' ukuran {$product->size} berhasil ditambahkan.");
    }
    
    /**
     * Get available sizes from daftar-harga.txt
     */
    private function getAvailableSizesFromPriceList()
    {
        $filePath = base_path('daftar-harga.txt');
        $sizes = [];
        
        if (!file_exists($filePath)) {
            // Fallback ke ukuran default jika file tidak ada
            return ['S', 'M', 'L', 'XL', 'XXL'];
        }
        
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            $line = trim($line);
            // Skip baris kosong, header, dan baris yang tidak mengandung ukuran
            if (empty($line) || strpos($line, 'NO') === 0 || strpos($line, 'HARGA') !== false) {
                continue;
            }
            
            // Cek apakah baris mengandung ukuran (angka atau huruf ukuran)
            if (preg_match('/^(\d+|[A-Z]+\d*|L\d+|SML)\s+\d+/', $line)) {
                $parts = preg_split('/\s+/', $line);
                if (count($parts) >= 2) {
                    $size = $parts[0];
                    // Tambahkan ukuran ke array jika belum ada
                    if (!in_array($size, $sizes)) {
                        $sizes[] = $size;
                    }
                }
            }
        }
        
        // Urutkan ukuran: angka dulu, lalu huruf
        usort($sizes, function($a, $b) {
            // Jika keduanya angka
            if (is_numeric($a) && is_numeric($b)) {
                return (int)$a - (int)$b;
            }
            // Jika keduanya huruf
            if (!is_numeric($a) && !is_numeric($b)) {
                return strcmp($a, $b);
            }
            // Angka lebih dulu dari huruf
            return is_numeric($a) ? -1 : 1;
        });
        
        return $sizes;
    }
    
    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('inventory');
        
        return view('admin.products.show', [
            'titleShop' => '🔍 Detail Produk - Admin RAVAZKA | Informasi Lengkap Seragam',
            'title' => '🔍 Detail Produk - Admin RAVAZKA | Informasi Lengkap Seragam',
            'metaDescription' => '📋 Lihat detail lengkap produk seragam sekolah di panel admin RAVAZKA. Informasi stok, harga, kategori, dan spesifikasi produk.',
            'metaKeywords' => 'detail produk RAVAZKA, info seragam, spesifikasi produk, admin panel',
            'product' => $product
        ]);
    }
    
    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $inventories = Inventory::all();
        
        return view('admin.products.edit', [
            'titleShop' => '✏️ Edit Produk - Admin RAVAZKA | Update Data Seragam',
            'title' => '✏️ Edit Produk - Admin RAVAZKA | Update Data Seragam',
            'metaDescription' => '🔧 Form edit produk seragam sekolah di panel admin RAVAZKA. Update harga, stok, deskripsi, dan informasi produk dengan mudah.',
            'metaKeywords' => 'edit produk RAVAZKA, update seragam, form admin, manajemen produk',
            'product' => $product,
            'inventories' => $inventories
        ]);
    }
    
    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'size' => 'required|string|max:10',
            'category' => 'required|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
                unlink(public_path('images/products/' . $product->image));
            }
            
            $image = $request->file('image_file');
            $imageName = time() . '_' . $validated['name'] . '_' . $validated['size'] . '.' . $image->getClientOriginalExtension();
            $imageName = Str::slug(pathinfo($imageName, PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $uploadPath = public_path('images/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['image'] = $imageName;
        }
        
        // Update slug if name or size changed
        if ($product->name !== $validated['name'] || $product->size !== $validated['size']) {
            $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['size']);
            
            // Ensure slug is unique (excluding current product)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        $product->update($validated);
        
        // Inventory stock akan otomatis terupdate melalui Product model event listeners
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Produk '{$product->name}' ukuran {$product->size} berhasil diperbarui.",
                'product' => $product
            ]);
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$product->name}' ukuran {$product->size} berhasil diperbarui.");
    }
    
    /**
     * Remove the specified product
     */
    public function destroy(Request $request, Product $product)
    {
        $productName = $product->name;
        $productSize = $product->size;
        $inventoryId = $product->inventory_id;
        
        $product->delete();
        
        // Inventory stock akan otomatis terupdate melalui Product model event listeners
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Produk '{$productName}' ukuran {$productSize} berhasil dihapus."
            ]);
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$productName}' ukuran {$productSize} berhasil dihapus.");
    }
    
    /**
     * Bulk delete products
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);
        
        // Ambil inventory_ids yang terkena dampak sebelum delete
        $affectedInventoryIds = Product::whereIn('id', $validated['product_ids'])
            ->pluck('inventory_id')
            ->unique();
        
        $deletedCount = Product::whereIn('id', $validated['product_ids'])->delete();
        
        // Update stock inventaris yang terkena dampak
        foreach ($affectedInventoryIds as $inventoryId) {
            $inventory = Inventory::find($inventoryId);
            if ($inventory) {
                $inventory->updateStock();
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', "{$deletedCount} produk berhasil dihapus.");
    }
    
    /**
     * Bulk delete products and inventories
     */

    
    /**
     * Get products by inventory for AJAX
     */
    public function getByInventory(Request $request)
    {
        $inventoryId = $request->get('inventory_id');
        
        if (!$inventoryId) {
            return response()->json([]);
        }
        
        $products = Product::where('inventory_id', $inventoryId)
            ->select('id', 'name', 'size', 'price', 'stock')
            ->orderBy('size')
            ->get();
            
        return response()->json($products);
    }

    /**
     * Tampilkan form untuk mengelola quantity produk berdasarkan inventory dan size
     */
    public function manageQuantity($inventoryId, $size)
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $products = Product::where('inventory_id', $inventoryId)
            ->where('size', $size)
            ->get();

        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada produk dengan ukuran tersebut.');
        }

        return view('admin.products.manage-quantity', [
            'titleShop' => 'RAVAZKA - Kelola Quantity Produk',
            'inventory' => $inventory,
            'products' => $products,
            'size' => $size
        ]);
    }

    /**
     * Update quantity produk
     */
    public function updateQuantity(Request $request, $inventoryId, $size)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:0'
        ]);

        foreach ($request->quantities as $productId => $quantity) {
            $product = Product::where('id', $productId)
                ->where('inventory_id', $inventoryId)
                ->where('size', $size)
                ->first();

            if ($product) {
                $product->update(['stock' => $quantity]);
            }
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Quantity produk berhasil diperbarui.');
    }

    /**
     * Tampilkan form untuk mengedit informasi produk berdasarkan inventory dan size
     */
    public function manageEdit($inventoryId, $size)
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $products = Product::where('inventory_id', $inventoryId)
            ->where('size', $size)
            ->get();

        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada produk dengan ukuran tersebut.');
        }

        return view('admin.products.manage-edit', [
            'titleShop' => 'RAVAZKA - Edit Info Produk',
            'inventory' => $inventory,
            'products' => $products,
            'size' => $size
        ]);
    }

    /**
     * Update informasi produk
     */
    public function updateProductInfo(Request $request, $inventoryId, $size)
    {
        $request->validate([
            'product_updates' => 'required|array',
            'product_updates.*.name' => 'required|string|max:255',
            'product_updates.*.price' => 'required|numeric|min:0',
            'product_updates.*.description' => 'nullable|string'
        ]);

        foreach ($request->product_updates as $productId => $updates) {
            $product = Product::where('id', $productId)
                ->where('inventory_id', $inventoryId)
                ->where('size', $size)
                ->first();

            if ($product) {
                $product->update([
                    'name' => $updates['name'],
                    'price' => $updates['price'],
                    'description' => $updates['description'] ?? $product->description
                ]);
            }
        }

        return redirect()->route('inventory.index')
            ->with('success', 'Informasi produk berhasil diperbarui.');
    }

    /**
     * Hapus semua produk berdasarkan inventory dan size
     */
    public function deleteBySize($inventoryId, $size)
    {
        $deletedCount = Product::where('inventory_id', $inventoryId)
            ->where('size', $size)
            ->delete();

        if ($deletedCount > 0) {
            return redirect()->route('inventory.index')
                ->with('success', "Berhasil menghapus {$deletedCount} produk dengan ukuran {$size}.");
        } else {
            return redirect()->route('inventory.index')
                ->with('error', 'Tidak ada produk yang dihapus.');
        }
    }

    /**
     * Adjust stock for individual product
     */
    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:add,reduce',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $action = $request->action;
        $quantity = (int) $request->quantity;
        $oldStock = $product->stock;

        if ($action === 'add') {
            $product->stock += $quantity;
            $message = "Berhasil menambah stok produk '{$product->name}' sebanyak {$quantity} unit. Stok sekarang: {$product->stock}";
        } else { // reduce
            if ($quantity > $product->stock) {
                return redirect()->back()
                    ->with('error', 'Jumlah yang dikurangi melebihi stok yang tersedia!');
            }
            
            $product->stock -= $quantity;
            $message = "Berhasil mengurangi stok produk '{$product->name}' sebanyak {$quantity} unit. Stok sekarang: {$product->stock}";
        }

        $product->save();

        // Update total stock di inventory
        if ($product->inventory) {
            $totalStock = Product::where('inventory_id', $product->inventory_id)->sum('stock');
            $product->inventory->update(['stock' => $totalStock]);
        }

        // Catat riwayat stok (opsional - jika ada model StockHistory)
        // StockHistory::create([
        //     'product_id' => $product->id,
        //     'inventory_id' => $product->inventory_id,
        //     'type' => $action === 'add' ? 'in' : 'out',
        //     'quantity' => $quantity,
        //     'notes' => $action === 'add' ? 'Penambahan stok manual' : 'Pengurangan stok manual',
        //     'user_id' => auth()->id(),
        // ]);

        return redirect()->back()->with('success', $message);
    }
}