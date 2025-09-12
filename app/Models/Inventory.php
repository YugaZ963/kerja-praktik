<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventory
 *
 * Represents an inventory item in the application.
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $category
 * @property int $stock
 * @property int $min_stock
 * @property float $purchase_price
 * @property float $selling_price
 * @property string|null $supplier
 * @property \Illuminate\Support\Carbon|null $last_restock
 * @property array|null $sizes_available
 * @property string|null $location
 * @property string|null $description
 * @property array|null $stock_history
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read string $selling_price_formatted
 * @property-read string $purchase_price_formatted
 * @property-read string $stock_status
 * @property-read float $total_value
 * @property-read int $available_sizes_count
 * @property-read array $available_sizes
 */
class Inventory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stock' => 'integer',
        'min_stock' => 'integer',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'last_restock' => 'date',
        'sizes_available' => 'array',
        'stock_history' => 'array'
    ];

    /**
     * Get the products associated with the inventory item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the formatted selling price.
     *
     * @return string
     */
    public function getSellingPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->selling_price, 0, ',', '.');
    }

    /**
     * Get the formatted purchase price.
     *
     * @return string
     */
    public function getPurchasePriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->purchase_price, 0, ',', '.');
    }

    /**
     * Update the stock based on the total stock of its products.
     *
     * @return int The total stock.
     */
    public function updateStock()
    {
        $totalStock = $this->products()->sum('stock');
        $this->update(['stock' => $totalStock]);
        return $totalStock;
    }

    /**
     * Update inventory data based on its products.
     *
     * @return array An array containing the updated stock, selling price, and category.
     */
    public function updateFromProducts()
    {
        $products = $this->products();
        
        $totalStock = $products->sum('stock');
        
        $totalValue = $products->selectRaw('SUM(price * stock) as total_value')->value('total_value');
        $averagePrice = $totalStock > 0 ? $totalValue / $totalStock : $this->selling_price;
        
        $category = $products->first()?->category ?? $this->category;
        
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

    /**
     * Get the stock status.
     *
     * @return string
     */
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

    /**
     * Get the stock status attribute.
     *
     * @return string
     */
    public function getStockStatusAttribute()
    {
        return $this->getStockStatus();
    }

    /**
     * Get the total value of the inventory.
     *
     * @return float
     */
    public function getTotalValue()
    {
        return $this->stock * $this->selling_price;
    }

    /**
     * Get the total value attribute.
     *
     * @return float
     */
    public function getTotalValueAttribute()
    {
        return $this->getTotalValue();
    }

    /**
     * Get the count of available sizes.
     *
     * @return int
     */
    public function getAvailableSizesCount()
    {
        return $this->products()->distinct('size')->count('size');
    }

    /**
     * Get the available sizes count attribute.
     *
     * @return int
     */
    public function getAvailableSizesCountAttribute()
    {
        return $this->getAvailableSizesCount();
    }

    /**
     * Get the available sizes.
     *
     * @return array
     */
    public function getAvailableSizesAttribute()
    {
        return $this->products()->distinct('size')->pluck('size')->filter()->values()->toArray();
    }
}
