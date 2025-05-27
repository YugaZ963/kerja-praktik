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

        $product = Arr::first($products, function($product) use ($slug) {
            return $product ['slug'] == $slug;
        });

        // dd($product);
        return view('product', ['titleShop' => 'RAVAZKA', 'product' => $product]);
});