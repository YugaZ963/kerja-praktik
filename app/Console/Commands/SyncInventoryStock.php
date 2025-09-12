<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;

/**
 * Class SyncInventoryStock
 *
 * A console command to synchronize inventory stock with the total stock of its related products.
 */
class SyncInventoryStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:sync-stock {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize inventory stock with the total stock from related products';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Starting inventory stock synchronization...');
        
        if ($dryRun) {
            $this->warn('DRY RUN mode - No changes will be saved');
        }
        
        $inventories = Inventory::with('products')->get();
        $updated = 0;
        $unchanged = 0;
        
        foreach ($inventories as $inventory) {
            $currentStock = $inventory->stock;
            $actualStock = $inventory->products()->sum('stock');
            
            if ($currentStock != $actualStock) {
                $this->line("ID: {$inventory->id} | {$inventory->name}");
                $this->line("  Current stock: {$currentStock}");
                $this->line("  Actual stock: {$actualStock}");
                
                if (!$dryRun) {
                    $inventory->update(['stock' => $actualStock]);
                    $this->info("  ✓ Updated to {$actualStock}");
                } else {
                    $this->comment("  → Will be updated to {$actualStock}");
                }
                
                $updated++;
            } else {
                $unchanged++;
            }
        }
        
        $this->newLine();
        $this->info("Synchronization complete!");
        $this->line("Total inventories: " . $inventories->count());
        $this->line("Updated: {$updated}");
        $this->line("Unchanged: {$unchanged}");
        
        if ($dryRun && $updated > 0) {
            $this->newLine();
            $this->comment("Run without --dry-run to save the changes:");
            $this->comment("php artisan inventory:sync-stock");
        }
        
        return 0;
    }
}