<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    // Properti yang dapat diisi secara massal
    protected $fillable = [
        'code',
        'name',
        'category',
        'stock',
        'min_stock',
        'purchase_price',
        'selling_price',
        'supplier',
        'last_restock',
        'sizes_available',
        'location',
        'description',
        'stock_history'
    ];

    // Cast atribut ke tipe data yang sesuai
    protected $casts = [
        'stock' => 'integer',
        'min_stock' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'last_restock' => 'date',
        'sizes_available' => 'array',
        'stock_history' => 'array'
    ];

    // Relasi dengan produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Format atribut saat diakses
    public function getSellingPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->selling_price, 0, ',', '.');
    }

    // Format atribut saat diakses
    public function getPurchasePriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->purchase_price, 0, ',', '.');
    }

    // Method untuk update stock berdasarkan total stock produk
    public function updateStock()
    {
        $totalStock = $this->products()->sum('stock');
        $this->update(['stock' => $totalStock]);
        return $totalStock;
    }
}
