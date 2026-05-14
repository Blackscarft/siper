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
        Schema::create('saldo_bulanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('bulan'); // 1 - 12
            $table->integer('tahun'); // Contoh: 2024
            $table->integer('stok_awal');   // Saldo dari akhir bulan lalu
            $table->integer('stok_masuk');  // Total mutasi masuk bulan ini
            $table->integer('stok_keluar'); // Total mutasi keluar bulan ini
            $table->integer('stok_akhir');  // (stok_awal + masuk) - keluar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_bulanans');
    }
};
