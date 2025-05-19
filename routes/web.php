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
    return view('product', ['titleShop' => 'RAVAZKA']);
});
