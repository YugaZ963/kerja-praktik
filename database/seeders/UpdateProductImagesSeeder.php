<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class UpdateProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping nama produk dengan nama file gambar yang tersedia
        $imageMapping = [
            // Kemeja SD
            'kemeja-sd-pdk.png' => ['Kemeja Pendek SD', 'Seragam SD Pendek', 'SD Pendek', 'PDK SD'],
            'kemeja-sd-pj.png' => ['Kemeja Panjang SD', 'Seragam SD Panjang', 'SD Panjang', 'PJ SD', 'Kemeja-pj-sd'],
            
            // Kemeja SMP
            'kemeja-smp-pdk.png' => ['Kemeja Pendek SMP', 'SMP Pendek', 'PDK SMP'],
            'kemeja-smp-pj.png' => ['Kemeja Panjang SMP', 'SMP Panjang', 'PJ SMP'],
            
            // Kemeja SMA
            'kemeja-sma-pdk.png' => ['Kemeja Pendek SMA', 'SMA Pendek', 'PDK SMA'],
            'kemeja-sma-pj.png' => ['Kemeja Panjang SMA', 'SMA Panjang', 'PJ SMA'],
            
            // Celana SD
            'celana-pj-sd.png' => ['Celana Panjang SD', 'Celana SD'],
            'celana-sd-coklat.png' => ['Celana SD Coklat'],
            'celana-sd-hijau.png' => ['Celana SD Hijau'],
            'celana-sd-hitam.png' => ['Celana SD Hitam'],
            'celana-sd-putih.png' => ['Celana SD Putih'],
            
            // Celana SMP
            'celana-pj-smp.png' => ['Celana Panjang SMP', 'Celana SMP'],
            'celana-pj-smp-biru-2.png' => ['Celana SMP Biru'],
            'celana-pj-smp-sma-coklat.png' => ['Celana SMP Coklat', 'Celana SMA Coklat'],
            'celana-pj-smp-sma-hijau.png' => ['Celana SMP Hijau', 'Celana SMA Hijau'],
            'celana-pj-smp-sma-hitam.png' => ['Celana SMP Hitam', 'Celana SMA Hitam'],
            'celana-pj-smp-sma-putih.png' => ['Celana SMP Putih', 'Celana SMA Putih'],
            
            // Celana SMA
            'celana-pj-sma.png' => ['Celana Panjang SMA', 'Celana SMA'],
            
            // Celana PDL
            'celana-pdl-coklat.png' => ['Celana PDL Coklat', 'PDL Coklat'],
            'celana-pdl-hitam.png' => ['Celana PDL Hitam', 'PDL Hitam', 'PDL'],
            
            // Rok SD
            'rok-pj-sd-coklat.png' => ['Rok SD Coklat'],
            'rok-pj-sd-hijau.png' => ['Rok SD Hijau'],
            'rok-pj-sd-hitam.png' => ['Rok SD Hitam'],
            'rok-pj-sd-merah.png' => ['Rok SD Merah'],
            'rok-pj-sd-putih.png' => ['Rok SD Putih', 'Rok SD', 'Rok'],
            
            // Pramuka
            'kemeja-pramuka-siaga-pdk-2.png' => ['Siaga', 'Pramuka Siaga', 'Kemeja Siaga'],
            'kemeja-pramuka-siaga-pdk(beta).png' => ['Siaga Beta'],
            'Kemeja-PJ-Pramuka.png' => ['Kemeja Pramuka', 'Penggalang', 'Penegak', 'Pramuka Panjang'],
            'Kerudung-pramuka.png' => ['Kerudung Pramuka', 'Kerudung'],
            
            // Sabuk
            'Sabuk.png' => ['Sabuk Umum'],
            'sabuk-sd.png' => ['Sabuk SD'],
            'sabuk-smp.png' => ['Sabuk SMP'],
            'sabuk-sma.png' => ['Sabuk SMA'],
            
            // Topi
            'Topi.png' => ['Topi Umum'],
            'topi-sd.png' => ['Topi SD'],
            'topi-smp.png' => ['Topi SMP'],
            'topi-sma.png' => ['Topi SMA'],
        ];
        
        // Path ke direktori gambar
        $imagePath = public_path('images');
        
        // Cek apakah direktori gambar ada
        if (!File::exists($imagePath)) {
            $this->command->error('Direktori public/images tidak ditemukan!');
            return;
        }
        
        // Ambil semua file gambar yang ada
        $availableImages = File::files($imagePath);
        $availableImageNames = array_map(function($file) {
            return $file->getFilename();
        }, $availableImages);
        
        $this->command->info('File gambar yang tersedia:');
        foreach ($availableImageNames as $imageName) {
            $this->command->line('- ' . $imageName);
        }
        
        $updatedCount = 0;
        
        // Loop melalui mapping dan update produk
        foreach ($imageMapping as $imageName => $productKeywords) {
            // Cek apakah file gambar ada
            if (!in_array($imageName, $availableImageNames)) {
                $this->command->warn("Gambar {$imageName} tidak ditemukan, dilewati.");
                continue;
            }
            
            // Cari produk berdasarkan kata kunci
            foreach ($productKeywords as $keyword) {
                $products = Product::where('name', 'LIKE', '%' . $keyword . '%')
                                 ->get();
                
                foreach ($products as $product) {
                    $product->update(['image' => $imageName]);
                    $updatedCount++;
                    $this->command->info("Updated: {$product->name} -> {$imageName}");
                }
            }
        }
        
        // Set default image untuk produk yang tidak memiliki gambar atau masih kosong
        $productsWithoutImage = Product::where(function($query) {
            $query->whereNull('image')->orWhere('image', '');
        })->get();
        
        foreach ($productsWithoutImage as $product) {
            $defaultImage = 'kemeja-sd-pdk.png'; // Default image
            
            // Set default berdasarkan kategori
            if (str_contains(strtolower($product->category), 'celana')) {
                $defaultImage = 'celana-pj-sd.png';
            } elseif (str_contains(strtolower($product->category), 'rok')) {
                $defaultImage = 'rok-pj-sd-putih.png';
            } elseif (str_contains(strtolower($product->name), 'batik') || str_contains(strtolower($product->name), 'koko')) {
                $defaultImage = 'kemeja-sd-pdk.png';
            } elseif (str_contains(strtolower($product->name), 'padang')) {
                $defaultImage = 'kemeja-sd-pdk.png';
            }
            
            if (in_array($defaultImage, $availableImageNames)) {
                 $product->update(['image' => $defaultImage]);
                 $this->command->info("Default image set: {$product->name} -> {$defaultImage}");
                 $updatedCount++;
             }
        }
        
        $this->command->info("\nTotal produk yang diperbarui: {$updatedCount}");
    }
    
    /**
     * Get default image based on product category
     */
    private function getDefaultImageByCategory($category)
    {
        $categoryMapping = [
            'Kemeja' => 'kemeja-sd-pdk.png',
            'Kemeja Batik' => 'kemeja-sd-pdk.png',
            'Kemeja Formal' => 'kemeja-sd-pdk.png',
            'Kemeja Premium' => 'kemeja-sd-pdk.png',
            'Pramuka' => 'kemeja-pramuka-sd.png',
            'Rok' => 'rok-pramuka-sd.png',
            'Celana' => 'celana-sd-pjg.png',
            'Celana PDL' => 'celana-pdl-sd.png',
        ];
        
        return $categoryMapping[$category] ?? 'kemeja-sd-pdk.png';
    }
}