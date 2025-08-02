<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;   // <--- Tambahkan ini

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        // Data mapping: inventory_id => [sizes...]
        $map = [
            1 => ['8', '9', '10', '11', '12', '13', '14', '15', '16'],   // INV-SD-001
            2 => ['8', '9', '10', '11', '12', '13', '14', '15', '16'],   // INV-SD-002
            3 => ['8', '9', '10', '11', '12', '13', '14', '15', '16'],   // INV-BATIK-001
            4 => ['8', '9', '10', '11', '12', '13', '14', '15', '16'],   // INV-KOKO-001
            5 => ['14', '15', '16', 'S', 'M', 'L', 'XL', 'L3', 'L4', 'L5', 'L6'], // INV-PADANG-001
            6 => ['3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],   // INV-ROK-001
            7 => ['3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],   // INV-CLN-001
        ];

        DB::table('products')->delete();

        $products = [];
        $id = 1;

        foreach ($map as $inventoryId => $sizes) {
            $inv = DB::table('inventories')->find($inventoryId);
            foreach ($sizes as $size) {
                $products[] = [
                    'slug'          => Str::slug($inv->name . '-' . $size),
                    'name'          => $inv->name . ' No. ' . $size,
                    'price'         => $inv->selling_price,
                    'description'   => $inv->description . ' ukuran ' . $size,
                    'stock'         => 20,
                    'size'          => $size,
                    'category'      => $inv->category,
                    'inventory_id'  => $inventoryId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
                $id++;
            }
        }

        DB::table('products')->insert($products);
    }
}
