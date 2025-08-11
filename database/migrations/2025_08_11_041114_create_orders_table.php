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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->text('notes')->nullable();
            $table->enum('payment_method', ['bri', 'dana']);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', [
                'pending',      // Pesanan dikirim ke WhatsApp
                'payment_pending', // Menunggu pembayaran
                'payment_verified', // Pembayaran terverifikasi
                'processing',   // Sedang disiapkan
                'packaged',     // Sudah dikemas
                'shipped',      // Sedang dikirim
                'delivered',    // Sudah sampai
                'completed',    // Transaksi selesai
                'cancelled'     // Dibatalkan
            ])->default('pending');
            $table->text('payment_proof')->nullable(); // Path bukti pembayaran
            $table->timestamp('payment_verified_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_proof')->nullable(); // Path foto bukti sampai
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
