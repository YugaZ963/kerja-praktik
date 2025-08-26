<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
    protected $description = 'Test sinkronisasi otomatis antara data produk dan inventaris';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Sinkronisasi Otomatis Produk-Inventaris');
        $this->newLine();

        if ($this->option('create-test-data')) {
            $this->createTestData();
        }

        // Test 1: Create Product
        $this->info('📝 Test 1: Membuat Produk Baru');
        $this->testCreateProduct();
        $this->newLine();

        // Test 2: Update Product
        $this->info('✏️ Test 2: Update Produk');
        $this->testUpdateProduct();
        $this->newLine();

        // Test 3: Delete Product
        $this->info('🗑️ Test 3: Hapus Produk');
        $this->testDeleteProduct();
        $this->newLine();

        // Test 4: Bulk Operations
        $this->info('📦 Test 4: Operasi Bulk');
        $this->testBulkOperations();
        $this->newLine();

        // Test 5: Inventory Calculations
        $this->info('🧮 Test 5: Perhitungan Inventaris');
        $this->testInventoryCalculations();
        $this->newLine();

        $this->info('✅ Semua test selesai!');
        return 0;
    }

    private function createTestData()
    {
        $this->info('🔧 Membuat data test...');
        
        // Buat inventory test
        $inventory = Inventory::create([
            'code' => 'TEST-001',
            'name' => 'Test Seragam',
            'category' => 'Seragam',
            'stock' => 0,
            'min_stock' => 10,
            'purchase_price' => 50000,
            'selling_price' => 75000,
            'supplier' => 'Test Supplier',
            'last_restock' => now()->toDateString(),
            'location' => 'Gudang Test',
            'sizes_available' => ['S', 'M', 'L', 'XL'],
            'stock_history' => [],
            'description' => 'Test inventory untuk sinkronisasi'
        ]);
        
        $this->line("✓ Test inventory created: {$inventory->name} (ID: {$inventory->id})");
    }

    private function testCreateProduct()
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        if (!$inventory) {
            $this->error('Test inventory tidak ditemukan. Jalankan dengan --create-test-data');
            return;
        }

        $oldStock = $inventory->stock;
        $oldPrice = $inventory->selling_price;
        
        // Buat produk baru
        $product = Product::create([
            'inventory_id' => $inventory->id,
            'name' => 'Test Seragam - M',
            'size' => 'M',
            'price' => 80000,
            'stock' => 25,
            'category' => 'Seragam',
            'description' => 'Test produk ukuran M',
            'slug' => 'test-seragam-m-' . time()
        ]);
        
        // Refresh inventory
        $inventory->refresh();
        
        $this->line("✓ Produk dibuat: {$product->name}");
        $this->line("  Stock inventory: {$oldStock} → {$inventory->stock}");
        $this->line("  Harga inventory: {$oldPrice} → {$inventory->selling_price}");
        
        // Validasi
        if ($inventory->stock == 25 && $inventory->selling_price == 80000) {
            $this->info('  ✅ Sinkronisasi berhasil!');
        } else {
            $this->error('  ❌ Sinkronisasi gagal!');
        }
    }

    private function testUpdateProduct()
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        $product = $inventory->products()->first();
        
        if (!$product) {
            $this->error('Test produk tidak ditemukan');
            return;
        }

        $oldStock = $inventory->stock;
        $oldPrice = $inventory->selling_price;
        
        // Update produk
        $product->update([
            'stock' => 35,
            'price' => 85000
        ]);
        
        // Refresh inventory
        $inventory->refresh();
        
        $this->line("✓ Produk diupdate: {$product->name}");
        $this->line("  Stock inventory: {$oldStock} → {$inventory->stock}");
        $this->line("  Harga inventory: {$oldPrice} → {$inventory->selling_price}");
        
        // Validasi
        if ($inventory->stock == 35 && $inventory->selling_price == 85000) {
            $this->info('  ✅ Sinkronisasi berhasil!');
        } else {
            $this->error('  ❌ Sinkronisasi gagal!');
        }
    }

    private function testDeleteProduct()
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        $product = $inventory->products()->first();
        
        if (!$product) {
            $this->error('Test produk tidak ditemukan');
            return;
        }

        $oldStock = $inventory->stock;
        $productName = $product->name;
        
        // Hapus produk
        $product->delete();
        
        // Refresh inventory
        $inventory->refresh();
        
        $this->line("✓ Produk dihapus: {$productName}");
        $this->line("  Stock inventory: {$oldStock} → {$inventory->stock}");
        
        // Validasi
        if ($inventory->stock == 0) {
            $this->info('  ✅ Sinkronisasi berhasil!');
        } else {
            $this->error('  ❌ Sinkronisasi gagal!');
        }
    }

    private function testBulkOperations()
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        
        // Buat beberapa produk sekaligus
        $products = [];
        $sizes = ['S', 'M', 'L', 'XL'];
        
        foreach ($sizes as $size) {
            $products[] = Product::create([
                'inventory_id' => $inventory->id,
                'name' => "Test Seragam - {$size}",
                'size' => $size,
                'price' => 75000 + (strlen($size) * 5000), // Harga berbeda per ukuran
                'stock' => 20,
                'category' => 'Seragam',
                'description' => "Test produk ukuran {$size}",
                'slug' => 'test-seragam-' . strtolower($size) . '-' . time()
            ]);
        }
        
        // Refresh inventory
        $inventory->refresh();
        
        $totalStock = $inventory->stock;
        $averagePrice = $inventory->selling_price;
        
        $this->line("✓ " . count($products) . " produk dibuat");
        $this->line("  Total stock: {$totalStock}");
        $this->line("  Harga rata-rata: {$averagePrice}");
        
        // Validasi
        if ($totalStock == 80) { // 4 produk x 20 stock
            $this->info('  ✅ Bulk create berhasil!');
        } else {
            $this->error('  ❌ Bulk create gagal!');
        }
    }

    private function testInventoryCalculations()
    {
        $inventory = Inventory::where('code', 'TEST-001')->first();
        
        $this->line("📊 Status Inventaris: {$inventory->name}");
        $this->line("  Stock: {$inventory->stock}");
        $this->line("  Status: {$inventory->stock_status}");
        $this->line("  Total Value: Rp " . number_format($inventory->total_value, 0, ',', '.'));
        $this->line("  Jumlah Ukuran: {$inventory->available_sizes_count}");
        
        // Test status stok
        $expectedStatus = $inventory->stock > $inventory->min_stock ? 'tersedia' : 
                         ($inventory->stock > 0 ? 'rendah' : 'habis');
        
        if ($inventory->stock_status == $expectedStatus) {
            $this->info('  ✅ Status stok benar!');
        } else {
            $this->error('  ❌ Status stok salah!');
        }
        
        // Cleanup test data
        $this->info('🧹 Membersihkan data test...');
        $inventory->products()->delete();
        $inventory->delete();
        $this->line('✓ Data test dibersihkan');
    }
}