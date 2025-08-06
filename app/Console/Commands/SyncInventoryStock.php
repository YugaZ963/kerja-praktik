<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;

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
    protected $description = 'Sinkronkan stok inventory dengan total stok dari products terkait';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Memulai sinkronisasi stok inventory...');
        
        if ($dryRun) {
            $this->warn('Mode DRY RUN - Tidak ada perubahan yang akan disimpan');
        }
        
        $inventories = Inventory::with('products')->get();
        $updated = 0;
        $unchanged = 0;
        
        foreach ($inventories as $inventory) {
            $currentStock = $inventory->stock;
            $actualStock = $inventory->products()->sum('stock');
            
            if ($currentStock != $actualStock) {
                $this->line("ID: {$inventory->id} | {$inventory->name}");
                $this->line("  Stok saat ini: {$currentStock}");
                $this->line("  Stok aktual: {$actualStock}");
                
                if (!$dryRun) {
                    $inventory->update(['stock' => $actualStock]);
                    $this->info("  ✓ Diperbarui ke {$actualStock}");
                } else {
                    $this->comment("  → Akan diperbarui ke {$actualStock}");
                }
                
                $updated++;
            } else {
                $unchanged++;
            }
        }
        
        $this->newLine();
        $this->info("Sinkronisasi selesai!");
        $this->line("Total inventory: " . $inventories->count());
        $this->line("Diperbarui: {$updated}");
        $this->line("Tidak berubah: {$unchanged}");
        
        if ($dryRun && $updated > 0) {
            $this->newLine();
            $this->comment("Jalankan tanpa --dry-run untuk menyimpan perubahan:");
            $this->comment("php artisan inventory:sync-stock");
        }
        
        return 0;
    }
}