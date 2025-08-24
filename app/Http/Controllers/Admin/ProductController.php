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
            'titleShop' => 'RAVAZKA - Kelola Produk',
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
        
        return view('admin.products.create', [
            'titleShop' => 'RAVAZKA - Tambah Produk',
            'inventories' => $inventories
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
            'image' => 'nullable|string|max:255'
        ]);
        
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
        
        // Update inventory stock
        $inventory = Inventory::find($validated['inventory_id']);
        $totalStock = Product::where('inventory_id', $validated['inventory_id'])->sum('stock');
        $inventory->update(['stock' => $totalStock]);
        
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
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('inventory');
        
        return view('admin.products.show', [
            'titleShop' => 'RAVAZKA - Detail Produk',
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
            'titleShop' => 'RAVAZKA - Edit Produk',
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
            'image' => 'nullable|string|max:255'
        ]);
        
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
        
        // Update inventory stock
        $inventory = Inventory::find($validated['inventory_id']);
        $totalStock = Product::where('inventory_id', $validated['inventory_id'])->sum('stock');
        $inventory->update(['stock' => $totalStock]);
        
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
        
        // Update inventory stock
        $inventory = Inventory::find($inventoryId);
        $totalStock = Product::where('inventory_id', $inventoryId)->sum('stock');
        $inventory->update(['stock' => $totalStock]);
        
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
        
        $deletedCount = Product::whereIn('id', $validated['product_ids'])->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', "{$deletedCount} produk berhasil dihapus.");
    }
    
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
}