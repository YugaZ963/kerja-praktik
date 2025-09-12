<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

/**
 * Class ProductObserver
 *
 * Observes the Product model's events.
 */
class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product): void
    {
        $this->syncInventoryData($product);
        Log::info("Product created: {$product->name} - {$product->size}, syncing inventory data");
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product): void
    {
        $this->syncInventoryData($product);
        
        if ($product->isDirty('inventory_id')) {
            $oldInventoryId = $product->getOriginal('inventory_id');
            if ($oldInventoryId) {
                $oldInventory = Inventory::find($oldInventoryId);
                if ($oldInventory) {
                    $oldInventory->updateStock();
                    $oldInventory->updateFromProducts();
                }
            }
        }
        
        Log::info("Product updated: {$product->name} - {$product->size}, syncing inventory data");
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product): void
    {
        $this->syncInventoryData($product);
        Log::info("Product deleted: {$product->name} - {$product->size}, syncing inventory data");
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product): void
    {
        $this->syncInventoryData($product);
        Log::info("Product restored: {$product->name} - {$product->size}, syncing inventory data");
    }

    /**
     * Sync inventory data based on product changes.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    private function syncInventoryData(Product $product): void
    {
        if ($product->inventory_id) {
            $inventory = Inventory::find($product->inventory_id);
            if ($inventory) {
                $inventory->updateStock();
                $inventory->updateFromProducts();
            }
        }
    }
}