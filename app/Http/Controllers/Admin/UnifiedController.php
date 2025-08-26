<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnifiedController extends Controller
{
    /**
     * Display unified view of products and inventory
     */
    public function index(Request $request)
    {
        // Base query untuk inventaris dengan relasi produk
        $query = Inventory::with(['products' => function($query) {
            $query->orderBy('size', 'asc');
        }]);
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%")
                  ->orWhereHas('products', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('size', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter berdasarkan supplier
        if ($request->filled('supplier')) {
            $query->where('supplier', 'like', "%{$request->supplier}%");
        }
        
        // Filter berdasarkan status stok
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
        
        // Filter berdasarkan ukuran produk
        if ($request->filled('size')) {
            $query->whereHas('products', function($productQuery) use ($request) {
                $productQuery->where('size', $request->size);
            });
        }
        
        // Filter berdasargan rentang harga
        if ($request->filled('price_min')) {
            $query->whereHas('products', function($productQuery) use ($request) {
                $productQuery->where('price', '>=', $request->price_min);
            });
        }
        if ($request->filled('price_max')) {
            $query->whereHas('products', function($productQuery) use ($request) {
                $productQuery->where('price', '<=', $request->price_max);
            });
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
            case 'category-asc':
                $query->orderBy('category', 'asc');
                break;
            case 'category-desc':
                $query->orderBy('category', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $inventories = $query->paginate(15)->withQueryString();
        
        // Get filter options
        $categories = Inventory::select('category')->distinct()->pluck('category');
        $suppliers = Inventory::select('supplier')->distinct()->whereNotNull('supplier')->pluck('supplier');
        $sizes = Product::select('size')->distinct()->pluck('size');
        
        // Calculate statistics
        $totalInventories = Inventory::count();
        $totalProducts = Product::count();
        $lowStockInventories = Inventory::whereRaw('stock <= min_stock')->count();
        $outOfStockInventories = Inventory::where('stock', 0)->count();
        $totalStockValue = DB::table('products')
            ->join('inventories', 'products.inventory_id', '=', 'inventories.id')
            ->sum(DB::raw('products.stock * products.price'));
        
        return view('admin.unified.index', [
            'titleShop' => '📊 Manajemen Produk & Inventaris Terpadu - Admin RAVAZKA',
            'title' => '📊 Manajemen Produk & Inventaris Terpadu - Admin RAVAZKA',
            'metaDescription' => '🔧 Panel admin terpadu untuk mengelola produk dan inventaris seragam sekolah RAVAZKA dalam satu tampilan. Kelola stok, harga, kategori dengan mudah.',
            'metaKeywords' => 'admin RAVAZKA, manajemen produk inventaris, dashboard terpadu, kelola seragam',
            'inventories' => $inventories,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'sizes' => $sizes,
            'stats' => [
                'total_inventories' => $totalInventories,
                'total_products' => $totalProducts,
                'low_stock_inventories' => $lowStockInventories,
                'out_of_stock_inventories' => $outOfStockInventories,
                'total_stock_value' => $totalStockValue
            ]
        ]);
    }
    
    /**
     * Get products for specific inventory item
     */
    public function getInventoryProducts($inventoryId)
    {
        $inventory = Inventory::with('products')->findOrFail($inventoryId);
        
        return response()->json([
            'inventory' => $inventory,
            'products' => $inventory->products
        ]);
    }
    
    /**
     * Export unified data to Excel
     */
    public function export(Request $request)
    {
        // Implementation for export functionality
        // This can be implemented later if needed
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia.');
    }
}