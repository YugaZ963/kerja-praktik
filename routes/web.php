<?php

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
Route::get('/product', function () {
    return view('product', ['titleShop' => 'RAVAZKA', 'product' => [
        [
            'name' => 'Seragam SD Pendek',
            'price' => 40000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 10,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'name' => 'Seragam SD Panjang',
            'price' => 43000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 14,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'name' => 'Topi SD',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 6,
            'size' => '-',
            'category' => 'Seragam Sekolah SD',
        ],
        [
            'name' => 'Sabuk SD',
            'price' => 10000,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatem!',
            'stock' => 11,
            'size' => 'M',
            'category' => 'Seragam Sekolah SD',
        ]
    ]]);
});
