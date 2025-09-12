<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * Represents a product in the application.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property float $price
 * @property float|null $weight
 * @property string|null $description
 * @property int $stock
 * @property string|null $size
 * @property string|null $category
 * @property int|null $inventory_id
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Inventory|null $inventory
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * Get the inventory that the product belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Update the stock and data of the associated inventory.
     *
     * @return void
     */
    public function updateInventoryStock()
    {
        if ($this->inventory_id) {
            $this->inventory->updateStock();
            $this->inventory->updateFromProducts();
        }
    }
}
