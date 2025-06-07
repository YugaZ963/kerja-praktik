<?php

namespace App\Models;

use Illuminate\Support\Arr;

class Inventory
{
  public static function all()
  {
    return [
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
  }

  public static function find($code): array
  {
    // return Arr::first(static::all(), function ($item) use ($code) {
    //   return $item['code'] == $code;
    // });

    $item = Arr::first(static::all(), fn($item) => $item['code'] == $code);

    if (! $item) {
      abort(404);
    }

    return $item;
  }
}
