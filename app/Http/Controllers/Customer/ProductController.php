<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
                  ->orWhere('size', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter ukuran
        if ($request->filled('size')) {
            $query->where('size', strtoupper($request->size));
        }
        
        // Filter berdasarkan inventory
        if ($request->filled('inventory')) {
            $query->where('inventory_id', $request->inventory);
        }

        // Filter harga
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter stok
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
                case 'low':
                    $query->where('stock', '>', 0)->where('stock', '<=', 5);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
            }
        }

        // Sorting
        switch ($request->sort) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
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
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        // Get categories and sizes for filters
        $categories = Product::distinct()->pluck('category');
        $sizes = Product::distinct()->pluck('size');

        return view('customer.products', [
            'titleShop' => '📚 Katalog Seragam Sekolah Lengkap - RAVAZKA | Semua Jenjang Tersedia',
            'title' => '📚 Katalog Seragam Sekolah Lengkap - RAVAZKA | Semua Jenjang Tersedia',
            'metaDescription' => '🛍️ Jelajahi koleksi lengkap seragam sekolah RAVAZKA! Tersedia untuk SD, SMP, SMA dengan berbagai ukuran dan model terbaru. ✅ Kualitas terjamin ✅ Harga bersaing ✅ Stok lengkap.',
            'metaKeywords' => 'katalog seragam lengkap, daftar produk seragam, RAVAZKA terpercaya, beli seragam online, seragam sekolah berkualitas',
            'products' => $products,
            'categories' => $categories,
            'sizes' => $sizes
        ])->with('active', 'products');
    }
}
