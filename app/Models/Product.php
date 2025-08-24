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

    // Event listeners untuk otomatis update stock inventory
    protected static function booted()
    {
        // Ketika produk dibuat, diupdate, atau dihapus
        static::created(function ($product) {
            $product->updateInventoryStock();
        });

        static::updated(function ($product) {
            $product->updateInventoryStock();
            
            // Jika inventory_id berubah, update stock inventory lama juga
            if ($product->isDirty('inventory_id')) {
                $oldInventoryId = $product->getOriginal('inventory_id');
                if ($oldInventoryId) {
                    $oldInventory = Inventory::find($oldInventoryId);
                    if ($oldInventory) {
                        $oldInventory->updateStock();
                    }
                }
            }
        });

        static::deleted(function ($product) {
            $product->updateInventoryStock();
        });
    }

    // Method untuk update stock inventory
    public function updateInventoryStock()
    {
        if ($this->inventory_id) {
            $this->inventory->updateStock();
        }
    }
}
