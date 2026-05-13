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
        Schema::create('transaksi_masuk_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_masuk_id')
                    ->constrained('transaksi_masuks')
                    ->onDelete('cascade'); // Jika header dihapus, detail ikut terhapus
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk_details');
    }
};
