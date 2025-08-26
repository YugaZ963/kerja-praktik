<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter ukuran
        if ($request->filled('size')) {
            $query->where('size', strtoupper($request->size));
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
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        return view('public.products', [
            'titleShop' => '📚 Katalog Seragam Sekolah Lengkap - RAVAZKA | Semua Jenjang Tersedia',
            'title' => '📚 Katalog Seragam Sekolah Lengkap - RAVAZKA | Semua Jenjang Tersedia',
            'metaDescription' => '🛍️ Jelajahi koleksi lengkap seragam sekolah RAVAZKA! Tersedia untuk SD, SMP, SMA dengan berbagai ukuran dan model terbaru. ✅ Kualitas terjamin ✅ Harga bersaing ✅ Stok lengkap.',
            'metaKeywords' => 'katalog seragam lengkap, daftar produk seragam, RAVAZKA terpercaya, beli seragam online, seragam sekolah berkualitas',
            'products' => $products
        ]);
    }
}
