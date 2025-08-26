<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::query();
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter berdasarkan status stok
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
        
        // Filter berdasarkan rentang harga
        if ($request->filled('price_min')) {
            $query->where('selling_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('selling_price', '<=', $request->price_max);
        }
        
        // Filter berdasarkan tanggal
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
    
    public function report(Request $request)
    {
        $query = Inventory::query();
        
        // Filter pencarian untuk laporan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
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
     * Get products by inventory for AJAX requests
     */
    public function getProducts($inventoryId)
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
     * Update inventory stock based on product changes
     */
    public function updateStockFromProducts(Request $request, $inventoryId)
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
     * Mendapatkan ringkasan inventaris beserta rincian produk berdasarkan ukuran
     */
    public function getSummary($id)
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
                'message' => 'Gagal mengambil ringkasan inventaris: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Menghapus semua produk berdasarkan ukuran dari inventaris tertentu
     */
    public function deleteProductsBySize(Request $request, $id)
    {
        try {
            $size = $request->query('size');
            
            if (!$size) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter ukuran diperlukan'
                ], 400);
            }
            
            $inventory = Inventory::findOrFail($id);
            $products = Product::where('inventory_id', $id)
                              ->where('size', $size)
                              ->get();
            
            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada produk dengan ukuran tersebut'
                ], 404);
            }
            
            // Hapus semua produk dengan ukuran tertentu
            Product::where('inventory_id', $id)
                   ->where('size', $size)
                   ->delete();
            
            // Update total stok inventaris
            $this->updateStockFromProducts(new Request(), $id);
            
            return response()->json([
                'success' => true,
                'message' => 'Semua produk dengan ukuran ' . $size . ' berhasil dihapus',
                'deleted_count' => $products->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tambah stok untuk produk dengan ukuran tertentu
     */
    public function addStock(Request $request, $inventoryId)
    {
        $request->validate([
            'size' => 'required|string',
            'stock' => 'required|integer|min:1'
        ]);

        try {
            $inventory = Inventory::findOrFail($inventoryId);
            $size = $request->size;
            $addStock = $request->stock;

            // Cari produk dengan ukuran yang sama
            $product = Product::where('inventory_id', $inventoryId)
                             ->where('size', $size)
                             ->first();

            if ($product) {
                // Jika produk sudah ada, tambah stoknya
                $product->increment('stock', $addStock);
            } else {
                // Jika produk belum ada, buat produk baru
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

            // Inventory stock akan otomatis terupdate melalui Product model event listeners

            return redirect()->route('inventory.index')
                ->with('success', "Berhasil menambah {$addStock} stok untuk ukuran {$size}");
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', 'Gagal menambah stok: ' . $e->getMessage());
        }
    }

    /**
     * Kurangi stok untuk produk dengan ukuran tertentu
     */
    public function reduceStock(Request $request, $inventoryId)
    {
        $request->validate([
            'size' => 'required|string',
            'stock' => 'required|integer|min:1'
        ]);

        try {
            $inventory = Inventory::findOrFail($inventoryId);
            $size = $request->size;
            $reduceStock = $request->stock;

            // Cari produk dengan ukuran yang sama
            $product = Product::where('inventory_id', $inventoryId)
                             ->where('size', $size)
                             ->first();

            if (!$product) {
                return redirect()->route('inventory.index')
                    ->with('error', "Produk dengan ukuran {$size} tidak ditemukan");
            }

            if ($product->stock < $reduceStock) {
                return redirect()->route('inventory.index')
                    ->with('error', "Stok tidak mencukupi. Stok saat ini: {$product->stock}");
            }

            // Kurangi stok
            $product->decrement('stock', $reduceStock);

            // Inventory stock akan otomatis terupdate melalui Product model event listeners

            return redirect()->route('inventory.index')
                ->with('success', "Berhasil mengurangi {$reduceStock} stok untuk ukuran {$size}");
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', 'Gagal mengurangi stok: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form edit produk berdasarkan ukuran
     */
    public function editProductsBySize($inventoryId, $size)
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
                ->with('error', 'Gagal memuat halaman edit: ' . $e->getMessage());
        }
    }
}