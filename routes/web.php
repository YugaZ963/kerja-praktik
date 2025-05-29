<?php

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
    return view('products', ['titleShop' => 'RAVAZKA', 'products' => [
        [
            'id' => 1,
            'slug' => 'seragam-sd-pendek',
            'name' => 'Seragam SD Pendek',
            'price' => 40000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 10,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'id' => 2,
            'slug' => 'seragam-sd-panjang',
            'name' => 'Seragam SD Panjang',
            'price' => 43000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 14,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'id' => 3,
            'slug' => 'topi-sd',
            'name' => 'Topi SD',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 6,
            'size' => '-',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'id' => 4,
            'slug' => 'sabuk-sd',
            'name' => 'Sabuk SD',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 11,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ]
    ]]);
});

Route::get('/products/{slug}', function ($slug) {
    // dd($id);
    $products = [
        [
            'id' => 1,
            'slug' => 'seragam-sd-pendek',
            'name' => 'Seragam SD Pendek',
            'price' => 40000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 10,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'id' => 2,
            'slug' => 'seragam-sd-panjang',
            'name' => 'Seragam SD Panjang',
            'price' => 43000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 14,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'id' => 3,
            'slug' => 'topi-sd',
            'name' => 'Topi SD',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 6,
            'size' => '-',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'id' => 4,
            'slug' => 'sabuk-sd',
            'name' => 'Sabuk SD',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 11,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ]
    ];

    $product = Arr::first($products, function ($product) use ($slug) {
        return $product['slug'] == $slug;
    });

    // dd($product);
    return view('product', ['titleShop' => 'RAVAZKA', 'product' => $product]);
});

// Rute untuk manajemen inventaris
// Definisi data dummy untuk inventaris
$dummyItems = [
    [
        'id' => 1,
        'code' => 'INV-SD-001',
        'name' => 'Seragam SD Pendek',
        'category' => 'Seragam Sekolah SD',
        'stock' => 45,
        'min_stock' => 10,
        'purchase_price' => 35000,
        'selling_price' => 40000,
        'supplier' => 'PT Seragam Jaya',
        'last_restock' => '2023-10-15',
    ],
    [
        'id' => 2,
        'code' => 'INV-SD-002',
        'name' => 'Seragam SD Panjang',
        'category' => 'Seragam Sekolah SD',
        'stock' => 38,
        'min_stock' => 10,
        'purchase_price' => 38000,
        'selling_price' => 43000,
        'supplier' => 'PT Seragam Jaya',
        'last_restock' => '2023-10-15',
    ],
    [
        'id' => 3,
        'code' => 'INV-SD-003',
        'name' => 'Topi SD',
        'category' => 'Seragam Sekolah SD',
        'stock' => 25,
        'min_stock' => 5,
        'purchase_price' => 8000,
        'selling_price' => 10000,
        'supplier' => 'CV Aksesoris Sekolah',
        'last_restock' => '2023-09-20',
    ],
];

Route::prefix('inventory')->group(function () use ($dummyItems) {
    Route::get('/', function () use ($dummyItems) {
        return view('inventory.index', [
            'titleShop' => 'RAVAZKA - Inventaris',
            'inventory_items' => $dummyItems
        ]);
    });

    // Detail item inventaris
    Route::get('/{code}', function ($code) use ($dummyItems) {
        $inventory_items = [
            [
                'id' => 1,
                'code' => 'INV-SD-001',
                'name' => 'Seragam SD Pendek',
                'category' => 'Seragam Sekolah SD',
                'stock' => 45,
                'min_stock' => 10,
                'purchase_price' => 35000,
                'selling_price' => 40000,
                'supplier' => 'PT Seragam Jaya',
                'last_restock' => '2023-10-15',
                'sizes_available' => ['S', 'M', 'L', 'XL'],
                'location' => 'Rak A-1',
                'description' => 'Seragam SD lengan pendek putih dengan kualitas premium, tahan lama dan nyaman dipakai.',
                'stock_history' => [
                    ['date' => '2023-10-15', 'type' => 'in', 'quantity' => 20, 'notes' => 'Pembelian dari supplier'],
                    ['date' => '2023-09-10', 'type' => 'in', 'quantity' => 30, 'notes' => 'Stok awal'],
                    ['date' => '2023-09-25', 'type' => 'out', 'quantity' => 5, 'notes' => 'Penjualan'],
                ]
            ],
            [
                'id' => 2,
                'code' => 'INV-SD-002',
                'name' => 'Seragam SD Panjang',
                'category' => 'Seragam Sekolah SD',
                'stock' => 38,
                'min_stock' => 10,
                'purchase_price' => 38000,
                'selling_price' => 43000,
                'supplier' => 'PT Seragam Jaya',
                'last_restock' => '2023-10-15',
                'sizes_available' => ['S', 'M', 'L', 'XL'],
                'location' => 'Rak A-2',
                'description' => 'Seragam SD lengan panjang putih dengan kualitas premium, tahan lama dan nyaman dipakai.',
                'stock_history' => [
                    ['date' => '2023-10-15', 'type' => 'in', 'quantity' => 15, 'notes' => 'Pembelian dari supplier'],
                    ['date' => '2023-09-10', 'type' => 'in', 'quantity' => 25, 'notes' => 'Stok awal'],
                    ['date' => '2023-09-25', 'type' => 'out', 'quantity' => 2, 'notes' => 'Penjualan'],
                ]
            ],
            [
                'id' => 3,
                'code' => 'INV-SD-003',
                'name' => 'Topi SD',
                'category' => 'Seragam Sekolah SD',
                'stock' => 25,
                'min_stock' => 5,
                'purchase_price' => 8000,
                'selling_price' => 10000,
                'supplier' => 'CV Aksesoris Sekolah',
                'last_restock' => '2023-09-20',
                'sizes_available' => ['All Size'],
                'location' => 'Rak B-1',
                'description' => 'Topi SD standar nasional dengan logo dan bahan berkualitas.',
                'stock_history' => [
                    ['date' => '2023-09-20', 'type' => 'in', 'quantity' => 25, 'notes' => 'Pembelian dari supplier'],
                    ['date' => '2023-08-15', 'type' => 'in', 'quantity' => 15, 'notes' => 'Stok awal'],
                    ['date' => '2023-09-05', 'type' => 'out', 'quantity' => 15, 'notes' => 'Penjualan'],
                ]
            ],
        ];

        $item = Arr::first($inventory_items, function ($item) use ($code) {
            return $item['code'] == $code;
        });

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
