<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
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
        
        // Filter berdasarkan status stok
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'low':
                    $query->whereRaw('stock <= 5')->whereRaw('stock > 0');
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'ready':
                    $query->whereRaw('stock > 5');
                    break;
                case 'critical':
                    $query->whereRaw('stock <= 3');
                    break;
            }
        }
        
        // Filter berdasarkan rentang harga
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
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
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
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
        
        return view('inventory.index', [
            'titleShop' => 'RAVAZKA - Inventaris',
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
        
        return view('inventory.report', [
            'titleShop' => 'RAVAZKA - Laporan Inventaris',
            'inventory_items' => $inventory_items
        ]);
    }
}