<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class WelcomeController
 *
 * Handles the display of the welcome page.
 */
class WelcomeController extends Controller
{
    /**
     * Display the welcome page.
     *
     * @return View
     */
    public function index(): View
    {
        // Ambil 3 pesanan terbaru dengan status berhasil/selesai saja
        $recentOrders = Order::with(['user', 'items.product'])
            ->whereIn('status', ['completed', 'delivered'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('public.welcome', [
            'titleShop' => '🏫 RAVAZKA - Toko Seragam Sekolah Terpercaya #1 | Kualitas Premium Harga Terjangkau ✨',
            'title' => '🏫 RAVAZKA - Toko Seragam Sekolah Terpercaya #1 | Kualitas Premium Harga Terjangkau ✨',
            'metaDescription' => '⭐ Toko seragam sekolah RAVAZKA terpercaya sejak 2010! Menyediakan seragam berkualitas premium untuk SD, SMP, SMA. ✅ Berbagai ukuran lengkap ✅ Model terbaru ✅ Harga terjangkau ✅ Pengiriman cepat. Pesan online sekarang juga!',
            'metaKeywords' => 'toko seragam sekolah terpercaya, beli seragam online murah, seragam sekolah berkualitas, RAVAZKA seragam, seragam SD SMP SMA, toko seragam Jakarta, seragam sekolah terlengkap',
            'recentOrders' => $recentOrders
        ]);
    }
}