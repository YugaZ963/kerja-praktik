<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

/**
 * Class FixInventoryStock
 *
 * A console command to check and fix inventory stock mismatches.
 */
class FixInventoryStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:fix-stock {--check : Only check for mismatches without fixing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix inventory stock mismatches by syncing with product stocks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('=== INVENTORY STOCK CHECKER/FIXER ===');
        $this->newLine();

        $inventories = Inventory::withCount('products')->get();
        $mismatches = [];
        
        foreach ($inventories as $inventory) {
            $correctStock = $inventory->products()->sum('stock');
            
            if ($inventory->stock != $correctStock) {
                $mismatches[] = [
                    'inventory' => $inventory,
                    'current_stock' => $inventory->stock,
                    'correct_stock' => $correctStock,
                    'difference' => $correctStock - $inventory->stock
                ];
            }
        }

        if (empty($mismatches)) {
            $this->info('✅ All inventory stocks are correct!');
            return 0;
        }

        $this->warn('Found ' . count($mismatches) . ' inventory stock mismatch(es):');
        $this->newLine();
        
        $headers = ['Inventory', 'Current Stock', 'Correct Stock', 'Difference'];
        $rows = [];
        
        foreach ($mismatches as $mismatch) {
            $rows[] = [
                $mismatch['inventory']->name,
                $mismatch['current_stock'],
                $mismatch['correct_stock'],
                ($mismatch['difference'] >= 0 ? '+' : '') . $mismatch['difference']
            ];
        }
        
        $this->table($headers, $rows);
        $this->newLine();

        if ($this->option('check')) {
            $this->info('Use "php artisan inventory:fix-stock" to fix these mismatches.');
            return 0;
        }

        if (!$this->confirm('Do you want to fix these mismatches?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        DB::beginTransaction();
        
        try {
            $fixedCount = 0;
            
            foreach ($mismatches as $mismatch) {
                $inventory = $mismatch['inventory'];
                $correctStock = $mismatch['correct_stock'];
                $oldStock = $mismatch['current_stock'];
                
                $inventory->update(['stock' => $correctStock]);
                
                $stockHistory = $inventory->stock_history;
                
                if (!is_array($stockHistory)) {
                    $stockHistory = [];
                }
                
                $stockHistory[] = [
                    'date' => now()->toDateString(),
                    'type' => 'correction',
                    'old_stock' => $oldStock,
                    'new_stock' => $correctStock,
                    'quantity' => $correctStock - $oldStock,
                    'notes' => 'Stock correction - sync with products (via artisan command)'
                ];
                
                $inventory->update(['stock_history' => $stockHistory]);
                
                $this->info("✅ Fixed: {$inventory->name} ({$oldStock} → {$correctStock})");
                $fixedCount++;
            }
            
            DB::commit();
            
            $this->newLine();
            $this->info("✅ Successfully fixed {$fixedCount} inventory stock mismatch(es)!");
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('❌ Error occurred: ' . $e->getMessage());
            $this->error('All changes have been rolled back.');
            return 1;
        }

        return 0;
    }
}