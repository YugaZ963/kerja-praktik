<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;

class UpdateInventoryStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua inventory
        $inventories = Inventory::all();
        
        foreach ($inventories as $inventory) {
            // Hitung total stock dari semua produk yang terkait
            $totalStock = Product::where('inventory_id', $inventory->id)
                                ->sum('stock');
            
            // Update stock inventory
            $inventory->update([
                'stock' => $totalStock
            ]);
            
            echo "Updated Inventory ID {$inventory->id} ({$inventory->name}): Stock = {$totalStock}\n";
        }
        
        echo "\nStock inventory berhasil diperbarui!\n";
    }
}