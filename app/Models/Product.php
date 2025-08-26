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
        'weight',
        'description',
        'stock',
        'size',
        'category',
        'inventory_id',
        'image'
    ];

    // Relasi dengan inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Event listeners sudah dipindahkan ke ProductObserver untuk struktur yang lebih baik

    // Method untuk update stock dan data inventory
    public function updateInventoryStock()
    {
        if ($this->inventory_id) {
            $this->inventory->updateStock();
            $this->inventory->updateFromProducts();
        }
    }
}
