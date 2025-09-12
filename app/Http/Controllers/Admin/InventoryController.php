<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class InventoryController
 *
 * Handles inventory management for the admin panel.
 */
class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Inventory::query();
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Stock status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low':
                    $query->whereRaw('stock <= 100')->whereRaw('stock > 0');
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'ready':
                    $query->whereRaw('stock > 100');
                    break;
                case 'critical':
                    $query->whereRaw('stock <= 50');
                    break;
            }
        }
        
        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('selling_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('selling_price', '<=', $request->price_max);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sorting
        switch ($request->sort) {
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
            case 'stock-asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock-desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'price-asc':
                $query->orderBy('selling_price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('selling_price', 'desc');
                break;
            case 'category-asc':
                $query->orderBy('category', 'asc');
                break;
            case 'category-desc':
                $query->orderBy('category', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $inventory_items = $query->paginate(15)->withQueryString();
        
        return view('admin.inventory.index', [
            'titleShop' => '📦 Manajemen Inventaris - Admin RAVAZKA | Kelola Stok Seragam',
            'title' => '📦 Manajemen Inventaris - Admin RAVAZKA | Kelola Stok Seragam',
            'metaDescription' => '🔧 Panel admin untuk mengelola inventaris seragam sekolah RAVAZKA. Monitor stok, harga, supplier, dan status inventaris dengan filter lengkap dan laporan real-time.',
            'metaKeywords' => 'inventaris RAVAZKA, manajemen stok seragam, admin inventaris, monitor stok, supplier seragam',
            'inventory_items' => $inventory_items
        ]);
    }
    
    /**
     * Display the inventory report.
     *
     * @param Request $request
     * @return View
     */
    public function report(Request $request): View
    {
        $query = Inventory::query();
        
        // Search filter for report
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Supplier filter
        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', "%{$request->supplier}%");
        }
        
        // Stock status filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereRaw('stock <= min_stock');
                    break;
                case 'critical':
                    $query->whereRaw('stock <= (min_stock * 0.5)');
                    break;
                case 'adequate':
                    $query->whereRaw('stock > min_stock');
                    break;
                case 'out':
                    $query->where('stock', 0);
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
            case 'stock-asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock-desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'value-asc':
                $query->orderByRaw('(stock * purchase_price) asc');
                break;
            case 'value-desc':
                $query->orderByRaw('(stock * purchase_price) desc');
                break;
            default:
                $query->latest();
        }
        
        $inventory_items = $query->paginate(15)->withQueryString();
        
        return view('admin.inventory.report', [
            'titleShop' => '📊 Laporan Inventaris - Admin RAVAZKA | Analisis Stok Seragam',
            'title' => '📊 Laporan Inventaris - Admin RAVAZKA | Analisis Stok Seragam',
            'metaDescription' => '📈 Laporan lengkap inventaris seragam sekolah RAVAZKA. Analisis stok, nilai inventaris, status supplier, dan tren penjualan untuk pengambilan keputusan bisnis.',
            'metaKeywords' => 'laporan inventaris RAVAZKA, analisis stok seragam, report admin, nilai inventaris, tren stok',
            'inventory_items' => $inventory_items
        ]);
    }
    
    /**
     * Get products by inventory for AJAX requests.
     *
     * @param int $inventoryId
     * @return JsonResponse
     */
    public function getProducts(int $inventoryId): JsonResponse
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $products = $inventory->products()->select('id', 'name', 'size', 'price', 'stock')->get();
        
        return response()->json([
            'inventory' => [
                'id' => $inventory->id,
                'name' => $inventory->name,
                'category' => $inventory->category,
                'sizes_available' => $inventory->sizes_available
            ],
            'products' => $products
        ]);
    }
    
    /**
     * Update inventory stock based on product changes.
     *
     * @param Request $request
     * @param int $inventoryId
     * @return JsonResponse
     */
    public function updateStockFromProducts(Request $request, int $inventoryId): JsonResponse
    {
        $inventory = Inventory::findOrFail($inventoryId);
        $totalStock = Product::where('inventory_id', $inventoryId)->sum('stock');
        
        $inventory->update(['stock' => $totalStock]);
        
        return response()->json([
            'success' => true,
            'new_stock' => $totalStock
        ]);
    }
    
    /**
     * Get an inventory summary with a breakdown of products by size.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getSummary(int $id): JsonResponse
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $products = Product::where('inventory_id', $id)->get();
            
            $sizeBreakdown = [];
            foreach ($products as $product) {
                $size = $product->size;
                if (!isset($sizeBreakdown[$size])) {
                    $sizeBreakdown[$size] = [
                        'size' => $size,
                        'products' => [],
                        'total_stock' => 0,
                        'total_value' => 0
                    ];
                }
                
                $sizeBreakdown[$size]['products'][] = $product;
                $sizeBreakdown[$size]['total_stock'] += $product->stock;
                $sizeBreakdown[$size]['total_value'] += $product->stock * $product->price;
            }
            
            return response()->json([
                'success' => true,
                'inventory' => $inventory,
                'size_breakdown' => $sizeBreakdown,
                'total_products' => $products->count(),
                'total_stock' => $products->sum('stock')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get inventory summary: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete all products of a specific size from a given inventory.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteProductsBySize(Request $request, int $id): JsonResponse
    {
        try {
            $size = $request->query('size');
            
            if (!$size) {
                return response()->json([
                    'success' => false,
                    'message' => 'Size parameter is required'
                ], 400);
            }
            
            $inventory = Inventory::findOrFail($id);
            $products = Product::where('inventory_id', $id)
                              ->where('size', $size)
                              ->get();
            
            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No products found with that size'
                ], 404);
            }
            
            // Delete all products with the specified size
            Product::where('inventory_id', $id)
                   ->where('size', $size)
                   ->delete();
            
            // Update the total stock of the inventory
            $this->updateStockFromProducts(new Request(), $id);
            
            return response()->json([
                'success' => true,
                'message' => 'All products with size ' . $size . ' have been deleted successfully',
                'deleted_count' => $products->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add stock for a product of a specific size.
     *
     * @param Request $request
     * @param int $inventoryId
     * @return RedirectResponse
     */
    public function addStock(Request $request, int $inventoryId): RedirectResponse
    {
        $request->validate([
            'size' => 'required|string',
            'stock' => 'required|integer|min:1'
        ]);

        try {
            $inventory = Inventory::findOrFail($inventoryId);
            $size = $request->size;
            $addStock = $request->stock;

            // Find a product with the same size
            $product = Product::where('inventory_id', $inventoryId)
                             ->where('size', $size)
                             ->first();

            if ($product) {
                // If the product exists, increment its stock
                $product->increment('stock', $addStock);
            } else {
                // If the product does not exist, create a new one
                Product::create([
                    'inventory_id' => $inventoryId,
                    'name' => $inventory->name . ' - ' . $size,
                    'size' => $size,
                    'price' => $inventory->selling_price,
                    'stock' => $addStock,
                    'category' => $inventory->category,
                    'description' => $inventory->description,
                    'slug' => \Illuminate\Support\Str::slug($inventory->name . '-' . $size . '-' . time())
                ]);
            }

            // Inventory stock is automatically updated via Product model event listeners

            return redirect()->route('inventory.index')
                ->with('success', "Successfully added {$addStock} stock for size {$size}");
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    /**
     * Reduce stock for a product of a specific size.
     *
     * @param Request $request
     * @param int $inventoryId
     * @return RedirectResponse
     */
    public function reduceStock(Request $request, int $inventoryId): RedirectResponse
    {
        $request->validate([
            'size' => 'required|string',
            'stock' => 'required|integer|min:1'
        ]);

        try {
            $inventory = Inventory::findOrFail($inventoryId);
            $size = $request->size;
            $reduceStock = $request->stock;

            // Find a product with the same size
            $product = Product::where('inventory_id', $inventoryId)
                             ->where('size', $size)
                             ->first();

            if (!$product) {
                return redirect()->route('inventory.index')
                    ->with('error', "Product with size {$size} not found");
            }

            if ($product->stock < $reduceStock) {
                return redirect()->route('inventory.index')
                    ->with('error', "Insufficient stock. Current stock: {$product->stock}");
            }

            // Reduce the stock
            $product->decrement('stock', $reduceStock);

            // Inventory stock is automatically updated via Product model event listeners

            return redirect()->route('inventory.index')
                ->with('success', "Successfully reduced {$reduceStock} stock for size {$size}");
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', 'Failed to reduce stock: ' . $e->getMessage());
        }
    }

    /**
     * Display the form for editing products of a specific size.
     *
     * @param int $inventoryId
     * @param string $size
     * @return View|RedirectResponse
     */
    public function editProductsBySize(int $inventoryId, string $size)
    {
        try {
            $inventory = Inventory::findOrFail($inventoryId);
            $products = Product::where('inventory_id', $inventoryId)
                              ->where('size', $size)
                              ->get();

            return view('admin.inventory.edit-products', [
                'titleShop' => 'RAVAZKA - Edit Produk',
                'inventory' => $inventory,
                'products' => $products,
                'size' => $size
            ]);
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', 'Failed to load edit page: ' . $e->getMessage());
        }
    }
}