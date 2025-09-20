<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Inventory;

uses(RefreshDatabase::class);

it('returns a successful response', function () {
    // Buat data minimal untuk test
    $inventory = Inventory::create([
        'code' => 'TEST001',
        'name' => 'Test Inventory',
        'category' => 'Test Category',
        'description' => 'Test Description',
        'stock' => 100,
        'min_stock' => 10,
        'optimal_stock' => 50,
        'purchase_price' => 50000,
        'selling_price' => 100000,
        'supplier' => 'Test Supplier',
        'location' => 'Test Location',
        'last_restock' => now(),
        'sizes_available' => ['S', 'M', 'L'],
        'stock_history' => []
    ]);
    
    Product::create([
        'name' => 'Test Product',
        'slug' => 'test-product',
        'category' => 'Test Category',
        'description' => 'Test Description',
        'price' => 100000,
        'stock' => 10,
        'size' => 'M',
        'inventory_id' => $inventory->id
    ]);
    
    $response = $this->get('/');

    $response->assertStatus(200);
});
