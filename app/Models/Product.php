<?php

namespace App\Models;

use Illuminate\Support\Arr;

class Product
{
  public static function all()
  {
    return [
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
  }

  public static function find($slug): array
  {
    // return Arr::first(static::all(), function ($product) use ($slug) {
    //   return $product['slug'] == $slug;
    // });

    $product = Arr::first(static::all(), fn($product) => $product['slug'] == $slug);

    if (! $product) {
      abort(404);
    }

    return $product;
  }
}
