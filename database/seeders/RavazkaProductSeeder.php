<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class RavazkaProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // KEMEJA PDK SD SMP SMA
        $kemejaPdkSizes = [
            '8' => 36000, '9' => 37000, '10' => 38000, '11' => 39000,
            '12' => 40000, '13' => 41000, '14' => 43000, '15' => 44000, '16' => 45000
        ];
        
        foreach ($kemejaPdkSizes as $size => $price) {
            Product::create([
                'name' => 'Kemeja Pendek SD SMP SMA',
                'slug' => 'kemeja-pendek-sd-smp-sma-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Kemeja pendek untuk siswa SD, SMP, dan SMA ukuran ' . $size,
                'stock' => 50,
                'size' => $size,
                'category' => 'Kemeja',
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
                'name' => 'Kemeja Panjang SD SMP SMA',
                'slug' => 'kemeja-panjang-sd-smp-sma-' . $size,
                'price' => $price,
                'weight' => 250,
                'description' => 'Kemeja panjang untuk siswa SD, SMP, dan SMA ukuran ' . $size,
                'stock' => 40,
                'size' => $size,
                'category' => 'Kemeja',
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
                'name' => 'Kemeja Batik Panjang',
                'slug' => 'kemeja-batik-panjang-' . $size,
                'price' => $price,
                'weight' => 280,
                'description' => 'Kemeja batik panjang ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Kemeja Batik',
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
                'name' => 'Kemeja Batik Koko Hijau',
                'slug' => 'kemeja-batik-koko-hijau-' . $size,
                'price' => $price,
                'weight' => 280,
                'description' => 'Kemeja batik koko hijau ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Kemeja Batik',
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
                'name' => 'Kemeja Padang',
                'slug' => 'kemeja-padang-' . strtolower($size),
                'price' => $price,
                'weight' => 300,
                'description' => 'Kemeja Padang ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Kemeja Formal',
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
                'name' => 'Rafillo Pendek',
                'slug' => 'rafillo-pendek-' . strtolower($size),
                'price' => $price,
                'weight' => 220,
                'description' => 'Kemeja Rafillo pendek ukuran ' . $size,
                'stock' => 15,
                'size' => $size,
                'category' => 'Kemeja Premium',
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
                'name' => 'Rafillo Panjang',
                'slug' => 'rafillo-panjang-' . strtolower($size),
                'price' => $price,
                'weight' => 270,
                'description' => 'Kemeja Rafillo panjang ukuran ' . $size,
                'stock' => 15,
                'size' => $size,
                'category' => 'Kemeja Premium',
                'inventory_id' => 4
            ]);
        }

        // K PRAMUKA PENDEK
        $pramukaPendekSizes = [
            '8' => 47000, '9' => 48000, '10' => 49000, '11' => 50000,
            '12' => 51000, '13' => 52000, '14' => 54000, '15' => 55000, '16' => 56000
        ];
        
        foreach ($pramukaPendekSizes as $size => $price) {
            Product::create([
                'name' => 'Kemeja Pramuka Pendek',
                'slug' => 'kemeja-pramuka-pendek-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Kemeja pramuka pendek ukuran ' . $size,
                'stock' => 35,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 1
            ]);
        }

        // K PRAMUKA PENGG SD/SMP
        $pramukapenggSizes = [
            '13' => 57000, '14' => 59000, '15' => 60000, '16' => 61000,
            'S' => 65000, 'M' => 67000, 'L' => 69000, 'XL' => 73000
        ];
        
        foreach ($pramukapenggSizes as $size => $price) {
            Product::create([
                'name' => 'Kemeja Pramuka Penggalang SD/SMP',
                'slug' => 'kemeja-pramuka-penggalang-sd-smp-' . strtolower($size),
                'price' => $price,
                'weight' => 220,
                'description' => 'Kemeja pramuka penggalang SD/SMP ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 1
            ]);
        }

        // K PRAM PENGG PJNG MANSET
        $pramukaMansetSizes = [
            '14' => 64000, '15' => 66000, '16' => 68000, 'S' => 72000,
            'M' => 74000, 'L' => 76000, 'XL' => 78000, 'L3' => 80000
        ];
        
        foreach ($pramukaMansetSizes as $size => $price) {
            Product::create([
                'name' => 'Kemeja Pramuka Penggalang Panjang Manset',
                'slug' => 'kemeja-pramuka-penggalang-panjang-manset-' . strtolower($size),
                'price' => $price,
                'weight' => 250,
                'description' => 'Kemeja pramuka penggalang panjang manset ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 1
            ]);
        }

        // K SIAGA PENDEK
        $siagaPendekSizes = [
            '8' => 53000, '9' => 54000, '10' => 55000, '11' => 56000,
            '12' => 57000, '13' => 58000, '14' => 60000, '15' => 61000, '16' => 62000
        ];
        
        foreach ($siagaPendekSizes as $size => $price) {
            Product::create([
                'name' => 'Kemeja Siaga Pendek',
                'slug' => 'kemeja-siaga-pendek-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Kemeja siaga pendek ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 1
            ]);
        }

        // K SIAGA PJNG
        $siagaPanjangSizes = [
            '8' => 58000, '9' => 59000, '10' => 60000, '11' => 61000,
            '12' => 62000, '13' => 63000, '14' => 65000, '15' => 66000, '16' => 67000
        ];
        
        foreach ($siagaPanjangSizes as $size => $price) {
            Product::create([
                'name' => 'Kemeja Siaga Panjang',
                'slug' => 'kemeja-siaga-panjang-' . $size,
                'price' => $price,
                'weight' => 230,
                'description' => 'Kemeja siaga panjang ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 1
            ]);
        }

        // K PEMB PJNG
        $pembinaPanjangSizes = [
            '16' => 67000, 'S' => 69000, 'M' => 71000, 'L' => 73000,
            'XL' => 75000, 'L3' => 77000, 'L4' => 79000, 'L5' => 81000, 'L6' => 83000
        ];
        
        foreach ($pembinaPanjangSizes as $size => $price) {
            Product::create([
                'name' => 'Kemeja Pembina Panjang',
                'slug' => 'kemeja-pembina-panjang-' . strtolower($size),
                'price' => $price,
                'weight' => 250,
                'description' => 'Kemeja pembina panjang ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 1
            ]);
        }

        // ROK PANJANG SD
        $rokPanjangSDSizes = [
            '3' => 48000, '4' => 49000, '5' => 50000, '6' => 51000,
            '7' => 52000, '8' => 53000, '9' => 54000, '10' => 56000,
            '11' => 58000, '12' => 61000
        ];
        
        foreach ($rokPanjangSDSizes as $size => $price) {
            Product::create([
                'name' => 'Rok Panjang SD',
                'slug' => 'rok-panjang-sd-' . $size,
                'price' => $price,
                'weight' => 180,
                'description' => 'Rok panjang untuk siswa SD ukuran ' . $size,
                'stock' => 40,
                'size' => $size,
                'category' => 'Rok',
                'inventory_id' => 2
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
                'name' => 'Rok Adu Manis Panjang SD',
                'slug' => 'rok-adu-manis-panjang-sd-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Rok adu manis panjang untuk siswa SD ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Rok Premium',
                'inventory_id' => 2
            ]);
        }

        // ROK PANJANG SPAN
        $rokSpanSizes = [
            'S' => 66000, 'M' => 66000, 'L' => 66000, 'XL' => 69000,
            'L3' => 72000, 'L4' => 75000, 'L5' => 78000, 'L6' => 81000
        ];
        
        foreach ($rokSpanSizes as $size => $price) {
            Product::create([
                'name' => 'Rok Panjang Span',
                'slug' => 'rok-panjang-span-' . strtolower($size),
                'price' => $price,
                'weight' => 190,
                'description' => 'Rok panjang span ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Rok',
                'inventory_id' => 2
            ]);
        }

        // ROK REMPEL BAPING / FULL
        $rokRempelSizes = [
            'S' => 76000, 'M' => 76000, 'L' => 76000, 'XL' => 79000,
            'L3' => 82000, 'L4' => 85000, 'L5' => 88000, 'L6' => 91000
        ];
        
        foreach ($rokRempelSizes as $size => $price) {
            Product::create([
                'name' => 'Rok Rempel Baping Full',
                'slug' => 'rok-rempel-baping-full-' . strtolower($size),
                'price' => $price,
                'weight' => 220,
                'description' => 'Rok rempel baping full ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Rok',
                'inventory_id' => 2
            ]);
        }

        // ROK PRAMUKA ASN
        $rokPramukaSizes = [
            'S' => 81000, 'M' => 84000, 'L' => 87000, 'XL' => 90000,
            'L3' => 93000, 'L4' => 96000, 'L5' => 99000, 'L6' => 102000
        ];
        
        foreach ($rokPramukaSizes as $size => $price) {
            Product::create([
                'name' => 'Rok Pramuka ASN',
                'slug' => 'rok-pramuka-asn-' . strtolower($size),
                'price' => $price,
                'weight' => 200,
                'description' => 'Rok pramuka ASN ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 2
            ]);
        }

        // CLN PANJANG SD
        $celanaSDSizes = [
            '3' => 47000, '4' => 48000, '5' => 49000, '6' => 50000,
            '7' => 51000, '8' => 52000, '9' => 53000, '10' => 55000,
            '11' => 57000, '12' => 59000
        ];
        
        foreach ($celanaSDSizes as $size => $price) {
            Product::create([
                'name' => 'Celana Panjang SD',
                'slug' => 'celana-panjang-sd-' . $size,
                'price' => $price,
                'weight' => 200,
                'description' => 'Celana panjang untuk siswa SD ukuran ' . $size,
                'stock' => 40,
                'size' => $size,
                'category' => 'Celana',
                'inventory_id' => 3
            ]);
        }

        // CELANA PDL SD
        $celanaPDLSDSizes = [
            '5' => 65000, '6' => 67000, '7' => 69000, '8' => 71000,
            '9' => 73000, '10' => 75000, '11' => 77000, '12' => 79000,
            '13' => 81000, '14' => 83000
        ];
        
        foreach ($celanaPDLSDSizes as $size => $price) {
            Product::create([
                'name' => 'Celana PDL SD',
                'slug' => 'celana-pdl-sd-' . $size,
                'price' => $price,
                'weight' => 250,
                'description' => 'Celana PDL untuk siswa SD ukuran ' . $size,
                'stock' => 30,
                'size' => $size,
                'category' => 'Celana PDL',
                'inventory_id' => 3
            ]);
        }

        // CLN PANJANG SMP SMA
        $celanaSMPSMASizes = [
            '25' => 58000, '26' => 59000, '27' => 60000, '28' => 61000,
            '29' => 62000, '30' => 63000, '31' => 64000, '32' => 65000,
            '33' => 66000, '34' => 68000
        ];
        
        foreach ($celanaSMPSMASizes as $size => $price) {
            Product::create([
                'name' => 'Celana Panjang SMP SMA',
                'slug' => 'celana-panjang-smp-sma-' . $size,
                'price' => $price,
                'weight' => 220,
                'description' => 'Celana panjang untuk siswa SMP dan SMA ukuran ' . $size,
                'stock' => 35,
                'size' => $size,
                'category' => 'Celana',
                'inventory_id' => 3
            ]);
        }

        // CLN PDL SMP SMA
        $celanaPDLSMPSMASizes = [
            '25' => 77000, '26' => 79000, '27' => 81000, '28' => 83000,
            '29' => 85000, '30' => 87000, '31' => 89000, '32' => 91000,
            '33' => 93000, '34' => 95000
        ];
        
        foreach ($celanaPDLSMPSMASizes as $size => $price) {
            Product::create([
                'name' => 'Celana PDL SMP SMA',
                'slug' => 'celana-pdl-smp-sma-' . $size,
                'price' => $price,
                'weight' => 270,
                'description' => 'Celana PDL untuk siswa SMP dan SMA ukuran ' . $size,
                'stock' => 25,
                'size' => $size,
                'category' => 'Celana PDL',
                'inventory_id' => 3
            ]);
        }

        // CELANA PRAMUKA ASN
        $celanaPramukaASNSizes = [
            '30' => 87000, '31' => 89000, '32' => 91000, '33' => 93000,
            '34' => 95000, '35' => 97000, '36' => 99000, '37' => 101000,
            '38' => 103000, '39' => 105000
        ];
        
        foreach ($celanaPramukaASNSizes as $size => $price) {
            Product::create([
                'name' => 'Celana Pramuka ASN',
                'slug' => 'celana-pramuka-asn-' . $size,
                'price' => $price,
                'weight' => 250,
                'description' => 'Celana pramuka ASN ukuran ' . $size,
                'stock' => 20,
                'size' => $size,
                'category' => 'Pramuka',
                'inventory_id' => 3
            ]);
        }
    }
}