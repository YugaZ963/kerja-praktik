<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ComprehensiveProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping gambar berdasarkan kategori dan nama produk
        $imageMapping = [
            // Kemeja
            'kemeja-sd-pdk.png' => ['Kemeja Pendek SD', 'KEMEJA PDK SD'],
            'kemeja-smp-pdk.png' => ['Kemeja Pendek SMP'],
            'kemeja-sma-pdk.png' => ['Kemeja Pendek SMA'],
            'kemeja-sd-pj.png' => ['Kemeja Panjang SD', 'KEMEJA PJNG SD'],
            'kemeja-smp-pj.png' => ['Kemeja Panjang SMP'],
            'kemeja-sma-pj.png' => ['Kemeja Panjang SMA'],
            
            // Kemeja Batik
            'kemeja-sd-pj.png' => ['KEMEJA BATIK PANJANG'],
            'kemeja-smp-pj.png' => ['KEMEJA BATIK KOKO HIJAU'],
            
            // Kemeja Formal
            'kemeja-sma-pj.png' => ['KEMEJA PADANG', 'RAFILLO PENDEK', 'RAFILLO PANJANG'],
            
            // Pramuka
            'kemeja-pramuka-siaga-pdk-2.png' => ['K SIAGA PENDEK'],
            'kemeja-pramuka-siaga-pdk(beta).png' => ['K SIAGA PJNG'],
            'Kemeja-PJ-Pramuka.png' => ['K PRAMUKA PENDEK', 'K PRAMUKA PENGG SD/SMP', 'K PRAM PENGG PJNG MANSET', 'K PEMB PJNG'],
            
            // Rok
            'rok-pj-sd-putih.png' => ['ROK PANJANG SD', 'ROK ADU MANIS PANJANG SD'],
            'rok-pj-sd-coklat.png' => ['ROK PANJANG SPAN', 'ROK REMPEL BAPING / FULL', 'ROK PRAMUKA ASN'],
            
            // Celana
            'celana-pj-sd.png' => ['CLN PANJANG SD'],
            'celana-pdl-coklat.png' => ['CELANA PDL SD'],
            'celana-pj-smp.png' => ['CLN PANJANG SMP SMA'],
            'celana-pdl-hitam.png' => ['CLN PDL SMP SMA', 'CELANA PRAMUKA ASN'],
            
            // Topi
            'topi-sd.png' => ['TOPI SD'],
            'topi-smp.png' => ['TOPI SMP'],
            'topi-sma.png' => ['TOPI SMA'],
            'Topi.png' => ['TOPI PRAMUKA'],
            
            // Kerudung
            'Kerudung-pramuka.png' => ['KERUDUNG SD', 'KERUDUNG SMP', 'KERUDUNG SMA', 'KERUDUNG PRAMUKA'],
            
            // Sabuk
            'sabuk-sd.png' => ['SABUK SD'],
            'sabuk-smp.png' => ['SABUK SMP'],
            'sabuk-sma.png' => ['SABUK SMA'],
            'Sabuk.png' => ['SABUK PRAMUKA'],
        ];

        // Function untuk mendapatkan gambar berdasarkan nama produk
        $getImage = function($productName) use ($imageMapping) {
            foreach ($imageMapping as $image => $keywords) {
                foreach ($keywords as $keyword) {
                    if (stripos($productName, $keyword) !== false) {
                        return 'images/' . $image;
                    }
                }
            }
            return 'images/ravazka.jpg'; // Default image
        };

        // Hapus produk yang ada
        Product::truncate();

        // KEMEJA PDK SD SMP SMA
        $kemejaPdkSizes = [
            '8' => 36000, '9' => 37000, '10' => 38000, '11' => 39000,
            '12' => 40000, '13' => 41000, '14' => 43000, '15' => 44000, '16' => 45000
        ];
        
        foreach ($kemejaPdkSizes as $size => $price) {
            Product::create([
                'name' => 'KEMEJA PDK SD SMP SMA No. ' . $size,
                'slug' => 'kemeja-pdk-sd-smp-sma-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Kemeja pendek untuk siswa SD, SMP, dan SMA ukuran ' . $size,
                'stock' => 50,
                'size' => $size,
                'category' => 'Kemeja Sekolah',
                'image' => $getImage('KEMEJA PDK SD'),
                'inventory_id' => 1
            ]);
        }

        // KEMEJA PJNG SD SMP SMA
        $kemejaPjngSizes = [
            '8' => 41000, '9' => 42000, '10' => 43000, '11' => 44000,
            '12' => 45000, '13' => 46000, '14' => 48000, '15' => 49000, '16' => 50000
        ];
        
        foreach ($kemejaPjngSizes as $size => $price) {
            Product::create([
                'name' => 'KEMEJA PJNG SD SMP SMA No. ' . $size,
                'slug' => 'kemeja-pjng-sd-smp-sma-' . $size,
                'price' => $price,
                'weight' => 250,
                'description' => 'Kemeja panjang untuk siswa SD, SMP, dan SMA ukuran ' . $size,
                'stock' => 40,
                'size' => $size,
                'category' => 'Kemeja Sekolah',
                'image' => $getImage('KEMEJA PJNG SD'),
                'inventory_id' => 1
            ]);
        }

        // KEMEJA BATIK PANJANG
        $kemejaBatikSizes = [
            '8' => 46000, '9' => 47000, '10' => 48000, '11' => 49000,
            '12' => 50000, '13' => 51000, '14' => 53000, '15' => 54000, '16' => 55000
        ];
        
        foreach ($kemejaBatikSizes as $size => $price) {
            Product::create([
                'name' => 'KEMEJA BATIK PANJANG No. ' . $size,
                'slug' => 'kemeja-batik-panjang-' . $size,
                'price' => $price,
                'weight' => 280,
                'description' => 'Kemeja batik panjang ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Kemeja Batik',
                'image' => $getImage('KEMEJA BATIK PANJANG'),
                'inventory_id' => 2
            ]);
        }

        // KEMEJA BATIK KOKO HIJAU
        $kemejaKokoSizes = [
            '8' => 56000, '9' => 57000, '10' => 58000, '11' => 59000,
            '12' => 60000, '13' => 61000, '14' => 63000, '15' => 64000, '16' => 65000
        ];
        
        foreach ($kemejaKokoSizes as $size => $price) {
            Product::create([
                'name' => 'KEMEJA BATIK KOKO HIJAU No. ' . $size,
                'slug' => 'kemeja-batik-koko-hijau-' . $size,
                'price' => $price,
                'weight' => 280,
                'description' => 'Kemeja batik koko hijau ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Kemeja Batik',
                'image' => $getImage('KEMEJA BATIK KOKO HIJAU'),
                'inventory_id' => 2
            ]);
        }

        // KEMEJA PADANG
        $kemejaPadangSizes = [
            '14' => 66000, '15' => 68000, '16' => 70000, 'S' => 75000,
            'M' => 75000, 'L' => 75000, 'XL' => 77000, 'L3' => 79000,
            'L4' => 81000, 'L5' => 83000, 'L6' => 85000
        ];
        
        foreach ($kemejaPadangSizes as $size => $price) {
            Product::create([
                'name' => 'KEMEJA PADANG ' . $size,
                'slug' => 'kemeja-padang-' . strtolower($size),
                'price' => $price,
                'weight' => 300,
                'description' => 'Kemeja Padang ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Kemeja Formal',
                'image' => $getImage('KEMEJA PADANG'),
                'inventory_id' => 3
            ]);
        }

        // RAFILLO PENDEK
        $rafilloPendekSizes = [
            'S' => 88000, 'M' => 91000, 'L' => 94000, 'XL' => 97000,
            'L3' => 100000, 'L4' => 103000, 'L5' => 106000, 'L6' => 111000
        ];
        
        foreach ($rafilloPendekSizes as $size => $price) {
            Product::create([
                'name' => 'RAFILLO PENDEK ' . $size,
                'slug' => 'rafillo-pendek-' . strtolower($size),
                'price' => $price,
                'weight' => 220,
                'description' => 'Kemeja Rafillo pendek ukuran ' . $size,
                'stock' => 15,
                'size' => $size,
                'category' => 'Kemeja Premium',
                'image' => $getImage('RAFILLO PENDEK'),
                'inventory_id' => 4
            ]);
        }

        // RAFILLO PANJANG
        $rafilloPanjangSizes = [
            'S' => 93000, 'M' => 96000, 'L' => 99000, 'XL' => 102000,
            'L3' => 105000, 'L4' => 108000, 'L5' => 113000, 'L6' => 118000
        ];
        
        foreach ($rafilloPanjangSizes as $size => $price) {
            Product::create([
                'name' => 'RAFILLO PANJANG ' . $size,
                'slug' => 'rafillo-panjang-' . strtolower($size),
                'price' => $price,
                'weight' => 250,
                'description' => 'Kemeja Rafillo panjang ukuran ' . $size,
                'stock' => 15,
                'size' => $size,
                'category' => 'Kemeja Premium',
                'image' => $getImage('RAFILLO PANJANG'),
                'inventory_id' => 4
            ]);
        }

        // K PRAMUKA PENDEK
        $kPramukaPendekSizes = [
            '8' => 47000, '9' => 48000, '10' => 49000, '11' => 50000,
            '12' => 51000, '13' => 52000, '14' => 54000, '15' => 55000, '16' => 56000
        ];
        
        foreach ($kPramukaPendekSizes as $size => $price) {
            Product::create([
                'name' => 'K PRAMUKA PENDEK No. ' . $size,
                'slug' => 'k-pramuka-pendek-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Kemeja pramuka pendek ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('K PRAMUKA PENDEK'),
                'inventory_id' => 11
            ]);
        }

        // K PRAMUKA PENGG SD/SMP
        $kPramukaPenggSizes = [
            '13' => 57000, '14' => 59000, '15' => 60000, '16' => 61000,
            'S' => 65000, 'M' => 67000, 'L' => 69000, 'XL' => 73000
        ];
        
        foreach ($kPramukaPenggSizes as $size => $price) {
            Product::create([
                'name' => 'K PRAMUKA PENGG SD/SMP ' . $size,
                'slug' => 'k-pramuka-pengg-sd-smp-' . strtolower($size),
                'price' => $price,
                'weight' => 220,
                'description' => 'Kemeja pramuka penggalang SD/SMP ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('K PRAMUKA PENGG SD/SMP'),
                'inventory_id' => 11
            ]);
        }

        // K PRAM PENGG PJNG MANSET
        $kPramPenggPjngSizes = [
            '14' => 64000, '15' => 66000, '16' => 68000, 'S' => 72000,
            'M' => 74000, 'L' => 76000, 'XL' => 78000, 'L3' => 80000
        ];
        
        foreach ($kPramPenggPjngSizes as $size => $price) {
            Product::create([
                'name' => 'K PRAM PENGG PJNG MANSET ' . $size,
                'slug' => 'k-pram-pengg-pjng-manset-' . strtolower($size),
                'price' => $price,
                'weight' => 250,
                'description' => 'Kemeja pramuka penggalang panjang manset ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('K PRAM PENGG PJNG MANSET'),
                'inventory_id' => 11
            ]);
        }

        // K SIAGA PENDEK
        $kSiagaPendekSizes = [
            '8' => 53000, '9' => 54000, '10' => 55000, '11' => 56000,
            '12' => 57000, '13' => 58000, '14' => 60000, '15' => 61000, '16' => 62000
        ];
        
        foreach ($kSiagaPendekSizes as $size => $price) {
            Product::create([
                'name' => 'K SIAGA PENDEK No. ' . $size,
                'slug' => 'k-siaga-pendek-' . $size,
                'price' => $price,
                'weight' => 180,
                'description' => 'Kemeja pramuka siaga pendek ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('K SIAGA PENDEK'),
                'inventory_id' => 11
            ]);
        }

        // K SIAGA PJNG
        $kSiagaPjngSizes = [
            '8' => 58000, '9' => 59000, '10' => 60000, '11' => 61000,
            '12' => 62000, '13' => 63000, '14' => 65000, '15' => 66000, '16' => 67000
        ];
        
        foreach ($kSiagaPjngSizes as $size => $price) {
            Product::create([
                'name' => 'K SIAGA PJNG No. ' . $size,
                'slug' => 'k-siaga-pjng-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Kemeja pramuka siaga panjang ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('K SIAGA PJNG'),
                'inventory_id' => 11
            ]);
        }

        // K PEMB PJNG
        $kPembPjngSizes = [
            '16' => 67000, 'S' => 69000, 'M' => 71000, 'L' => 73000,
            'XL' => 75000, 'L3' => 77000, 'L4' => 79000, 'L5' => 81000, 'L6' => 83000
        ];
        
        foreach ($kPembPjngSizes as $size => $price) {
            Product::create([
                'name' => 'K PEMB PJNG ' . $size,
                'slug' => 'k-pemb-pjng-' . strtolower($size),
                'price' => $price,
                'weight' => 250,
                'description' => 'Kemeja pramuka pembina panjang ukuran ' . $size,
                'stock' => 15,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('K PEMB PJNG'),
                'inventory_id' => 11
            ]);
        }

        // ROK PANJANG SD
        $rokPanjangSdSizes = [
            '3' => 48000, '4' => 49000, '5' => 50000, '6' => 51000,
            '7' => 52000, '8' => 53000, '9' => 54000, '10' => 56000,
            '11' => 58000, '12' => 61000
        ];
        
        foreach ($rokPanjangSdSizes as $size => $price) {
            Product::create([
                'name' => 'ROK PANJANG SD No. ' . $size,
                'slug' => 'rok-panjang-sd-' . $size,
                'price' => $price,
                'weight' => 150,
                'description' => 'Rok panjang untuk siswa SD ukuran ' . $size,
                'stock' => 35,
                'size' => $size,
                'category' => 'Rok',
                'image' => $getImage('ROK PANJANG SD'),
                'inventory_id' => 6
            ]);
        }

        // ROK ADU MANIS PANJANG SD
        $rokAduManisSizes = [
            '3' => 96000, '4' => 98500, '5' => 101000, '6' => 103500,
            '7' => 106000, '8' => 108500, '9' => 113500, '10' => 118500,
            '11' => 123500, '12' => 128500
        ];
        
        foreach ($rokAduManisSizes as $size => $price) {
            Product::create([
                'name' => 'ROK ADU MANIS PANJANG SD No. ' . $size,
                'slug' => 'rok-adu-manis-panjang-sd-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Rok adu manis panjang untuk siswa SD ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Rok',
                'image' => $getImage('ROK ADU MANIS PANJANG SD'),
                'inventory_id' => 6
            ]);
        }

        // ROK PANJANG SPAN
        $rokPanjangSpanSizes = [
            'S' => 66000, 'M' => 66000, 'L' => 66000, 'XL' => 69000,
            'L3' => 72000, 'L4' => 75000, 'L5' => 78000, 'L6' => 81000
        ];
        
        foreach ($rokPanjangSpanSizes as $size => $price) {
            Product::create([
                'name' => 'ROK PANJANG SPAN ' . $size,
                'slug' => 'rok-panjang-span-' . strtolower($size),
                'price' => $price,
                'weight' => 180,
                'description' => 'Rok panjang span ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Rok',
                'image' => $getImage('ROK PANJANG SPAN'),
                'inventory_id' => 6
            ]);
        }

        // ROK REMPEL BAPING / FULL
        $rokRempelSizes = [
            'S' => 76000, 'M' => 76000, 'L' => 76000, 'XL' => 79000,
            'L3' => 82000, 'L4' => 85000, 'L5' => 88000, 'L6' => 91000
        ];
        
        foreach ($rokRempelSizes as $size => $price) {
            Product::create([
                'name' => 'ROK REMPEL BAPING / FULL ' . $size,
                'slug' => 'rok-rempel-baping-full-' . strtolower($size),
                'price' => $price,
                'weight' => 200,
                'description' => 'Rok rempel baping / full ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Rok',
                'image' => $getImage('ROK REMPEL BAPING / FULL'),
                'inventory_id' => 6
            ]);
        }

        // ROK PRAMUKA ASN
        $rokPramukaAsnSizes = [
            'S' => 81000, 'M' => 84000, 'L' => 87000, 'XL' => 90000,
            'L3' => 93000, 'L4' => 96000, 'L5' => 99000, 'L6' => 102000
        ];
        
        foreach ($rokPramukaAsnSizes as $size => $price) {
            Product::create([
                'name' => 'ROK PRAMUKA ASN ' . $size,
                'slug' => 'rok-pramuka-asn-' . strtolower($size),
                'price' => $price,
                'weight' => 180,
                'description' => 'Rok pramuka ASN ukuran ' . $size,
                'stock' => 15,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('ROK PRAMUKA ASN'),
                'inventory_id' => 11
            ]);
        }

        // CLN PANJANG SD
        $clnPanjangSdSizes = [
            '3' => 47000, '4' => 48000, '5' => 49000, '6' => 50000,
            '7' => 51000, '8' => 52000, '9' => 53000, '10' => 55000,
            '11' => 57000, '12' => 59000
        ];
        
        foreach ($clnPanjangSdSizes as $size => $price) {
            Product::create([
                'name' => 'CLN PANJANG SD No. ' . $size,
                'slug' => 'cln-panjang-sd-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Celana panjang untuk siswa SD ukuran ' . $size,
                'stock' => 40,
                'size' => $size,
                'category' => 'Celana',
                'image' => $getImage('CLN PANJANG SD'),
                'inventory_id' => 7
            ]);
        }

        // CELANA PDL SD
        $celanaPdlSdSizes = [
            '5' => 65000, '6' => 67000, '7' => 69000, '8' => 71000,
            '9' => 73000, '10' => 75000, '11' => 77000, '12' => 79000,
            '13' => 81000, '14' => 83000
        ];
        
        foreach ($celanaPdlSdSizes as $size => $price) {
            Product::create([
                'name' => 'CELANA PDL SD No. ' . $size,
                'slug' => 'celana-pdl-sd-' . $size,
                'price' => $price,
                'weight' => 250,
                'description' => 'Celana PDL untuk siswa SD ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Celana',
                'image' => $getImage('CELANA PDL SD'),
                'inventory_id' => 7
            ]);
        }

        // CLN PANJANG SMP SMA
        $clnPanjangSmpSmaSizes = [
            '25' => 58000, '26' => 59000, '27' => 60000, '28' => 61000,
            '29' => 62000, '30' => 63000, '31' => 64000, '32' => 65000,
            '33' => 66000, '34' => 68000
        ];
        
        foreach ($clnPanjangSmpSmaSizes as $size => $price) {
            Product::create([
                'name' => 'CLN PANJANG SMP SMA No. ' . $size,
                'slug' => 'cln-panjang-smp-sma-' . $size,
                'price' => $price,
                'weight' => 220,
                'description' => 'Celana panjang untuk siswa SMP SMA ukuran ' . $size,
                'stock' => 35,
                'size' => $size,
                'category' => 'Celana',
                'image' => $getImage('CLN PANJANG SMP SMA'),
                'inventory_id' => 7
            ]);
        }

        // CLN PDL SMP SMA
        $clnPdlSmpSmaSizes = [
            '25' => 77000, '26' => 79000, '27' => 81000, '28' => 83000,
            '29' => 85000, '30' => 87000, '31' => 89000, '32' => 91000,
            '33' => 93000, '34' => 95000
        ];
        
        foreach ($clnPdlSmpSmaSizes as $size => $price) {
            Product::create([
                'name' => 'CLN PDL SMP SMA No. ' . $size,
                'slug' => 'cln-pdl-smp-sma-' . $size,
                'price' => $price,
                'weight' => 280,
                'description' => 'Celana PDL untuk siswa SMP SMA ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Celana',
                'image' => $getImage('CLN PDL SMP SMA'),
                'inventory_id' => 7
            ]);
        }

        // CELANA PRAMUKA ASN
        $celanaPramukaAsnSizes = [
            '30' => 87000, '31' => 89000, '32' => 91000, '33' => 93000,
            '34' => 95000, '35' => 97000, '36' => 99000, '37' => 101000,
            '38' => 103000, '39' => 105000
        ];
        
        foreach ($celanaPramukaAsnSizes as $size => $price) {
            Product::create([
                'name' => 'CELANA PRAMUKA ASN No. ' . $size,
                'slug' => 'celana-pramuka-asn-' . $size,
                'price' => $price,
                'weight' => 300,
                'description' => 'Celana pramuka ASN ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('CELANA PRAMUKA ASN'),
                'inventory_id' => 11
            ]);
        }

        // TOPI SD
        $topiSizes = ['S', 'M', 'L', 'XL'];
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'TOPI SD ' . $size,
                'slug' => 'topi-sd-' . strtolower($size),
                'price' => 10000,
                'weight' => 50,
                'description' => 'Topi untuk siswa SD ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('TOPI SD'),
                'inventory_id' => 8
            ]);
        }

        // TOPI SMP
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'TOPI SMP ' . $size,
                'slug' => 'topi-smp-' . strtolower($size),
                'price' => 10000,
                'weight' => 50,
                'description' => 'Topi untuk siswa SMP ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('TOPI SMP'),
                'inventory_id' => 8
            ]);
        }

        // TOPI SMA
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'TOPI SMA ' . $size,
                'slug' => 'topi-sma-' . strtolower($size),
                'price' => 10000,
                'weight' => 50,
                'description' => 'Topi untuk siswa SMA ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('TOPI SMA'),
                'inventory_id' => 8
            ]);
        }

        // TOPI PRAMUKA
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'TOPI PRAMUKA ' . $size,
                'slug' => 'topi-pramuka-' . strtolower($size),
                'price' => 10000,
                'weight' => 50,
                'description' => 'Topi pramuka ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('TOPI PRAMUKA'),
                'inventory_id' => 11
            ]);
        }

        // KERUDUNG SD
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'KERUDUNG SD ' . $size,
                'slug' => 'kerudung-sd-' . strtolower($size),
                'price' => 10000,
                'weight' => 30,
                'description' => 'Kerudung untuk siswa SD ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('KERUDUNG SD'),
                'inventory_id' => 9
            ]);
        }

        // KERUDUNG SMP
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'KERUDUNG SMP ' . $size,
                'slug' => 'kerudung-smp-' . strtolower($size),
                'price' => 10000,
                'weight' => 30,
                'description' => 'Kerudung untuk siswa SMP ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('KERUDUNG SMP'),
                'inventory_id' => 9
            ]);
        }

        // KERUDUNG SMA
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'KERUDUNG SMA ' . $size,
                'slug' => 'kerudung-sma-' . strtolower($size),
                'price' => 10000,
                'weight' => 30,
                'description' => 'Kerudung untuk siswa SMA ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('KERUDUNG SMA'),
                'inventory_id' => 9
            ]);
        }

        // KERUDUNG PRAMUKA
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'KERUDUNG PRAMUKA ' . $size,
                'slug' => 'kerudung-pramuka-' . strtolower($size),
                'price' => 10000,
                'weight' => 30,
                'description' => 'Kerudung pramuka ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('KERUDUNG PRAMUKA'),
                'inventory_id' => 11
            ]);
        }

        // SABUK SD
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'SABUK SD ' . $size,
                'slug' => 'sabuk-sd-' . strtolower($size),
                'price' => 10000,
                'weight' => 100,
                'description' => 'Sabuk untuk siswa SD ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('SABUK SD'),
                'inventory_id' => 10
            ]);
        }

        // SABUK SMP
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'SABUK SMP ' . $size,
                'slug' => 'sabuk-smp-' . strtolower($size),
                'price' => 10000,
                'weight' => 100,
                'description' => 'Sabuk untuk siswa SMP ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('SABUK SMP'),
                'inventory_id' => 10
            ]);
        }

        // SABUK SMA
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'SABUK SMA ' . $size,
                'slug' => 'sabuk-sma-' . strtolower($size),
                'price' => 10000,
                'weight' => 100,
                'description' => 'Sabuk untuk siswa SMA ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Aksesoris',
                'image' => $getImage('SABUK SMA'),
                'inventory_id' => 10
            ]);
        }

        // SABUK PRAMUKA
        foreach ($topiSizes as $size) {
            Product::create([
                'name' => 'SABUK PRAMUKA ' . $size,
                'slug' => 'sabuk-pramuka-' . strtolower($size),
                'price' => 10000,
                'weight' => 100,
                'description' => 'Sabuk pramuka ukuran ' . $size,
                'stock' => 100,
                'size' => $size,
                'category' => 'Pramuka',
                'image' => $getImage('SABUK PRAMUKA'),
                'inventory_id' => 11
            ]);
        }

        $this->command->info('Comprehensive product seeder completed successfully!');
        $this->command->info('Total products created: ' . Product::count());
    }
}