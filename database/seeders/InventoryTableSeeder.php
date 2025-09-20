<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        // Hapus data yang ada dengan cara yang aman untuk foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Inventory::truncate();
        \App\Models\Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $inventories = [
            [
                'id'              => 1,
                'code'            => 'INV-SD-001',
                'name'            => 'Seragam SD Pendek',
                'category'        => 'Kemeja Sekolah',
                'stock'           => 0,        // <-- sudah ada
                'min_stock'       => 50,
                'optimal_stock'   => 100,
                'purchase_price'  => 35000,
                'selling_price'   => 40000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => ['8', '9', '10', '11', '12', '13', '14', '15', '16'],
                'location'        => 'Rak A-1',
                'description'     => 'Kemeja seragam sekolah lengan pendek putih premium',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 180, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 2,
                'code'            => 'INV-SD-002',
                'name'            => 'Seragam SD Panjang',
                'category'        => 'Kemeja Sekolah',
                'stock'           => 0,        // <-- tambahkan ini
                'min_stock'       => 50,
                'optimal_stock'   => 100,
                'purchase_price'  => 38000,
                'selling_price'   => 43000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => ['8', '9', '10', '11', '12', '13', '14', '15', '16'],
                'location'        => 'Rak A-2',
                'description'     => 'Kemeja seragam sekolah lengan panjang putih premium',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 80, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 3,
                'code'            => 'INV-BATIK-001',
                'name'            => 'Kemeja Batik Panjang',
                'category'        => 'Kemeja Batik',
                'stock'           => 0,        // <-- tambahkan
                'min_stock'       => 50,
                'optimal_stock'   => 100,
                'purchase_price'  => 42000,
                'selling_price'   => 46000,
                'supplier'        => 'CV Batik Nusantara',
                'sizes_available' => json_encode(['8', '9', '10', '11', '12', '13', '14', '15', '16']),
                'location'        => 'Rak C-1',
                'description'     => 'Kemeja batik lengan panjang motif sekolah',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => json_encode([
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 180, 'notes' => 'Stok awal']
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 4,
                'code'            => 'INV-BAJU-KOKO-001',
                'name'            => 'Baju Koko',
                'category'        => 'Kemeja Sekolah',
                'stock'           => 30,
                'min_stock'       => 1,
                'optimal_stock'   => 12,
                'purchase_price'  => 0,
                'selling_price'   => 47500,
                'supplier'        => 'PT Sabana',
                'sizes_available' => json_encode(['M', 'S']),
                'location'        => 'Rak S-2',
                'description'     => 'kefneskfnekfsneskfnfklesnfeskfnesl',
                'last_restock'    => '2025-09-12',
                'stock_history'   => json_encode([
                    ['date' => '2025-09-12', 'type' => 'in', 'quantity' => 30, 'notes' => 'Stok awal']
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 5,
                'code'            => 'INV-PADANG-001',
                'name'            => 'Kemeja Padang',
                'category'        => 'Kemeja Padang',
                'stock'           => 0,        // <-- tambahkan
                'min_stock'       => 30,
                'optimal_stock'   => 60,
                'purchase_price'  => 62000,
                'selling_price'   => 66000,
                'supplier'        => 'PD Padang Garment',
                'sizes_available' => ['14', '15', '16', 'S', 'M', 'L', 'XL', 'L3', 'L4', 'L5', 'L6'],
                'location'        => 'Rak D-1',
                'description'     => 'Kemeja padang motif khas untuk seragam sekolah',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 150, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 6,
                'code'            => 'INV-ROK-001',
                'name'            => 'Rok Panjang SD',
                'category'        => 'Rok Sekolah',
                'stock'           => 0,        // <-- tambahkan
                'min_stock'       => 30,
                'optimal_stock'   => 60,
                'purchase_price'  => 44000,
                'selling_price'   => 48000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => ['3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                'location'        => 'Rak E-1',
                'description'     => 'Rok panjang seragam SD warna biru dongker',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 150, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 7,
                'code'            => 'INV-CLN-001',
                'name'            => 'Celana Panjang SD',
                'category'        => 'Celana Sekolah',
                'stock'           => 0,        // <-- tambahkan
                'min_stock'       => 30,
                'optimal_stock'   => 60,
                'purchase_price'  => 43000,
                'selling_price'   => 47000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => ['3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
                'location'        => 'Rak F-1',
                'description'     => 'Celana panjang seragam SD warna abu-abu',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 120, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 8,
                'code'            => 'INV-TOPI-001',
                'name'            => 'Topi Sekolah',
                'category'        => 'Aksesoris',
                'stock'           => 0,
                'min_stock'       => 100,
                'optimal_stock'   => 200,
                'purchase_price'  => 8000,
                'selling_price'   => 10000,
                'supplier'        => 'CV Aksesoris Sekolah',
                'sizes_available' => ['Kecil', 'Besar'],
                'location'        => 'Rak G-1',
                'description'     => 'Topi seragam sekolah berbagai tingkat',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 200, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 9,
                'code'            => 'INV-KERUDUNG-001',
                'name'            => 'Kerudung Sekolah',
                'category'        => 'Aksesoris',
                'stock'           => 0,
                'min_stock'       => 100,
                'optimal_stock'   => 200,
                'purchase_price'  => 8000,
                'selling_price'   => 10000,
                'supplier'        => 'CV Aksesoris Sekolah',
                'sizes_available' => ['S', 'M', 'L', 'XL'],
                'location'        => 'Rak G-2',
                'description'     => 'Kerudung seragam sekolah berbagai tingkat',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 400, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 10,
                'code'            => 'INV-SABUK-001',
                'name'            => 'Sabuk Sekolah',
                'category'        => 'Aksesoris',
                'stock'           => 0,
                'min_stock'       => 100,
                'optimal_stock'   => 200,
                'purchase_price'  => 8000,
                'selling_price'   => 10000,
                'supplier'        => 'CV Aksesoris Sekolah',
                'sizes_available' => ['Kecil', 'Besar'],
                'location'        => 'Rak G-3',
                'description'     => 'Sabuk seragam sekolah berbagai tingkat',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 400, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 11,
                'code'            => 'INV-PRAMUKA-001',
                'name'            => 'Seragam Pramuka',
                'category'        => 'Pramuka',
                'stock'           => 0,
                'min_stock'       => 50,
                'optimal_stock'   => 100,
                'purchase_price'  => 45000,
                'selling_price'   => 50000,
                'supplier'        => 'CV Pramuka Indonesia',
                'sizes_available' => ['8', '9', '10', '11', '12', '13', '14', '15', '16', 'S', 'M', 'L', 'XL', 'L3', 'L4', 'L5', 'L6'],
                'location'        => 'Rak H-1',
                'description'     => 'Seragam pramuka lengkap berbagai tingkat',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => [
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 200, 'notes' => 'Stok awal']
                ],
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ];

        // Gunakan model Eloquent untuk insert agar array casting berfungsi
        foreach ($inventories as $inventory) {
            \App\Models\Inventory::create($inventory);
        }

        // Update stock otomatis dari produk
        foreach ($inventories as $inv) {
            $stock = DB::table('products')
                ->where('inventory_id', $inv['id'])
                ->sum('stock');
            DB::table('inventories')
                ->where('id', $inv['id'])
                ->update(['stock' => $stock]);
        }
    }
}
