<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 1. pastikan kolom sudah ada (jika belum)
            // 2. tambahkan FK
            $table->unsignedBigInteger('inventory_id')->change(); // opsional
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventories')
                ->onDelete('cascade');  // jika inventory dihapus, produk ikut terhapus
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
        });
    }
};
