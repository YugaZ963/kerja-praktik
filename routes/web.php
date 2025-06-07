<?php

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome', ['titleShop' => 'RAVAZKA']);
});

Route::get('/about', function () {
    return view('about', ['titleShop' => 'RAVAZKA']);
});
Route::get('/contact', function () {
    return view('contact', ['titleShop' => 'RAVAZKA']);
});
Route::get('/products', function () {
    return view('products', ['titleShop' => 'RAVAZKA', 'products' => Product::all()]);
});

Route::get('/products/{slug}', function ($slug) {
    // dd($id);


    $product = Product::find($slug);

    // dd($product);
    return view('product', ['titleShop' => 'RAVAZKA', 'product' => $product]);
});

// Rute untuk manajemen inventaris
Route::prefix('inventory')->group(function () {
    Route::get('/', function () {
        return view('inventory.index', [
            'titleShop' => 'RAVAZKA - Inventaris',
            'inventory_items' => Inventory::all()
        ]);
    });

    // Detail item inventaris
    Route::get('/{code}', function ($code) {
        $item = Inventory::find($code);
        return view('inventory.detail', ['titleShop' => 'RAVAZKA - Detail Inventaris', 'item' => $item]);
    });

    // Halaman laporan inventaris
    Route::get('/reports/stock', function () {
        return view('inventory.reports.stock', [
            'titleShop' => 'RAVAZKA - Laporan Stok',
            'report_date' => date('Y-m-d'),
            'categories' => [
                'Seragam Sekolah SD' => [
                    'total_items' => 4,
                    'total_stock' => 138,
                    'total_value' => 4_025_000,
                ],
                'Seragam Sekolah SMP' => [
                    'total_items' => 1,
                    'total_stock' => 40,
                    'total_value' => 1_600_000,
                ],
                'Seragam Sekolah SMA' => [
                    'total_items' => 1,
                    'total_stock' => 35,
                    'total_value' => 1_575_000,
                ],
            ],
            'low_stock_items' => [
                [
                    'code' => 'INV-SD-003',
                    'name' => 'Topi SD',
                    'current_stock' => 25,
                    'min_stock' => 5,
                    'status' => 'Aman',
                ],
                [
                    'code' => 'INV-SD-004',
                    'name' => 'Sabuk SD',
                    'current_stock' => 30,
                    'min_stock' => 8,
                    'status' => 'Aman',
                ],
            ],
        ]);
    });
});
