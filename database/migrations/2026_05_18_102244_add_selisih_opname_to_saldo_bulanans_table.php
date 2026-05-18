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
        Schema::table('saldo_bulanans', function (Blueprint $table) {
            // Menambahkan kolom selisih_opname setelah kolom stok_keluar
            // Menggunakan tipe integer karena selisih bisa bernilai minus (-) atau plus (+)
            $table->integer('selisih_opname')->default(0)->after('stok_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saldo_bulanans', function (Blueprint $table) {
            // Menghapus kolom jika database di-rollback
            $table->dropColumn('selisih_opname');
        });
    }
};
