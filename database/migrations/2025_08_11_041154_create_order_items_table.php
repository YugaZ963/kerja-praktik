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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Simpan nama produk saat order dibuat
            $table->string('product_size'); // Simpan ukuran produk saat order dibuat
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Harga per unit saat order dibuat
            $table->decimal('total', 10, 2); // Total harga item (quantity * price)
            $table->timestamps();
            
            $table->index(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
