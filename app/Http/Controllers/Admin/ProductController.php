<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class ProductController
 *
 * Handles product management for the admin panel.
 */
class ProductController extends Controller
{
    /**
     * Show the form for creating a new product.
     *
     * @return View
     */
    public function create(): View
    {
        $inventories = Inventory::all();
        
        $availableSizes = $this->getAvailableSizesFromPriceList();
        
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
     * Store a newly created product in storage.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
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
        
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . $validated['name'] . '_' . $validated['size'] . '.' . $image->getClientOriginalExtension();
            $imageName = Str::slug(pathinfo($imageName, PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('images/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['image'] = $imageName;
        }
        
        $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['size']);
        
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $product = Product::create($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Product '{$product->name}' size {$product->size} has been added successfully.",
                'product' => $product
            ]);
        }
        
        return redirect()->route('inventory.index')
            ->with('success', "Product '{$product->name}' size {$product->size} has been added successfully.");
    }
    
    /**
     * Get available sizes from a price list file and combine them with actual sizes from the database.
     *
     * @return array
     */
    private function getAvailableSizesFromPriceList(): array
    {
        $filePath = base_path('daftar-harga.txt');
        $sizes = [];
        
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            $lines = explode("\n", $content);
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, 'NO') === 0 || strpos($line, 'HARGA') !== false) {
                    continue;
                }
                
                if (preg_match('/^(\d+|[A-Z]+\d*|L\d+|SML)\s+\d+/', $line)) {
                    $parts = preg_split('/\s+/', $line);
                    if (count($parts) >= 2) {
                        $size = $parts[0];
                        if (!in_array($size, $sizes)) {
                            $sizes[] = $size;
                        }
                    }
                }
            }
        }
        
        $actualSizes = \App\Models\Product::distinct()->pluck('size')->toArray();
        
        $allSizes = array_unique(array_merge($sizes, $actualSizes));
        
        $allSizes = array_filter($allSizes, function($size) {
            return !empty($size) && $size !== null;
        });
        
        usort($allSizes, function($a, $b) {
            if (is_numeric($a) && is_numeric($b)) {
                return (int)$a - (int)$b;
            }
            if (!is_numeric($a) && !is_numeric($b)) {
                return strcmp($a, $b);
            }
            return is_numeric($a) ? -1 : 1;
        });
        
        if (empty($allSizes)) {
            return ['S', 'M', 'L', 'XL', 'XXL'];
        }
        
        return array_values($allSizes);
    }
    
    /**
     * Display the specified product.
     *
     * @param Product $product
     * @return View
     */
    public function show(Product $product): View
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
     * Show the form for editing the specified product.
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
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
     * Update the specified product in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse|RedirectResponse
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
        
        if ($request->hasFile('image_file')) {
            if ($product->image && file_exists(public_path('images/products/' . $product->image))) {
                unlink(public_path('images/products/' . $product->image));
            }
            
            $image = $request->file('image_file');
            $imageName = time() . '_' . $validated['name'] . '_' . $validated['size'] . '.' . $image->getClientOriginalExtension();
            $imageName = Str::slug(pathinfo($imageName, PATHINFO_FILENAME)) . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('images/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $validated['image'] = $imageName;
        }
        
        if ($product->name !== $validated['name'] || $product->size !== $validated['size']) {
            $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['size']);
            
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }
        
        $product->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Product '{$product->name}' size {$product->size} has been updated successfully.",
                'product' => $product
            ]);
        }
        
        return redirect()->route('inventory.index')
            ->with('success', "Product '{$product->name}' size {$product->size} has been updated successfully.");
    }
    
    /**
     * Remove the specified product from storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(Request $request, Product $product)
    {
        $productName = $product->name;
        $productSize = $product->size;
        $inventoryId = $product->inventory_id;
        
        $product->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Product '{$productName}' size {$productSize} has been deleted successfully."
            ]);
        }
        
        return redirect()->route('inventory.index')
            ->with('success', "Product '{$productName}' size {$productSize} has been deleted successfully.");
    }
    
    /**
     * Remove multiple products from storage in bulk.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);
        
        $affectedInventoryIds = Product::whereIn('id', $validated['product_ids'])
            ->pluck('inventory_id')
            ->unique();
        
        $deletedCount = Product::whereIn('id', $validated['product_ids'])->delete();
        
        foreach ($affectedInventoryIds as $inventoryId) {
            $inventory = Inventory::find($inventoryId);
            if ($inventory) {
                $inventory->updateStock();
            }
        }
        
        return redirect()->route('inventory.index')
            ->with('success', "{$deletedCount} products have been deleted successfully.");
    }
    
    /**
     * Get products by inventory for AJAX requests.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getByInventory(Request $request): JsonResponse
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
     * Adjust the stock for an individual product.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function adjustStock(Request $request, int $id): RedirectResponse
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
            $message = "Successfully added {$quantity} units to '{$product->name}'. Current stock: {$product->stock}";
        } else { // reduce
            if ($quantity > $product->stock) {
                return redirect()->back()
                    ->with('error', 'The quantity to reduce exceeds the available stock!');
            }
            
            $product->stock -= $quantity;
            $message = "Successfully reduced {$quantity} units from '{$product->name}'. Current stock: {$product->stock}";
        }

        $product->save();

        if ($product->inventory) {
            $totalStock = Product::where('inventory_id', $product->inventory_id)->sum('stock');
            $product->inventory->update(['stock' => $totalStock]);
        }

        return redirect()->back()->with('success', $message);
    }
}