<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Class TestAutoSync
 *
 * A console command to test the automatic synchronization between products and inventory.
 */
class TestAutoSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:test-auto-sync {--create-test-data : Create test data for testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the automatic synchronization between product and inventory data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('🧪 Testing Product-Inventory Automatic Synchronization');
        $this->newLine();

        if ($this->option('create-test-data')) {
            $this->createTestData();
        }

        $this->info('📝 Test 1: Creating a New Product');
        $this->testCreateProduct();
        $this->newLine();

        $this->info('✏️ Test 2: Updating a Product');
        $this->testUpdateProduct();
        $this->newLine();

        $this->info('🗑️ Test 3: Deleting a Product');
        $this->testDeleteProduct();
        $this->newLine();

        $this->info('📦 Test 4: Bulk Operations');
        $this->testBulkOperations();
        $this->newLine();

        $this->info('🧮 Test 5: Inventory Calculations');
        $this->testInventoryCalculations();
        $this->newLine();

        $this->info('✅ All tests completed!');
        return 0;
    }

    /**
     * Create test data for the synchronization tests.
     *
     * @return void
     */
    private function createTestData(): void
    {
        $this->info('🔧 Creating test data...');
        
        $inventory = Inventory::create([
            'code' => 'TEST-001',
            'name' => 'Test Uniform',
            'category' => 'Uniform',
            'stock' => 0,
            'min_stock' => 10,
            'purchase_price' => 50000,
            'selling_price' => 75000,
            'supplier' => 'Test Supplier',
            'last_restock' => now()->toDateString(),
            'location' => 'Test Warehouse',
            'sizes_available' => ['S', 'M', 'L', 'XL'],
            'stock_history' => [],
            'description' => 'Test inventory for synchronization'
        ]);
        
        $this->line("✓ Test inventory created: {$inventory->name} (ID: {$inventory->id})");
    }

    /**
     * Test the creation of a new product and its effect on inventory.
     *
     * @return void
     */
    private function testCreateProduct(): void
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        if (!$inventory) {
            $this->error('Test inventory not found. Run with --create-test-data');
            return;
        }

        $oldStock = $inventory->stock;
        $oldPrice = $inventory->selling_price;
        
        $product = Product::create([
            'inventory_id' => $inventory->id,
            'name' => 'Test Uniform - M',
            'size' => 'M',
            'price' => 80000,
            'stock' => 25,
            'category' => 'Uniform',
            'description' => 'Test product size M',
            'slug' => 'test-uniform-m-' . time()
        ]);
        
        $inventory->refresh();
        
        $this->line("✓ Product created: {$product->name}");
        $this->line("  Inventory stock: {$oldStock} → {$inventory->stock}");
        $this->line("  Inventory price: {$oldPrice} → {$inventory->selling_price}");
        
        if ($inventory->stock == 25 && $inventory->selling_price == 80000) {
            $this->info('  ✅ Synchronization successful!');
        } else {
            $this->error('  ❌ Synchronization failed!');
        }
    }

    /**
     * Test the update of a product and its effect on inventory.
     *
     * @return void
     */
    private function testUpdateProduct(): void
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        $product = $inventory->products()->first();
        
        if (!$product) {
            $this->error('Test product not found');
            return;
        }

        $oldStock = $inventory->stock;
        $oldPrice = $inventory->selling_price;
        
        $product->update([
            'stock' => 35,
            'price' => 85000
        ]);
        
        $inventory->refresh();
        
        $this->line("✓ Product updated: {$product->name}");
        $this->line("  Inventory stock: {$oldStock} → {$inventory->stock}");
        $this->line("  Inventory price: {$oldPrice} → {$inventory->selling_price}");
        
        if ($inventory->stock == 35 && $inventory->selling_price == 85000) {
            $this->info('  ✅ Synchronization successful!');
        } else {
            $this->error('  ❌ Synchronization failed!');
        }
    }

    /**
     * Test the deletion of a product and its effect on inventory.
     *
     * @return void
     */
    private function testDeleteProduct(): void
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        $product = $inventory->products()->first();
        
        if (!$product) {
            $this->error('Test product not found');
            return;
        }

        $oldStock = $inventory->stock;
        $productName = $product->name;
        
        $product->delete();
        
        $inventory->refresh();
        
        $this->line("✓ Product deleted: {$productName}");
        $this->line("  Inventory stock: {$oldStock} → {$inventory->stock}");
        
        if ($inventory->stock == 0) {
            $this->info('  ✅ Synchronization successful!');
        } else {
            $this->error('  ❌ Synchronization failed!');
        }
    }

    /**
     * Test bulk product operations and their effect on inventory.
     *
     * @return void
     */
    private function testBulkOperations(): void
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        
        $products = [];
        $sizes = ['S', 'M', 'L', 'XL'];
        
        foreach ($sizes as $size) {
            $products[] = Product::create([
                'inventory_id' => $inventory->id,
                'name' => "Test Uniform - {$size}",
                'size' => $size,
                'price' => 75000 + (strlen($size) * 5000),
                'stock' => 20,
                'category' => 'Uniform',
                'description' => "Test product size {$size}",
                'slug' => 'test-uniform-' . strtolower($size) . '-' . time()
            ]);
        }
        
        $inventory->refresh();
        
        $totalStock = $inventory->stock;
        $averagePrice = $inventory->selling_price;
        
        $this->line("✓ " . count($products) . " products created");
        $this->line("  Total stock: {$totalStock}");
        $this->line("  Average price: {$averagePrice}");
        
        if ($totalStock == 80) {
            $this->info('  ✅ Bulk create successful!');
        } else {
            $this->error('  ❌ Bulk create failed!');
        }
    }

    /**
     * Test inventory calculations.
     *
     * @return void
     */
    private function testInventoryCalculations(): void
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        
        $this->line("📊 Inventory Status: {$inventory->name}");
        $this->line("  Stock: {$inventory->stock}");
        $this->line("  Status: {$inventory->stock_status}");
        $this->line("  Total Value: Rp " . number_format($inventory->total_value, 0, ',', '.'));
        $this->line("  Number of Sizes: {$inventory->available_sizes_count}");
        
        $expectedStatus = $inventory->stock > $inventory->min_stock ? 'available' :
                         ($inventory->stock > 0 ? 'low' : 'out_of_stock');
        
        if ($inventory->stock_status == $expectedStatus) {
            $this->info('  ✅ Stock status correct!');
        } else {
            $this->error('  ❌ Stock status incorrect!');
        }
        
        $this->info('🧹 Cleaning up test data...');
        $inventory->products()->delete();
        $inventory->delete();
        $this->line('✓ Test data cleaned up');
    }
}