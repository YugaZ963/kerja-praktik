<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryTableSeeder extends Seeder
{
    public function run()
    {
        $inventories = [
            [
                'id'              => 1,
                'code'            => 'INV-SD-001',
                'name'            => 'Seragam SD Pendek',
                'category'        => 'Kemeja Sekolah',
                'stock'           => 0,        // <-- sudah ada
                'min_stock'       => 50,
                'purchase_price'  => 35000,
                'selling_price'   => 40000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => json_encode(['8', '9', '10', '11', '12', '13', '14', '15', '16']),
                'location'        => 'Rak A-1',
                'description'     => 'Kemeja seragam sekolah lengan pendek putih premium',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => json_encode([
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 180, 'notes' => 'Stok awal']
                ]),
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
                'purchase_price'  => 38000,
                'selling_price'   => 43000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => json_encode(['8', '9', '10', '11', '12', '13', '14', '15', '16']),
                'location'        => 'Rak A-2',
                'description'     => 'Kemeja seragam sekolah lengan panjang putih premium',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => json_encode([
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 180, 'notes' => 'Stok awal']
                ]),
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
                'code'            => 'INV-KOKO-001',
                'name'            => 'Kemeja Batik Koko Hijau',
                'category'        => 'Kemeja Batik Koko',
                'stock'           => 0,        // <-- tambahkan
                'min_stock'       => 50,
                'purchase_price'  => 52000,
                'selling_price'   => 56000,
                'supplier'        => 'CV Batik Nusantara',
                'sizes_available' => json_encode(['8', '9', '10', '11', '12', '13', '14', '15', '16']),
                'location'        => 'Rak C-2',
                'description'     => 'Kemeja koko hijau batik premium',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => json_encode([
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 180, 'notes' => 'Stok awal']
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
                'purchase_price'  => 62000,
                'selling_price'   => 66000,
                'supplier'        => 'PD Padang Garment',
                'sizes_available' => json_encode(['14', '15', '16', 'S', 'M', 'L', 'XL', 'L3', 'L4', 'L5', 'L6']),
                'location'        => 'Rak D-1',
                'description'     => 'Kemeja padang motif khas untuk seragam sekolah',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => json_encode([
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 110, 'notes' => 'Stok awal']
                ]),
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
                'purchase_price'  => 44000,
                'selling_price'   => 48000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => json_encode(['3', '4', '5', '6', '7', '8', '9', '10', '11', '12']),
                'location'        => 'Rak E-1',
                'description'     => 'Rok panjang seragam SD warna biru dongker',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => json_encode([
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 100, 'notes' => 'Stok awal']
                ]),
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
                'purchase_price'  => 43000,
                'selling_price'   => 47000,
                'supplier'        => 'PT Seragam Jaya',
                'sizes_available' => json_encode(['3', '4', '5', '6', '7', '8', '9', '10', '11', '12']),
                'location'        => 'Rak F-1',
                'description'     => 'Celana panjang seragam SD warna abu-abu',
                'last_restock'    => now()->toDateString(),
                'stock_history'   => json_encode([
                    ['date' => now()->toDateString(), 'type' => 'in', 'quantity' => 100, 'notes' => 'Stok awal']
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ];

        DB::table('inventories')->delete();
        DB::table('inventories')->insert($inventories);

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
