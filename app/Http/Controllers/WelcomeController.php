<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil 3 pesanan terbaru dengan status berhasil/selesai saja
        $recentOrders = Order::with('user')
            ->whereIn('status', ['completed', 'delivered'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('welcome', [
            'titleShop' => 'RAVAZKA',
            'recentOrders' => $recentOrders
        ]);
    }
}