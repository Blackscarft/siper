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
        Schema::create('transaksi_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique(); // Contoh: BK-20260513-001
            $table->date('tanggal_keluar');
            $table->text('keterangan');
            $table->foreignUuid('user_id')->constrained('users', 'id'); // Mencatat siapa adminnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluars');
    }
};
