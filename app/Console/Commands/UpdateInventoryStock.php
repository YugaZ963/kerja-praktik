<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\Product;

class UpdateInventoryStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:update-stock {--force : Force update all inventory stock}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update inventory stock based on related products stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating inventory stock...');
        
        $inventories = Inventory::all();
        $updated = 0;
        
        foreach ($inventories as $inventory) {
            $oldStock = $inventory->stock;
            $newStock = $inventory->updateStock();
            
            if ($oldStock != $newStock || $this->option('force')) {
                $this->line("Updated: {$inventory->name} - Stock: {$oldStock} → {$newStock}");
                $updated++;
            }
        }
        
        if ($updated > 0) {
            $this->info("Successfully updated {$updated} inventory items.");
        } else {
            $this->info("All inventory stock is already up to date.");
        }
        
        // Show summary
        $this->newLine();
        $this->info('Current Inventory Summary:');
        $this->table(
            ['ID', 'Name', 'Stock', 'Products Count'],
            $inventories->map(function($inv) {
                return [
                    $inv->id,
                    $inv->name,
                    $inv->fresh()->stock,
                    $inv->products()->count()
                ];
            })->toArray()
        );
        
        return 0;
    }
}
