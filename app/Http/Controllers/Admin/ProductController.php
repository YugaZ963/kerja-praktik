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
        
        return redirect()->route('inventory.index')
            ->with('success', "Produk '{$product->name}' ukuran {$product->size} berhasil ditambahkan.");
    }
    
    /**
     * Get available sizes from daftar-harga.txt and combine with actual sizes in database
     */
    private function getAvailableSizesFromPriceList()
    {
        $filePath = base_path('daftar-harga.txt');
        $sizes = [];
        
        // Ambil ukuran dari file daftar-harga.txt
        if (file_exists($filePath)) {
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
        }
        
        // Ambil ukuran yang benar-benar ada di database
        $actualSizes = \App\Models\Product::distinct()->pluck('size')->toArray();
        
        // Gabungkan ukuran dari file dengan ukuran aktual di database
        $allSizes = array_unique(array_merge($sizes, $actualSizes));
        
        // Filter ukuran kosong atau null
        $allSizes = array_filter($allSizes, function($size) {
            return !empty($size) && $size !== null;
        });
        
        // Urutkan ukuran: angka dulu, lalu huruf
        usort($allSizes, function($a, $b) {
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
        
        // Fallback ke ukuran default jika tidak ada ukuran yang ditemukan
        if (empty($allSizes)) {
            return ['S', 'M', 'L', 'XL', 'XXL'];
        }
        
        return array_values($allSizes);
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
        
        return redirect()->route('inventory.index')
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
        
        return redirect()->route('inventory.index')
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
        
        return redirect()->route('inventory.index')
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