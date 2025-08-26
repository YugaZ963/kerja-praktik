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

    // Method untuk update data inventaris berdasarkan produk
    public function updateFromProducts()
    {
        $products = $this->products();
        
        // Update total stock
        $totalStock = $products->sum('stock');
        
        // Hitung harga rata-rata berdasarkan produk yang ada
        $totalValue = $products->selectRaw('SUM(price * stock) as total_value')->value('total_value');
        $averagePrice = $totalStock > 0 ? $totalValue / $totalStock : $this->selling_price;
        
        // Ambil kategori dari produk pertama (asumsi semua produk dalam inventory sama kategorinya)
        $category = $products->first()?->category ?? $this->category;
        
        // Update data inventory
        $this->update([
            'stock' => $totalStock,
            'selling_price' => round($averagePrice, 2),
            'category' => $category
        ]);
        
        return [
            'stock' => $totalStock,
            'selling_price' => $averagePrice,
            'category' => $category
        ];
    }

    // Method untuk mendapatkan status stok
    public function getStockStatus()
    {
        if ($this->stock <= 0) {
            return 'habis';
        } elseif ($this->stock <= $this->min_stock) {
            return 'rendah';
        } else {
            return 'tersedia';
        }
    }

    // Accessor untuk status stok
    public function getStockStatusAttribute()
    {
        return $this->getStockStatus();
    }

    // Method untuk mendapatkan total nilai inventaris
    public function getTotalValue()
    {
        return $this->stock * $this->selling_price;
    }

    // Accessor untuk total nilai inventaris
    public function getTotalValueAttribute()
    {
        return $this->getTotalValue();
    }

    // Method untuk mendapatkan jumlah ukuran yang tersedia
    public function getAvailableSizesCount()
    {
        return $this->products()->distinct('size')->count('size');
    }

    // Accessor untuk jumlah ukuran yang tersedia
    public function getAvailableSizesCountAttribute()
    {
        return $this->getAvailableSizesCount();
    }
}
