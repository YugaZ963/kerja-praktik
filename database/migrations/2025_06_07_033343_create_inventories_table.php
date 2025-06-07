<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // INV-SD-001
            $table->string('name'); // Seragam SD Pendek
            $table->string('category'); // Seragam Sekolah SD
            $table->integer('stock'); // 45
            $table->integer('min_stock'); // 10
            $table->decimal('purchase_price', 10, 2); // 35000 → 35000.00
            $table->decimal('selling_price', 10, 2); // 40000 → 40000.00
            $table->string('supplier'); // PT Seragam Jaya
            $table->date('last_restock'); // 2023-10-15
            $table->json('sizes_available'); // ['S', 'M', 'L', 'XL']
            $table->string('location'); // Rak A-1
            $table->text('description'); // Deskripsi panjang
            $table->json('stock_history'); // Array riwayat stok
            $table->timestamps(); // created_at dan updated-at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
