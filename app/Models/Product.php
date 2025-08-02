<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  // Mass assignment protection
  protected $fillable = [
    'name',
    'slug',
    'price',
    'description',
    'stock',
    'size',
    'category',
    'inventory_id'  // Tambahkan jika menggunakan relasi
  ];

  // Relasi dengan inventory
  public function inventory()
  {
    return $this->belongsTo(Inventory::class);
  }
}
