<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ProductController
 *
 * Handles the display of products to the public.
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('size')) {
            $query->where('size', strtoupper($request->size));
        }

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

    /**
     * Display the specified product.
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->with('inventory')->firstOrFail();
        
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('public.product-detail', [
            'titleShop' => '🔍 ' . $product->name . ' - Detail Produk RAVAZKA | ' . $product->category,
            'title' => '🔍 ' . $product->name . ' - Detail Produk RAVAZKA | ' . $product->category,
            'metaDescription' => '📋 Detail lengkap ' . $product->name . ' dari RAVAZKA. Lihat spesifikasi, harga, stok, dan ukuran yang tersedia. Kualitas terjamin dengan harga terjangkau.',
            'metaKeywords' => $product->name . ', detail produk seragam, ' . $product->category . ', RAVAZKA, beli seragam online',
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}
