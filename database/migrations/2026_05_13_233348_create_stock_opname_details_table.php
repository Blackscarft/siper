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
        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs');
            $table->integer('stok_sistem'); // Stok yang tercatat di komputer
            $table->integer('stok_fisik');  // Stok yang dihitung manual
            $table->integer('selisih');     // stok_fisik - stok_sistem
            $table->text('catatan')->nullable(); // Alasan selisih (misal: pecah/hilang)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_details');
    }
};
