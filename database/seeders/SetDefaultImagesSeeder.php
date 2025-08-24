<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class SetDefaultImagesSeeder extends Seeder
{
    public function run()
    {
        // Set gambar default untuk semua produk yang tidak memiliki gambar
        $productsWithoutImage = Product::whereNull('image')
            ->orWhere('image', '')
            ->get();
        
        $updatedCount = 0;
        
        foreach ($productsWithoutImage as $product) {
            $defaultImage = 'kemeja-sd-pdk.png';
            
            // Set default berdasarkan kategori atau nama
            if (str_contains(strtolower($product->category), 'celana')) {
                $defaultImage = 'celana-pj-sd.png';
            } elseif (str_contains(strtolower($product->category), 'rok')) {
                $defaultImage = 'rok-pj-sd-putih.png';
            }
            
            $product->update(['image' => $defaultImage]);
            $this->command->info("Set default image: {$product->name} -> {$defaultImage}");
            $updatedCount++;
        }
        
        $this->command->info("Total produk yang diperbarui: {$updatedCount}");
    }
}